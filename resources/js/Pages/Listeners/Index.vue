<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { Head, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import ListenerCard from '@/Components/ListenerCard.vue'

const props = defineProps({
    listeners: {
        type: Array,
        default: () => [],
    },
})

const listeners = ref([...props.listeners])
const page = usePage()
let echoChannel = null
let pollInterval = null

onMounted(() => {
    // Subscribe to public channel for listener status changes
    if (window.Echo) {
        echoChannel = window.Echo.channel('listeners')
            .listen('.listener.status.changed', (event) => {
                const idx = listeners.value.findIndex(l => l.id === event.id)
                if (event.status === 'online') {
                    if (idx === -1) {
                        listeners.value.push(event)
                    } else {
                        listeners.value[idx] = { ...listeners.value[idx], ...event }
                    }
                } else {
                    if (idx !== -1) {
                        listeners.value.splice(idx, 1)
                    }
                }
            })
    }

    // Fallback polling every 10 seconds
    pollInterval = setInterval(async () => {
        try {
            const response = await fetch(route('listeners.index'), {
                headers: { 'X-Inertia': 'true', 'X-Inertia-Version': page.version }
            })
            if (response.ok) {
                const data = await response.json()
                if (data.props?.listeners) {
                    listeners.value = data.props.listeners
                }
            }
        } catch {}
    }, 10000)
})

onUnmounted(() => {
    if (echoChannel) window.Echo?.leave('listeners')
    if (pollInterval) clearInterval(pollInterval)
})
</script>

<template>
    <AppLayout>
        <Head title="Find a Listener" />

        <template #header>
            <div class="text-center max-w-2xl mx-auto">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Find Your Listener</h1>
                <p class="text-gray-500">Connect with a caring volunteer who's ready to listen. You don't have to face it alone.</p>
            </div>
        </template>

        <!-- Empty State -->
        <div v-if="listeners.length === 0"
             class="text-center py-20">
            <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-purple-100 flex items-center justify-center">
                <svg class="w-10 h-10 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">No listeners available right now</h3>
            <p class="text-gray-400 text-sm max-w-sm mx-auto">
                All our volunteers are currently busy or offline. Please check back in a few minutes.
            </p>
            <div class="mt-4 flex items-center justify-center gap-2 text-sm text-purple-500">
                <span class="w-2 h-2 rounded-full bg-purple-400 animate-pulse"></span>
                <span>This page updates automatically</span>
            </div>
        </div>

        <!-- Listener Grid -->
        <div v-else>
            <div class="flex items-center justify-between mb-6">
                <p class="text-sm text-gray-500">
                    <span class="font-semibold text-purple-600">{{ listeners.length }}</span>
                    {{ listeners.length === 1 ? 'listener' : 'listeners' }} available now
                </p>
                <div class="flex items-center gap-2 text-xs text-gray-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    Live updates enabled
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                <ListenerCard
                    v-for="listener in listeners"
                    :key="listener.id"
                    :listener="listener"
                />
            </div>
        </div>
    </AppLayout>
</template>
