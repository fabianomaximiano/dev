<?php
/**
 * Plugin Name: Sistema de Vagas e Currículos
 * Description: Gerencia vagas de emprego e currículos de candidatos, permitindo candidatura rápida às vagas disponíveis.
 * Version: 2.0
 * Author: Fabiano Maximiano
 */

if (!defined('ABSPATH')) exit;

$arquivos = [
    '/includes/ativacao.php',
    '/includes/formularios.php',
    '/includes/importacao.php',
    '/includes/admin-panel.php',
    '/includes/painel-candidato.php',
    '/includes/formulario-curriculo.php',
    '/includes/formulario-vaga.php',
    '/includes/listar-vagas.php',
    '/includes/functions.php'
];

foreach ($arquivos as $arquivo) {
    $caminho = __DIR__ . $arquivo;
    if (file_exists($caminho)) {
        require_once $caminho;
    }
}

// Hook de ativação
register_activation_hook(__FILE__, 'svc_criar_tabelas');
