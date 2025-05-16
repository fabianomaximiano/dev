document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form.needs-validation');
    const cpfInput = form.querySelector('input[name="cpf"]');
    const telInput = form.querySelector('input[name="telefone"]');
    const cepInput = document.getElementById('cep');
    const senhaInput = document.getElementById('senha');
    const toggleSenha = document.getElementById('toggle-senha');
    const msgErroCpf = document.getElementById('mensagem-erro-cpf');
    const iconSenha = document.getElementById('icon-senha');

    // Aplica máscaras usando jQuery Mask
    jQuery(function ($) {
        $('input[name="cpf"]').mask('000.000.000-00');
        $('input[name="telefone"]').mask('(00) 00000-0000');
        $('input[name="cep"]').mask('00000-000');
    });

    // Toggle mostrar/ocultar senha
    // if (toggleSenha) {
    //     toggleSenha.addEventListener('click', function () {
    //         if (senhaInput.type === 'password') {
    //             senhaInput.type = 'text';
    //             toggleSenha.textContent = 'Ocultar';
    //         } else {
    //             senhaInput.type = 'password';
    //             toggleSenha.textContent = 'Mostrar';
    //         }
    //     });
    // }

    // Toggle mostrar/ocultar senha com ícone
       

        if (senhaInput && toggleSenha && iconSenha) {
            toggleSenha.addEventListener('click', function () {
                const isSenha = senhaInput.type === 'password';
                senhaInput.type = isSenha ? 'text' : 'password';
                iconSenha.classList.toggle('fa-eye');
                iconSenha.classList.toggle('fa-eye-slash');
            });
        }


    // Validação de CPF
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

    form.addEventListener('submit', function (e) {
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }

        if (!validarCPF(cpfInput.value)) {
            e.preventDefault();
            cpfInput.classList.add('is-invalid');
            if (msgErroCpf) {
                msgErroCpf.innerHTML = '<div class="alert alert-danger mt-3">CPF inválido. Verifique e tente novamente.</div>';
            }
        } else {
            cpfInput.classList.remove('is-invalid');
            if (msgErroCpf) {
                msgErroCpf.innerHTML = '';
            }
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
                        document.getElementById('endereco').value = data.logradouro;
                        document.getElementById('cidade').value = data.localidade;
                        document.getElementById('estado').value = data.uf;

                        // Exibe campos ocultos
                        document.getElementById('grupo-endereco').classList.remove('d-none');
                        document.getElementById('grupo-cidade').classList.remove('d-none');
                        document.getElementById('grupo-estado').classList.remove('d-none');
                        document.getElementById('grupo-numero').classList.remove('d-none');
                    }
                });
        }
    });
});


function validarSenha(senha) {
    const regex = /^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
    return regex.test(senha);
}

form.addEventListener('submit', function (e) {
    // ... CPF etc

    const senhaInput = form.querySelector('input[name="senha"]');
    const senha = senhaInput.value;
    const erroSenha = document.getElementById('erro-senha');

    if (!validarSenha(senha)) {
        e.preventDefault();
        senhaInput.classList.add('is-invalid');
        erroSenha.classList.remove('d-none');
        erroSenha.innerText = 'A senha deve ter no mínimo 8 caracteres, com letra maiúscula, número e símbolo.';
    } else {
        senhaInput.classList.remove('is-invalid');
        erroSenha.classList.add('d-none');
    }
});
