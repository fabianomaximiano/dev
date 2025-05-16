<?php
// Impede acesso direto
if (!defined('ABSPATH')) exit;

// Inicia sessão para controle do candidato
add_action('init', function () {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
});

// Adiciona dinamicamente itens ao menu principal
add_filter('wp_nav_menu_items', 'astra_child_menu_dinamico', 10, 2);
function astra_child_menu_dinamico($items, $args) {
    if ($args->theme_location === 'primary') {
        if (!empty($_SESSION['candidato_id'])) {
            $painel = '<li class="menu-item"><a href="' . site_url('/painel-do-candidato') . '">Painel</a></li>';
            $sair = '<li class="menu-item"><a href="' . site_url('/logout-candidato') . '">Sair</a></li>';
            $items .= $painel . $sair;
        } else {
            $login = '<li class="menu-item"><a href="' . site_url('/login-candidato') . '">Login</a></li>';
            $cadastro = '<li class="menu-item"><a href="' . site_url('/cadastro-candidato') . '">Cadastre-se</a></li>';
            $items .= $login . $cadastro;
        }
    }
    return $items;
}
