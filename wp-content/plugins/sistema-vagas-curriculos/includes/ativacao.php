<?php
if (!defined('ABSPATH')) exit;
/**
 * Função principal de ativação
 * Cria tabelas e páginas do sistema
 */

/**
 * Cria as tabelas no banco de dados
 */
function svc_criar_tabelas() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $t_candidatos     = $wpdb->prefix . 'svc_candidatos';
    $t_curriculos     = $wpdb->prefix . 'svc_curriculos';
    $t_vagas          = $wpdb->prefix . 'svc_vagas';
    $t_candidaturas   = $wpdb->prefix . 'svc_candidaturas';

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    // Tabela candidatos
    dbDelta("CREATE TABLE $t_candidatos (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT(20) UNSIGNED,
        nome_completo VARCHAR(255) NOT NULL,
        cpf VARCHAR(20) NOT NULL UNIQUE,
        email VARCHAR(255) NOT NULL,
        telefone VARCHAR(20),
        endereco TEXT,
        cidade VARCHAR(100),
        estado VARCHAR(100),
        senha VARCHAR(255),
        criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
    ) $charset_collate;");

    // Tabela currículos
    dbDelta("CREATE TABLE $t_curriculos (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        candidato_id INT NOT NULL,
        formacao TEXT,
        experiencia TEXT,
        cursos_complementares TEXT,
        idiomas TEXT,
        arquivo_curriculo VARCHAR(255),
        criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (candidato_id) REFERENCES $t_candidatos(id) ON DELETE CASCADE
    ) $charset_collate;");

    // Tabela vagas
    dbDelta("CREATE TABLE $t_vagas (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        titulo VARCHAR(255) NOT NULL,
        descricao TEXT,
        requisitos TEXT,
        diferenciais TEXT,
        categoria VARCHAR(255),
        criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
    ) $charset_collate;");

    // Tabela candidaturas
    dbDelta("CREATE TABLE $t_candidaturas (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        candidato_id INT NOT NULL,
        vaga_id INT NOT NULL,
        status VARCHAR(50) DEFAULT 'Recebido',
        data_candidatura DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (candidato_id) REFERENCES $t_candidatos(id) ON DELETE CASCADE,
        FOREIGN KEY (vaga_id) REFERENCES $t_vagas(id) ON DELETE CASCADE
    ) $charset_collate;");

    // Criação automática de páginas
    $paginas = [
        'cadastro-candidato' => ['Cadastro de Candidato', '[formulario_candidato]'],
        'meu-curriculo' => ['Cadastro de Currículo', '[formulario_curriculo]'],
        'login-candidato' => ['Login do Candidato', '[login_candidato]'],
        'painel-do-candidato' => ['Painel do Candidato', '[painel_candidato]'],
        'cadastro-de-vagas' => ['Cadastro de Vagas', '[formulario_vaga]'],
        'vagas-disponiveis' => ['Vagas Disponíveis', '[listar_vagas]'],
    ];

    foreach ($paginas as $slug => [$titulo, $shortcode]) {
        $existe = get_page_by_path($slug);
        if (!$existe) {
            wp_insert_post([
                'post_title'   => $titulo,
                'post_name'    => $slug,
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_content' => $shortcode,
            ]);
        }
    }
}
