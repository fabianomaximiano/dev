document.addEventListener("DOMContentLoaded", function () {
    const forms = document.getElementsByClassName("needs-validation");
    Array.prototype.forEach.call(forms, function (form) {
        form.addEventListener("submit", function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add("was-validated");
        }, false);
    });
});
