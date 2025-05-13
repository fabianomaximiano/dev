<?php
if (!defined('ABSPATH')) exit;

function svc_criar_tabelas() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    $candidatos     = $wpdb->prefix . 'svc_candidatos';
    $curriculos     = $wpdb->prefix . 'svc_curriculos';
    $vagas          = $wpdb->prefix . 'svc_vagas';
    $candidaturas   = $wpdb->prefix . 'svc_candidaturas';

    // 1) Tabela de Candidatos
    dbDelta("
        CREATE TABLE $candidatos (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            user_id INT(11) NOT NULL,
            nome_completo VARCHAR(255) NOT NULL,
            cpf VARCHAR(20) NOT NULL UNIQUE,
            email VARCHAR(100) NOT NULL,
            telefone VARCHAR(20),
            criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) $charset_collate;
    ");

    // 2) Tabela de Currículos
    dbDelta("
        CREATE TABLE $curriculos (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            candidato_id INT(11) NOT NULL,
            objetivo TEXT,
            experiencias LONGTEXT,
            formacao LONGTEXT,
            cursos LONGTEXT,
            idiomas LONGTEXT,
            arquivo_curriculo VARCHAR(255),
            criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (candidato_id) REFERENCES $candidatos(id) ON DELETE CASCADE
        ) $charset_collate;
    ");

    // 3) Tabela de Vagas
    dbDelta("
        CREATE TABLE $vagas (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            titulo VARCHAR(255) NOT NULL,
            descricao LONGTEXT,
            requisitos LONGTEXT,
            diferenciais LONGTEXT,
            categoria VARCHAR(100),
            criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) $charset_collate;
    ");

    // 4) Tabela de Candidaturas
    dbDelta("
        CREATE TABLE $candidaturas (
            id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            candidato_id INT(11) NOT NULL,
            vaga_id INT(11) NOT NULL,
            status VARCHAR(50) NOT NULL DEFAULT 'Em análise',
            data_candidatura DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (candidato_id) REFERENCES $candidatos(id) ON DELETE CASCADE,
            FOREIGN KEY (vaga_id) REFERENCES $vagas(id) ON DELETE CASCADE
        ) $charset_collate;
    ");
}
