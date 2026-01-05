console.log("Vue app.js carregado");
import { createApp, reactive } from "vue";
import TaskModal from "./vue/components/TaskModal.vue";

const state = reactive({
    visible: false,
    task: null,
    actionTaskId: null,
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
        }

        function deleteFromModal() {
            if (confirm("Eliminar esta tarefa?")) {
                document.getElementById("delete-form").submit();
            }
        }

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

            window.location.reload();
        }

        return {
            state,
            close,
            toggleFromModal,
            deleteFromModal,
            saveFromModal,
        };
    },
    components: { TaskModal },
    template: `
        <task-modal
            :visible="state.visible"
            :task="state.task"
            @close="close"
            @toggle="toggleFromModal"
            @delete="deleteFromModal"
            @save="saveFromModal"
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
