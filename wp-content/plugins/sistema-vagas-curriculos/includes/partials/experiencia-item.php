<?php
$index = isset($_GET['index']) ? intval($_GET['index']) : 0;
?>
<div class="form-row border p-2 mb-2">
    <div class="form-group col-md-6">
        <label>Empresa</label>
        <input type="text" name="experiencias[<?= $index ?>][empresa]" class="form-control">
    </div>
    <div class="form-group col-md-6">
        <label>Função</label>
        <input type="text" name="experiencias[<?= $index ?>][funcao]" class="form-control">
    </div>
    <div class="form-group col-md-3">
        <label>Mês/Ano Entrada</label>
        <input type="text" name="experiencias[<?= $index ?>][entrada]" class="form-control">
    </div>
    <div class="form-group col-md-3">
        <label>Mês/Ano Saída</label>
        <input type="text" name="experiencias[<?= $index ?>][saida]" class="form-control">
    </div>
    <div class="form-group col-md-6">
        <label>Atividades</label>
        <textarea name="experiencias[<?= $index ?>][descricao]" class="form-control"></textarea>
    </div>
</div>
