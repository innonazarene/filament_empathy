<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Head, usePage, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
    donations: {
        type: Array,
        default: () => [],
    },
})

const page = usePage()
const user = computed(() => page.props.auth.user)
const currentStatus = ref(user.value.status)
const isUpdatingStatus = ref(false)
const incomingCall = ref(null)
let echoChannel = null

async function toggleStatus() {
    isUpdatingStatus.value = true
    const newStatus = currentStatus.value === 'online' ? 'offline' : 'online'

    try {
        const response = await fetch(route('listener.status'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ status: newStatus }),
        })
        const data = await response.json()
        currentStatus.value = data.status
    } catch (err) {
        console.error('Status update error:', err)
    } finally {
        isUpdatingStatus.value = false
    }
}

function acceptCall() {
    if (incomingCall.value) {
        router.visit(route('calls.show', incomingCall.value.call_id))
        incomingCall.value = null
    }
}

function declineCall() {
    incomingCall.value = null
}

onMounted(() => {
    if (window.Echo && user.value) {
        echoChannel = window.Echo.private('listener.' + user.value.id)
            .listen('.call.initiated', (data) => {
                incomingCall.value = data
            })
    }
})

onUnmounted(() => {
    if (echoChannel && user.value) {
        window.Echo?.leave('listener.' + user.value.id)
    }
})

const totalEarned = computed(() => {
    return props.donations.reduce((sum, d) => sum + parseFloat(d.amount), 0).toFixed(2)
})
</script>

<template>
    <AppLayout>
        <Head title="Listener Dashboard" />

        <!-- Incoming Call Overlay -->
        <transition
            enter-active-class="transition ease-out duration-300"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
        >
            <div v-if="incomingCall"
                 class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
                <div class="bg-white rounded-3xl shadow-2xl p-8 max-w-sm w-full text-center">
                    <div class="relative inline-block mb-4">
                        <img :src="incomingCall.caller?.avatar_url || '/default-avatar.png'"
                             :alt="incomingCall.caller?.name"
                             class="w-24 h-24 rounded-full object-cover mx-auto ring-4 ring-purple-200" />
                        <span class="absolute inset-0 rounded-full ring-4 ring-purple-400 animate-ping opacity-30"></span>
                    </div>
                    <p class="text-sm font-medium text-purple-600 mb-1 uppercase tracking-wider">Incoming Call</p>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ incomingCall.caller?.name }}</h2>
                    <div class="flex gap-4">
                        <button @click="declineCall"
                                class="flex-1 py-3 rounded-xl bg-gray-100 text-gray-600 font-semibold hover:bg-gray-200 transition-colors">
                            Decline
                        </button>
                        <button @click="acceptCall"
                                class="flex-1 py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold hover:from-emerald-600 hover:to-teal-700 transition-all shadow-sm">
                            Accept
                        </button>
                    </div>
                </div>
            </div>
        </transition>

        <template #header>
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">My Dashboard</h1>
                    <p class="text-sm text-gray-500 mt-1">Welcome back, {{ user.name }}</p>
                </div>
                <!-- Status Toggle -->
                <button
                    @click="toggleStatus"
                    :disabled="isUpdatingStatus"
                    class="flex items-center gap-3 px-5 py-3 rounded-xl font-semibold text-sm transition-all shadow-sm disabled:opacity-60"
                    :class="currentStatus === 'online'
                        ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200 border-2 border-emerald-300'
                        : 'bg-gray-100 text-gray-600 hover:bg-gray-200 border-2 border-gray-300'"
                >
                    <span class="w-3 h-3 rounded-full"
                          :class="currentStatus === 'online' ? 'bg-emerald-500 animate-pulse' : 'bg-gray-400'"></span>
                    <span>{{ isUpdatingStatus ? 'Updating...' : (currentStatus === 'online' ? 'You\'re Online' : 'You\'re Offline') }}</span>
                </button>
            </div>
        </template>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-purple-100">
                <p class="text-sm text-gray-500 mb-1">Total Sessions</p>
                <p class="text-3xl font-bold text-gray-900">{{ donations.length }}</p>
                <p class="text-xs text-purple-500 mt-1">sessions with donations</p>
            </div>
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-purple-100">
                <p class="text-sm text-gray-500 mb-1">Total Earned</p>
                <p class="text-3xl font-bold text-emerald-600">${{ totalEarned }}</p>
                <p class="text-xs text-purple-500 mt-1">from caller donations</p>
            </div>
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-purple-100">
                <p class="text-sm text-gray-500 mb-1">Current Balance</p>
                <p class="text-3xl font-bold text-indigo-600">${{ parseFloat(user.balance).toFixed(2) }}</p>
                <p class="text-xs text-purple-500 mt-1">available balance</p>
            </div>
        </div>

        <!-- Tip Banner -->
        <div v-if="currentStatus !== 'online'" class="mb-8 p-4 rounded-2xl bg-gradient-to-r from-purple-50 to-indigo-50 border border-purple-100 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-purple-800">You're currently offline</p>
                <p class="text-xs text-purple-600 mt-0.5">Toggle your status to "Online" above to start receiving calls from people seeking support.</p>
            </div>
        </div>

        <!-- Donation History -->
        <div class="bg-white rounded-2xl shadow-sm border border-purple-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-800">Donation History</h2>
                <p class="text-sm text-gray-400 mt-0.5">Appreciation tokens received from callers</p>
            </div>

            <div v-if="donations.length === 0" class="text-center py-12">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-purple-50 flex items-center justify-center">
                    <svg class="w-8 h-8 text-purple-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <p class="text-gray-500 text-sm">No donations yet</p>
                <p class="text-gray-400 text-xs mt-1">Start taking calls to receive appreciation from callers.</p>
            </div>

            <div v-else class="divide-y divide-gray-50">
                <div v-for="donation in donations" :key="donation.id"
                     class="flex items-center gap-4 px-6 py-4 hover:bg-purple-50/50 transition-colors">
                    <img :src="donation.caller.avatar_url" :alt="donation.caller.name"
                         class="w-10 h-10 rounded-full object-cover flex-shrink-0 ring-2 ring-purple-100" />
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800">{{ donation.caller.name }}</p>
                        <p v-if="donation.message" class="text-xs text-gray-500 truncate mt-0.5">"{{ donation.message }}"</p>
                        <p v-else class="text-xs text-gray-400 mt-0.5">No message</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-base font-bold text-emerald-600">${{ parseFloat(donation.amount).toFixed(2) }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ donation.created_at }}</p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
