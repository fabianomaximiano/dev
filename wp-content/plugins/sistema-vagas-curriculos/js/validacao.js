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

    const form = document.querySelector('form.needs-validation');
    if (form) {
        const cpfInput = form.querySelector('input[name="cpf"]');
        if (cpfInput) {
            form.addEventListener('submit', function (e) {
                if (!validarCPF(cpfInput.value)) {
                    e.preventDefault();
                    cpfInput.classList.add('is-invalid');
                    alert("CPF inválido!");
                } else {
                    cpfInput.classList.remove('is-invalid');
                }
            });
        }
    }

    jQuery(function ($) {
        $('input[name=cpf]').mask('000.000.000-00');
        $('input[name=telefone]').mask('(00) 00000-0000');
    });
});

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
