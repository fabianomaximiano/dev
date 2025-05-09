<?php
add_shortcode('painel_candidato', 'svc_painel_candidato');

function svc_painel_candidato() {
    if (!is_user_logged_in()) {
        return '<p>Você precisa estar logado para acessar seu painel.</p>';
    }

    global $wpdb;
    $user_id = get_current_user_id();
    $tabela_candidatos = $wpdb->prefix . 'svc_candidatos';
    $tabela_curriculos = $wpdb->prefix . 'svc_curriculos';

    $candidato_id = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM $tabela_candidatos WHERE user_id = %d",
        $user_id
    ));

    if (!$candidato_id) {
        return '<p>Erro: seu cadastro de candidato não foi encontrado.</p>';
    }

    $curriculo_id = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM $tabela_curriculos WHERE candidato_id = %d",
        $candidato_id
    ));

    $pagina_curriculo = site_url('/meu-curriculo');

    if ($curriculo_id) {
        $botao = "<a href='{$pagina_curriculo}' class='btn btn-secondary'>Editar Currículo</a>";
    } else {
        $botao = "<a href='{$pagina_curriculo}' class='btn btn-primary'>Preencher Currículo</a>";
    }

    return "<div class='painel-candidato mt-4'><p>Bem-vindo(a) ao seu painel!</p>$botao</div>";
}
