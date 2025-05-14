<?php
/**
 * Template Name: Painel do Candidato
 * Description: Painel completo para candidatos logados acessarem seu currículo, status e candidaturas.
 */
get_header(); ?>

<div class="container my-5">
    <h1 class="mb-4">Painel do Candidato</h1>
    <?php echo do_shortcode('[painel_candidato]'); ?>
</div>

<?php get_footer(); ?>
