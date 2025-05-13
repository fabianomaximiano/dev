<?php
add_shortcode('formulario_curriculo', 'svc_formulario_curriculo');

function svc_formulario_curriculo() {
    if (!is_user_logged_in()) {
        return '<div class="alert alert-warning">Você precisa estar logado para acessar esta página.</div>';
    }

    global $wpdb;
    $user_id = get_current_user_id();
    $tabela_candidatos = $wpdb->prefix . 'svc_candidatos';
    $tabela_curriculos = $wpdb->prefix . 'svc_curriculos';

    $candidato = $wpdb->get_row($wpdb->prepare("SELECT * FROM $tabela_candidatos WHERE user_id = %d", $user_id));
    if (!$candidato) {
        return '<div class="alert alert-danger">Cadastro de candidato não encontrado.</div>';
    }

    $curriculo = $wpdb->get_row($wpdb->prepare("SELECT * FROM $tabela_curriculos WHERE candidato_id = %d", $candidato->id));
    $is_edit = !!$curriculo;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['objetivo'])) {
        $objetivo = sanitize_textarea_field($_POST['objetivo']);
        $experiencias = maybe_serialize($_POST['experiencias'] ?? []);
        $formacao = maybe_serialize($_POST['formacao'] ?? []);
        $cursos = maybe_serialize($_POST['cursos'] ?? []);
        $idiomas = maybe_serialize($_POST['idiomas'] ?? []);

        if ($is_edit) {
            $wpdb->update($tabela_curriculos, [
                'objetivo' => $objetivo,
                'experiencias' => $experiencias,
                'formacao' => $formacao,
                'cursos' => $cursos,
                'idiomas' => $idiomas
            ], ['id' => $curriculo->id]);
        } else {
            $wpdb->insert($tabela_curriculos, [
                'candidato_id' => $candidato->id,
                'objetivo' => $objetivo,
                'experiencias' => $experiencias,
                'formacao' => $formacao,
                'cursos' => $cursos,
                'idiomas' => $idiomas
            ]);
        }

        echo '<div class="alert alert-success">Currículo salvo com sucesso.</div>';
    }

    // Preparar dados
    $dados = [
        'objetivo' => $curriculo->objetivo ?? '',
        'experiencias' => maybe_unserialize($curriculo->experiencias ?? []) ?: [],
        'formacao' => maybe_unserialize($curriculo->formacao ?? []) ?: [],
        'cursos' => maybe_unserialize($curriculo->cursos ?? []) ?: [],
        'idiomas' => maybe_unserialize($curriculo->idiomas ?? []) ?: [],
    ];

    wp_enqueue_style('bootstrap4', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
    wp_enqueue_script('jquery');
    wp_enqueue_script('validacao', plugin_dir_url(__FILE__) . '../assets/js/validacao.js', ['jquery'], null, true);

    ob_start(); ?>
    <form method="post" class="needs-validation mt-4" novalidate>
        <div class="form-group">
            <label>Objetivo Profissional</label>
            <textarea name="objetivo" class="form-control" required><?= esc_textarea($dados['objetivo']) ?></textarea>
            <div class="invalid-feedback">Informe seu objetivo profissional.</div>
        </div>

        <h5 class="mt-4">Experiências Profissionais</h5>
        <div id="experiencias">
            <?php foreach ($dados['experiencias'] as $i => $exp): ?>
                <?php include __DIR__ . '/partials/experiencia-item.php'; ?>
            <?php endforeach; ?>
            <?php if (empty($dados['experiencias'])): ?>
                <?php include __DIR__ . '/partials/experiencia-item.php'; ?>
            <?php endif; ?>
        </div>
        <button type="button" class="btn btn-outline-secondary mb-3" onclick="addExp()">+ Adicionar Experiência</button>

        <h5 class="mt-4">Formação Acadêmica</h5>
        <div id="formacao">
            <?php foreach ($dados['formacao'] as $i => $curso): ?>
                <?php include __DIR__ . '/partials/formacao-item.php'; ?>
            <?php endforeach; ?>
            <?php if (empty($dados['formacao'])): ?>
                <?php include __DIR__ . '/partials/formacao-item.php'; ?>
            <?php endif; ?>
        </div>
        <button type="button" class="btn btn-outline-secondary mb-3" onclick="addFormacao()">+ Adicionar Formação</button>

        <h5 class="mt-4">Cursos Complementares</h5>
        <div id="cursos">
            <?php foreach ($dados['cursos'] as $i => $cc): ?>
                <?php include __DIR__ . '/partials/curso-item.php'; ?>
            <?php endforeach; ?>
            <?php if (empty($dados['cursos'])): ?>
                <?php include __DIR__ . '/partials/curso-item.php'; ?>
            <?php endif; ?>
        </div>
        <button type="button" class="btn btn-outline-secondary mb-3" onclick="addCurso()">+ Adicionar Curso</button>

        <h5 class="mt-4">Idiomas</h5>
        <div id="idiomas">
            <?php foreach ($dados['idiomas'] as $i => $idioma): ?>
                <div class="form-group">
                    <input type="text" name="idiomas[<?= $i ?>]" class="form-control" value="<?= esc_attr($idioma) ?>" placeholder="Ex: Inglês, Espanhol">
                </div>
            <?php endforeach; ?>
            <?php if (empty($dados['idiomas'])): ?>
                <div class="form-group">
                    <input type="text" name="idiomas[0]" class="form-control" placeholder="Ex: Inglês, Espanhol">
                </div>
            <?php endif; ?>
        </div>
        <button type="button" class="btn btn-outline-secondary mb-3" onclick="addIdioma()">+ Adicionar Idioma</button>

        <button type="submit" class="btn btn-success mt-3">Salvar Currículo</button>
    </form>

    <script>
    let expCount = <?= count($dados['experiencias']) ?: 1 ?>;
    let formacaoCount = <?= count($dados['formacao']) ?: 1 ?>;
    let cursoCount = <?= count($dados['cursos']) ?: 1 ?>;
    let idiomaCount = <?= count($dados['idiomas']) ?: 1 ?>;

    function addExp() {
        fetch('<?= plugin_dir_url(__FILE__) ?>partials/experiencia-item.php?index=' + expCount)
            .then(res => res.text())
            .then(html => {
                document.getElementById('experiencias').insertAdjacentHTML('beforeend', html);
                expCount++;
            });
    }

    function addFormacao() {
        fetch('<?= plugin_dir_url(__FILE__) ?>partials/formacao-item.php?index=' + formacaoCount)
            .then(res => res.text())
            .then(html => {
                document.getElementById('formacao').insertAdjacentHTML('beforeend', html);
                formacaoCount++;
            });
    }

    function addCurso() {
        fetch('<?= plugin_dir_url(__FILE__) ?>partials/curso-item.php?index=' + cursoCount)
            .then(res => res.text())
            .then(html => {
                document.getElementById('cursos').insertAdjacentHTML('beforeend', html);
                cursoCount++;
            });
    }

    function addIdioma() {
        const container = document.getElementById('idiomas');
        const html = '<div class="form-group"><input type="text" name="idiomas[' + idiomaCount + ']" class="form-control" placeholder="Ex: Inglês, Espanhol"></div>';
        container.insertAdjacentHTML('beforeend', html);
        idiomaCount++;
    }
    </script>
    <?php
    return ob_get_clean();
}
