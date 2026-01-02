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
        };
    },
    methods: {
        openFromElement(event) {
            const raw = event.currentTarget?.dataset?.task;
            if (!raw) return;

            const task = JSON.parse(raw);
            this.modal.task = task;
            this.modal.visible = true;
        },

        close() {
            this.modal.visible = false;
            this.modal.task = null;
        },

        toggleFromModal() {
            const id = this.modal?.task?.id;
            if (!id) return;

            const form = document.getElementById(`toggle-form-${id}`);
            if (!form) return;

            form.submit(); // faz PATCH /toggle
        },

        deleteFromModal() {
            const id = this.modal?.task?.id;
            if (!id) return;

            if (!confirm("Tens a certeza que queres eliminar esta tarefa?"))
                return;

            const form = document.getElementById(`delete-form-${id}`);
            if (!form) return;

            form.submit(); // faz DELETE
        },
    },
})
    .component("task-modal", TaskModal)
    .mount("#app");
