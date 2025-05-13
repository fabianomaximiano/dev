<?php
add_shortcode('painel_candidato', 'svc_painel_candidato');

function svc_painel_candidato() {
    if (!is_user_logged_in()) {
        return '<p class="alert alert-warning">Você precisa estar logado para acessar o painel do candidato.</p>';
    }

    global $wpdb;
    $user_id = get_current_user_id();

    // Obter ID do candidato
    $candidato = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}svc_candidatos WHERE user_id = %d", $user_id
    ));

    if (!$candidato) {
        return '<p class="alert alert-danger">Cadastro de candidato não encontrado.</p>';
    }

    // Verificar se já tem currículo
    $curriculo_id = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM {$wpdb->prefix}svc_curriculos WHERE candidato_id = %d", $candidato->id
    ));

    $url_curriculo = site_url('/meu-curriculo');
    $url_editar_dados = site_url('/cadastro-de-candidato');
    $url_senha = site_url('/wp-login.php?action=lostpassword');
    $pdf_url = add_query_arg('exportar_pdf', '1', $_SERVER['REQUEST_URI']);

    ob_start();
    ?>

    <div class="painel-candidato mt-4">
        <h2>Painel do Candidato</h2>

        <div class="mb-3">
            <p><strong>Nome:</strong> <?= esc_html($candidato->nome_completo); ?></p>
            <p><strong>CPF:</strong> <?= esc_html($candidato->cpf); ?></p>
            <p><strong>E-mail:</strong> <?= esc_html($candidato->email); ?></p>
        </div>

        <div class="mb-4">
            <?php if ($curriculo_id): ?>
                <a href="<?= esc_url($url_curriculo); ?>" class="btn btn-secondary">Editar Currículo</a>
            <?php else: ?>
                <a href="<?= esc_url($url_curriculo); ?>" class="btn btn-primary">Preencher Currículo</a>
            <?php endif; ?>

            <a href="<?= esc_url($url_editar_dados); ?>" class="btn btn-outline-primary">Editar Dados</a>
            <a href="<?= esc_url($url_senha); ?>" class="btn btn-outline-warning">Alterar Senha</a>
            <a href="<?= esc_url($pdf_url); ?>" class="btn btn-outline-danger">Exportar PDF</a>
        </div>

        <h4>Minhas Candidaturas</h4>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Vaga</th>
                    <th>Data</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $candidaturas = $wpdb->get_results($wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}svc_candidaturas WHERE candidato_id = %d ORDER BY data_candidatura DESC",
                $candidato->id
            ));

            if ($candidaturas) {
                foreach ($candidaturas as $item) {
                    echo '<tr>';
                    echo '<td>' . esc_html($item->vaga_titulo) . '</td>';
                    echo '<td>' . date('d/m/Y', strtotime($item->data_candidatura)) . '</td>';
                    echo '<td>' . esc_html($item->status) . '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="3">Nenhuma candidatura encontrada.</td></tr>';
            }
            ?>
            </tbody>
        </table>
    </div>

    <?php
    return ob_get_clean();
}

// 🧾 Exportar currículo em PDF
add_action('init', 'svc_exportar_curriculo_pdf');

function svc_exportar_curriculo_pdf() {
    if (!is_user_logged_in() || !isset($_GET['exportar_pdf'])) return;

    global $wpdb;
    require_once __DIR__ . '/../vendor/autoload.php'; // Caminho para Dompdf

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

    // Gerar PDF
    $dompdf = new Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $dompdf->stream("curriculo_" . $user_id . ".pdf", ["Attachment" => true]);
    exit;
}
