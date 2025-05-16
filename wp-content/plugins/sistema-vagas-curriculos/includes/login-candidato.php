<?php
add_shortcode('login_candidato', 'svc_login_candidato');

function svc_login_candidato() {
    if (is_user_logged_in()) {
        return '<div class="alert alert-info">Você já está logado como administrador.</div>';
    }

    if (!isset($_SESSION)) session_start();
    global $wpdb;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
        $email = sanitize_email($_POST['email']);
        $senha = $_POST['senha'];

        $candidato = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}svc_candidatos WHERE email = %s", $email
        ));

        if ($candidato && password_verify($senha, $candidato->senha)) {
            $_SESSION['candidato_id'] = $candidato->id;

            // Verifica se tem currículo
            $curriculo = $wpdb->get_var($wpdb->prepare(
                "SELECT id FROM {$wpdb->prefix}svc_curriculos WHERE candidato_id = %d", $candidato->id
            ));

            $url = $curriculo ? '/painel-do-candidato' : '/meu-curriculo';
            wp_redirect(site_url($url));
            exit;
        } else {
            echo '<div class="alert alert-danger">E-mail ou senha inválidos.</div>';
        }
    }

    ob_start(); ?>
    <div class="container my-4">
        <h2>Login do Candidato</h2>
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
        </form>
        <!-- <p class="mt-3">Não tem cadastro? <a href="<?php //echo site_url('/cadastro-candidato'); ?>">Cadastre-se aqui</a></p> -->
        <small>
            Não tem uma conta? <a href="<?php echo site_url('cadastro-candidato'); ?>">Cadastre-se</a> |
            <a href="<?php echo site_url('/recuperar-senha'); ?>">Esqueceu a senha?</a>
        </small>

    </div>
    <?php
    return ob_get_clean();
}
