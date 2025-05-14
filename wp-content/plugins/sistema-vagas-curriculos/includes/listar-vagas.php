<?php
if (!defined('ABSPATH')) exit;

add_shortcode('listar_vagas', 'svc_listar_vagas');

function svc_listar_vagas() {
    ob_start();

    // Trata candidatura
    if (isset($_GET['candidatar']) && is_user_logged_in()) {
        global $wpdb;
        $user_id = get_current_user_id();

        $candidato_id = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM {$wpdb->prefix}svc_candidatos WHERE user_id = %d", $user_id
        ));

        if ($candidato_id) {
            $vaga_id = intval($_GET['candidatar']);

            // Verifica se já existe candidatura
            $existe = $wpdb->get_var($wpdb->prepare(
                "SELECT id FROM {$wpdb->prefix}svc_candidaturas WHERE candidato_id = %d AND vaga_id = %d",
                $candidato_id, $vaga_id
            ));

            if (!$existe) {
                $wpdb->insert("{$wpdb->prefix}svc_candidaturas", [
                    'candidato_id' => $candidato_id,
                    'vaga_id' => $vaga_id,
                    'status' => 'Recebido',
                    'data_candidatura' => current_time('mysql')
                ]);
                echo '<div class="alert alert-success">Você se candidatou à vaga com sucesso!</div>';
            } else {
                echo '<div class="alert alert-info">Você já se candidatou a essa vaga.</div>';
            }
        }
    }

    $args = [
        'post_type'      => 'post',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC'
    ];

    $vagas = new WP_Query($args);

    if ($vagas->have_posts()) {
        echo '<div class="row">';
        while ($vagas->have_posts()) : $vagas->the_post();
            $vaga_id = get_the_ID();
            $requisitos = get_post_meta($vaga_id, 'requisitos', true);
            $diferenciais = get_post_meta($vaga_id, 'diferenciais', true);
            $categoria = get_post_meta($vaga_id, 'categoria', true);
            ?>
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><?php the_title(); ?></h5>
                        <p class="card-text"><?php the_excerpt(); ?></p>
                        <?php if ($categoria): ?>
                            <p><strong>Categoria:</strong> <?php echo esc_html($categoria); ?></p>
                        <?php endif; ?>
                        <?php if ($requisitos): ?>
                            <p><strong>Requisitos:</strong><br><?php echo nl2br(esc_html($requisitos)); ?></p>
                        <?php endif; ?>
                        <?php if ($diferenciais): ?>
                            <p><strong>Diferenciais:</strong><br><?php echo nl2br(esc_html($diferenciais)); ?></p>
                        <?php endif; ?>
                        <?php if (is_user_logged_in()) : ?>
                            <a href="<?php echo esc_url(add_query_arg('candidatar', $vaga_id)); ?>" class="btn btn-primary">Candidatar-se</a>
                        <?php else : ?>
                            <a href="<?php echo esc_url(site_url('/login-candidato')); ?>" class="btn btn-outline-secondary">Fazer login para se candidatar</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php
        endwhile;
        echo '</div>';
        wp_reset_postdata();
    } else {
        echo '<p>Nenhuma vaga disponível no momento.</p>';
    }

    return ob_get_clean();
}
