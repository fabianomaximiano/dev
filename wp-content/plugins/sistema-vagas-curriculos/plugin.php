<?php
/**
 * Plugin Name: Sistema de Vagas e Currículos
 * Description: Gerencia vagas de emprego e currículos de candidatos.
 * Version: 1.0
 * Author: Seu Nome
 */

if (!defined('ABSPATH')) exit;

$arquivos = [
    '/includes/ativacao.php',
    '/includes/formularios.php',
    '/includes/importacao.php',
    '/includes/admin-panel.php',
    '/includes/painel-candidato.php',
    '/includes/formulario-curriculo.php'
];

foreach ($arquivos as $arquivo) {
    $caminho = __DIR__ . $arquivo;
    if (file_exists($caminho)) {
        require_once $caminho;
    } else {
        error_log("Arquivo ausente no plugin: " . $arquivo);
    }
}

register_activation_hook(__FILE__, 'svc_criar_tabelas');
