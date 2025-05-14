<?php
        if (!defined('ABSPATH')) exit;

        function svc_instalacao_completa() {
            global $wpdb;
            $charset_collate = $wpdb->get_charset_collate();
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';

            $candidatos     = $wpdb->prefix . 'svc_candidatos';
            $curriculos     = $wpdb->prefix . 'svc_curriculos';
            $vagas          = $wpdb->prefix . 'svc_vagas';
            $candidaturas   = $wpdb->prefix . 'svc_candidaturas';

            // 1) Criar tabelas (sem FK, que são criadas via SQL manual)
            dbDelta("
                CREATE TABLE $candidatos (
                    id INT(11) NOT NULL AUTO_INCREMENT,
                    user_id INT(11) NOT NULL,
                    nome_completo VARCHAR(255) NOT NULL,
                    cpf VARCHAR(20) NOT NULL UNIQUE,
                    email VARCHAR(100) NOT NULL,
                    telefone VARCHAR(20),
                    senha VARCHAR(255) NOT NULL,
                    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (id)
                ) $charset_collate;
            ");

            dbDelta("
                CREATE TABLE $curriculos (
                    id INT(11) NOT NULL AUTO_INCREMENT,
                    candidato_id INT(11) NOT NULL,
                    objetivo TEXT,
                    experiencias LONGTEXT,
                    formacao LONGTEXT,
                    cursos LONGTEXT,
                    idiomas LONGTEXT,
                    arquivo_curriculo VARCHAR(255),
                    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (id)
                ) $charset_collate;
            ");

            dbDelta("
                CREATE TABLE $vagas (
                    id INT(11) NOT NULL AUTO_INCREMENT,
                    titulo VARCHAR(255) NOT NULL,
                    descricao LONGTEXT,
                    requisitos LONGTEXT,
                    diferenciais LONGTEXT,
                    categoria VARCHAR(100),
                    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (id)
                ) $charset_collate;
            ");

            dbDelta("
                CREATE TABLE $candidaturas (
                    id INT(11) NOT NULL AUTO_INCREMENT,
                    candidato_id INT(11) NOT NULL,
                    curriculo_id INT(11) NOT NULL,
                    vaga_id INT(11) NOT NULL,
                    status VARCHAR(50) NOT NULL DEFAULT 'Em análise',
                    data_candidatura DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (id)
                ) $charset_collate;
            ");

            // 2) Criar páginas padrão com shortcodes
            $paginas = [
                ['title' => 'Login do Candidato',    'slug' => 'login-candidato',      'shortcode' => '[login_candidato]'],
                ['title' => 'Cadastro de Candidato', 'slug' => 'cadastro-candidato',   'shortcode' => '[formulario_candidato]'],
                ['title' => 'Cadastro de Currículo', 'slug' => 'meu-curriculo',        'shortcode' => '[formulario_curriculo]'],
                ['title' => 'Painel do Candidato',   'slug' => 'painel-do-candidato',  'shortcode' => '[painel_candidato]'],
                ['title' => 'Vagas Disponíveis',     'slug' => 'vagas-disponiveis',    'shortcode' => '[listar_vagas]'],
            ];

            foreach ($paginas as $pagina) {
                $existe = get_page_by_path($pagina['slug']);
                if (!$existe) {
                    wp_insert_post([
                        'post_title'    => $pagina['title'],
                        'post_name'     => $pagina['slug'],
                        'post_content'  => $pagina['shortcode'],
                        'post_status'   => 'publish',
                        'post_type'     => 'page'
                    ]);
                }
            }
        }

        // Hook de ativação
        register_activation_hook(__FILE__, 'svc_instalacao_completa');
