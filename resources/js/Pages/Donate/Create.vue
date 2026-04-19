<script setup>
import { ref, computed } from 'vue'
import { Head, useForm, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
    call: {
        type: Object,
        required: true,
    },
})

const presets = [1, 5, 10, 20]
const customAmount = ref('')
const selectedPreset = ref(null)

const form = useForm({
    call_id: props.call.id,
    amount: '',
    message: '',
})

function selectPreset(amount) {
    selectedPreset.value = amount
    customAmount.value = ''
    form.amount = amount.toString()
}

function onCustomInput() {
    selectedPreset.value = null
    form.amount = customAmount.value
}

const finalAmount = computed(() => parseFloat(form.amount) || 0)

function submit() {
    form.post(route('donations.store'))
}

function skip() {
    router.visit(route('listeners.index'))
}
</script>

<template>
    <AppLayout>
        <Head title="Support Your Listener" />

        <div class="max-w-lg mx-auto">
            <!-- Header Card -->
            <div class="text-center mb-8">
                <div class="inline-flex p-1 rounded-2xl bg-gradient-to-br from-purple-100 to-indigo-100 mb-4">
                    <img :src="call.listener.avatar_url" :alt="call.listener.name"
                         class="w-20 h-20 rounded-xl object-cover" />
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-1">Support {{ call.listener.name }}</h1>
                <p class="text-gray-500 text-sm">
                    Your session
                    <span v-if="call.duration" class="font-medium text-purple-600">lasted {{ call.duration }}</span>.
                    If you found value in this conversation, consider leaving a token of appreciation.
                </p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-purple-100 p-6">
                <!-- Preset amounts -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Choose an amount</label>
                    <div class="grid grid-cols-4 gap-2">
                        <button
                            v-for="preset in presets"
                            :key="preset"
                            @click="selectPreset(preset)"
                            class="py-3 rounded-xl font-semibold text-sm transition-all border-2"
                            :class="selectedPreset === preset
                                ? 'border-purple-500 bg-purple-50 text-purple-700 shadow-sm'
                                : 'border-gray-200 bg-white text-gray-600 hover:border-purple-300 hover:bg-purple-50'"
                        >
                            ${{ preset }}
                        </button>
                    </div>
                </div>

                <!-- Custom amount -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Or enter a custom amount</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-medium">$</span>
                        <input
                            v-model="customAmount"
                            @input="onCustomInput"
                            type="number"
                            min="0.50"
                            step="0.01"
                            placeholder="0.00"
                            class="w-full pl-8 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-400 focus:ring-0 text-gray-800 font-medium transition-colors"
                            :class="{ 'border-purple-400 bg-purple-50': customAmount && !selectedPreset }"
                        />
                    </div>
                    <p v-if="form.errors.amount" class="mt-1 text-sm text-red-500">{{ form.errors.amount }}</p>
                </div>

                <!-- Message -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Leave a message
                        <span class="font-normal text-gray-400">(optional)</span>
                    </label>
                    <textarea
                        v-model="form.message"
                        rows="3"
                        placeholder="Thank you for listening..."
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-400 focus:ring-0 text-gray-700 text-sm resize-none transition-colors"
                    ></textarea>
                    <p v-if="form.errors.message" class="mt-1 text-sm text-red-500">{{ form.errors.message }}</p>
                </div>

                <!-- Amount summary -->
                <div v-if="finalAmount > 0"
                     class="mb-6 p-4 rounded-xl bg-gradient-to-r from-purple-50 to-indigo-50 border border-purple-100">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Donation amount</span>
                        <span class="text-2xl font-bold text-purple-700">${{ finalAmount.toFixed(2) }}</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-3">
                    <button
                        @click="skip"
                        class="flex-1 py-3 rounded-xl border-2 border-gray-200 text-gray-600 font-semibold text-sm hover:bg-gray-50 transition-colors"
                    >
                        Skip for now
                    </button>
                    <button
                        @click="submit"
                        :disabled="!form.amount || parseFloat(form.amount) <= 0 || form.processing"
                        class="flex-1 py-3 rounded-xl bg-gradient-to-r from-purple-500 to-indigo-600 text-white font-semibold text-sm hover:from-purple-600 hover:to-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-sm"
                    >
                        <span v-if="form.processing">Sending...</span>
                        <span v-else>Donate ${{ finalAmount > 0 ? finalAmount.toFixed(2) : '0.00' }}</span>
                    </button>
                </div>
            </div>

            <p class="text-center text-xs text-gray-400 mt-4">
                100% of your donation goes directly to {{ call.listener.name }}.
            </p>
        </div>
    </AppLayout>
</template>
