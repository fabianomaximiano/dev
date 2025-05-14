<?php if (!defined('ABSPATH')) exit; ?>

<?php if (is_user_logged_in()): ?>
    <div class="form-group">
        <label for="nova_senha">Alterar Senha</label>
        <input type="password" name="nova_senha" id="nova_senha" class="form-control" placeholder="Nova senha">
        <small class="form-text text-muted">Deixe em branco se não quiser alterar sua senha.</small>
    </div>
<?php endif; ?>
