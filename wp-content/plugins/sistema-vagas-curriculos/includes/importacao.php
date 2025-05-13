<?php
// Simulação de importação e extração de dados de currículo

function svc_importar_curriculo($arquivo_tmp) {
    // Aqui futuramente pode ser implementada a leitura com libraries como PhpWord, Smalot/pdfparser etc.
    return file_get_contents($arquivo_tmp); // Por enquanto, só retorna o texto bruto
}

function svc_extrair_dados_curriculo($conteudo) {
    // Simulação de extração — pode-se usar expressões regulares ou IA no futuro
    return [
        'nome' => 'Nome Detectado',
        'email' => 'email@detectado.com',
        'cpf' => '000.000.000-00'
    ];
}
