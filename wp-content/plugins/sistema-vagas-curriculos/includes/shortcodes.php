<?php
if (!defined('ABSPATH')) exit;

/**
 * Shortcode: [menu_candidato]
 * Exibe o menu dinâmico de acordo com o estado do login do candidato
 */
add_shortcode('menu_candidato', 'svc_menu_candidato_shortcode');

function svc_menu_candidato_shortcode() {
    if (session_status() === PHP_SESSION_NONE) session_start();

    ob_start(); ?>
    <ul class="nav justify-content-center my-3">
        <li class="nav-item"><a class="nav-link" href="<?php echo site_url('/vagas-disponiveis'); ?>">Vagas</a></li>

        <?php if (!empty($_SESSION['candidato_id'])) : ?>
            <li class="nav-item"><a class="nav-link" href="<?php echo site_url('/painel-do-candidato'); ?>">Painel</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo site_url('/logout-candidato'); ?>">Sair</a></li>
        <?php else : ?>
            <li class="nav-item"><a class="nav-link" href="<?php echo site_url('/login-candidato'); ?>">Login</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo site_url('/cadastro-candidato'); ?>">Cadastrar-se</a></li>
        <?php endif; ?>

        <?php if (current_user_can('manage_options')) : ?>
            <li class="nav-item"><a class="nav-link" href="<?php echo admin_url(); ?>">Admin</a></li>
        <?php endif; ?>
    </ul>
    <?php
    return ob_get_clean();
}

/**
 * Shortcode: [logout_candidato]
 * Realiza logout do candidato e redireciona para a página de login
 */
add_shortcode('logout_candidato', 'svc_logout_candidato');

function svc_logout_candidato() {
    if (session_status() === PHP_SESSION_NONE) session_start();

    if (isset($_SESSION['candidato_id'])) {
        unset($_SESSION['candidato_id']);
        session_destroy();
    }

    wp_redirect(site_url('/login-candidato'));
    exit;
}
// Conteúdo de exemplo para shortcodes.php
