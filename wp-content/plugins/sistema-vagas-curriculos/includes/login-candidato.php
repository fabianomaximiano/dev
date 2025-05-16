<?php
add_shortcode('login_candidato', 'svc_login_candidato');

function svc_login_candidato() {
    if (is_user_logged_in()) {
        return '<div class="alert alert-info">Você já está logado como administrador.</div>';
    }

    if (session_status() === PHP_SESSION_NONE) session_start();

    global $wpdb;
    $mensagem = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
        $email = sanitize_email($_POST['email']);
        $senha = $_POST['senha'];

        $candidato = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}svc_candidatos WHERE email = %s", $email
        ));

        if ($candidato && password_verify($senha, $candidato->senha)) {
            $_SESSION['candidato_id'] = $candidato->id;

            // Redireciona para currículo se ainda não tiver
            $curriculo = $wpdb->get_var($wpdb->prepare(
                "SELECT id FROM {$wpdb->prefix}svc_curriculos WHERE candidato_id = %d", $candidato->id
            ));

            $url = $curriculo ? '/painel-do-candidato' : '/meu-curriculo';
            wp_redirect(site_url($url));
            exit;
        } else {
            $mensagem = '<div class="alert alert-danger mt-3">E-mail ou senha inválidos.</div>';
        }
    }

    ob_start(); ?>
    
    <form method="post" class="needs-validation" novalidate>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Senha</label>
            <input type="password" name="senha" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Entrar</button>
        <div class="mt-3">
            <a href="<?php echo site_url('/cadastro-candidato'); ?>">Cadastre-se</a> |
            <a href="<?php echo site_url('/recuperar-senha'); ?>">Esqueceu a senha?</a>
        </div>

        <?php echo $mensagem; ?>
    </form>

    <?php
    return ob_get_clean();
}
