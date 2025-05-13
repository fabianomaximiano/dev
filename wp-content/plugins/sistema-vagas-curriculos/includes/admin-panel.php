<?php
add_action('admin_menu', 'svc_adicionar_menu_admin');

function svc_adicionar_menu_admin() {
    add_menu_page(
        'Gerenciar Vagas',
        'Vagas',
        'manage_options',
        'edit.php?post_type=vaga',
        null,
        'dashicons-businessman',
        6
    );

    add_submenu_page(
        'edit.php?post_type=vaga',
        'Currículos',
        'Currículos',
        'manage_options',
        'svc_curriculos',
        'svc_pagina_curriculos'
    );

    add_submenu_page(
        'edit.php?post_type=vaga',
        'Candidaturas',
        'Candidaturas',
        'manage_options',
        'svc_candidaturas',
        'svc_pagina_candidaturas'
    );

    add_submenu_page(
        'edit.php?post_type=vaga',
        'Painel do Candidato',
        'Painel do Candidato',
        'read',
        'painel-candidato',
        'svc_redirecionar_para_painel'
    );
}

function svc_pagina_curriculos() {
    echo '<div class="wrap"><h1>Currículos</h1>';
    echo '<p>Esta área poderá listar todos os currículos dos candidatos.</p>';
    echo '</div>';
}

function svc_pagina_candidaturas() {
    global $wpdb;

    $candidaturas = $wpdb->get_results("
        SELECT c.id AS candidatura_id, c.data_candidatura,
               can.nome_completo, can.email, can.cpf,
               p.ID as vaga_id, p.post_title
        FROM {$wpdb->prefix}svc_candidaturas c
        LEFT JOIN {$wpdb->prefix}svc_candidatos can ON c.candidato_id = can.id
        LEFT JOIN {$wpdb->prefix}posts p ON c.vaga_id = p.ID
        ORDER BY c.data_candidatura DESC
    ");

    echo '<div class="wrap"><h1>Candidaturas Recebidas</h1>';
    if (empty($candidaturas)) {
        echo '<p>Nenhuma candidatura registrada.</p></div>';
        return;
    }

    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead><tr>
            <th>Candidato</th>
            <th>CPF</th>
            <th>E-mail</th>
            <th>Vaga</th>
            <th>Data</th>
        </tr></thead><tbody>';

    foreach ($candidaturas as $c) {
        echo '<tr>';
        echo '<td>' . esc_html($c->nome_completo) . '</td>';
        echo '<td>' . esc_html($c->cpf) . '</td>';
        echo '<td><a href="mailto:' . esc_attr($c->email) . '">' . esc_html($c->email) . '</a></td>';
        echo '<td><a href="' . esc_url(get_permalink($c->vaga_id)) . '" target="_blank">' . esc_html($c->post_title) . '</a></td>';
        echo '<td>' . date('d/m/Y H:i', strtotime($c->data_candidatura)) . '</td>';
        echo '</tr>';
    }

    echo '</tbody></table></div>';
}

function svc_redirecionar_para_painel() {
    $pagina = get_page_by_path('painel-do-candidato');
    if ($pagina) {
        echo '<script>window.location.href = "' . esc_url(get_permalink($pagina)) . '";</script>';
        exit;
    } else {
        echo '<div class="wrap"><h1>Página não encontrada</h1><p>Crie uma página com o slug "painel-do-candidato".</p></div>';
    }
}
