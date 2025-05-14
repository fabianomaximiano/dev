<?php
        // Impede acesso direto
        if (!defined('ABSPATH')) {
            exit;
        }

        // Inicia sessão para controle de login do candidato
        add_action('init', function () {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
        });

        // Carrega scripts e estilos (Bootstrap, máscaras, validações)
        add_action('wp_enqueue_scripts', function () {
            // Bootstrap 4
            wp_enqueue_style('bootstrap-css', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
            wp_enqueue_script('bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js', ['jquery'], null, true);

            // jQuery Mask Plugin
            wp_enqueue_script('jquery-mask', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js', ['jquery'], null, true);

            // Validações JS personalizadas
            wp_enqueue_script('validacao-js', plugin_dir_url(__FILE__) . 'assets/js/validacao.js', ['jquery'], null, true);

            // Estilos adicionais do plugin
            wp_enqueue_style('svc-style', plugin_dir_url(__FILE__) . 'assets/css/style.css');
        });

        // Remove páginas internas do menu quando for exibido automaticamente (por temas)
        add_filter('get_pages', function ($pages, $args) {
            $ocultar_slugs = [
                'meu-curriculo',
                'logout-candidato',
                'cadastro-candidato',
                'login-candidato',
                'painel-do-candidato'
            ];

            return array_filter($pages, function ($page) use ($ocultar_slugs) {
                return !in_array($page->post_name, $ocultar_slugs);
            });
        }, 10, 2);

        // Importa shortcodes (ex: menu_candidato, logout)
        require_once __DIR__ . '/shortcodes.php';