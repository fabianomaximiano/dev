<?php
$index = isset($_GET['index']) ? intval($_GET['index']) : 0;
?>
<div class="form-row border p-2 mb-2">
    <div class="form-group col-md-6">
        <label>Instituição</label>
        <input type="text" name="formacao[<?= $index ?>][instituicao]" class="form-control">
    </div>
    <div class="form-group col-md-6">
        <label>Curso</label>
        <input type="text" name="formacao[<?= $index ?>][curso]" class="form-control">
    </div>
    <div class="form-group col-md-6">
        <label>Ano Conclusão ou Previsão</label>
        <input type="text" name="formacao[<?= $index ?>][conclusao]" class="form-control">
    </div>
</div>
