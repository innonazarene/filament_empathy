<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Head, usePage, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
    stats: { type: Object, required: true },
    calls: { type: Array, default: () => [] },
    donations: { type: Array, default: () => [] },
    users: { type: Array, default: () => [] },
})

const page = usePage()
const activeTab = ref('overview')
const liveStats = ref({ ...props.stats })
let echoChannel = null

function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user?')) {
        router.delete(route('admin.users.destroy', userId), {
            preserveScroll: true,
        })
    }
}

onMounted(() => {
    if (window.Echo) {
        // Listen to listener status changes to update online count
        echoChannel = window.Echo.channel('listeners')
            .listen('.listener.status.changed', (event) => {
                if (event.status === 'online') {
                    liveStats.value.online_listeners++
                } else if (event.status === 'offline') {
                    liveStats.value.online_listeners = Math.max(0, liveStats.value.online_listeners - 1)
                }
            })
    }
})

onUnmounted(() => {
    if (echoChannel) window.Echo?.leave('listeners')
})

const statCards = computed(() => [
    {
        label: 'Total Users',
        value: liveStats.value.total_users,
        icon: 'users',
        color: 'purple',
    },
    {
        label: 'Listeners Online',
        value: liveStats.value.online_listeners,
        icon: 'online',
        color: 'emerald',
        live: true,
    },
    {
        label: 'Active Calls',
        value: liveStats.value.active_calls,
        icon: 'phone',
        color: 'blue',
        live: true,
    },
    {
        label: 'Total Donations',
        value: '$' + parseFloat(liveStats.value.total_donations).toFixed(2),
        icon: 'heart',
        color: 'rose',
    },
])

const statusColors = {
    pending: 'bg-yellow-100 text-yellow-700',
    active: 'bg-emerald-100 text-emerald-700',
    ended: 'bg-gray-100 text-gray-600',
    missed: 'bg-red-100 text-red-700',
}

const roleColors = {
    admin: 'bg-rose-100 text-rose-700',
    listener: 'bg-purple-100 text-purple-700',
    caller: 'bg-blue-100 text-blue-700',
}
</script>

<template>
    <AppLayout>
        <Head title="Admin Dashboard" />

        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
                    <p class="text-sm text-gray-500 mt-1">Platform overview and management</p>
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-400 bg-white rounded-xl px-3 py-2 border border-gray-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Live updates on</span>
                </div>
            </div>
        </template>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div v-for="card in statCards" :key="card.label"
                 class="bg-white rounded-2xl p-5 shadow-sm border border-purple-100 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">{{ card.label }}</span>
                    <span v-if="card.live" class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                </div>
                <p class="text-3xl font-bold"
                   :class="{
                       'text-purple-700': card.color === 'purple',
                       'text-emerald-600': card.color === 'emerald',
                       'text-blue-600': card.color === 'blue',
                       'text-rose-600': card.color === 'rose',
                   }">
                    {{ card.value }}
                </p>
            </div>
        </div>

        <!-- Tabs -->
        <div class="flex gap-1 mb-6 bg-gray-100 rounded-xl p-1 w-fit">
            <button v-for="tab in ['overview', 'calls', 'donations', 'users']" :key="tab"
                    @click="activeTab = tab"
                    class="px-5 py-2 rounded-lg text-sm font-semibold capitalize transition-all"
                    :class="activeTab === tab
                        ? 'bg-white text-purple-700 shadow-sm'
                        : 'text-gray-500 hover:text-gray-700'">
                {{ tab }}
            </button>
        </div>

        <!-- Overview Tab -->
        <div v-if="activeTab === 'overview'" class="space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Calls -->
                <div class="bg-white rounded-2xl shadow-sm border border-purple-100 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-800">Recent Calls</h3>
                    </div>
                    <div class="divide-y divide-gray-50">
                        <div v-for="call in calls.slice(0, 5)" :key="call.id"
                             class="flex items-center gap-3 px-5 py-3">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-800">
                                    <span class="font-medium">{{ call.caller.name }}</span>
                                    <span class="text-gray-400 mx-1">→</span>
                                    <span class="font-medium">{{ call.listener.name }}</span>
                                </p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ call.created_at }}</p>
                            </div>
                            <span class="text-xs px-2.5 py-1 rounded-full font-medium" :class="statusColors[call.status] || 'bg-gray-100 text-gray-600'">
                                {{ call.status }}
                            </span>
                        </div>
                        <div v-if="calls.length === 0" class="text-center py-8 text-gray-400 text-sm">No calls yet</div>
                    </div>
                </div>

                <!-- Recent Donations -->
                <div class="bg-white rounded-2xl shadow-sm border border-purple-100 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-800">Recent Donations</h3>
                    </div>
                    <div class="divide-y divide-gray-50">
                        <div v-for="donation in donations.slice(0, 5)" :key="donation.id"
                             class="flex items-center gap-3 px-5 py-3">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-800">
                                    <span class="font-medium">{{ donation.caller.name }}</span>
                                    <span class="text-gray-400 mx-1">→</span>
                                    <span class="font-medium">{{ donation.listener.name }}</span>
                                </p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ donation.created_at }}</p>
                            </div>
                            <span class="font-bold text-emerald-600">${{ parseFloat(donation.amount).toFixed(2) }}</span>
                        </div>
                        <div v-if="donations.length === 0" class="text-center py-8 text-gray-400 text-sm">No donations yet</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calls Tab -->
        <div v-if="activeTab === 'calls'" class="bg-white rounded-2xl shadow-sm border border-purple-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">All Calls ({{ calls.length }})</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3 text-left">Caller</th>
                            <th class="px-6 py-3 text-left">Listener</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-left">Duration</th>
                            <th class="px-6 py-3 text-left">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr v-for="call in calls" :key="call.id" class="hover:bg-purple-50/30">
                            <td class="px-6 py-4 font-medium text-gray-800">{{ call.caller.name }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ call.listener.name }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium" :class="statusColors[call.status] || 'bg-gray-100 text-gray-600'">
                                    {{ call.status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ call.duration || '—' }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ call.created_at }}</td>
                        </tr>
                        <tr v-if="calls.length === 0">
                            <td colspan="5" class="text-center py-12 text-gray-400">No calls yet</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Donations Tab -->
        <div v-if="activeTab === 'donations'" class="bg-white rounded-2xl shadow-sm border border-purple-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">All Donations ({{ donations.length }})</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3 text-left">Caller</th>
                            <th class="px-6 py-3 text-left">Listener</th>
                            <th class="px-6 py-3 text-left">Amount</th>
                            <th class="px-6 py-3 text-left">Message</th>
                            <th class="px-6 py-3 text-left">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr v-for="donation in donations" :key="donation.id" class="hover:bg-purple-50/30">
                            <td class="px-6 py-4 font-medium text-gray-800">{{ donation.caller.name }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ donation.listener.name }}</td>
                            <td class="px-6 py-4 font-bold text-emerald-600">${{ parseFloat(donation.amount).toFixed(2) }}</td>
                            <td class="px-6 py-4 text-gray-500 max-w-xs truncate">{{ donation.message || '—' }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ donation.created_at }}</td>
                        </tr>
                        <tr v-if="donations.length === 0">
                            <td colspan="5" class="text-center py-12 text-gray-400">No donations yet</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Users Tab -->
        <div v-if="activeTab === 'users'" class="bg-white rounded-2xl shadow-sm border border-purple-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">All Users ({{ users.length }})</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3 text-left">User</th>
                            <th class="px-6 py-3 text-left">Role</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-left">Balance</th>
                            <th class="px-6 py-3 text-left">Joined</th>
                            <th class="px-6 py-3 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr v-for="u in users" :key="u.id" class="hover:bg-purple-50/30">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img :src="u.avatar_url" :alt="u.name"
                                         class="w-8 h-8 rounded-full object-cover ring-1 ring-purple-100" />
                                    <div>
                                        <p class="font-medium text-gray-800">{{ u.name }}</p>
                                        <p class="text-xs text-gray-400">{{ u.email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium" :class="roleColors[u.role] || 'bg-gray-100 text-gray-600'">
                                    {{ u.role }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="flex items-center gap-1.5 text-xs"
                                      :class="u.status === 'online' ? 'text-emerald-600' : u.status === 'busy' ? 'text-amber-600' : 'text-gray-400'">
                                    <span class="w-1.5 h-1.5 rounded-full"
                                          :class="u.status === 'online' ? 'bg-emerald-500' : u.status === 'busy' ? 'bg-amber-500' : 'bg-gray-400'"></span>
                                    {{ u.status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-700">${{ parseFloat(u.balance).toFixed(2) }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ u.created_at }}</td>
                            <td class="px-6 py-4">
                                <button v-if="u.role !== 'admin'"
                                        @click="deleteUser(u.id)"
                                        class="text-xs px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 font-medium transition-colors">
                                    Delete
                                </button>
                                <span v-else class="text-xs text-gray-300">—</span>
                            </td>
                        </tr>
                        <tr v-if="users.length === 0">
                            <td colspan="6" class="text-center py-12 text-gray-400">No users yet</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
