<?php
if (!defined('ABSPATH')) exit;

/**
 * Cria todas as tabelas do sistema
 */
function svc_criar_tabelas() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $t_candidatos   = $wpdb->prefix . 'svc_candidatos';
    $t_curriculos   = $wpdb->prefix . 'svc_curriculos';
    $t_vagas        = $wpdb->prefix . 'svc_vagas';
    $t_candidaturas = $wpdb->prefix . 'svc_candidaturas';

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    dbDelta("CREATE TABLE $t_candidatos (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT(20) UNSIGNED,
        nome_completo VARCHAR(255) NOT NULL,
        cpf VARCHAR(20) NOT NULL UNIQUE,
        email VARCHAR(255) NOT NULL,
        telefone VARCHAR(20) NOT NULL,
        senha VARCHAR(255) NOT NULL,
        endereco TEXT,
        cidade VARCHAR(100),
        estado VARCHAR(100),
        criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
    ) $charset_collate;");

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

    dbDelta("CREATE TABLE $t_vagas (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        titulo VARCHAR(255) NOT NULL,
        descricao TEXT,
        requisitos TEXT,
        diferenciais TEXT,
        categoria VARCHAR(255),
        criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
    ) $charset_collate;");

    dbDelta("CREATE TABLE $t_candidaturas (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        candidato_id INT NOT NULL,
        vaga_id INT NOT NULL,
        status VARCHAR(50) DEFAULT 'Recebido',
        data_candidatura DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (candidato_id) REFERENCES $t_candidatos(id) ON DELETE CASCADE,
        FOREIGN KEY (vaga_id) REFERENCES $t_vagas(id) ON DELETE CASCADE
    ) $charset_collate;");
}

/**
 * Cria categorias-padrão no sistema
 */
function svc_criar_categorias_padrao() {
    $categorias = ['TI', 'RH', 'Financeiro', 'Logística', 'Marketing'];
    foreach ($categorias as $cat) {
        if (!term_exists($cat, 'category')) {
            wp_insert_term($cat, 'category');
        }
    }
}

/**
 * Cria páginas padrão do sistema, caso não existam
 */
function svc_criar_paginas_automaticas() {
    $paginas = [
        'cadastro-candidato'    => ['Cadastro de Candidato', '[formulario_candidato]'],
        'login-candidato'       => ['Login do Candidato', '[login_candidato]'],
        'painel-do-candidato'   => ['Painel do Candidato', '[painel_candidato]'],
        'meu-curriculo'         => ['Cadastro de Currículo', '[formulario_curriculo]'],
        'cadastro-de-vagas'     => ['Cadastro de Vagas', '[formulario_vaga]'],
        'vagas-disponiveis'     => ['Vagas Disponíveis', '[listar_vagas]'],
        'recuperar-senha'       => ['Recuperar Senha', '[recuperar_senha]'],
        'resetar-senha'         => ['Redefinir Senha', '[resetar_senha]'],
        'logout-candidato'      => ['Sair', '[logout_candidato]'],
        'editar-dados'          => ['Editar Dados Cadastrais', '[formulario_editar_dados]'],
        'minhas-candidaturas'   => ['Minhas Candidaturas', '[minhas_candidaturas]'],
    ];

    foreach ($paginas as $slug => [$titulo, $shortcode]) {
        if (null === get_page_by_path($slug)) {
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

/**
 * Função principal que roda toda a instalação/ativação
 */
function svc_instalacao_completa() {
    svc_criar_tabelas();
    svc_criar_categorias_padrao();
    svc_criar_paginas_automaticas();

    set_transient('svc_ativado_com_sucesso', true, 30);
}

/**
 * Mensagem administrativa após ativação
 */
add_action('admin_notices', function () {
    if (get_transient('svc_ativado_com_sucesso')) {
        echo '<div class="notice notice-success is-dismissible"><p><strong>Plugin Sistema de Vagas e Currículos:</strong> tabelas, páginas e categorias criadas com sucesso.</p></div>';
        delete_transient('svc_ativado_com_sucesso');
    }
});
