<?php
if (!defined('ABSPATH')) exit;

function svc_painel_candidato_shortcode() {
    if (!is_user_logged_in()) {
        return '<div class="alert alert-warning">Você precisa estar logado para acessar o painel do candidato.</div>';
    }

    $user_id = get_current_user_id();

    ob_start();
    ?>
    <div class="painel-candidato">
        <h3>Bem-vindo ao seu painel</h3>
        <ul class="list-group mb-3">
            <li class="list-group-item"><a href="<?php echo site_url('/meu-curriculo'); ?>">Meu Currículo</a></li>
            <li class="list-group-item"><a href="<?php echo site_url('/editar-dados'); ?>">Editar Dados Cadastrais</a></li>
            <li class="list-group-item"><a href="<?php echo wp_lostpassword_url(); ?>">Alterar Senha</a></li>
            <li class="list-group-item"><a href="<?php echo site_url('/minhas-candidaturas'); ?>">Status das Vagas</a></li>
        </ul>
    </div>
    <?php
    return ob_get_clean();
}
