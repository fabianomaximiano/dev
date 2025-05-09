<?php
function svc_criar_tabelas() {
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    $tabela_vagas = $wpdb->prefix . 'svc_vagas';
    $tabela_candidatos = $wpdb->prefix . 'svc_candidatos';
    $tabela_curriculos = $wpdb->prefix . 'svc_curriculos';
    $tabela_experiencias = $wpdb->prefix . 'svc_experiencias';
    $tabela_formacoes = $wpdb->prefix . 'svc_formacoes';
    $tabela_cursos = $wpdb->prefix . 'svc_cursos';
    $tabela_idiomas = $wpdb->prefix . 'svc_idiomas';
    $tabela_candidaturas = $wpdb->prefix . 'svc_candidaturas';

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    $sql = [];

    $sql[] = "CREATE TABLE $tabela_vagas (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        titulo VARCHAR(255),
        descricao TEXT,
        requisitos TEXT,
        diferenciais TEXT,
        data_cadastro DATE NOT NULL
    ) $charset_collate;";

    $sql[] = "CREATE TABLE $tabela_candidatos (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT UNSIGNED,
        nome_completo VARCHAR(255),
        rg VARCHAR(20),
        cpf VARCHAR(20) UNIQUE,
        endereco VARCHAR(255),
        cidade VARCHAR(100),
        estado VARCHAR(2),
        email VARCHAR(100),
        senha VARCHAR(255)
    ) $charset_collate;";

    $sql[] = "CREATE TABLE $tabela_curriculos (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        candidato_id BIGINT UNSIGNED,
        FOREIGN KEY (candidato_id) REFERENCES $tabela_candidatos(id)
    ) $charset_collate;";

    $sql[] = "CREATE TABLE $tabela_experiencias (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        curriculo_id BIGINT UNSIGNED,
        nome_empresa VARCHAR(255),
        funcao VARCHAR(255),
        mes_entrada TINYINT,
        ano_entrada SMALLINT,
        mes_saida TINYINT,
        ano_saida SMALLINT,
        descricao TEXT,
        FOREIGN KEY (curriculo_id) REFERENCES $tabela_curriculos(id)
    ) $charset_collate;";

    $sql[] = "CREATE TABLE $tabela_formacoes (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        curriculo_id BIGINT UNSIGNED,
        instituicao VARCHAR(255),
        curso VARCHAR(255),
        ano_conclusao YEAR,
        em_andamento BOOLEAN DEFAULT 0,
        FOREIGN KEY (curriculo_id) REFERENCES $tabela_curriculos(id)
    ) $charset_collate;";

    $sql[] = "CREATE TABLE $tabela_cursos (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        curriculo_id BIGINT UNSIGNED,
        instituicao VARCHAR(255),
        curso VARCHAR(255),
        FOREIGN KEY (curriculo_id) REFERENCES $tabela_curriculos(id)
    ) $charset_collate;";

    $sql[] = "CREATE TABLE $tabela_idiomas (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        curriculo_id BIGINT UNSIGNED,
        idioma VARCHAR(50),
        nivel VARCHAR(50),
        FOREIGN KEY (curriculo_id) REFERENCES $tabela_curriculos(id)
    ) $charset_collate;";

    $sql[] = "CREATE TABLE $tabela_candidaturas (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        vaga_id BIGINT UNSIGNED,
        candidato_id BIGINT UNSIGNED,
        data_candidatura DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (vaga_id) REFERENCES $tabela_vagas(id),
        FOREIGN KEY (candidato_id) REFERENCES $tabela_candidatos(id)
    ) $charset_collate;";

    foreach ($sql as $query) {
        dbDelta($query);
    }
}
