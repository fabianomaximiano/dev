
<?php if (!defined('ABSPATH')) exit; ?>

<div class="form-group">
    <label>Formação Acadêmica</label>
    <div id="formacao-container">
        <?php
        $formacoes = isset($curriculo->formacao) ? json_decode($curriculo->formacao, true) : [];
        $total = max(3, count($formacoes));
        for ($i = 0; $i < $total; $i++):
            $f = $formacoes[$i] ?? ['instituicao' => '', 'curso' => '', 'conclusao' => ''];
        ?>
        <div class="formacao-item mb-3 border p-3 rounded bg-light">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Instituição</label>
                    <input type="text" name="formacao[<?php echo $i; ?>][instituicao]" class="form-control" value="<?php echo esc_attr($f['instituicao']); ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Curso</label>
                    <input type="text" name="formacao[<?php echo $i; ?>][curso]" class="form-control" value="<?php echo esc_attr($f['curso']); ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label>Ano de conclusão ou data prevista</label>
                <input type="text" name="formacao[<?php echo $i; ?>][conclusao]" class="form-control" placeholder="2025 ou Previsto 2026" value="<?php echo esc_attr($f['conclusao']); ?>">
            </div>
        </div>
        <?php endfor; ?>
    </div>

    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="adicionarFormacao()">Adicionar curso</button>
</div>

<script>
function adicionarFormacao() {
    const container = document.getElementById('formacao-container');
    const total = container.children.length;

    const novo = document.createElement('div');
    novo.classList.add('formacao-item', 'mb-3', 'border', 'p-3', 'rounded', 'bg-light');
    novo.innerHTML = `
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Instituição</label>
                <input type="text" name="formacao[${total}][instituicao]" class="form-control" required>
            </div>
            <div class="form-group col-md-6">
                <label>Curso</label>
                <input type="text" name="formacao[${total}][curso]" class="form-control" required>
            </div>
        </div>
        <div class="form-group">
            <label>Ano de conclusão ou data prevista</label>
            <input type="text" name="formacao[${total}][conclusao]" class="form-control" placeholder="2025 ou Previsto 2026">
        </div>
    `;
    container.appendChild(novo);
}
</script>
