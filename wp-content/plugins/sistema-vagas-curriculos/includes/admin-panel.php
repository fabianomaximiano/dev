<?php
if (!defined('ABSPATH')) exit;

function svc_admin_menu() {
    add_menu_page('Gerenciar Vagas', 'Vagas', 'manage_options', 'svc_vagas', 'svc_listar_vagas_admin');
}
add_action('admin_menu', 'svc_admin_menu');

function svc_listar_vagas_admin() {
    global $wpdb;
    $tabela = $wpdb->prefix . 'svc_vagas';
    $vagas = $wpdb->get_results("SELECT * FROM $tabela ORDER BY criado_em DESC");

    echo '<div class="wrap"><h1>Vagas Cadastradas</h1><table class="widefat"><thead><tr><th>Título</th><th>Categoria</th><th>Data</th></tr></thead><tbody>';
    foreach ($vagas as $vaga) {
        echo '<tr>';
        echo '<td>' . esc_html($vaga->titulo) . '</td>';
        echo '<td>' . esc_html($vaga->categoria) . '</td>';
        echo '<td>' . esc_html(date('d/m/Y H:i', strtotime($vaga->criado_em))) . '</td>';
        echo '</tr>';
    }
    echo '</tbody></table></div>';
}
