<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-purple-50 via-blue-50 to-indigo-100 py-12 px-4">
        <!-- Logo -->
        <div class="mb-8 text-center">
            <Link href="/" class="inline-flex items-center gap-3 group">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center shadow-lg group-hover:shadow-purple-300 transition-shadow">
                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">Empathy</span>
            </Link>
            <p class="text-sm text-gray-500 mt-2">You don't have to face it alone.</p>
        </div>

        <!-- Card -->
        <div class="w-full max-w-md bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl shadow-purple-100 border border-purple-100 px-8 py-8">
            <slot />
        </div>
    </div>
</template>
