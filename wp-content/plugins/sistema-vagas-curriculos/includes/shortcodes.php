<?php
// Bloqueia acesso direto ao arquivo
if (!defined('ABSPATH')) {
    exit;
}

// Shortcode para exibir o menu do candidato
function svc_menu_candidato_shortcode()
{
    if (!is_user_logged_in()) {
        return '<div class="alert alert-warning">Você precisa estar logado para acessar esta área.</div>';
    }

    $user_id = get_current_user_id();
    global $wpdb;
    $tabela_candidatos = $wpdb->prefix . 'svc_candidatos';
    $candidato = $wpdb->get_row($wpdb->prepare("SELECT * FROM $tabela_candidatos WHERE user_id = %d", $user_id));

    if (!$candidato) {
        return '<div class="alert alert-info">Complete seu cadastro para acessar o menu do candidato.</div>';
    }

    ob_start();
    ?>
    <div class="container my-4">
        <div class="card shadow-sm rounded-2xl">
            <div class="card-body">
                <h5 class="card-title mb-4">Painel do Candidato</h5>
                <div class="list-group">
                    <a href="<?php echo esc_url(site_url('/meu-curriculo')); ?>" class="list-group-item list-group-item-action">
                        <i class="bi bi-person-lines-fill"></i> Meu Currículo
                    </a>
                    <a href="<?php echo esc_url(site_url('/minhas-candidaturas')); ?>" class="list-group-item list-group-item-action">
                        <i class="bi bi-list-check"></i> Minhas Candidaturas
                    </a>
                    <a href="<?php echo esc_url(site_url('/editar-cadastro')); ?>" class="list-group-item list-group-item-action">
                        <i class="bi bi-pencil-square"></i> Editar Cadastro
                    </a>
                    <a href="<?php echo esc_url(site_url('/alterar-senha')); ?>" class="list-group-item list-group-item-action">
                        <i class="bi bi-shield-lock"></i> Alterar Senha
                    </a>
                    <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="list-group-item list-group-item-action text-danger">
                        <i class="bi bi-box-arrow-right"></i> Sair
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('menu_candidato', 'svc_menu_candidato_shortcode');
