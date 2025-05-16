<?php
// Evita acesso direto
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Registra a action Ajax para salvar o currículo
add_action( 'wp_ajax_salvar_curriculo', 'svc_salvar_curriculo_callback' );

function svc_salvar_curriculo_callback() {
    // Valida nonce para segurança
    check_ajax_referer( 'svc_nonce', 'security' );

    // Recebe os dados enviados via POST
    $dados = isset($_POST['dados']) ? $_POST['dados'] : [];

    // Aqui você deve validar e sanitizar os dados
    // Exemplo simples de sanitização (adaptar conforme os campos do currículo)
    $empresa = sanitize_text_field( $dados['experiencia_empresa'] ?? '' );
    $funcao = sanitize_text_field( $dados['experiencia_funcao'] ?? '' );
    $periodo = sanitize_text_field( $dados['experiencia_periodo'] ?? '' );
    // ... continue para os demais campos do currículo

    // Salvar os dados no banco de dados (exemplo simples)
    $user_id = get_current_user_id();

    if ( ! $user_id ) {
        wp_send_json_error( [ 'message' => 'Usuário não logado.' ] );
        wp_die();
    }

    // Exemplo de update_user_meta para salvar os dados do currículo
    update_user_meta( $user_id, 'curriculo_experiencia_empresa', $empresa );
    update_user_meta( $user_id, 'curriculo_experiencia_funcao', $funcao );
    update_user_meta( $user_id, 'curriculo_experiencia_periodo', $periodo );
    // ... salve os demais campos da mesma forma

    // Retorna resposta de sucesso
    wp_send_json_success( [ 'message' => 'Currículo salvo com sucesso!' ] );

    wp_die();
}
