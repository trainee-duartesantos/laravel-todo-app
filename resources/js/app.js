import "./bootstrap";
import { createApp } from "vue";
import TaskModal from "./vue/components/TaskModal.vue";

createApp({
    data() {
        return {
            modal: {
                visible: false,
                task: null,
            },
            actionTaskId: null,
            editForm: {
                title: "",
                priority: "",
                due: "",
            },
        };
    },

    methods: {
        openFromElement(event) {
            const raw = event.currentTarget?.dataset?.task;
            if (!raw) return;

            const task = JSON.parse(raw);

            this.modal.task = task;
            this.modal.visible = true;

            // preparar edição
            this.actionTaskId = task.id;
            this.editForm.title = task.title;
            this.editForm.priority = task.priority_key ?? "medium";
            this.editForm.due = task.due_raw ?? "";
        },

        close() {
            this.modal.visible = false;
            this.modal.task = null;
        },

        toggleFromModal() {
            const form = document.getElementById(
                `toggle-form-${this.actionTaskId}`
            );
            if (form) form.submit();
        },

        deleteFromModal() {
            if (!confirm("Tens a certeza que queres eliminar esta tarefa?"))
                return;

            const form = document.getElementById(
                `delete-form-${this.actionTaskId}`
            );
            if (form) form.submit();
        },

        saveFromModal(data) {
            if (!this.modal?.task?.id) return;

            fetch(`/tasks/${this.modal.task.id}`, {
                method: "PATCH",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                    Accept: "application/json",
                },
                body: JSON.stringify({
                    title: data.title,
                    priority: data.priority,
                    due_date: data.due_date,
                }),
            })
                .then((response) => {
                    if (!response.ok) throw new Error("Erro ao guardar tarefa");
                    window.location.reload(); // 🔄 atualiza lista
                })
                .catch((err) => {
                    console.error(err);
                    alert("Erro ao guardar tarefa");
                });
        },
    },
})
    .component("task-modal", TaskModal)
    .mount("#app");
