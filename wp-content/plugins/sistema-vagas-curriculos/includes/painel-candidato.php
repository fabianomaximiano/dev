<?php
if (!is_user_logged_in()) {
    wp_redirect(wp_login_url());
    exit;
}

$current_user = wp_get_current_user();
$pagina = $_GET['pagina'] ?? 'meu-curriculo';

?>

<div class="container my-4">
    <div class="row">
        <div class="col-md-3">
            <?php include __DIR__ . '/template-parts/menu-candidato.php'; ?>
        </div>
        <div class="col-md-9">
            <h2>Bem-vindo, <?php echo esc_html($current_user->display_name); ?>!</h2>

            <?php
            $paginas_validas = [
                'meu-curriculo',
                'minhas-candidaturas',
                'editar-cadastro',
                'alterar-senha',
            ];

            if (in_array($pagina, $paginas_validas)) {
                include __DIR__ . "/template-parts/painel-{$pagina}.php";
            } else {
                echo '<p>Página não encontrada.</p>';
            }
            ?>
        </div>
    </div>
</div>

<?php
// includes/painel-candidato.php

// Verifica a página atual via query string, default para 'meu-curriculo'
$pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 'meu-curriculo';

// Define as páginas válidas que podem ser carregadas
$paginas_validas = [
    'meu-curriculo',
    'minhas-candidaturas',
    'editar-cadastro',
    'alterar-senha',
    'importar-curriculo'
];

// Header do painel
?>
<div class="painel-candidato-container">
    <nav class="menu-lateral">
        <ul>
            <li><a href="?pagina=meu-curriculo">Meu Currículo</a></li>
            <li><a href="?pagina=minhas-candidaturas">Minhas Candidaturas</a></li>
            <li><a href="?pagina=editar-cadastro">Editar Cadastro</a></li>
            <li><a href="?pagina=alterar-senha">Alterar Senha</a></li>
            <li><a href="?pagina=importar-curriculo">Importar Currículo</a></li>
            <li><a href="<?php echo wp_logout_url(home_url()); ?>">Sair</a></li>
        </ul>
    </nav>

    <main class="conteudo-painel">
        <?php
        if (in_array($pagina, $paginas_validas)) {
            include __DIR__ . "/template-parts/painel-{$pagina}.php";
        } else {
            echo '<p>Página não encontrada.</p>';
        }
        ?>
    </main>
</div>

