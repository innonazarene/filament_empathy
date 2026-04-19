<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import SimplePeer from 'simple-peer'

const props = defineProps({
    call: {
        type: Object,
        required: true,
    },
})

const page = usePage()
const currentUser = computed(() => page.props.auth.user)
const isCaller = computed(() => currentUser.value.id === props.call.caller_id)
const remoteUser = computed(() => isCaller.value ? props.call.listener : props.call.caller)

// Refs
const localVideo = ref(null)
const remoteVideo = ref(null)
const callStatus = ref(props.call.status)
const callDuration = ref('00:00')
const isMuted = ref(false)
const isVideoOff = ref(false)
const isConnected = ref(false)
const isEnding = ref(false)
const errorMessage = ref('')

let localStream = null
let peer = null
let echoChannel = null
let durationTimer = null
let startTime = null

// ---- Media ----
async function startMedia() {
    try {
        localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true })
        if (localVideo.value) {
            localVideo.value.srcObject = localStream
        }
    } catch (err) {
        // Try audio-only fallback
        try {
            localStream = await navigator.mediaDevices.getUserMedia({ video: false, audio: true })
            isVideoOff.value = true
            if (localVideo.value) {
                localVideo.value.srcObject = localStream
            }
        } catch (audioErr) {
            errorMessage.value = 'Could not access camera or microphone. Please check permissions.'
        }
    }
}

// ---- Signaling ----
async function sendSignal(type, signal) {
    try {
        await fetch(route('signal.send', props.call.id), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ type, signal }),
        })
    } catch (err) {
        console.error('Signal send error:', err)
    }
}

// ---- Peer setup ----
function createPeer(initiator) {
    if (!localStream) return

    peer = new SimplePeer({
        initiator,
        trickle: true,
        stream: localStream,
        config: {
            iceServers: [
                { urls: 'stun:stun.l.google.com:19302' },
                { urls: 'stun:stun1.l.google.com:19302' },
            ],
        },
    })

    peer.on('signal', (signal) => {
        const type = signal.type === 'offer' ? 'offer'
                   : signal.type === 'answer' ? 'answer'
                   : 'ice-candidate'
        sendSignal(type, signal)
    })

    peer.on('stream', (stream) => {
        if (remoteVideo.value) {
            remoteVideo.value.srcObject = stream
            isConnected.value = true
            startTimer()
        }
    })

    peer.on('connect', () => {
        isConnected.value = true
        callStatus.value = 'active'
    })

    peer.on('error', (err) => {
        console.error('Peer error:', err)
        errorMessage.value = 'Connection error. Please try again.'
    })

    peer.on('close', () => {
        isConnected.value = false
    })
}

function handleIncomingSignal(data) {
    // Ignore our own signals
    if (data.from_user_id === currentUser.value.id) return

    if (!peer) {
        // Listener receives an offer — create non-initiator peer
        createPeer(false)
    }

    try {
        peer.signal(data.signal)
    } catch (err) {
        console.error('Signal error:', err)
    }
}

// ---- Timer ----
function startTimer() {
    startTime = Date.now()
    durationTimer = setInterval(() => {
        const elapsed = Math.floor((Date.now() - startTime) / 1000)
        const m = Math.floor(elapsed / 60).toString().padStart(2, '0')
        const s = (elapsed % 60).toString().padStart(2, '0')
        callDuration.value = `${m}:${s}`
    }, 1000)
}

// ---- Controls ----
function toggleMute() {
    if (localStream) {
        localStream.getAudioTracks().forEach(track => {
            track.enabled = !track.enabled
        })
        isMuted.value = !isMuted.value
    }
}

function toggleVideo() {
    if (localStream) {
        localStream.getVideoTracks().forEach(track => {
            track.enabled = !track.enabled
        })
        isVideoOff.value = !isVideoOff.value
    }
}

async function endCall() {
    isEnding.value = true
    cleanup()
    try {
        await fetch(route('calls.end', props.call.id), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Inertia': 'true',
                Accept: 'application/json',
            },
        })
    } catch {}
    router.delete(route('calls.end', props.call.id))
}

function cleanup() {
    if (durationTimer) clearInterval(durationTimer)
    if (peer) {
        peer.destroy()
        peer = null
    }
    if (localStream) {
        localStream.getTracks().forEach(t => t.stop())
        localStream = null
    }
    if (echoChannel) {
        window.Echo?.leave('call.' + props.call.id)
        echoChannel = null
    }
}

// ---- Lifecycle ----
onMounted(async () => {
    await startMedia()

    if (window.Echo) {
        echoChannel = window.Echo.private('call.' + props.call.id)
            .listen('.webrtc.signal', (data) => {
                handleIncomingSignal(data)
            })
            .listen('.call.ended', () => {
                cleanup()
                if (isCaller.value) {
                    router.visit(route('donations.create', props.call.id))
                } else {
                    router.visit(route('listener.dashboard'))
                }
            })
    }

    // Caller initiates — small delay to let listener subscribe first
    if (isCaller.value) {
        setTimeout(() => {
            createPeer(true)
        }, 1500)
    }
})

onUnmounted(() => {
    cleanup()
})
</script>

<template>
    <div class="relative w-full h-screen bg-gray-900 overflow-hidden">
        <!-- Remote Video (full screen) -->
        <video
            ref="remoteVideo"
            autoplay
            playsinline
            class="absolute inset-0 w-full h-full object-cover"
            :class="{ 'opacity-0': !isConnected }"
        ></video>

        <!-- Connecting overlay -->
        <div v-if="!isConnected"
             class="absolute inset-0 bg-gradient-to-br from-purple-900 via-indigo-900 to-gray-900 flex items-center justify-center">
            <div class="text-center">
                <img :src="remoteUser.avatar_url" :alt="remoteUser.name"
                     class="w-28 h-28 rounded-full object-cover mx-auto mb-4 ring-4 ring-purple-400/50 shadow-2xl" />
                <h2 class="text-2xl font-bold text-white mb-2">{{ remoteUser.name }}</h2>
                <div class="flex items-center justify-center gap-2 text-purple-300">
                    <span class="flex gap-1">
                        <span class="w-2 h-2 rounded-full bg-purple-400 animate-bounce" style="animation-delay: 0ms"></span>
                        <span class="w-2 h-2 rounded-full bg-purple-400 animate-bounce" style="animation-delay: 150ms"></span>
                        <span class="w-2 h-2 rounded-full bg-purple-400 animate-bounce" style="animation-delay: 300ms"></span>
                    </span>
                    <span class="text-sm">
                        {{ isCaller ? 'Calling...' : 'Connecting...' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Error overlay -->
        <div v-if="errorMessage"
             class="absolute top-4 left-1/2 -translate-x-1/2 z-50 bg-red-500/90 backdrop-blur text-white px-6 py-3 rounded-xl text-sm font-medium shadow-lg max-w-sm text-center">
            {{ errorMessage }}
        </div>

        <!-- Local Video (picture-in-picture) -->
        <div class="absolute top-4 right-4 z-20 w-32 h-24 sm:w-44 sm:h-32 rounded-2xl overflow-hidden shadow-2xl ring-2 ring-white/20 bg-gray-800">
            <video
                ref="localVideo"
                autoplay
                muted
                playsinline
                class="w-full h-full object-cover"
                :class="{ 'opacity-0': isVideoOff }"
            ></video>
            <div v-if="isVideoOff" class="absolute inset-0 bg-gray-800 flex items-center justify-center">
                <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                </svg>
            </div>
        </div>

        <!-- Top info bar -->
        <div class="absolute top-4 left-4 z-20 flex items-center gap-3">
            <div class="bg-black/40 backdrop-blur-md rounded-xl px-4 py-2 flex items-center gap-2">
                <div class="w-2 h-2 rounded-full" :class="isConnected ? 'bg-emerald-400 animate-pulse' : 'bg-yellow-400 animate-pulse'"></div>
                <span class="text-white text-sm font-medium">
                    {{ isConnected ? callDuration : (isCaller ? 'Calling...' : 'Waiting...') }}
                </span>
            </div>
            <div v-if="isConnected" class="bg-black/40 backdrop-blur-md rounded-xl px-3 py-2">
                <span class="text-white text-sm">{{ remoteUser.name }}</span>
            </div>
        </div>

        <!-- Bottom controls -->
        <div class="absolute bottom-8 left-0 right-0 z-20 flex items-center justify-center gap-4">
            <!-- Mute -->
            <button @click="toggleMute"
                    class="w-14 h-14 rounded-full flex items-center justify-center transition-all shadow-lg"
                    :class="isMuted ? 'bg-red-500 hover:bg-red-600' : 'bg-white/20 hover:bg-white/30 backdrop-blur-md'">
                <svg v-if="!isMuted" class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7 4a3 3 0 016 0v4a3 3 0 11-6 0V4zm4 10.93A7.001 7.001 0 0017 8a1 1 0 10-2 0A5 5 0 015 8a1 1 0 00-2 0 7.001 7.001 0 006 6.93V17H6a1 1 0 100 2h8a1 1 0 100-2h-3v-2.07z" clip-rule="evenodd"/>
                </svg>
                <svg v-else class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM12.293 7.293a1 1 0 011.414 0L15 8.586l1.293-1.293a1 1 0 111.414 1.414L16.414 10l1.293 1.293a1 1 0 01-1.414 1.414L15 11.414l-1.293 1.293a1 1 0 01-1.414-1.414L13.586 10l-1.293-1.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>

            <!-- End Call -->
            <button @click="endCall"
                    :disabled="isEnding"
                    class="w-16 h-16 rounded-full bg-red-500 hover:bg-red-600 flex items-center justify-center shadow-xl transition-all hover:scale-105 active:scale-95 disabled:opacity-70">
                <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                </svg>
            </button>

            <!-- Toggle Video -->
            <button @click="toggleVideo"
                    class="w-14 h-14 rounded-full flex items-center justify-center transition-all shadow-lg"
                    :class="isVideoOff ? 'bg-red-500 hover:bg-red-600' : 'bg-white/20 hover:bg-white/30 backdrop-blur-md'">
                <svg v-if="!isVideoOff" class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                </svg>
                <svg v-else class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8zM3 3l18 18"/>
                </svg>
            </button>
        </div>
    </div>
</template>
