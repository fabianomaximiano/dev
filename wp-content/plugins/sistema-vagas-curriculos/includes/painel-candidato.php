<?php
if (!defined('ABSPATH')) exit;

add_shortcode('painel_candidato', 'svc_painel_candidato');

function svc_painel_candidato() {
    if (!is_user_logged_in()) {
        return '<div class="alert alert-warning">Você precisa estar logado para acessar o painel do candidato.</div>';
    }

    global $wpdb;
    $user_id = get_current_user_id();

    $candidato = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}svc_candidatos WHERE user_id = %d", $user_id
    ));

    if (!$candidato) {
        return '<div class="alert alert-danger">Cadastro de candidato não encontrado.</div>';
    }

    $curriculo_id = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM {$wpdb->prefix}svc_curriculos WHERE candidato_id = %d", $candidato->id
    ));

    $url_curriculo = site_url('/meu-curriculo');

    ob_start(); ?>

    <div class="container my-4">
        <h2 class="mb-4">Painel do Candidato</h2>

        <p><strong>Nome:</strong> <?php echo esc_html($candidato->nome_completo); ?></p>
        <p><strong>CPF:</strong> <?php echo esc_html($candidato->cpf); ?></p>
        <p><strong>Email:</strong> <?php echo esc_html($candidato->email); ?></p>

        <?php if ($curriculo_id) : ?>
            <a href="<?php echo esc_url($url_curriculo); ?>" class="btn btn-secondary mr-2">Editar Currículo</a>
        <?php else : ?>
            <a href="<?php echo esc_url($url_curriculo); ?>" class="btn btn-primary mr-2">Preencher Currículo</a>
        <?php endif; ?>

        <!-- Botão para exportar currículo em PDF (se implementado) -->
        <?php $pdf_url = add_query_arg('exportar_pdf', '1', $_SERVER['REQUEST_URI']); ?>
        <a href="<?php echo esc_url($pdf_url); ?>" class="btn btn-outline-danger">Exportar PDF</a>

        <hr>
        <h4 class="mt-4">Minhas Candidaturas</h4>

        <?php
        $candidaturas = $wpdb->get_results($wpdb->prepare(
            "SELECT c.data_candidatura, c.status, p.ID as vaga_id, p.post_title
             FROM {$wpdb->prefix}svc_candidaturas c
             LEFT JOIN {$wpdb->prefix}posts p ON c.vaga_id = p.ID
             WHERE c.candidato_id = %d
             ORDER BY c.data_candidatura DESC",
            $candidato->id
        ));

        if ($candidaturas) :
        ?>
            <ul class="list-group mt-3">
                <?php foreach ($candidaturas as $c) : ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <a href="<?php echo esc_url(get_permalink($c->vaga_id)); ?>">
                                <?php echo esc_html($c->post_title); ?>
                            </a><br>
                            <small>Status: <strong><?php echo esc_html($c->status); ?></strong></small>
                        </div>
                        <span class="badge badge-primary badge-pill">
                            <?php echo date('d/m/Y', strtotime($c->data_candidatura)); ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <p class="mt-3">Você ainda não se candidatou a nenhuma vaga.</p>
        <?php endif; ?>
    </div>

    <?php
    return ob_get_clean();
}
