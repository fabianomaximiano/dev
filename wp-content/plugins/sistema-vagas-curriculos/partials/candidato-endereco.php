<?php if (!defined('ABSPATH')) exit; ?>

<div class="form-group">
    <label for="endereco">Endereço completo</label>
    <textarea name="endereco" id="endereco" class="form-control" rows="2" required><?php echo esc_textarea($curriculo->endereco ?? ''); ?></textarea>
</div>
// Conteúdo de exemplo para candidato-endereco.php
