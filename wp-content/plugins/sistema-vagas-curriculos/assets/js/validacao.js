function validarCPF(cpf) {
    cpf = cpf.replace(/[^\d]+/g, '');
    if (cpf.length !== 11 || /^(\d)\1+$/.test(cpf)) return false;

    for (let t = 9; t < 11; t++) {
        let d = 0;
        for (let i = 0; i < t; i++) {
            d += parseInt(cpf.charAt(i)) * (t + 1 - i);
        }
        d = (d * 10) % 11;
        if (d === 10) d = 0;
        if (parseInt(cpf.charAt(t)) !== d) return false;
    }
    return true;
}

document.addEventListener("DOMContentLoaded", function () {
    const forms = document.getElementsByClassName("needs-validation");

    Array.prototype.forEach.call(forms, function (form) {
        const cpfInput = form.querySelector('input[name="cpf"]');

        form.addEventListener("submit", function (event) {
            let valido = true;

            if (cpfInput && !validarCPF(cpfInput.value)) {
                cpfInput.classList.add("is-invalid");
                alert("CPF inválido!");
                valido = false;
            } else {
                cpfInput.classList.remove("is-invalid");
            }

            if (!form.checkValidity() || !valido) {
                event.preventDefault();
                event.stopPropagation();
            }

            form.classList.add("was-validated");
        });
    });

    jQuery(function ($) {
        $('input[name=cpf]').mask('000.000.000-00');
        $('input[name=telefone]').mask('(00) 00000-0000');
    });
});
