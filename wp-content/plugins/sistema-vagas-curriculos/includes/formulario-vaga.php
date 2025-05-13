<?php
add_shortcode('formulario_vaga', 'svc_formulario_vaga');

function svc_formulario_vaga() {
    if (!current_user_can('manage_options')) {
        return '<p class="alert alert-danger">Acesso restrito. Você não tem permissão para cadastrar vagas.</p>';
    }

    wp_enqueue_style('bootstrap4', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
    wp_enqueue_script('form-validacao', plugin_dir_url(__FILE__) . '../assets/js/validacao.js', [], null, true);

    $mensagem = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titulo_vaga'])) {
        $titulo = sanitize_text_field($_POST['titulo_vaga']);
        $descricao = wp_kses_post($_POST['descricao']);
        $requisitos = sanitize_textarea_field($_POST['requisitos']);
        $diferenciais = sanitize_textarea_field($_POST['diferenciais']);
        $categoria_id = intval($_POST['categoria']);

        $post_id = wp_insert_post([
            'post_type' => 'vaga',
            'post_title' => $titulo,
            'post_content' => $descricao,
            'post_status' => 'publish'
        ]);

        if ($post_id && !is_wp_error($post_id)) {
            wp_set_post_terms($post_id, [$categoria_id], 'category');
            update_post_meta($post_id, 'requisitos', $requisitos);
            update_post_meta($post_id, 'diferenciais', $diferenciais);
            $mensagem = '<div class="alert alert-success mt-3">Vaga cadastrada com sucesso!</div>';
        } else {
            $mensagem = '<div class="alert alert-danger mt-3">Erro ao cadastrar vaga.</div>';
        }
    }

    $categorias = get_categories(['hide_empty' => false]);

    ob_start();
    echo $mensagem;
    ?>
    <form method="post" class="needs-validation mt-4" novalidate>
        <div class="form-group">
            <label for="titulo_vaga">Título da vaga</label>
            <input type="text" class="form-control" id="titulo_vaga" name="titulo_vaga" required>
            <div class="invalid-feedback">Informe o título da vaga.</div>
        </div>
        <div class="form-group">
            <label for="descricao">Descrição</label>
            <textarea class="form-control" id="descricao" name="descricao" rows="4" required></textarea>
            <div class="invalid-feedback">Informe a descrição da vaga.</div>
        </div>
        <div class="form-group">
            <label for="requisitos">Requisitos</label>
            <textarea class="form-control" id="requisitos" name="requisitos" rows="3" required></textarea>
            <div class="invalid-feedback">Informe os requisitos da vaga.</div>
        </div>
        <div class="form-group">
            <label for="diferenciais">Diferenciais</label>
            <textarea class="form-control" id="diferenciais" name="diferenciais" rows="3"></textarea>
        </div>
        <div class="form-group">
            <label for="categoria">Categoria</label>
            <select class="form-control" id="categoria" name="categoria" required>
                <option value="">Selecione</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= esc_attr($cat->term_id); ?>"><?= esc_html($cat->name); ?></option>
                <?php endforeach; ?>
            </select>
            <div class="invalid-feedback">Escolha uma categoria.</div>
        </div>
        <button type="submit" class="btn btn-success">Cadastrar Vaga</button>
    </form>
    <?php
    return ob_get_clean();
}
