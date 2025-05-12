<?php
add_shortcode('painel_candidato', 'svc_painel_candidato');
function svc_painel_candidato() {
    if (!is_user_logged_in()) return '<p>Você precisa estar logado.</p>';
    global $wpdb;
    $user_id = get_current_user_id();
    $cid = $wpdb->get_var($wpdb->prepare("SELECT id FROM {$wpdb->prefix}svc_candidatos WHERE user_id = %d", $user_id));
    if (!$cid) return '<p>Cadastro não encontrado.</p>';
    $curriculo = $wpdb->get_var($wpdb->prepare("SELECT id FROM {$wpdb->prefix}svc_curriculos WHERE candidato_id = %d", $cid));
    $link = site_url('/meu-curriculo');
    return $curriculo ?
        "<a href='$link' class='btn btn-secondary'>Editar Currículo</a>" :
        "<a href='$link' class='btn btn-primary'>Preencher Currículo</a>";
}
