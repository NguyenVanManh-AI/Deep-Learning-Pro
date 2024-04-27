<template>
    <div>
        InformationChannel
    </div>
</template>

<script>
import useEventBus from '@/composables/useEventBus'
const { emitEvent } = useEventBus();

export default {
    name: "InformationChannel",
    setup() {
        document.title = "Information Channel | AI System";
    },
    mounted() {
        emitEvent('eventTitleHeader', 'Information Channel');
    },
}
</script>

<style scoped>
</style>