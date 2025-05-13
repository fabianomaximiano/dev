<?php
if (!defined('ABSPATH')) exit;

function svc_formulario_vaga_shortcode() {
    if (!current_user_can('administrator')) {
        return '<div class="alert alert-danger">Apenas administradores podem cadastrar vagas.</div>';
    }

    ob_start();
    ?>
    <form method="post" class="needs-validation" novalidate>
        <div class="form-group">
            <label for="titulo">Título da Vaga</label>
            <input type="text" name="titulo" id="titulo" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="descricao">Descrição</label>
            <textarea name="descricao" id="descricao" class="form-control" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label for="requisitos">Requisitos</label>
            <textarea name="requisitos" id="requisitos" class="form-control" rows="2" required></textarea>
        </div>
        <div class="form-group">
            <label for="diferenciais">Diferenciais</label>
            <textarea name="diferenciais" id="diferenciais" class="form-control" rows="2"></textarea>
        </div>
        <div class="form-group">
            <label for="categoria">Categoria</label>
            <input type="text" name="categoria" id="categoria" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Cadastrar Vaga</button>
    </form>
    <?php
    return ob_get_clean();
}
