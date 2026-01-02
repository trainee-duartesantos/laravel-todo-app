<template>
    <transition name="fade-scale">
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

                <!-- MODO VISUALIZAR -->
                <template v-if="!editing">
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
                            class="px-4 py-2 rounded bg-gray-200"
                            @click="editing = true"
                        >
                            Editar
                        </button>

                        <button
                            class="px-4 py-2 rounded text-white"
                            :class="
                                task.status === 'completed'
                                    ? 'bg-yellow-600'
                                    : 'bg-green-600'
                            "
                            @click="$emit('toggle')"
                        >
                            {{
                                task.status === "completed"
                                    ? "Reabrir"
                                    : "Concluir"
                            }}
                        </button>

                        <button
                            class="px-4 py-2 rounded bg-red-600 text-white"
                            @click="$emit('delete')"
                        >
                            Eliminar
                        </button>
                    </div>
                </template>

                <!-- MODO EDITAR -->
                <template v-else>
                    <h2 class="text-lg font-bold mb-4">Editar tarefa</h2>

                    <div class="space-y-3">
                        <input
                            v-model="form.title"
                            type="text"
                            class="w-full border rounded px-3 py-2"
                        />

                        <select
                            v-model="form.priority"
                            class="w-full border rounded px-3 py-2"
                        >
                            <option value="low">Baixa</option>
                            <option value="medium">Média</option>
                            <option value="high">Alta</option>
                        </select>

                        <input
                            v-model="form.due"
                            type="date"
                            class="w-full border rounded px-3 py-2"
                        />
                    </div>

                    <hr class="my-5" />

                    <div class="flex justify-end gap-3">
                        <button
                            class="px-4 py-2 rounded bg-gray-200"
                            @click="$emit('close')"
                        >
                            Cancelar
                        </button>

                        <button
                            class="px-4 py-2 rounded bg-blue-600 text-white"
                            @click="save"
                        >
                            Guardar
                        </button>
                    </div>
                </template>
            </div>
        </div>
    </transition>
</template>

<script setup>
import { ref, watch, onMounted, onUnmounted } from "vue";

const props = defineProps({
    visible: Boolean,
    task: Object,
});

const emit = defineEmits(["close", "toggle", "delete", "save"]);

const editing = ref(false);

const form = ref({
    title: "",
    priority: "medium",
    due: "",
});

watch(
    () => props.task,
    (task) => {
        if (!task) return;

        form.value = {
            title: task.title,
            priority: task.priority_key ?? "medium",
            due: task.due_raw ?? "",
        };

        editing.value = false;
    },
    { immediate: true }
);

function save() {
    emit("save", {
        title: form.value.title,
        priority: form.value.priority,
        due_date: form.value.due,
    });
}

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
