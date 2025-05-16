<?php
// templates/painel-meu-curriculo.php
// Formulário do painel do candidato para editar/visualizar currículo

// Supondo que a verificação de login e dados do candidato já tenha sido feita antes deste include

?>

<h2>Cadastro de Currículo</h2>

<form id="form-meu-curriculo" method="post" enctype="multipart/form-data" novalidate>

  <h3>Dados Pessoais</h3>
  <div class="form-group">
    <label for="nome_completo">Nome Completo</label>
    <input type="text" id="nome_completo" name="nome_completo" class="form-control" required>
  </div>
  <div class="form-group">
    <label for="cpf">CPF</label>
    <input type="text" id="cpf" name="cpf" class="form-control" required>
  </div>
  <div class="form-group">
    <label for="email">E-mail</label>
    <input type="email" id="email" name="email" class="form-control" required>
  </div>
  <div class="form-group">
    <label for="telefone">Telefone</label>
    <input type="text" id="telefone" name="telefone" class="form-control" required>
  </div>

  <h3>Resumo Profissional</h3>
  <div class="form-group">
    <label for="resumo_profissional">Resumo</label>
    <textarea id="resumo_profissional" name="resumo_profissional" class="form-control" rows="5"></textarea>
  </div>

  <h3>Experiência Profissional</h3>
  <div id="experiencias">
    <div class="experiencia-item mb-3 p-3 border rounded">
      <div class="form-row">
        <div class="form-group col-md-4">
          <label>Empresa</label>
          <input type="text" name="experiencia_empresa[]" class="form-control">
        </div>
        <div class="form-group col-md-4">
          <label>Função</label>
          <input type="text" name="experiencia_funcao[]" class="form-control">
        </div>
        <div class="form-group col-md-4">
          <label>Período</label>
          <input type="text" name="experiencia_periodo[]" class="form-control">
        </div>
      </div>
    </div>
  </div>
  <button type="button" id="btn-add-experiencia" class="btn btn-secondary mb-4">Adicionar Experiência</button>

  <h3>Formação Acadêmica</h3>
  <div id="formacoes">
    <div class="formacao-item mb-3 p-3 border rounded">
      <div class="form-row">
        <div class="form-group col-md-6">
          <label>Curso</label>
          <input type="text" name="formacao_curso[]" class="form-control">
        </div>
        <div class="form-group col-md-6">
          <label>Instituição</label>
          <input type="text" name="formacao_instituicao[]" class="form-control">
        </div>
      </div>
    </div>
  </div>
  <button type="button" id="btn-add-formacao" class="btn btn-secondary mb-4">Adicionar Formação</button>

  <h3>Cursos Complementares</h3>
  <div id="cursos">
    <div class="curso-item mb-3 p-3 border rounded">
      <div class="form-row">
        <div class="form-group col-md-6">
          <label>Curso</label>
          <input type="text" name="curso_nome[]" class="form-control">
        </div>
        <div class="form-group col-md-6">
          <label>Instituição</label>
          <input type="text" name="curso_instituicao[]" class="form-control">
        </div>
      </div>
    </div>
  </div>
  <button type="button" id="btn-add-curso" class="btn btn-secondary mb-4">Adicionar Curso</button>

  <h3>Habilidades</h3>
  <div class="form-group">
    <input type="text" name="habilidades" class="form-control" placeholder="Liste suas habilidades separadas por vírgula">
  </div>

  <h3>Idiomas</h3>
  <div class="form-group">
    <input type="text" name="idiomas" class="form-control" placeholder="Liste seus idiomas separados por vírgula">
  </div>

  <h3>Informações Adicionais</h3>
  <div class="form-group">
    <textarea name="informacoes_adicionais" class="form-control" rows="4"></textarea>
  </div>

  <button type="submit" class="btn btn-primary">Salvar Currículo</button>

</form>
