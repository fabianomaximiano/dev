<?php
add_shortcode('formulario_curriculo', 'svc_formulario_curriculo');

function svc_formulario_curriculo() {
    add_action('wp_enqueue_scripts', function () {
        wp_enqueue_style('bootstrap4', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
        wp_enqueue_script('bootstrap4', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js', ['jquery'], null, true);
        wp_enqueue_script('form-validacao', plugin_dir_url(__FILE__) . '../assets/js/validacao.js', [], null, true);
    });

    ob_start(); ?>
    <form method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
        <div class="form-group">
            <label for="curriculo_file">Importar Currículo (PDF ou DOCX)</label>
            <input type="file" name="curriculo_file" class="form-control" accept=".pdf,.docx" required>
            <div class="invalid-feedback">Selecione um arquivo PDF ou Word.</div>
        </div>
        <button type="submit" name="importar_curriculo" class="btn btn-primary">Importar</button>
    </form>
    <?php
    if (isset($_POST['importar_curriculo']) && isset($_FILES['curriculo_file'])) {
        $arquivo = $_FILES['curriculo_file'];
        if ($arquivo['error'] === 0) {
            $caminho = $arquivo['tmp_name'];
            $conteudo = svc_importar_curriculo($caminho);
            $dados = svc_extrair_dados_curriculo($conteudo);
            echo "<div class='mt-4'><h5>Dados extraídos:</h5>";
            echo "<pre>" . esc_html(print_r($dados, true)) . "</pre></div>";
        } else {
            echo "<p class='text-danger'>Erro ao enviar o arquivo.</p>";
        }
    }
    return ob_get_clean();
}
