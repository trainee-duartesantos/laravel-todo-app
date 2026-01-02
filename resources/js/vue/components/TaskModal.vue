<template>
    <transition name="fade-scale">
        <!-- Backdrop: click fora fecha -->
        <div
            v-if="visible && task"
            class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
            @click.self="$emit('close')"
        >
            <div
                class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative"
            >
                <button
                    class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-xl"
                    @click="$emit('close')"
                >
                    ✕
                </button>

                <h2 class="text-lg font-bold mb-4">
                    {{ task.title }}
                </h2>

                <div class="space-y-2">
                    <p><strong>Estado:</strong> {{ task.status }}</p>
                    <p><strong>Prioridade:</strong> {{ task.priority }}</p>
                    <p><strong>Data limite:</strong> {{ task.due }}</p>
                </div>

                <hr class="my-5" />

                <div class="flex justify-end gap-3">
                    <button
                        class="px-4 py-2 rounded text-white"
                        :class="
                            task.status === 'completed'
                                ? 'bg-yellow-600 hover:bg-yellow-700'
                                : 'bg-green-600 hover:bg-green-700'
                        "
                        @click="$emit('toggle')"
                    >
                        {{
                            task.status === "completed" ? "Reabrir" : "Concluir"
                        }}
                    </button>

                    <button
                        class="px-4 py-2 rounded bg-red-600 hover:bg-red-700 text-white"
                        @click="$emit('delete')"
                    >
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    </transition>
</template>

<script setup>
import { onMounted, onUnmounted } from "vue";

const props = defineProps({
    visible: Boolean,
    task: Object,
});

const emit = defineEmits(["close", "toggle", "delete"]);

function onKeydown(e) {
    if (e.key === "Escape" && props.visible) {
        emit("close");
    }
}

onMounted(() => window.addEventListener("keydown", onKeydown));
onUnmounted(() => window.removeEventListener("keydown", onKeydown));
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
