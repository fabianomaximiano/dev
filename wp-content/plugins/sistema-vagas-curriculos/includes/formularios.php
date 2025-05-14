<?php
add_shortcode('formulario_candidato', 'svc_formulario_candidato');

function svc_formulario_candidato() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cpf'])) {
        global $wpdb;
        $cpf    = sanitize_text_field($_POST['cpf']);
        $email  = sanitize_email($_POST['email']);
        $nome   = sanitize_text_field($_POST['nome_completo']);
        $tel    = sanitize_text_field($_POST['telefone']);
        $user_id = get_current_user_id();

        $tabela = $wpdb->prefix . 'svc_candidatos';

        $existe = $wpdb->get_var($wpdb->prepare("SELECT id FROM $tabela WHERE cpf = %s", $cpf));
        if ($existe) {
            echo "<div class='alert alert-danger'>CPF já cadastrado!</div>";
        } else {
            $wpdb->insert($tabela, [
                'user_id' => $user_id,
                'nome_completo' => $nome,
                'cpf' => $cpf,
                'email' => $email,
                'telefone' => $tel
            ]);

            // Login automático se não estiver logado
            if (!is_user_logged_in()) {
                $user = get_user_by('email', $email);
                if ($user) {
                    wp_set_current_user($user->ID);
                    wp_set_auth_cookie($user->ID);
                }
            }

            // Redireciona para o currículo
            wp_redirect(site_url('/meu-curriculo'));
            exit;
        }
    }

    ob_start(); ?>
    <form method="post" class="needs-validation" novalidate>
        <div class="form-group">
            <label>Nome completo</label>
            <input type="text" name="nome_completo" class="form-control" required>
        </div>
        <div class="form-group">
            <label>CPF</label>
            <input type="text" name="cpf" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Telefone</label>
            <input type="text" name="telefone" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Cadastrar</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form.needs-validation');
            const cpfInput = form.querySelector('input[name="cpf"]');
            const telInput = form.querySelector('input[name="telefone"]');

            // Máscaras
            jQuery(function ($) {
                $('input[name="cpf"]').mask('000.000.000-00');
                $('input[name="telefone"]').mask('(00) 00000-0000');
            });

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
                    alert("CPF inválido!");
                } else {
                    cpfInput.classList.remove('is-invalid');
                }

                form.classList.add('was-validated');
            }, false);
        });
    </script>
    <?php
    return ob_get_clean();
}
