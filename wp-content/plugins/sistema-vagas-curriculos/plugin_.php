<?php
/**
 * Plugin Name: Sistema de Vagas e Currículos
 * Description: Gerencia vagas de emprego e currículos de candidatos.
 * Version: 1.0
 * Author: Seu Nome
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/includes/ativacao.php';
require_once __DIR__ . '/includes/formularios.php';
require_once __DIR__ . '/includes/importacao.php';
require_once __DIR__ . '/includes/admin-panel.php';

register_activation_hook( __FILE__, 'svc_criar_tabelas' );
