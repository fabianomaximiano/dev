<?php
if (!defined('ABSPATH')) exit;

// Garante que a sessão esteja ativa
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

add_shortcode('formulario_curriculo', 'svc_formulario_curriculo');

function svc_formulario_curriculo() {
    $dados = $_SESSION['curriculo_dados'] ?? [
        'experiencia' => [[]],
        'formacao' => [[]],
        'cursos' => [[]],
    ];

    ob_start();
    ?>
    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="needs-validation" novalidate>
        <input type="hidden" name="action" value="salvar_curriculo">
        <input type="hidden" name="importado" value="<?php echo isset($_SESSION['curriculo_dados']) ? 1 : 0; ?>">

        <?php if (isset($_GET['importado'])): ?>
            <div class="alert alert-info">Currículo importado. Revise os dados abaixo antes de salvar.</div>
        <?php endif; ?>

        <h4 class="mt-4">Experiência Profissional</h4>
        <div id="experiencias">
            <?php foreach ($dados['experiencia'] as $index => $exp): ?>
                <div class="form-row border rounded p-3 mb-3">
                    <div class="form-group col-md-4">
                        <label>Empresa</label>
                        <input type="text" name="experiencia[<?= $index ?>][empresa]" class="form-control" value="<?= esc_attr($exp['empresa'] ?? '') ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Função</label>
                        <input type="text" name="experiencia[<?= $index ?>][funcao]" class="form-control" value="<?= esc_attr($exp['funcao'] ?? '') ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Período</label>
                        <input type="text" name="experiencia[<?= $index ?>][periodo]" class="form-control" value="<?= esc_attr($exp['periodo'] ?? '') ?>">
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <h4>Formação Acadêmica</h4>
        <div id="formacoes">
            <?php foreach ($dados['formacao'] as $index => $form): ?>
                <div class="form-row border rounded p-3 mb-3">
                    <div class="form-group col-md-6">
                        <label>Curso</label>
                        <input type="text" name="formacao[<?= $index ?>][curso]" class="form-control" value="<?= esc_attr($form['curso'] ?? '') ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Instituição</label>
                        <input type="text" name="formacao[<?= $index ?>][instituicao]" class="form-control" value="<?= esc_attr($form['instituicao'] ?? '') ?>">
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <h4>Cursos Complementares</h4>
        <div id="cursos">
            <?php foreach ($dados['cursos'] as $index => $curso): ?>
                <div class="form-row border rounded p-3 mb-3">
                    <div class="form-group col-md-6">
                        <label>Curso</label>
                        <input type="text" name="cursos[<?= $index ?>][curso]" class="form-control" value="<?= esc_attr($curso['curso'] ?? '') ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Instituição</label>
                        <input type="text" name="cursos[<?= $index ?>][instituicao]" class="form-control" value="<?= esc_attr($curso['instituicao'] ?? '') ?>">
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <button type="submit" class="btn btn-primary">Salvar Currículo</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const forms = document.getElementsByClassName('needs-validation');
            Array.prototype.forEach.call(forms, function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        });
    </script>
    <?php
    // Limpa os dados da sessão
    unset($_SESSION['curriculo_dados']);

    return ob_get_clean();
}
