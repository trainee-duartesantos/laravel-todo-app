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

        function showToast(message) {
            state.toast.message = message;
            state.toast.visible = true;
        }

        function closeToast() {
            state.toast.visible = false;
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

        async function saveFromModal(data) {
            await fetch(`/tasks/${state.actionTaskId}`, {
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

            showToast("Tarefa atualizada ✔️");
            setTimeout(() => window.location.reload(), 800);
        }

        return {
            state,
            close,
            toggleFromModal,
            deleteFromModal,
            saveFromModal,
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

// 🌍 Função chamada pelo Blade
window.openFromElement = function (event) {
    console.log("CLICK NA TAREFA");
    const el = event.currentTarget;
    const task = JSON.parse(el.dataset.task);

    state.task = task;
    state.visible = true;
    state.actionTaskId = task.id;

    document.getElementById("toggle-form").action = `/tasks/${task.id}/toggle`;

    document.getElementById("delete-form").action = `/tasks/${task.id}`;
};
