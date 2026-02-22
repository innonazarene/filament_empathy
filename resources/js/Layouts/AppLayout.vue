<script setup>
import { ref, computed } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'

const page = usePage()
const user = computed(() => page.props.auth.user)
const showMobileMenu = ref(false)

const homeRoute = computed(() => {
    if (!user.value) return route('login')
    if (user.value.role === 'admin') return route('admin.dashboard')
    if (user.value.role === 'listener') return route('listener.dashboard')
    return route('listeners.index')
})

const flash = computed(() => page.props.flash)
const showFlash = ref(true)

function dismissFlash() {
    showFlash.value = false
    setTimeout(() => showFlash.value = true, 100)
}
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-purple-50 via-blue-50 to-indigo-50">
        <!-- Navbar -->
        <nav class="bg-white/80 backdrop-blur-md shadow-sm border-b border-purple-100 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <!-- Logo -->
                    <Link :href="homeRoute" class="flex items-center gap-2 group">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center shadow-md group-hover:shadow-purple-300 transition-shadow">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">Empathy</span>
                    </Link>

                    <!-- Nav links -->
                    <div class="hidden sm:flex items-center gap-6" v-if="user">
                        <Link v-if="user.role === 'caller'" :href="route('listeners.index')"
                              class="text-sm font-medium text-gray-600 hover:text-purple-600 transition-colors">
                            Find a Listener
                        </Link>
                        <Link v-if="user.role === 'listener'" :href="route('listener.dashboard')"
                              class="text-sm font-medium text-gray-600 hover:text-purple-600 transition-colors">
                            Dashboard
                        </Link>
                        <Link v-if="user.role === 'admin'" :href="route('admin.dashboard')"
                              class="text-sm font-medium text-gray-600 hover:text-purple-600 transition-colors">
                            Admin
                        </Link>
                    </div>

                    <!-- User menu -->
                    <div class="flex items-center gap-3" v-if="user">
                        <div class="hidden sm:flex items-center gap-2">
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium"
                                  :class="{
                                      'bg-purple-100 text-purple-700': user.role === 'listener',
                                      'bg-blue-100 text-blue-700': user.role === 'caller',
                                      'bg-rose-100 text-rose-700': user.role === 'admin',
                                  }">
                                {{ user.role }}
                            </span>
                        </div>
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button class="flex items-center gap-2 rounded-full focus:outline-none focus:ring-2 focus:ring-purple-400 focus:ring-offset-2">
                                    <img :src="user.avatar_url" :alt="user.name"
                                         class="w-9 h-9 rounded-full object-cover ring-2 ring-purple-200 hover:ring-purple-400 transition-all" />
                                </button>
                            </template>
                            <template #content>
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <p class="text-sm font-semibold text-gray-800">{{ user.name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ user.email }}</p>
                                </div>
                                <DropdownLink :href="route('profile.edit')">
                                    Profile Settings
                                </DropdownLink>
                                <DropdownLink :href="route('logout')" method="post" as="button">
                                    Sign Out
                                </DropdownLink>
                            </template>
                        </Dropdown>
                    </div>
                    <div v-else class="flex items-center gap-3">
                        <Link :href="route('login')" class="text-sm font-medium text-gray-600 hover:text-purple-600 transition-colors">
                            Sign In
                        </Link>
                        <Link :href="route('register')"
                              class="text-sm font-medium px-4 py-2 rounded-lg bg-gradient-to-r from-purple-500 to-indigo-600 text-white hover:from-purple-600 hover:to-indigo-700 transition-all shadow-sm">
                            Get Started
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Flash Messages -->
        <transition enter-active-class="transition ease-out duration-300" enter-from-class="opacity-0 -translate-y-2" enter-to-class="opacity-100 translate-y-0" leave-active-class="transition ease-in duration-200" leave-from-class="opacity-100" leave-to-class="opacity-0">
            <div v-if="showFlash && (flash.success || flash.error)" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div v-if="flash.success"
                     class="flex items-center gap-3 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium">{{ flash.success }}</span>
                    <button @click="dismissFlash" class="ml-auto text-emerald-600 hover:text-emerald-800">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </button>
                </div>
                <div v-if="flash.error"
                     class="flex items-center gap-3 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium">{{ flash.error }}</span>
                    <button @click="dismissFlash" class="ml-auto text-red-600 hover:text-red-800">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </button>
                </div>
            </div>
        </transition>

        <!-- Page Header slot -->
        <header v-if="$slots.header" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-4">
            <slot name="header" />
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <slot />
        </main>
    </div>
</template>
