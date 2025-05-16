document.addEventListener('DOMContentLoaded', function () {
  const experienciasContainer = document.getElementById('experiencias');
  const btnAddExperiencia = document.getElementById('btn-add-experiencia');

  const formacoesContainer = document.getElementById('formacoes');
  const btnAddFormacao = document.getElementById('btn-add-formacao');

  const cursosContainer = document.getElementById('cursos');
  const btnAddCurso = document.getElementById('btn-add-curso');

  // Função para criar bloco de experiência
  function criarExperiencia() {
    const div = document.createElement('div');
    div.classList.add('experiencia-item', 'mb-3', 'p-3', 'border', 'rounded');
    div.innerHTML = `
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
    `;
    return div;
  }

  // Função para criar bloco de formação
  function criarFormacao() {
    const div = document.createElement('div');
    div.classList.add('formacao-item', 'mb-3', 'p-3', 'border', 'rounded');
    div.innerHTML = `
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
    `;
    return div;
  }

  // Função para criar bloco de curso complementar
  function criarCurso() {
    const div = document.createElement('div');
    div.classList.add('curso-item', 'mb-3', 'p-3', 'border', 'rounded');
    div.innerHTML = `
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
    `;
    return div;
  }

  btnAddExperiencia.addEventListener('click', function () {
    experienciasContainer.appendChild(criarExperiencia());
  });

  btnAddFormacao.addEventListener('click', function () {
    formacoesContainer.appendChild(criarFormacao());
  });

  btnAddCurso.addEventListener('click', function () {
    cursosContainer.appendChild(criarCurso());
  });

  // Aqui você pode adicionar código para envio do formulário via Ajax, validações, máscaras, etc.

  jQuery('#form-meu-curriculo').on('submit', function(e) {
    e.preventDefault();

    var dados = jQuery(this).serializeArray();

    jQuery.ajax({
        url: svc_ajax_object.ajax_url,
        method: 'POST',
        data: {
            action: 'salvar_curriculo',
            security: svc_ajax_object.nonce,
            dados: dados
        },
        success: function(response) {
            if (response.success) {
                alert(response.data.message);
            } else {
                alert('Erro ao salvar currículo.');
            }
        },
        error: function() {
            alert('Erro ao enviar a requisição.');
        }
    });
});

});
