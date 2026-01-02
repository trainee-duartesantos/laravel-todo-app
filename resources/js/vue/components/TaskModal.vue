<template>
    <transition name="fade-scale">
        <div
            v-if="visible && task"
            class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
        >
            <div
                class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative"
            >
                <button
                    class="absolute top-3 right-3 text-gray-400 hover:text-gray-600"
                    @click="$emit('close')"
                >
                    ✕
                </button>

                <h2 class="text-lg font-bold mb-4">
                    {{ task.title }}
                </h2>

                <p><strong>Estado:</strong> {{ task.status }}</p>
                <p><strong>Prioridade:</strong> {{ task.priority }}</p>
                <p><strong>Data limite:</strong> {{ task.due }}</p>
            </div>
        </div>
    </transition>
</template>

<script setup>
defineProps({
    visible: Boolean,
    task: Object,
});
</script>

<style scoped>
.fade-scale-enter-active,
.fade-scale-leave-active {
    transition: all 0.2s ease;
}

.fade-scale-enter-from,
.fade-scale-leave-to {
    opacity: 0;
    transform: scale(0.95);
}
</style>
