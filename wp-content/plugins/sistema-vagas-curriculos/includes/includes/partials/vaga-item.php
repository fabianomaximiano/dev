<div class="vaga-item border-bottom pb-3 mb-3">
    <h5><?php echo esc_html($vaga['titulo']); ?></h5>
    <p><?php echo esc_html($vaga['descricao']); ?></p>
    <p><strong>Categoria:</strong> <?php echo esc_html($vaga['categoria']); ?></p>
    <a href="?candidatar=<?php echo intval($vaga['id']); ?>" class="btn btn-primary">Candidatar-se</a>
</div>
    