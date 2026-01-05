<template>
    <transition name="toast">
        <div
            v-if="visible"
            class="fixed top-5 right-5 z-[9999] bg-green-600 text-white px-4 py-3 rounded-lg shadow-lg flex items-center gap-2"
            role="alert"
        >
            <span>{{ message }}</span>
            <button
                class="ml-2 text-white/80 hover:text-white"
                @click="close"
                aria-label="Fechar"
            >
                ✕
            </button>
        </div>
    </transition>
</template>

<script setup>
import { watch } from "vue";

const props = defineProps({
    visible: Boolean,
    message: String,
});

const emit = defineEmits(["close"]);

function close() {
    emit("close");
}

// auto close (3s)
watch(
    () => props.visible,
    (v) => {
        if (v) {
            setTimeout(() => emit("close"), 3000);
        }
    }
);
</script>

<style scoped>
.toast-enter-active,
.toast-leave-active {
    transition: all 0.25s ease;
}
.toast-enter-from {
    opacity: 0;
    transform: translateY(-10px);
}
.toast-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
</style>
