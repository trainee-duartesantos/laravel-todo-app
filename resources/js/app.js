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
            const task = JSON.parse(event.currentTarget.dataset.task);
            this.modal.task = task;
            this.modal.visible = true;
        },
        close() {
            this.modal.visible = false;
            this.modal.task = null;
        },
    },
})
    .component("task-modal", TaskModal)
    .mount("#app");
