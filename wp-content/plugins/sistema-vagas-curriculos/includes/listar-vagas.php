<?php
add_shortcode('listar_vagas', 'svc_listar_vagas');

function svc_listar_vagas() {
    $args = [
        'post_type' => 'post',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC'
    ];

    $vagas = new WP_Query($args);

    ob_start();
    echo '<div class="container my-4">';
    echo '<h2 class="mb-4">Vagas Disponíveis</h2>';

    if ($vagas->have_posts()) {
        echo '<div class="list-group">';
        while ($vagas->have_posts()) {
            $vagas->the_post();
            $vaga_id = get_the_ID();
            $titulo = get_the_title();
            $link = get_permalink();
            $requisitos = get_post_meta($vaga_id, 'requisitos', true);
            $diferenciais = get_post_meta($vaga_id, 'diferenciais', true);

            echo '<div class="list-group-item">';
            echo '<h5>' . esc_html($titulo) . '</h5>';
            echo '<p>' . get_the_excerpt() . '</p>';
            echo '<p><strong>Requisitos:</strong> ' . esc_html($requisitos) . '</p>';
            echo '<p><strong>Diferenciais:</strong> ' . esc_html($diferenciais) . '</p>';

            // Verifica status do usuário
            if (!is_user_logged_in()) {
                echo '<a href="' . esc_url(site_url('/login-candidato')) . '" class="btn btn-outline-primary">Fazer login para se candidatar</a>';
            } else {
                $user_id = get_current_user_id();
                global $wpdb;
                $candidato = $wpdb->get_row($wpdb->prepare("SELECT id FROM {$wpdb->prefix}svc_candidatos WHERE user_id = %d", $user_id));

                if (!$candidato) {
                    echo '<a href="' . esc_url(site_url('/cadastro-candidato')) . '" class="btn btn-warning">Preencha seu cadastro</a>';
                } else {
                    // Verifica se já tem currículo
                    $curriculo = $wpdb->get_var($wpdb->prepare(
                        "SELECT id FROM {$wpdb->prefix}svc_curriculos WHERE candidato_id = %d", $candidato->id
                    ));

                    if (!$curriculo) {
                        echo '<a href="' . esc_url(site_url('/meu-curriculo')) . '" class="btn btn-info">Preencher Currículo</a>';
                    } else {
                        // Verifica se já se candidatou
                        $ja_candidatou = $wpdb->get_var($wpdb->prepare(
                            "SELECT id FROM {$wpdb->prefix}svc_candidaturas WHERE candidato_id = %d AND vaga_id = %d",
                            $candidato->id, $vaga_id
                        ));

                        if ($ja_candidatou) {
                            echo '<button class="btn btn-secondary" disabled>Já Candidatado</button>';
                        } else {
                            // Botão para se candidatar
                            $url = add_query_arg([
                                'candidatar' => $vaga_id,
                                '_wpnonce' => wp_create_nonce('svc_candidatar')
                            ]);
                            echo '<a href="' . esc_url($url) . '" class="btn btn-success">Candidatar-se</a>';
                        }
                    }
                }
            }

            echo '</div>';
        }
        echo '</div>';
    } else {
        echo '<p>Não há vagas disponíveis no momento.</p>';
    }

    echo '</div>';

    wp_reset_postdata();
    return ob_get_clean();
}

// Lógica de candidatura (com validação e nonce)
add_action('init', function () {
    if (isset($_GET['candidatar'], $_GET['_wpnonce']) && is_user_logged_in()) {
        if (!wp_verify_nonce($_GET['_wpnonce'], 'svc_candidatar')) {
            wp_die('Falha de segurança.');
        }

        $vaga_id = intval($_GET['candidatar']);
        $user_id = get_current_user_id();
        global $wpdb;

        $candidato = $wpdb->get_row($wpdb->prepare(
            "SELECT id FROM {$wpdb->prefix}svc_candidatos WHERE user_id = %d", $user_id
        ));

        if ($candidato) {
            // Verifica se já se candidatou
            $existe = $wpdb->get_var($wpdb->prepare(
                "SELECT id FROM {$wpdb->prefix}svc_candidaturas WHERE candidato_id = %d AND vaga_id = %d",
                $candidato->id, $vaga_id
            ));

            if (!$existe) {
                $wpdb->insert("{$wpdb->prefix}svc_candidaturas", [
                    'candidato_id' => $candidato->id,
                    'vaga_id' => $vaga_id,
                    'status' => 'Recebido',
                    'data_candidatura' => current_time('mysql')
                ]);
            }

            wp_redirect(site_url('/painel-do-candidato?sucesso=candidatura'));
            exit;
        } else {
            wp_redirect(site_url('/cadastro-candidato'));
            exit;
        }
    }
});
