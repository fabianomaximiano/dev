<?php if (!defined('ABSPATH')) exit; ?>

<div class="form-group">
    <label>Experiências Profissionais</label>
    <div id="experiencias-container">
        <?php
        $experiencias = isset($curriculo->experiencia) ? json_decode($curriculo->experiencia, true) : [];
        $total = max(3, count($experiencias));
        for ($i = 0; $i < $total; $i++):
            $exp = $experiencias[$i] ?? ['empresa' => '', 'funcao' => '', 'entrada' => '', 'saida' => '', 'descricao' => ''];
        ?>
        <div class="experiencia-item mb-3 border p-3 rounded bg-light">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Empresa</label>
                    <input type="text" name="experiencia[<?php echo $i; ?>][empresa]" class="form-control" value="<?php echo esc_attr($exp['empresa']); ?>" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Função exercida</label>
                    <input type="text" name="experiencia[<?php echo $i; ?>][funcao]" class="form-control" value="<?php echo esc_attr($exp['funcao']); ?>" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Mês/Ano de entrada</label>
                    <input type="text" name="experiencia[<?php echo $i; ?>][entrada]" class="form-control" placeholder="MM/AAAA" value="<?php echo esc_attr($exp['entrada']); ?>">
                </div>
                <div class="form-group col-md-6">
                    <label>Mês/Ano de saída</label>
                    <input type="text" name="experiencia[<?php echo $i; ?>][saida]" class="form-control" placeholder="MM/AAAA" value="<?php echo esc_attr($exp['saida']); ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Descrição das atividades</label>
                <textarea name="experiencia[<?php echo $i; ?>][descricao]" class="form-control" rows="2"><?php echo esc_textarea($exp['descricao']); ?></textarea>
            </div>
        </div>
        <?php endfor; ?>
    </div>

    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="adicionarExperiencia()">Adicionar experiência</button>
</div>

<script>
function adicionarExperiencia() {
    const container = document.getElementById('experiencias-container');
    const total = container.children.length;

    const novo = document.createElement('div');
    novo.classList.add('experiencia-item', 'mb-3', 'border', 'p-3', 'rounded', 'bg-light');
    novo.innerHTML = `
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Empresa</label>
                <input type="text" name="experiencia[${total}][empresa]" class="form-control" required>
            </div>
            <div class="form-group col-md-6">
                <label>Função exercida</label>
                <input type="text" name="experiencia[${total}][funcao]" class="form-control" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Mês/Ano de entrada</label>
                <input type="text" name="experiencia[${total}][entrada]" class="form-control" placeholder="MM/AAAA">
            </div>
            <div class="form-group col-md-6">
                <label>Mês/Ano de saída</label>
                <input type="text" name="experiencia[${total}][saida]" class="form-control" placeholder="MM/AAAA">
            </div>
        </div>
        <div class="form-group">
            <label>Descrição das atividades</label>
            <textarea name="experiencia[${total}][descricao]" class="form-control" rows="2"></textarea>
        </div>
    `;
    container.appendChild(novo);
}
</script>
// Conteúdo de exemplo para candidato-experiencias.php
