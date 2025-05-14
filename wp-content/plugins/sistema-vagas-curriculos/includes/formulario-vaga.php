<?php
if (!defined('ABSPATH')) exit;

function svc_formulario_vaga() {
    global $wpdb;

    $mensagem = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titulo'])) {
        $titulo       = sanitize_text_field($_POST['titulo']);
        $descricao    = wp_kses_post($_POST['descricao']);
        $requisitos   = sanitize_textarea_field($_POST['requisitos']);
        $diferenciais = sanitize_textarea_field($_POST['diferenciais']);
        $categoria    = sanitize_text_field($_POST['categoria']);

        $inserido = $wpdb->insert(
            "{$wpdb->prefix}svc_vagas",
            [
                'titulo'        => $titulo,
                'descricao'     => $descricao,
                'requisitos'    => $requisitos,
                'diferenciais'  => $diferenciais,
                'categoria'     => $categoria,
                'criado_em'     => current_time('mysql')
            ]
        );

        if ($inserido) {
            $mensagem = '<div class="alert alert-success">Vaga cadastrada com sucesso!</div>';
        } else {
            $mensagem = '<div class="alert alert-danger">Erro ao cadastrar a vaga.</div>';
        }
    }

    ob_start();
    echo $mensagem;
    ?>

    <form method="post" class="needs-validation" novalidate>
        <div class="form-group">
            <label for="titulo">Título da vaga</label>
            <input type="text" name="titulo" id="titulo" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="descricao">Descrição</label>
            <textarea name="descricao" id="descricao" class="form-control" rows="4" required></textarea>
        </div>

        <div class="form-group">
            <label for="requisitos">Requisitos</label>
            <textarea name="requisitos" id="requisitos" class="form-control" rows="3" required></textarea>
        </div>

        <div class="form-group">
            <label for="diferenciais">Diferenciais</label>
            <textarea name="diferenciais" id="diferenciais" class="form-control" rows="3"></textarea>
        </div>

        <div class="form-group">
            <label for="categoria">Categoria da vaga</label>
            <input type="text" name="categoria" id="categoria" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Cadastrar Vaga</button>
    </form>

    <?php
    return ob_get_clean();
}
add_shortcode('formulario_vaga', 'svc_formulario_vaga');
