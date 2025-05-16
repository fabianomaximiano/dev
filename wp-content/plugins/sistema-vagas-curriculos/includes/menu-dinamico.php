<?php
if (!defined('ABSPATH')) exit;

// Exibe menu dinamicamente no topo do site (antes do <main>)
add_action('wp_body_open', 'svc_injetar_menu_dinamico');

function svc_injetar_menu_dinamico() {
    if (session_status() === PHP_SESSION_NONE) session_start();

    echo '<div id="menu-candidato-plugin" style="background: #f8f9fa; padding: 10px 0; border-bottom: 1px solid #ddd;">
    <div class="container">
        <ul class="nav justify-content-center">';

    echo '<li class="nav-item"><a class="nav-link" href="' . site_url('/vagas-disponiveis') . '">Vagas</a></li>';

    if (!empty($_SESSION['candidato_id'])) {
        echo '<li class="nav-item"><a class="nav-link" href="' . site_url('/painel-do-candidato') . '">Painel</a></li>';
        echo '<li class="nav-item"><a class="nav-link" href="' . site_url('/logout-candidato') . '">Sair</a></li>';
    } else {
        echo '<li class="nav-item"><a class="nav-link" href="' . site_url('/login-candidato') . '">Login</a></li>';
        echo '<li class="nav-item"><a class="nav-link" href="' . site_url('/cadastro-candidato') . '">Cadastrar-se</a></li>';
    }

    echo '</ul>
    </div>
</div>';
}
