# Plugin: Sistema de Vagas e Currículos

## Descrição

Plugin WordPress para gerenciar vagas de emprego e currículos. Recursos incluídos:

- Cadastro de vagas (admin)
- Cadastro de candidatos
- Cadastro e importação de currículos
- Painel do candidato com edição de dados, acompanhamento de candidaturas
- Validação de CPF e e-mail
- Máscaras para CPF e telefone
- Notificações por e-mail ao administrador e candidato
- Shortcodes públicos com restrições baseadas em login
- Painel administrativo para listar candidaturas
- Filtros por categoria de vaga

## Instalação

1. Copie a pasta do plugin para `wp-content/plugins`.
2. Ative o plugin no painel do WordPress.
3. As páginas principais serão criadas automaticamente:
   - `/vagas`
   - `/painel-candidato`
   - `/meu-curriculo`
   - `/cadastrar-vaga`

## Shortcodes

- `[listar_vagas]` — Lista todas as vagas públicas com botão "Candidatar-se"
- `[formulario_vaga]` — Formulário para cadastro de vaga (apenas admins)
- `[painel_candidato]` — Painel de controle do candidato (após login)
- `[formulario_curriculo]` — Cadastro de currículo após login

## Organização

- `includes/` — Lógica PHP e banco de dados
- `js/` — Scripts JS (validação, máscaras)
- `css/` — Estilos visuais
- `templates/` — Partials reutilizáveis em HTML

## Requisitos

- WordPress 5.5+
- PHP 7.4+
- Bootstrap 4 no tema ativo
