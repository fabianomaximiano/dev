<?php
add_shortcode('formulario_candidato', 'svc_formulario_candidato');

function svc_formulario_candidato() {
    // Enfileira Bootstrap e script de validação
    add_action('wp_enqueue_scripts', function () {
        wp_enqueue_style('bootstrap4', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
        wp_enqueue_script('bootstrap4', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js', ['jquery'], null, true);
        wp_enqueue_script('form-validacao', plugin_dir_url(__FILE__) . '../assets/js/validacao.js', [], null, true);
    });

    ob_start(); ?>
    <form method="post" id="form-candidato" class="needs-validation" novalidate>
        <div class="form-group">
            <label>Nome Completo</label>
            <input type="text" name="nome_completo" class="form-control" required>
            <div class="invalid-feedback">Campo obrigatório.</div>
        </div>

        <div class="form-group">
            <label>RG</label>
            <input type="text" name="rg" class="form-control" required>
            <div class="invalid-feedback">Campo obrigatório.</div>
        </div>

        <div class="form-group">
            <label>CPF</label>
            <input type="text" name="cpf" class="form-control" required pattern="\d{11}">
            <div class="invalid-feedback">Digite os 11 números do CPF.</div>
        </div>

        <div class="form-group">
            <label>Endereço</label>
            <input type="text" name="endereco" class="form-control" required>
            <div class="invalid-feedback">Campo obrigatório.</div>
        </div>

        <div class="form-group">
            <label>Cidade</label>
            <input type="text" name="cidade" class="form-control" required>
            <div class="invalid-feedback">Campo obrigatório.</div>
        </div>

        <div class="form-group">
            <label>Estado</label>
            <input type="text" name="estado" class="form-control" required maxlength="2">
            <div class="invalid-feedback">Campo obrigatório (UF).</div>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
            <div class="invalid-feedback">Email inválido.</div>
        </div>

        <div class="form-group">
            <label>Senha</label>
            <input type="password" name="senha" class="form-control" required>
            <div class="invalid-feedback">Campo obrigatório.</div>
        </div>

        <button type="submit" name="salvar_candidato" class="btn btn-primary">Salvar</button>
    </form>
    <?php return ob_get_clean();
}
