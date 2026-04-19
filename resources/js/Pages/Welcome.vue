<script setup>
import { Head, Link } from '@inertiajs/vue3'

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
})
</script>

<template>
    <Head title="Welcome to Empathy" />

    <div class="min-h-screen bg-gradient-to-br from-purple-50 via-indigo-50 to-blue-100">
        <!-- Navigation -->
        <nav class="max-w-7xl mx-auto px-6 py-5 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center shadow-md">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="text-xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">Empathy</span>
            </div>
            <div class="flex items-center gap-4">
                <Link v-if="canLogin" :href="route('login')"
                      class="text-sm font-medium text-gray-600 hover:text-purple-600 transition-colors">
                    Sign In
                </Link>
                <Link v-if="canRegister" :href="route('register')"
                      class="px-5 py-2 rounded-xl bg-gradient-to-r from-purple-500 to-indigo-600 text-white text-sm font-semibold hover:from-purple-600 hover:to-indigo-700 transition-all shadow-md hover:shadow-purple-200">
                    Get Started
                </Link>
            </div>
        </nav>

        <!-- Hero -->
        <main class="max-w-5xl mx-auto px-6 py-20 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-purple-100 text-purple-700 text-sm font-medium mb-8">
                <span class="w-2 h-2 rounded-full bg-purple-500 animate-pulse"></span>
                Listeners are online now
            </div>

            <h1 class="text-5xl sm:text-6xl font-bold text-gray-900 leading-tight mb-6">
                Someone is ready
                <br />
                <span class="bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 bg-clip-text text-transparent">
                    to listen
                </span>
            </h1>

            <p class="text-xl text-gray-500 max-w-2xl mx-auto leading-relaxed mb-12">
                Connect with a compassionate volunteer for a private voice or video call.
                Free to use. No judgment. Just listening.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <Link :href="route('register')"
                      class="px-8 py-4 rounded-2xl bg-gradient-to-r from-purple-500 to-indigo-600 text-white font-semibold text-lg hover:from-purple-600 hover:to-indigo-700 transition-all shadow-lg hover:shadow-purple-200">
                    Find a Listener
                </Link>
                <Link :href="route('register')"
                      class="px-8 py-4 rounded-2xl bg-white border-2 border-purple-200 text-purple-700 font-semibold text-lg hover:bg-purple-50 transition-all">
                    Become a Listener
                </Link>
            </div>

            <!-- Feature Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-20 text-left">
                <div class="bg-white/70 backdrop-blur rounded-2xl p-6 border border-purple-100 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Completely Private</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">End-to-end encrypted video calls. Your conversations stay between you and your listener.</p>
                </div>

                <div class="bg-white/70 backdrop-blur rounded-2xl p-6 border border-indigo-100 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Compassionate Support</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Our volunteer listeners are trained to provide a safe, non-judgmental space for you to be heard.</p>
                </div>

                <div class="bg-white/70 backdrop-blur rounded-2xl p-6 border border-blue-100 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">Available Now</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Connect in seconds with listeners who are online right now. No appointments needed.</p>
                </div>
            </div>
        </main>
    </div>
</template>
