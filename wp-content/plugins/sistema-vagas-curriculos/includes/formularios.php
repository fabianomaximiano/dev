<?php
add_shortcode('formulario_candidato', 'svc_formulario_candidato');

function svc_formulario_candidato() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cpf'])) {
        global $wpdb;
        $cpf = sanitize_text_field($_POST['cpf']);
        $email = sanitize_email($_POST['email']);
        $nome = sanitize_text_field($_POST['nome_completo']);
        $user_id = get_current_user_id();

        $tabela = $wpdb->prefix . 'svc_candidatos';
        $existe = $wpdb->get_var($wpdb->prepare("SELECT id FROM $tabela WHERE cpf = %s", $cpf));

        if ($existe) {
            return "<p class='text-danger'>CPF já cadastrado!</p>";
        } else {
            $wpdb->insert($tabela, [
                'user_id' => $user_id,
                'nome_completo' => $nome,
                'cpf' => $cpf,
                'email' => $email
            ]);
            return "<p class='text-success'>Cadastro realizado com sucesso.</p>";
        }
    }

    ob_start(); ?>
    <form method="post" class="needs-validation" novalidate>
        <div class="form-group">
            <label>Nome completo</label>
            <input type="text" name="nome_completo" class="form-control" required>
        </div>
        <div class="form-group">
            <label>CPF</label>
            <input type="text" name="cpf" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Cadastrar</button>
    </form>
    <?php
    return ob_get_clean();
}
