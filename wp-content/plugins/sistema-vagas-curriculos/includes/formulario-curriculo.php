<?php
add_shortcode('formulario_curriculo', 'svc_formulario_curriculo');
function svc_formulario_curriculo() {
    wp_enqueue_style('bootstrap4', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
    ob_start(); ?>
    <form method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
        <div class="form-group">
            <label>Importar Currículo (PDF ou Word)</label>
            <input type="file" name="curriculo_file" class="form-control" required>
            <div class="invalid-feedback">Arquivo obrigatório.</div>
        </div>
        <button type="submit" class="btn btn-primary">Importar</button>
    </form>
    <?php return ob_get_clean();
}
