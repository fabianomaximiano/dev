<?php
if (!defined('ABSPATH')) exit;

// Shortcode [recuperar_senha] - Solicitação de código
add_shortcode('recuperar_senha', 'svc_form_recuperar_senha');
function svc_form_recuperar_senha() {
    $mensagem = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cpf'], $_POST['email'])) {
        global $wpdb;
        $cpf   = sanitize_text_field($_POST['cpf']);
        $email = sanitize_email($_POST['email']);
        $tabela = $wpdb->prefix . 'svc_candidatos';

        $candidato = $wpdb->get_row($wpdb->prepare("SELECT * FROM $tabela WHERE cpf = %s AND email = %s", $cpf, $email));

        if ($candidato) {
            $codigo = rand(100000, 999999);
            $_SESSION['recuperar_senha'] = [
                'cpf' => $cpf,
                'email' => $email,
                'codigo' => $codigo,
                'timestamp' => time()
            ];

            // Envia e-mail
            wp_mail($email, 'Código de Recuperação de Senha', "Seu código de verificação: $codigo");

            wp_redirect(site_url('/nova-senha'));
            exit;
        } else {
            $mensagem = '<div class="alert alert-danger">CPF ou e-mail não encontrados.</div>';
        }
    }

    ob_start(); ?>
    <form method="post" class="needs-validation" novalidate>
        <h3>Recuperar Senha</h3>
        <div class="form-group">
            <label>CPF</label>
            <input type="text" name="cpf" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Enviar código</button>
        <?php echo $mensagem; ?>
    </form>
    <?php
    return ob_get_clean();
}

// Shortcode [nova_senha] - Inserção de nova senha com código
add_shortcode('nova_senha', 'svc_form_nova_senha');
function svc_form_nova_senha() {
    $mensagem = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigo'], $_POST['senha'], $_POST['confirmar'])) {
        if (!isset($_SESSION['recuperar_senha'])) {
            return '<div class="alert alert-warning">Sessão expirada. Inicie o processo novamente.</div>';
        }

        $dados = $_SESSION['recuperar_senha'];
        if ((time() - $dados['timestamp']) > 600) {
            unset($_SESSION['recuperar_senha']);
            return '<div class="alert alert-warning">Código expirado. Inicie o processo novamente.</div>';
        }

        if ($_POST['codigo'] != $dados['codigo']) {
            return '<div class="alert alert-danger">Código inválido.</div>';
        }

        if ($_POST['senha'] !== $_POST['confirmar']) {
            return '<div class="alert alert-danger">As senhas não coincidem.</div>';
        }

        $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
        global $wpdb;
        $tabela = $wpdb->prefix . 'svc_candidatos';
        $wpdb->update($tabela, ['senha' => $senha], ['cpf' => $dados['cpf'], 'email' => $dados['email']]);

        unset($_SESSION['recuperar_senha']);

        return '<div class="alert alert-success">Senha atualizada com sucesso. <a href="' . site_url('/login-candidato') . '">Acessar</a></div>';
    }

    ob_start(); ?>
    <form method="post" class="needs-validation" novalidate>
        <h3>Nova Senha</h3>
        <div class="form-group">
            <label>Código de verificação</label>
            <input type="text" name="codigo" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Nova senha</label>
            <input type="password" name="senha" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Confirmar senha</label>
            <input type="password" name="confirmar" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Salvar nova senha</button>
        <?php echo $mensagem; ?>
    </form>
    <?php
    return ob_get_clean();
}
