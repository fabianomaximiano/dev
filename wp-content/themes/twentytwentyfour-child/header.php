<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="<?php echo home_url(); ?>">Início</a>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a class="nav-link" href="<?php echo site_url('/vagas-disponiveis'); ?>">Vagas</a></li>

            <?php if (!empty($_SESSION['candidato_id'])) : ?>
                <li class="nav-item"><a class="nav-link" href="<?php echo site_url('/painel-do-candidato'); ?>">Painel</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo site_url('/logout-candidato'); ?>">Sair</a></li>
            <?php else : ?>
                <li class="nav-item"><a class="nav-link" href="<?php echo site_url('/login-candidato'); ?>">Login</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo site_url('/cadastro-candidato'); ?>">Cadastrar-se</a></li>
            <?php endif; ?>

            <?php if (current_user_can('manage_options')) : ?>
                <li class="nav-item"><a class="nav-link" href="<?php echo admin_url(); ?>">Admin</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
