document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form.needs-validation');
    const cpfInput = form.querySelector('input[name="cpf"]');
    const telInput = form.querySelector('input[name="telefone"]');
    const cepInput = document.getElementById('cep');

    jQuery(function ($) {
        $('input[name="cpf"]').mask('000.000.000-00');
        $('input[name="telefone"]').mask('(00) 00000-0000');
        $('input[name="cep"]').mask('00000-000');
    });

    // Validação de CPF
    function validarCPF(cpf) {
        cpf = cpf.replace(/[^\d]+/g, '');
        if (cpf.length !== 11 || /^([0-9])\1+$/.test(cpf)) return false;
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

    form.addEventListener('submit', function (e) {
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }

        if (!validarCPF(cpfInput.value)) {
            e.preventDefault();
            cpfInput.classList.add('is-invalid');
            const alertaCPF = document.createElement('div');
            alertaCPF.classList.add('alert', 'alert-danger', 'mt-3');
            alertaCPF.textContent = "CPF inválido!";
            if (!form.querySelector('.alert-danger')) {
                form.appendChild(alertaCPF);
            }
        } else {
            cpfInput.classList.remove('is-invalid');
            const alertaExistente = form.querySelector('.alert-danger');
            if (alertaExistente) alertaExistente.remove();
        }

        // Validação confirmação senha
        const senhaInput = document.getElementById('senha');
        const senhaConfirmInput = document.getElementById('senha_confirmacao');
        const erroSenha = document.getElementById('erro-senha');

        if (senhaInput.value !== senhaConfirmInput.value) {
            e.preventDefault();
            erroSenha.textContent = 'As senhas não conferem.';
            senhaConfirmInput.classList.add('is-invalid');
        } else {
            erroSenha.textContent = '';
            senhaConfirmInput.classList.remove('is-invalid');
        }

        form.classList.add('was-validated');
    }, false);

    // Busca endereço via ViaCEP
    cepInput.addEventListener('blur', function () {
        const cep = cepInput.value.replace(/\D/g, '');
        if (cep.length === 8) {
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    if (!data.erro) {
                        document.getElementById('logradouro').value = data.logradouro;
                        document.getElementById('cidade').value = data.localidade;
                        document.getElementById('estado').value = data.uf;

                        document.getElementById('grupo-endereco').classList.remove('d-none');
                        document.getElementById('grupo-cidade').classList.remove('d-none');
                        document.getElementById('grupo-estado').classList.remove('d-none');
                        document.getElementById('grupo-numero').classList.remove('d-none');
                    }
                });
        }
    });

    // Toggle senha
    const toggleSenha = document.getElementById('toggle-senha');
    const senhaInput = document.getElementById('senha');
    const iconSenha = document.getElementById('icon-senha');

    toggleSenha.addEventListener('click', () => {
        if (senhaInput.type === 'password') {
            senhaInput.type = 'text';
            iconSenha.classList.remove('fa-eye');
            iconSenha.classList.add('fa-eye-slash');
        } else {
            senhaInput.type = 'password';
            iconSenha.classList.remove('fa-eye-slash');
            iconSenha.classList.add('fa-eye');
        }
    });
});
