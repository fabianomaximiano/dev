<div class="form-group">
    <label>Experiências Profissionais</label>
    <div id="experiencias">
        <div class="experiencia mb-2">
            <input type="text" name="experiencias[]" class="form-control" placeholder="Empresa - Cargo - Período" required>
        </div>
    </div>
    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="adicionarExperiencia()">Adicionar</button>
</div>
<script>
function adicionarExperiencia() {
    const container = document.getElementById('experiencias');
    const novo = document.createElement('div');
    novo.classList.add('experiencia', 'mb-2');
    novo.innerHTML = '<input type="text" name="experiencias[]" class="form-control" placeholder="Empresa - Cargo - Período" required>';
    container.appendChild(novo);
}
</script>
