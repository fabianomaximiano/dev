<?php
if (!defined('ABSPATH')) exit;

/**
 * Função principal de ativação
 * Cria tabelas e páginas do sistema
 */
function svc_instalacao_completa() {
    svc_criar_tabelas();
    svc_criar_paginas();
}

/**
 * Cria as tabelas no banco de dados
 */
function svc_criar_tabelas() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $tabela_candidatos = $wpdb->prefix . 'svc_candidatos';
    $tabela_curriculos = $wpdb->prefix . 'svc_curriculos';
    $tabela_vagas = $wpdb->prefix . 'svc_vagas';
    $tabela_candidaturas = $wpdb->prefix . 'svc_candidaturas';

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    // Tabela candidatos
    dbDelta("CREATE TABLE $tabela_candidatos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT UNSIGNED NOT NULL,
        nome_completo VARCHAR(255) NOT NULL,
        cpf VARCHAR(20) NOT NULL UNIQUE,
        email VARCHAR(255) NOT NULL,
        telefone VARCHAR(20),
        criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
    ) $charset_collate;");

    // Tabela currículos
    dbDelta("CREATE TABLE $tabela_curriculos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        candidato_id INT NOT NULL,
        formacao TEXT,
        experiencia TEXT,
        cursos_complementares TEXT,
        idiomas TEXT,
        endereco TEXT,
        status VARCHAR(50) DEFAULT 'Incompleto',
        arquivo_curriculo VARCHAR(255),
        criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (candidato_id) REFERENCES $tabela_candidatos(id) ON DELETE CASCADE
    ) $charset_collate;");

    // Tabela vagas
    dbDelta("CREATE TABLE $tabela_vagas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        titulo VARCHAR(255) NOT NULL,
        descricao TEXT,
        requisitos TEXT,
        diferenciais TEXT,
        categoria VARCHAR(100),
        criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
    ) $charset_collate;");

    // Tabela candidaturas
    dbDelta("CREATE TABLE $tabela_candidaturas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        candidato_id INT NOT NULL,
        vaga_id INT NOT NULL,
        status VARCHAR(50) DEFAULT 'Em análise',
        data_candidatura DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (candidato_id) REFERENCES $tabela_candidatos(id) ON DELETE CASCADE,
        FOREIGN KEY (vaga_id) REFERENCES $tabela_vagas(id) ON DELETE CASCADE
    ) $charset_collate;");
}

/**
 * Cria páginas padrão com seus respectivos shortcodes
 */
function svc_criar_paginas() {
    $paginas = [
        'cadastro-candidato'     => ['Cadastro de Candidato',     '[formulario_candidato]'],
        'login-candidato'        => ['Login do Candidato',        '[login_candidato]'],
        'logout-candidato'       => ['Logout do Candidato',       '[logout_candidato]'],
        'painel-do-candidato'    => ['Painel do Candidato',       '[painel_candidato]'],
        'meu-curriculo'          => ['Cadastro de Currículo',     '[formulario_curriculo]'],
        'vagas-disponiveis'      => ['Vagas Disponíveis',         '[listar_vagas]'],
        'cadastro-de-vagas'      => ['Cadastro de Vagas',         '[formulario_vaga]']
    ];

    foreach ($paginas as $slug => [$titulo, $shortcode]) {
        if (!get_page_by_path($slug)) {
            wp_insert_post([
                'post_title'     => $titulo,
                'post_name'      => $slug,
                'post_status'    => 'publish',
                'post_type'      => 'page',
                'post_content'   => $shortcode
            ]);
        }
    }
}
