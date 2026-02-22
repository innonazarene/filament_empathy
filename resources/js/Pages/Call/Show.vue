<script setup>
import { Head } from '@inertiajs/vue3'
import VideoCall from '@/Components/VideoCall.vue'

const props = defineProps({
    call: {
        type: Object,
        required: true,
    },
})
</script>

<template>
    <Head title="Call" />
    <VideoCall :call="call" />
</template>
