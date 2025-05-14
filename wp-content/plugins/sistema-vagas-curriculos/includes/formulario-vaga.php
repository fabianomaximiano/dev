<?php
if (!defined('ABSPATH')) exit;

add_shortcode('formulario_vaga', 'svc_formulario_vaga');

function svc_formulario_vaga() {
    if (!current_user_can('manage_options')) {
        return '<div class="alert alert-danger">Apenas administradores podem cadastrar vagas.</div>';
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titulo'])) {
        $titulo       = sanitize_text_field($_POST['titulo']);
        $descricao    = wp_kses_post($_POST['descricao']);
        $requisitos   = sanitize_textarea_field($_POST['requisitos']);
        $diferenciais = sanitize_textarea_field($_POST['diferenciais']);
        $categoria    = sanitize_text_field($_POST['categoria']);

        $post_id = wp_insert_post([
            'post_title'   => $titulo,
            'post_content' => $descricao,
            'post_status'  => 'publish',
            'post_type'    => 'post'
        ]);

        if ($post_id) {
            update_post_meta($post_id, 'requisitos', $requisitos);
            update_post_meta($post_id, 'diferenciais', $diferenciais);
            update_post_meta($post_id, 'categoria', $categoria);

            echo '<div class="alert alert-success">Vaga cadastrada com sucesso.</div>';
        } else {
            echo '<div class="alert alert-danger">Erro ao cadastrar vaga.</div>';
        }
    }

    ob_start(); ?>
    <form method="post" class="needs-validation" novalidate>
        <div class="form-group">
            <label>Título da vaga</label>
            <input type="text" name="titulo" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Descrição</label>
            <textarea name="descricao" class="form-control" rows="4" required></textarea>
        </div>

        <div class="form-group">
            <label>Requisitos</label>
            <textarea name="requisitos" class="form-control" rows="3" required></textarea>
        </div>

        <div class="form-group">
            <label>Diferenciais</label>
            <textarea name="diferenciais" class="form-control" rows="2"></textarea>
        </div>

        <div class="form-group">
            <label>Categoria</label>
            <input type="text" name="categoria" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Cadastrar Vaga</button>
    </form>
    <?php
    return ob_get_clean();
}
