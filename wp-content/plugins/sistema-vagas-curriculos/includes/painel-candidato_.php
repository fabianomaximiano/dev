<?php
add_shortcode('painel_candidato', 'svc_painel_candidato');

function svc_painel_candidato() {
    if (!is_user_logged_in()) {
        return '<p class="alert alert-warning">Você precisa estar logado para acessar o painel do candidato.</p>';
        $pdf_url = add_query_arg('exportar_pdf', '1', $_SERVER['REQUEST_URI']);
        echo '<a href="' . esc_url($pdf_url) . '" class="btn btn-outline-danger ml-2">Exportar PDF</a>';
    }

    global $wpdb;
    $user_id = get_current_user_id();

    $candidato_id = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM {$wpdb->prefix}svc_candidatos WHERE user_id = %d", $user_id
    ));

    if (!$candidato_id) {
        return '<p class="alert alert-danger">Cadastro de candidato não encontrado.</p>';
    }

    $curriculo_id = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM {$wpdb->prefix}svc_curriculos WHERE candidato_id = %d", $candidato_id
    ));

    $url_curriculo = site_url('/meu-curriculo');

    ob_start();
    echo '<div class="painel-candidato mt-4">';
    if ($curriculo_id) {
        echo '<a href="' . esc_url($url_curriculo) . '" class="btn btn-secondary">Editar Currículo</a>';
    } else {
        echo '<a href="' . esc_url($url_curriculo) . '" class="btn btn-primary">Preencher Currículo</a>';
    }
    echo '</div>';

    return ob_get_clean();

    add_action('init', 'svc_exportar_curriculo_pdf');

function svc_exportar_curriculo_pdf() {
    if (!is_user_logged_in() || !isset($_GET['exportar_pdf'])) return;

    global $wpdb;
    require_once __DIR__ . '/../vendor/autoload.php'; // Caminho para o Dompdf

    $user_id = get_current_user_id();

    $candidato = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}svc_candidatos WHERE user_id = %d", $user_id
    ));

    if (!$candidato) return;

    $curriculo = $wpdb->get_var($wpdb->prepare(
        "SELECT dados FROM {$wpdb->prefix}svc_curriculos WHERE candidato_id = %d",
        $candidato->id
    ));

    if (!$curriculo) return;

    // Geração do HTML
    $html = '<h2>Currículo de ' . esc_html($candidato->nome_completo) . '</h2>';
    $html .= '<p><strong>CPF:</strong> ' . esc_html($candidato->cpf) . '</p>';
    $html .= '<p><strong>Email:</strong> ' . esc_html($candidato->email) . '</p>';
    $html .= '<hr><pre style="font-family:Arial; font-size:13px;">' . esc_html($curriculo) . '</pre>';

    // Geração do PDF
    $dompdf = new Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $dompdf->stream("curriculo_" . $user_id . ".pdf", ["Attachment" => true]);
    exit;
}

}
