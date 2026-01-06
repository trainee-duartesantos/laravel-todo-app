import { createApp, reactive } from "vue";
import TaskModal from "./vue/components/TaskModal.vue";
import Toast from "./vue/components/Toast.vue";

const state = reactive({
    visible: false,
    task: null,
    actionTaskId: null,
    toast: {
        visible: false,
        message: "",
    },
});

const app = createApp({
    setup() {
        function close() {
            state.visible = false;
            state.task = null;
            state.actionTaskId = null;
        }

        function showToast(message) {
            state.toast.message = message;
            state.toast.visible = true;
        }

        function closeToast() {
            state.toast.visible = false;
        }

        function toggleFromModal() {
            document.getElementById("toggle-form").submit();
            showToast("Estado da tarefa atualizado ✔️");
        }

        function deleteFromModal() {
            if (confirm("Eliminar esta tarefa?")) {
                document.getElementById("delete-form").submit();
                showToast("Tarefa eliminada 🗑️");
            }
        }

        async function saveFromModal(data) {
            const res = await fetch(`/tasks/${state.actionTaskId}`, {
                method: "PATCH",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                    Accept: "application/json",
                },
                body: JSON.stringify(data),
            });

            if (!res.ok) {
                alert("Erro ao guardar a tarefa");
                return;
            }

            // 🔔 feedback
            showToast("Tarefa atualizada ✔️");

            // ❌ fecha o modal
            state.visible = false;
            state.task = null;
            state.actionTaskId = null;

            // 🔄 atualiza a lista
            window.location.reload();
        }

        return {
            state,
            close,
            toggleFromModal,
            deleteFromModal,
            saveFromModal,
            showToast,
            closeToast,
        };
    },
    components: { TaskModal, Toast },
    template: `
        <task-modal
            :visible="state.visible"
            :task="state.task"
            @close="close"
            @toggle="toggleFromModal"
            @delete="deleteFromModal"
            @save="saveFromModal"
        />

        <toast
            :visible="state.toast.visible"
            :message="state.toast.message"
            @close="closeToast"
        />
    `,
});

app.mount("#task-modal-root");

// 🌍 Função global chamada pelo Blade
window.openFromElement = function (event) {
    const el = event.currentTarget;
    const task = JSON.parse(el.dataset.task);

    state.task = task;
    state.visible = true;
    state.actionTaskId = task.id;

    document.getElementById("toggle-form").action = `/tasks/${task.id}/toggle`;
    document.getElementById("delete-form").action = `/tasks/${task.id}`;
};

const flash = document.getElementById("welcome-flash");

if (flash) {
    setTimeout(() => {
        flash.classList.add("transition", "opacity-0", "translate-y-1");

        setTimeout(() => flash.remove(), 300);
    }, 3000); // 👈 tempo visível (3s)
}
