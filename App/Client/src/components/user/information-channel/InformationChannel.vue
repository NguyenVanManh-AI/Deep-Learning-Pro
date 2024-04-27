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
        document.title = "Information Channel | LINE Bot";
    },
    mounted() {
        emitEvent('eventTitleHeader', 'Information Channel');
    },
}
</script>

<style scoped>
</style>