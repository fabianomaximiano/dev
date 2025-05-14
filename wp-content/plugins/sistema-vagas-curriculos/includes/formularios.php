<?php
if (!defined('ABSPATH')) exit;

/**
 * Shortcode: [formulario_candidato]
 * Formulário de cadastro de candidatos
 */
add_shortcode('formulario_candidato', 'svc_formulario_candidato');

function svc_formulario_candidato() {
    ob_start();

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
            echo '<div class="alert alert-danger">CPF já cadastrado.</div>';
        } else {
            $wpdb->insert($tabela, [
                'user_id'       => $user_id,
                'nome_completo' => $nome,
                'cpf'           => $cpf,
                'email'         => $email,
                'telefone'      => $tel,
                'criado_em'     => current_time('mysql')
            ]);

            // Envia e-mail para admin e candidato
            $admin_email = get_option('admin_email');
            $assunto = 'Novo Cadastro de Candidato';
            $mensagem = "Nome: $nome\nCPF: $cpf\nEmail: $email\nTelefone: $tel";

            wp_mail($admin_email, $assunto, $mensagem);
            wp_mail($email, 'Confirmação de Cadastro', "Seu cadastro foi realizado com sucesso!");

            // Redireciona para formulário de currículo
            wp_redirect(site_url('/meu-curriculo'));
            exit;
        }
    }

    ?>
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
    <?php

    return ob_get_clean();
}
