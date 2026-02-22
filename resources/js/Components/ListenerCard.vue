<script setup>
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    listener: {
        type: Object,
        required: true,
    },
})

const form = useForm({
    listener_id: props.listener.id,
})

function call() {
    form.post(route('calls.store'))
}
</script>

<template>
    <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-all duration-200 border border-purple-100 hover:border-purple-200 overflow-hidden group">
        <div class="p-6">
            <!-- Avatar & Status -->
            <div class="flex items-start justify-between mb-4">
                <div class="relative">
                    <img :src="listener.avatar_url" :alt="listener.name"
                         class="w-16 h-16 rounded-2xl object-cover ring-2 ring-purple-100 group-hover:ring-purple-300 transition-all" />
                    <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-400 rounded-full border-2 border-white shadow-sm"></span>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Available
                </span>
            </div>

            <!-- Name & Bio -->
            <h3 class="text-lg font-semibold text-gray-800 mb-1">{{ listener.name }}</h3>
            <p class="text-sm text-gray-500 line-clamp-2 mb-5 leading-relaxed">
                {{ listener.bio || 'Here to listen and support you through whatever you\'re going through.' }}
            </p>

            <!-- Call Button -->
            <button @click="call"
                    :disabled="form.processing"
                    class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-purple-500 to-indigo-600 text-white text-sm font-semibold hover:from-purple-600 hover:to-indigo-700 disabled:opacity-60 disabled:cursor-not-allowed transition-all shadow-sm hover:shadow-purple-200 active:scale-95">
                <svg v-if="!form.processing" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                </svg>
                <svg v-else class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                {{ form.processing ? 'Connecting...' : 'Start Call' }}
            </button>
        </div>
    </div>
</template>
