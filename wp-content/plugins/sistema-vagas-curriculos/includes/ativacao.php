<?php
function svc_criar_tabelas() {
    global $wpdb;
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    $charset = $wpdb->get_charset_collate();
    $candidatos = $wpdb->prefix . 'svc_candidatos';
    $curriculos = $wpdb->prefix . 'svc_curriculos';

    dbDelta("CREATE TABLE $candidatos (
        id BIGINT AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT,
        nome_completo VARCHAR(255),
        cpf VARCHAR(20) UNIQUE,
        email VARCHAR(100)
    ) $charset;");

    dbDelta("CREATE TABLE $curriculos (
        id BIGINT AUTO_INCREMENT PRIMARY KEY,
        candidato_id BIGINT,
        dados LONGTEXT
    ) $charset;");

    svc_criar_paginas_padrao();
}

// function svc_criar_paginas_padrao() {
//     $paginas = [
//         'painel-do-candidato' => ['title' => 'Painel do Candidato', 'content' => '[painel_candidato]'],
//         'meu-curriculo' => ['title' => 'Meu Currículo', 'content' => '[formulario_curriculo]'],
//         'cadastro-de-candidato' => ['title' => 'Cadastro de Candidato', 'content' => '[formulario_candidato]'],
//     ];
//     foreach ($paginas as $slug => $dados) {
//         if (!get_page_by_path($slug)) {
//             wp_insert_post([
//                 'post_title' => $dados['title'],
//                 'post_name' => $slug,
//                 'post_content' => $dados['content'],
//                 'post_status' => 'publish',
//                 'post_type' => 'page'
//             ]);
//         }
//     }
// }
function svc_criar_paginas_padrao() {
    $paginas = [
        'vagas' => ['title' => 'Vagas', 'content' => '[listar_vagas]'],
        'cadastro-de-candidato' => ['title' => 'Cadastro de Candidato', 'content' => '[formulario_candidato]'],
        'meu-curriculo' => ['title' => 'Meu Currículo', 'content' => '[formulario_curriculo]'],
        'painel-do-candidato' => ['title' => 'Painel do Candidato', 'content' => '[painel_candidato]'],
        'cadastrar-vaga' => ['title' => 'Cadastrar Vaga', 'content' => '[formulario_vaga]'],
    ];
    foreach ($paginas as $slug => $dados) {
        if (!get_page_by_path($slug)) {
            wp_insert_post([
                'post_title'   => $dados['title'],
                'post_name'    => $slug,
                'post_content' => $dados['content'],
                'post_status'  => 'publish',
                'post_type'    => 'page'
            ]);
        }
    }
}

function svc_registrar_post_type_vaga() {
    register_post_type('vaga', [
        'label' => 'Vagas',
        'public' => true,
        'has_archive' => true,
        'rewrite' => ['slug' => 'vagas'],
        'show_in_rest' => true,
        'supports' => ['title', 'editor'],
        'taxonomies' => ['category'], // ← aqui
        'menu_icon' => 'dashicons-businessman'
    ]);
}

function svc_criar_categorias_padrao() {
    $categorias = ['TI', 'RH', 'Financeiro', 'Logística', 'Marketing'];

    foreach ($categorias as $cat) {
        if (!term_exists($cat, 'category')) {
            wp_insert_term($cat, 'category');
        }
    }
}
svc_criar_categorias_padrao();


