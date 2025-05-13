<?php
add_shortcode('listar_vagas', 'svc_listar_vagas');

function svc_listar_vagas() {
    $vagas = get_posts([
        'post_type' => 'vaga',
        'post_status' => 'publish',
        'numberposts' => -1,
        'orderby' => 'date',
        'order' => 'DESC'
    ]);

    $categorias = get_categories(['hide_empty' => false]);

    echo '<form method="get" class="form-inline mb-3">';
    echo '<label class="mr-2" for="filtro_categoria">Filtrar por categoria:</label>';
    echo '<select name="categoria" id="filtro_categoria" class="form-control mr-2">';
    echo '<option value="">Todas</option>';
    foreach ($categorias as $cat) {
        $selected = (isset($_GET['categoria']) && $_GET['categoria'] == $cat->slug) ? 'selected' : '';
        echo '<option value="' . esc_attr($cat->slug) . '" ' . $selected . '>' . esc_html($cat->name) . '</option>';
    }
    echo '</select>';
    echo '<button type="submit" class="btn btn-secondary">Filtrar</button>';
    echo '</form>';

    $args = [
        'post_type' => 'vaga',
        'post_status' => 'publish',
        'numberposts' => -1,
        'orderby' => 'date',
        'order' => 'DESC',
    ];

    if (!empty($_GET['categoria'])) {
        $args['category_name'] = sanitize_text_field($_GET['categoria']);
    }

    $vagas = get_posts($args);

    if (!$vagas) return '<p>Nenhuma vaga disponível no momento.</p>';

    ob_start();
    wp_enqueue_style('bootstrap4', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
    echo '<div class="container my-4">';
    foreach ($vagas as $vaga) {
        echo '<div class="vaga border rounded p-3 mb-4">';
        echo '<h4>' . esc_html($vaga->post_title) . '</h4>';
        echo '<div>' . wpautop($vaga->post_content) . '</div>';

        if (is_user_logged_in()) {
            echo '<form method="post">
                <input type="hidden" name="vaga_id" value="' . esc_attr($vaga->ID) . '">
                <button type="submit" name="svc_candidatar" class="btn btn-primary mt-2">Candidatar-se</button>
            </form>';
        } else {
            $url_login = wp_login_url(get_permalink());
            echo '<a href="' . esc_url($url_login) . '" class="btn btn-secondary mt-2">Fazer login para se candidatar</a>';
        }

        echo '</div>';
    }
    echo '</div>';

    return ob_get_clean();
}

// Processar candidatura ao enviar formulário
add_action('init', 'svc_processar_candidatura');
function svc_processar_candidatura() {
    if (!is_user_logged_in() || !isset($_POST['svc_candidatar'])) return;

    global $wpdb;
    $user_id = get_current_user_id();
    $vaga_id = intval($_POST['vaga_id']);

    $candidato_id = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM {$wpdb->prefix}svc_candidatos WHERE user_id = %d", $user_id
    ));

    if (!$candidato_id) return;

    // Evita candidaturas duplicadas
    $existe = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM {$wpdb->prefix}svc_candidaturas WHERE candidato_id = %d AND vaga_id = %d",
        $candidato_id, $vaga_id
    ));

    if (!$existe) {
        $wpdb->insert($wpdb->prefix . 'svc_candidaturas', [
            'candidato_id' => $candidato_id,
            'vaga_id' => $vaga_id
        ]);

        // Envia notificação ao admin
        $admin_email = get_option('admin_email');
        $assunto = "Nova candidatura para a vaga #" . $vaga_id;
        $mensagem = "O candidato ID {$user_id} se candidatou à vaga: " . get_permalink($vaga_id);
        wp_mail($admin_email, $assunto, $mensagem);
    }
}
