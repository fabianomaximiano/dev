<?php
if (!defined('ABSPATH')) exit;

// Shortcodes
add_shortcode('formulario_candidato', 'svc_formulario_candidato_shortcode');
add_shortcode('formulario_curriculo', 'svc_formulario_curriculo_shortcode');
add_shortcode('formulario_vaga', 'svc_formulario_vaga_shortcode');
add_shortcode('painel_candidato', 'svc_painel_candidato_shortcode');
add_shortcode('listar_vagas', 'svc_listar_vagas_shortcode');

// Scripts e estilos
function svc_enqueue_scripts() {
    wp_enqueue_style('bootstrap-css', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
    wp_enqueue_script('jquery-mask', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js', ['jquery'], null, true);
    wp_enqueue_script('validacao-svc', plugin_dir_url(__FILE__) . '../assets/js/validacao.js', ['jquery'], null, true);

    wp_enqueue_script('bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js', ['jquery'], null, true);
}
add_action('wp_enqueue_scripts', 'svc_enqueue_scripts');

// Redirecionamento após cadastro
function svc_redirecionar_apos_cadastro() {
    if (isset($_GET['cadastro']) && $_GET['cadastro'] == 'sucesso') {
        echo "<script>window.location.href = '" . site_url('/cadastro-de-curriculo') . "';</script>";
    }
}
add_action('wp_footer', 'svc_redirecionar_apos_cadastro');
