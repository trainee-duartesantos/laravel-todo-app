<template>
    <transition name="fade-scale">
        <div
            v-if="visible && task"
            class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
            role="dialog"
            aria-modal="true"
            aria-labelledby="modal-title"
            @click.self="close"
        >
            <div
                class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative"
            >
                <!-- Fechar -->
                <button
                    aria-label="Fechar modal"
                    class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-xl"
                    @click="close"
                >
                    ✕
                </button>

                <!-- ================= MODO VISUALIZAR ================= -->
                <template v-if="!editing">
                    <h2 id="modal-title" class="text-lg font-bold mb-4">
                        {{ task.title }}
                    </h2>

                    <div class="space-y-2 text-sm">
                        <p><strong>Estado:</strong> {{ task.status }}</p>
                        <p><strong>Prioridade:</strong> {{ task.priority }}</p>
                        <p><strong>Data limite:</strong> {{ task.due }}</p>
                    </div>

                    <div v-if="task.description" class="mt-4">
                        <p class="text-sm text-gray-500 mb-1">Descrição</p>
                        <div
                            class="p-3 bg-gray-50 rounded text-sm whitespace-pre-line"
                        >
                            {{ task.description }}
                        </div>
                    </div>

                    <hr class="my-5" />

                    <div class="flex justify-end gap-3">
                        <button
                            class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300"
                            @click="editing = true"
                        >
                            Editar
                        </button>

                        <button
                            class="px-4 py-2 rounded text-white"
                            :class="
                                task.status === 'completed'
                                    ? 'bg-yellow-600 hover:bg-yellow-700'
                                    : 'bg-green-600 hover:bg-green-700'
                            "
                            @click="emit('toggle')"
                        >
                            {{
                                task.status === "completed"
                                    ? "Reabrir"
                                    : "Concluir"
                            }}
                        </button>

                        <button
                            class="px-4 py-2 rounded bg-red-600 hover:bg-red-700 text-white"
                            @click="emit('delete')"
                        >
                            Eliminar
                        </button>
                    </div>
                </template>

                <!-- ================= MODO EDITAR ================= -->
                <template v-else>
                    <h2 id="modal-title" class="text-lg font-bold mb-4">
                        Editar tarefa
                    </h2>

                    <div class="space-y-3">
                        <input
                            ref="titleInput"
                            v-model="form.title"
                            type="text"
                            class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300"
                            @keydown.enter.prevent="save"
                            @keydown.esc="close"
                        />

                        <select
                            v-model="form.priority"
                            class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300"
                        >
                            <option value="low">Baixa</option>
                            <option value="medium">Média</option>
                            <option value="high">Alta</option>
                        </select>

                        <input
                            v-model="form.due"
                            type="date"
                            class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300"
                        />

                        <textarea
                            v-model="form.description"
                            rows="4"
                            placeholder="Descrição (opcional)"
                            class="w-full border rounded px-3 py-2 text-sm resize-none focus:ring focus:ring-blue-300"
                        />
                    </div>

                    <hr class="my-5" />

                    <div class="flex justify-end gap-3">
                        <button
                            class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300"
                            @click="close"
                        >
                            Cancelar
                        </button>

                        <button
                            class="px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white flex items-center gap-2"
                            :disabled="saving"
                            @click="save"
                        >
                            <span v-if="saving">A guardar…</span>
                            <span v-else>Guardar</span>
                        </button>
                    </div>
                </template>
            </div>
        </div>
    </transition>
</template>

<script setup>
import { ref, watch, nextTick, onMounted, onUnmounted } from "vue";

const props = defineProps({
    visible: Boolean,
    task: Object,
});

const emit = defineEmits(["close", "toggle", "delete", "save"]);

const editing = ref(false);
const saving = ref(false);
const titleInput = ref(null);

const form = ref({
    title: "",
    description: "",
    priority: "medium",
    due: "",
});

/* Sync tarefa → form */
watch(
    () => props.task,
    (task) => {
        if (!task) return;

        form.value = {
            title: task.title,
            description: task.description ?? "",
            priority: task.priority_key ?? "medium",
            due: task.due_raw ?? "",
        };

        editing.value = false;
    },
    { immediate: true }
);

/* Foco automático + bloquear scroll */
watch(
    () => editing.value,
    async (isEditing) => {
        if (isEditing) {
            await nextTick();
            titleInput.value?.focus();
        }
    }
);

async function save() {
    saving.value = true;

    await emit("save", {
        title: form.value.title,
        description: form.value.description,
        priority: form.value.priority,
        due_date: form.value.due,
    });

    saving.value = false;
}

/* Fechar com ESC */
function onKeydown(e) {
    if (e.key === "Escape" && props.visible) {
        close();
    }
}

function close() {
    editing.value = false;
    saving.value = false;
    emit("close");
}

onMounted(() => window.addEventListener("keydown", onKeydown));
onUnmounted(() => window.removeEventListener("keydown", onKeydown));
</script>

<style scoped>
.fade-scale-enter-active,
.fade-scale-leave-active {
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}
.fade-scale-enter-from {
    opacity: 0;
    transform: scale(0.95) translateY(10px);
}
.fade-scale-leave-to {
    opacity: 0;
    transform: scale(0.97);
}
</style>
