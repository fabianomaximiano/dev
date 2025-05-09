<?php
/**
 * Plugin Name: Sistema de Vagas e Currículos
 * Description: Plugin para cadastro de vagas e currículos com painel para candidatos.
 * Version: 1.0
 * Author: Seu Nome
 */

if (!defined('ABSPATH')) exit;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/includes/ativacao.php';
require_once __DIR__ . '/includes/formularios.php';
require_once __DIR__ . '/includes/importacao.php';
require_once __DIR__ . '/includes/admin-panel.php';
require_once __DIR__ . '/includes/painel-candidato.php';
require_once __DIR__ . '/includes/formulario-curriculo.php';

register_activation_hook(__FILE__, 'svc_criar_tabelas');
