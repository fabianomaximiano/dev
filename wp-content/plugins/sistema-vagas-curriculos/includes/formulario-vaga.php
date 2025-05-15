<?php
add_shortcode('formulario_vaga', 'svc_formulario_vaga');

function svc_formulario_vaga() {
    if (!current_user_can('manage_options')) {
        return '<p class="alert alert-danger">Apenas administradores podem cadastrar vagas.</p>';
    }

    global $wpdb;
    $mensagem = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titulo'])) {
        $titulo       = sanitize_text_field($_POST['titulo']);
        $descricao    = wp_kses_post($_POST['descricao']);
        $requisitos   = sanitize_textarea_field($_POST['requisitos']);
        $diferenciais = sanitize_textarea_field($_POST['diferenciais']);
        $categoria    = sanitize_text_field($_POST['categoria']);
        $nova_cat     = sanitize_text_field($_POST['nova_categoria']);

        // Verifica se nova categoria foi preenchida
        if (!empty($nova_cat)) {
            if (!term_exists($nova_cat, 'category')) {
                $nova_term = wp_insert_term($nova_cat, 'category');
                if (!is_wp_error($nova_term)) {
                    $categoria = $nova_cat;
                }
            } else {
                $categoria = $nova_cat;
            }
        }

        $resultado = $wpdb->insert($wpdb->prefix . 'svc_vagas', [
            'titulo'       => $titulo,
            'descricao'    => $descricao,
            'requisitos'   => $requisitos,
            'diferenciais' => $diferenciais,
            'categoria'    => $categoria,
            'criado_em'    => current_time('mysql')
        ]);

        if ($resultado) {
            $mensagem = "<div class='alert alert-success mt-3'>Vaga cadastrada com sucesso!</div>";
        } else {
            $mensagem = "<div class='alert alert-danger mt-3'>Erro ao cadastrar vaga.</div>";
        }
    }

    ob_start();
    $categorias = get_categories(['hide_empty' => false]);
    ?>

    <form method="post" class="needs-validation" novalidate>
        <div class="form-group">
            <label>Título da vaga</label>
            <input type="text" name="titulo" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Descrição da vaga</label>
            <textarea name="descricao" class="form-control" required></textarea>
        </div>

        <div class="form-group">
            <label>Requisitos</label>
            <textarea name="requisitos" class="form-control" required></textarea>
        </div>

        <div class="form-group">
            <label>Diferenciais</label>
            <textarea name="diferenciais" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <label>Categoria</label>
            <select name="categoria" class="form-control">
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?php echo esc_attr($cat->name); ?>"><?php echo esc_html($cat->name); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Nova categoria (opcional)</label>
            <input type="text" name="nova_categoria" class="form-control" placeholder="Digite uma nova categoria">
            <small class="form-text text-muted">Se preencher, a nova categoria será usada.</small>
        </div>

        <button type="submit" class="btn btn-primary">Cadastrar Vaga</button>
        <?php if (!empty($mensagem)) echo $mensagem; ?>
    </form>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const forms = document.getElementsByClassName('needs-validation');
        Array.prototype.forEach.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    });
    </script>

    <?php
    return ob_get_clean();
}
