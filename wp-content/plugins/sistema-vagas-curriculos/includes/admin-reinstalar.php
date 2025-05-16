<?php
if (!defined('ABSPATH')) exit;

/**
 * Página oculta para reinstalar o plugin (apagar e recriar tabelas e páginas)
 * Só aparece se SVC_MODO_DEV estiver true no wp-config.php OU se for ativada dinamicamente
 */

add_action('admin_menu', function () {
    if (get_option('svc_modo_dev') !== '1') return;

    add_submenu_page(
        null, // Oculto no menu
        'Reinstalar Plugin SVC',
        'Reinstalar Plugin',
        'manage_options',
        'svc-reinstalar-plugin',
        'svc_render_reinstalar_plugin_page'
    );
});

function svc_render_reinstalar_plugin_page() {
    if (!current_user_can('manage_options')) {
        wp_die('Acesso restrito.');
    }

    echo '<div class="wrap"><h1>Reinstalar Plugin SVC</h1>';

    if (isset($_POST['svc_reinstalar'])) {
        svc_drop_tabelas();
        svc_criar_tabelas();
        echo '<div class="updated notice"><p>Plugin reinstalado com sucesso!</p></div>';
    }

    echo '<form method="post">';
    echo '<p>Essa ação apagará todas as tabelas e recriará os dados padrão do plugin.</p>';
    echo '<p><input type="submit" name="svc_reinstalar" class="button button-primary" value="Reinstalar Agora"></p>';
    echo '</form></div>';
}

/**
 * Deleta as tabelas do plugin manualmente (usado em reinstalação)
 */
function svc_drop_tabelas() {
    global $wpdb;
    $prefix = $wpdb->prefix;

    $wpdb->query("DROP TABLE IF EXISTS {$prefix}svc_candidaturas");
    $wpdb->query("DROP TABLE IF EXISTS {$prefix}svc_curriculos");
    $wpdb->query("DROP TABLE IF EXISTS {$prefix}svc_candidatos");
    $wpdb->query("DROP TABLE IF EXISTS {$prefix}svc_vagas");
}

/**
 * Ativa ou desativa o modo DEV no banco de dados
 */
register_activation_hook(__FILE__, function () {
    add_option('svc_modo_dev', '1');
});

register_deactivation_hook(__FILE__, function () {
    delete_option('svc_modo_dev');
});
