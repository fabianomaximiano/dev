<?php
function svc_importar_curriculo($arquivo_tmp) {
    return file_get_contents($arquivo_tmp); // apenas simulação
}

function svc_extrair_dados_curriculo($conteudo) {
    return ['nome' => 'Extraído do currículo', 'email' => 'teste@email.com'];
}
