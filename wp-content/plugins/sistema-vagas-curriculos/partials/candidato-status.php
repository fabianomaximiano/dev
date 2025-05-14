<?php if (!defined('ABSPATH')) exit; ?>

<?php if (!empty($curriculo->status)): ?>
    <div class="alert alert-info">
        <strong>Status do currículo:</strong> <?php echo esc_html($curriculo->status); ?>
    </div>
<?php else: ?>
    <div class="alert alert-warning">
        <strong>Status do currículo:</strong> Incompleto
    </div>
<?php endif; ?>
