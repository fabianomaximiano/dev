<?php
if (!defined('ABSPATH')) exit;

function svc_importar_curriculo($file) {
    if (isset($file) && $file['error'] == 0) {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $permitidas = ['pdf', 'doc', 'docx'];

        if (!in_array(strtolower($ext), $permitidas)) {
            return 'Formato de arquivo não permitido.';
        }

        $upload_dir = wp_upload_dir();
        $destino = $upload_dir['basedir'] . '/curriculos/';

        if (!file_exists($destino)) {
            mkdir($destino, 0755, true);
        }

        $nome_arquivo = uniqid() . '.' . $ext;
        $caminho_final = $destino . $nome_arquivo;

        if (move_uploaded_file($file['tmp_name'], $caminho_final)) {
            return $upload_dir['baseurl'] . '/curriculos/' . $nome_arquivo;
        } else {
            return 'Erro ao mover o arquivo.';
        }
    }

    return 'Nenhum arquivo enviado.';
}
