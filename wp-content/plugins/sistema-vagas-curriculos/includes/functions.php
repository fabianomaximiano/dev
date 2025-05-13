<?php
if (!defined('ABSPATH')) exit;

// Registrar shortcodes
add_shortcode('formulario_vaga', 'svc_formulario_vaga_shortcode');
add_shortcode('formulario_curriculo', 'svc_formulario_curriculo_shortcode');
add_shortcode('painel_candidato', 'svc_painel_candidato_shortcode');
add_shortcode('listar_vagas', 'svc_listar_vagas_shortcode');

// Ocultar menus personalizados para usuários não autorizados
add_action('init', function () {
    if (!is_user_logged_in()) {
        remove_shortcode('formulario_curriculo');
        remove_shortcode('painel_candidato');
    }
    if (!current_user_can('administrator')) {
        remove_shortcode('formulario_vaga');
    }
});
