<?php

if (!empty($_POST['nova_senha'])) {
    wp_update_user([
        'ID' => get_current_user_id(),
        'user_pass' => sanitize_text_field($_POST['nova_senha'])
    ]);
}


if (!defined('ABSPATH')) exit;

add_shortcode('formulario_curriculo', 'svc_formulario_curriculo');

function svc_formulario_curriculo() {
    if (!is_user_logged_in()) {
        return '<div class="alert alert-warning">Você precisa estar logado para preencher o currículo.</div>';
    }

    global $wpdb;
    $user_id = get_current_user_id();

    $candidato_id = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM {$wpdb->prefix}svc_candidatos WHERE user_id = %d", $user_id
    ));

    if (!$candidato_id) {
        return '<div class="alert alert-danger">Você precisa completar o cadastro de candidato antes.</div>';
    }

    $curriculo = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}svc_curriculos WHERE candidato_id = %d", $candidato_id
    ));

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['formacao'])) {
        $formacao = sanitize_textarea_field($_POST['formacao']);
        $experiencia = sanitize_textarea_field($_POST['experiencia']);
        $cursos = sanitize_textarea_field($_POST['cursos']);
        $idiomas = sanitize_text_field($_POST['idiomas']);
        $endereco = sanitize_textarea_field($_POST['endereco']);
        $status = 'Completo';

        // Upload do currículo
        $arquivo_url = $curriculo->arquivo_curriculo ?? '';
        if (!empty($_FILES['curriculo']['name'])) {
            $uploaded = media_handle_upload('curriculo', 0);
            if (!is_wp_error($uploaded)) {
                $arquivo_url = wp_get_attachment_url($uploaded);
            }
        }

        $dados = [
            'formacao' => $formacao,
            'experiencia' => $experiencia,
            'cursos_complementares' => $cursos,
            'idiomas' => $idiomas,
            'endereco' => $endereco,
            'status' => $status,
            'arquivo_curriculo' => $arquivo_url,
            'criado_em' => current_time('mysql')
        ];

        if ($curriculo) {
            $wpdb->update("{$wpdb->prefix}svc_curriculos", $dados, ['id' => $curriculo->id]);
            echo '<div class="alert alert-success">Currículo atualizado com sucesso.</div>';
        } else {
            $dados['candidato_id'] = $candidato_id;
            $wpdb->insert("{$wpdb->prefix}svc_curriculos", $dados);
            echo '<div class="alert alert-success">Currículo cadastrado com sucesso.</div>';
        }
    }

    ob_start();
    ?>
    <form method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
        <div class="form-group">
            <label>Formação acadêmica</label>
            <textarea name="formacao" class="form-control" rows="3" required><?php echo esc_textarea($curriculo->formacao ?? ''); ?></textarea>
        </div>

        <div class="form-group">
            <label>Experiências profissionais</label>
            <textarea name="experiencia" class="form-control" rows="3" required><?php echo esc_textarea($curriculo->experiencia ?? ''); ?></textarea>
        </div>

        <div class="form-group">
            <label>Cursos complementares</label>
            <textarea name="cursos" class="form-control" rows="3"><?php echo esc_textarea($curriculo->cursos_complementares ?? ''); ?></textarea>
        </div>

        <div class="form-group">
            <label>Idiomas</label>
            <input type="text" name="idiomas" class="form-control" value="<?php echo esc_attr($curriculo->idiomas ?? ''); ?>">
        </div>

        <div class="form-group">
            <label>Endereço</label>
            <textarea name="endereco" class="form-control" rows="2"><?php echo esc_textarea($curriculo->endereco ?? ''); ?></textarea>
        </div>

        <div class="form-group">
            <label>Importar currículo (PDF ou Word)</label>
            <input type="file" name="curriculo" class="form-control-file" accept=".pdf,.doc,.docx">
        </div>

        <button type="submit" class="btn btn-success">Salvar Currículo</button>
    </form>
    <?php
    return ob_get_clean();
}
