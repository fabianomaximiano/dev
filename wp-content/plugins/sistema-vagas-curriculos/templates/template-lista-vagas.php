<?php
/* Template Name: Lista de Vagas */
get_header();
?>
<div class="container my-5">
    <h2 class="mb-4">Vagas Disponíveis</h2>
    <?php echo do_shortcode('[listar_vagas]'); ?>
</div>
<?php get_footer(); ?>
