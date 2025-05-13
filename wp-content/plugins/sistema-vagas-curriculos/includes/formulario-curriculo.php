<?php
if (!defined('ABSPATH')) exit;

function svc_formulario_curriculo_shortcode() {
    if (!is_user_logged_in()) {
        return '<div class="alert alert-warning">Você precisa estar logado para cadastrar seu currículo.</div>';
    }

    ob_start();
    include plugin_dir_path(__FILE__) . 'partials/curriculo-form-formacao.php';
    include plugin_dir_path(__FILE__) . 'partials/curriculo-form-experiencias.php';
    include plugin_dir_path(__FILE__) . 'partials/curriculo-form-cursos.php';
    return ob_get_clean();
}
