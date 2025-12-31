import "./bootstrap";

window.openModal = function (title, status, priority, due) {
    document.getElementById("modal-title").innerText = title;
    document.getElementById("modal-status").innerText = status;
    document.getElementById("modal-priority").innerText = priority;
    document.getElementById("modal-due").innerText = due;

    document.getElementById("task-modal").classList.remove("hidden");
    document.getElementById("task-modal").classList.add("flex");
};

window.closeModal = function () {
    document.getElementById("task-modal").classList.add("hidden");
    document.getElementById("task-modal").classList.remove("flex");
};
