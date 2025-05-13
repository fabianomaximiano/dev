<?php
$index = isset($_GET['index']) ? intval($_GET['index']) : 0;
?>
<div class="form-row border p-2 mb-2">
    <div class="form-group col-md-6">
        <label>Instituição</label>
        <input type="text" name="cursos[<?= $index ?>][instituicao]" class="form-control">
    </div>
    <div class="form-group col-md-6">
        <label>Curso</label>
        <input type="text" name="cursos[<?= $index ?>][curso]" class="form-control">
    </div>
</div>
