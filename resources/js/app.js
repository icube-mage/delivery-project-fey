import "./bootstrap";
window.addEventListener("swal:confirm", (e) => {
    swal.fire({
        icon: "question",
        text: e.detail.text,
        showCancelButton: true,
        confirmButtonText: "Yes",
        cancelButtonText: "No",
        iconColor: "#0E7490",
        confirmButtonColor: "#0E7490",
    }).then((response) => {
        if (response.isConfirmed) {
            window.livewire.emit("store");
        }
    });
});
window.addEventListener("swal:success", (e) => {
    swal.fire({
        title: "Success",
        text: e.detail.text,
        icon: "success",
    });
});
