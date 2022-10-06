import "./bootstrap";
window.addEventListener("swal:confirm", (e) => {
    swal.fire({
        icon: "question",
        text: e.detail.text,
        title: e.detail.title ?? "",
        showCancelButton: true,
        confirmButtonText: "Yes",
        cancelButtonText: "No",
        iconColor: "#0E7490",
        confirmButtonColor: "#0E7490",
        allowOutsideClick: false,
    }).then((response) => {
        if (response.isConfirmed) {
            if (document.getElementById("loading")) {
                document.getElementById("loading").innerHTML = `
                <div class="text-orange-700 px-6 py-4 border-0 rounded relative mb-4 bg-orange-100">
                    <span class="text-xl inline-block mr-5 align-middle">
                        <svg class="w-6 h-6 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </span>
                    <span class="inline-block align-middle mr-8">
                        Your data is being processed, Don\'t leave this page until you get <strong>success</strong> message or your data will be corrupted
                    </span>
                </div>`;
            }
            if (e.detail.action == "delete") {
                window.livewire.emit("destroy", [e.detail.item]);
            } else {
                window.livewire.emit("store");
            }
        }
    });
});
window.addEventListener("swal:success", (e) => {
    window.livewire.emit("closeModal");
    swal.fire({
        title: "Success",
        text: e.detail.text,
        icon: "success",
        allowOutsideClick: false,
    });
    if (document.getElementById("loading")) {
        document.getElementById("loading").innerHTML = "";
    }
    window.livewire.emit("clearTemp");
});
