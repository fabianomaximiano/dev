<?php
if (!defined('ABSPATH')) exit;

// Autoload de dependências do Composer
require_once plugin_dir_path(__FILE__) . '../vendor/autoload.php';

// Usos corretos no topo
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;

// Ações do WordPress
add_action('admin_post_nopriv_importar_curriculo', 'svc_importar_curriculo');
add_action('admin_post_importar_curriculo', 'svc_importar_curriculo');

function svc_importar_curriculo() {
    if (empty($_FILES['curriculo_arquivo']['tmp_name'])) {
        wp_redirect(site_url('/meu-curriculo?erro=arquivo_vazio'));
        exit;
    }

    // Garante que a sessão esteja ativa
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $arquivo = $_FILES['curriculo_arquivo']['tmp_name'];
    $nome    = $_FILES['curriculo_arquivo']['name'];
    $ext     = strtolower(pathinfo($nome, PATHINFO_EXTENSION));

    $textoExtraido = '';

    // PDF
    if ($ext === 'pdf') {
        try {
            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile($arquivo);
            $textoExtraido = $pdf->getText();
        } catch (Exception $e) {
            wp_redirect(site_url('/meu-curriculo?erro=pdf_falha'));
            exit;
        }
    }

    // DOCX
    elseif ($ext === 'docx') {
        try {
            $phpWord = \PhpOffice\PhpWord\IOFactory::load($arquivo, 'Word2007');

            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if ($element instanceof Text) {
                        $textoExtraido .= $element->getText() . "\n";
                    } elseif ($element instanceof TextRun) {
                        foreach ($element->getElements() as $subElement) {
                            if ($subElement instanceof Text) {
                                $textoExtraido .= $subElement->getText() . "\n";
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            wp_redirect(site_url('/meu-curriculo?erro=doc_falha'));
            exit;
        }
    }

    // Tipo não suportado
    else {
        wp_redirect(site_url('/meu-curriculo?erro=tipo_nao_suportado'));
        exit;
    }

    // Processa texto extraído em blocos estruturados
    $dados = [
        'experiencia' => [],
        'formacao' => [],
        'cursos' => [],
    ];

    $linhas = explode("\n", strip_tags($textoExtraido));
    foreach ($linhas as $linha) {
        $linha = trim($linha);

        if (stripos($linha, 'empresa') !== false || stripos($linha, 'cargo') !== false) {
            $dados['experiencia'][] = [
                'empresa' => $linha,
                'funcao' => '',
                'periodo' => ''
            ];
        } elseif (stripos($linha, 'curso') !== false || stripos($linha, 'faculdade') !== false) {
            $dados['formacao'][] = [
                'curso' => $linha,
                'instituicao' => ''
            ];
        } elseif (stripos($linha, 'complementar') !== false) {
            $dados['cursos'][] = [
                'curso' => $linha,
                'instituicao' => ''
            ];
        }
    }

    $_SESSION['curriculo_dados'] = $dados;

    // Redireciona de volta para o formulário
    wp_redirect(site_url('/meu-curriculo?importado=1'));
    exit;
}
