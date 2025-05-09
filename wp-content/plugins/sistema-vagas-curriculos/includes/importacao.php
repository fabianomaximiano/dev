<?php
use PhpOffice\PhpWord\IOFactory as WordIO;
use Smalot\PdfParser\Parser as PdfParser;

function svc_importar_curriculo($arquivo_caminho) {
    $ext = pathinfo($arquivo_caminho, PATHINFO_EXTENSION);
    $conteudo = '';

    if ($ext === 'docx') {
        $phpWord = WordIO::load($arquivo_caminho);
        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if (method_exists($element, 'getText')) {
                    $conteudo .= $element->getText() . "\n";
                }
            }
        }
    } elseif ($ext === 'pdf') {
        $parser = new PdfParser();
        $pdf = $parser->parseFile($arquivo_caminho);
        $conteudo = $pdf->getText();
    } else {
        return 'Formato de arquivo não suportado.';
    }

    return $conteudo;
}

function svc_extrair_dados_curriculo($texto) {
    $dados = [];

    if (preg_match('/^[A-Z\s]{5,}$/m', $texto, $m)) {
        $dados['nome'] = trim($m[0]);
    }

    if (preg_match('/[a-zA-Z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}/i', $texto, $m)) {
        $dados['email'] = $m[0];
    }

    if (preg_match('/\(?\d{2}\)?[\s.-]?\d{4,5}[\s.-]?\d{4}/', $texto, $m)) {
        $dados['telefone'] = $m[0];
    }

    preg_match_all('/(?:Empresa:|Empresa)[\s:]*([^\n]+)\n.*?(Função|Cargo)[\s:]*([^\n]+)\n.*?(Entrada|Período)[\s:]*([^\n]+)/i', $texto, $experiencias, PREG_SET_ORDER);
    $dados['experiencias'] = [];
    foreach ($experiencias as $exp) {
        $dados['experiencias'][] = [
            'empresa' => trim($exp[1]),
            'funcao' => trim($exp[3]),
            'periodo' => trim($exp[5]),
        ];
    }

    preg_match_all('/Curso[\s:]*([^\n]+)\n.*?(Instituição|Faculdade)[\s:]*([^\n]+)/i', $texto, $cursos, PREG_SET_ORDER);
    $dados['cursos'] = [];
    foreach ($cursos as $curso) {
        $dados['cursos'][] = [
            'nome' => trim($curso[1]),
            'instituicao' => trim($curso[3]),
        ];
    }

    return $dados;
}
