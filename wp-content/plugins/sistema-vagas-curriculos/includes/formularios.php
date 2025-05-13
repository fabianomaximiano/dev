<?php
add_shortcode('formulario_candidato', 'svc_formulario_candidato');

function svc_formulario_candidato() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cpf'])) {
        global $wpdb;

        $cpf = preg_replace('/[^0-9]/', '', $_POST['cpf']);
        $email = sanitize_email($_POST['email']);
        $telefone = sanitize_text_field($_POST['telefone']);
        $nome = sanitize_text_field($_POST['nome_completo']);
        $user_id = get_current_user_id();

        // Validação básica de CPF
        if (!svc_validar_cpf($cpf)) {
            return "<p class='text-danger'>CPF inválido.</p>";
        }

        if (!is_email($email)) {
            return "<p class='text-danger'>E-mail inválido.</p>";
        }

        $tabela = $wpdb->prefix . 'svc_candidatos';
        $existe = $wpdb->get_var($wpdb->prepare("SELECT id FROM $tabela WHERE cpf = %s", $cpf));

        if ($existe) {
            return "<p class='text-danger'>CPF já cadastrado!</p>";
        } else {
            $wpdb->insert($tabela, [
                'user_id' => $user_id,
                'nome_completo' => $nome,
                'cpf' => $cpf,
                'email' => $email,
                'telefone' => $telefone
            ]);

            // Enviar e-mails
            $admin_email = get_option('admin_email');
            $mensagem = "Novo candidato cadastrado:\n\nNome: $nome\nCPF: $cpf\nEmail: $email\nTelefone: $telefone";

            wp_mail($admin_email, 'Novo Cadastro de Candidato', $mensagem);
            wp_mail($email, 'Confirmação de Cadastro', "Olá $nome,\n\nSeu cadastro foi realizado com sucesso!");

            //return "<p class='text-success'>Cadastro realizado com sucesso.</p>";

            // Redirecionar para o formulário de currículo
            wp_redirect(site_url('/meu-curriculo'));
            exit;

        }
    }

    // Enfileira scripts e estilos
    wp_enqueue_style('bootstrap4', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-mask', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js', ['jquery'], null, true);
    wp_add_inline_script('jquery-mask', "
        jQuery(document).ready(function($){
            $('input[name=cpf]').mask('000.000.000-00');
            $('input[name=telefone]').mask('(00) 00000-0000');
        });
    ");

    ob_start(); ?>
    <form method="post" class="needs-validation" novalidate>
        <div class="form-group">
            <label>Nome completo</label>
            <input type="text" name="nome_completo" placeholder="Favor informar nome completo" class="form-control" required>
            <div class="invalid-feedback">Informe seu nome completo.</div>
        </div>
        <div class="form-group">
            <label>CPF</label>
            <input type="text" name="cpf" class="form-control" placeholder="000.000.000.00" required pattern="\d{3}\.\d{3}\.\d{3}-\d{2}">
            <div class="invalid-feedback">Informe um CPF válido.</div>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" placeholder="nome@dominio.com.br" class="form-control" required>
            <div class="invalid-feedback">Informe um e-mail válido.</div>
        </div>
        <div class="form-group">
            <label>Telefone</label>
            <input type="text" name="telefone" placeholder="(11)00000-0000" class="form-control" required>
            <div class="invalid-feedback">Informe um telefone válido.</div>
        </div>
        <button type="submit" class="btn btn-primary">Cadastrar</button>
    </form>
    <?php
    return ob_get_clean();
}

function svc_validar_cpf($cpf) {
    if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) return false;
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) return false;
    }
    return true;
}
