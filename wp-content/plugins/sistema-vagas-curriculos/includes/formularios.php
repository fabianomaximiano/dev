<?php
if (!defined('ABSPATH')) exit;

add_shortcode('formulario_candidato', 'svc_formulario_candidato');

function svc_formulario_candidato() {
    if (session_status() === PHP_SESSION_NONE) session_start();

    ob_start();
    global $wpdb;
    $mensagem = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cpf'])) {
        $cpf         = sanitize_text_field($_POST['cpf']);
        $email       = sanitize_email($_POST['email']);
        $nome        = sanitize_text_field($_POST['nome_completo']);
        $tel         = sanitize_text_field($_POST['telefone']);
        $senha       = sanitize_text_field($_POST['senha']);
        $senha_conf  = sanitize_text_field($_POST['senha_confirmacao']);
        $cep         = sanitize_text_field($_POST['cep']);
        $logradouro  = sanitize_text_field($_POST['logradouro']);
        $numero      = sanitize_text_field($_POST['numero']);
        $cidade      = sanitize_text_field($_POST['cidade']);
        $estado      = sanitize_text_field($_POST['estado']);

        $tabela = $wpdb->prefix . 'svc_candidatos';

        $existe = $wpdb->get_var($wpdb->prepare("SELECT id FROM $tabela WHERE cpf = %s", $cpf));

        if ($existe) {
            $mensagem = "<div class='alert alert-danger mt-3'>CPF já cadastrado!</div>";
        } elseif (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $senha)) {
            $mensagem = "<div class='alert alert-danger mt-3'>A senha deve ter no mínimo 8 caracteres, com letra maiúscula, número e símbolo.</div>";
        } elseif ($senha !== $senha_conf) {
            $mensagem = "<div class='alert alert-danger mt-3'>As senhas não conferem.</div>";
        } else {
            $endereco_completo = $logradouro . ', ' . $numero;

            $wpdb->insert($tabela, [
                'nome_completo' => $nome,
                'cpf'           => $cpf,
                'email'         => $email,
                'telefone'      => $tel,
                'senha'         => password_hash($senha, PASSWORD_DEFAULT),
                'endereco'      => $endereco_completo,
                'cidade'        => $cidade,
                'estado'        => $estado
            ]);

            $_SESSION['candidato_id'] = $wpdb->insert_id;
            wp_redirect(site_url('/meu-curriculo'));
            exit;
        }
    }
    ?>

    <form method="post" class="needs-validation" novalidate>
        <div class="form-group">
            <label>Nome completo</label>
            <input type="text" name="nome_completo" class="form-control" required value="<?php echo esc_attr($_POST['nome_completo'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label>CPF</label>
            <input type="text" name="cpf" id="cpf" class="form-control" required value="<?php echo esc_attr($_POST['cpf'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required value="<?php echo esc_attr($_POST['email'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label>Telefone</label>
            <input type="text" name="telefone" id="telefone" class="form-control" required value="<?php echo esc_attr($_POST['telefone'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label>Senha</label>
            <div class="input-group">
                <input type="password" name="senha" id="senha" class="form-control" required>
                <div class="input-group-append">
                    <span class="input-group-text" id="toggle-senha" style="cursor:pointer;">
                        <i class="fa fa-eye" id="icon-senha"></i>
                    </span>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Confirmar Senha</label>
            <input type="password" name="senha_confirmacao" id="senha_confirmacao" class="form-control" required>
            <div class="invalid-feedback" id="erro-senha"></div>
        </div>

        <div class="form-group">
            <label>CEP</label>
            <input type="text" name="cep" id="cep" class="form-control" required value="<?php echo esc_attr($_POST['cep'] ?? ''); ?>">
        </div>

        <div class="form-group d-none" id="grupo-endereco">
            <label>Endereço</label>
            <input type="text" name="logradouro" id="logradouro" class="form-control" readonly value="<?php echo esc_attr($_POST['logradouro'] ?? ''); ?>">
        </div>

        <div class="form-group d-none" id="grupo-numero">
            <label>Número</label>
            <input type="text" name="numero" id="numero" class="form-control" required value="<?php echo esc_attr($_POST['numero'] ?? ''); ?>">
        </div>

        <div class="form-group d-none" id="grupo-cidade">
            <label>Cidade</label>
            <input type="text" name="cidade" id="cidade" class="form-control" readonly value="<?php echo esc_attr($_POST['cidade'] ?? ''); ?>">
        </div>

        <div class="form-group d-none" id="grupo-estado">
            <label>Estado</label>
            <input type="text" name="estado" id="estado" class="form-control" readonly value="<?php echo esc_attr($_POST['estado'] ?? ''); ?>">
        </div>

        <button type="submit" class="btn btn-primary">Cadastrar</button>

        <?php echo $mensagem; ?>
    </form>

    <?php
    return ob_get_clean();
}
