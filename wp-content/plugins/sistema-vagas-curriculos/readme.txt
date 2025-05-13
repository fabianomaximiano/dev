=== Sistema de Vagas e Currículos ===
Contributors: Seu Nome
Tags: vagas, currículos, candidatura, RH, emprego, PDF, cadastro de vaga
Requires at least: 5.0
Tested up to: 6.5
Stable tag: 2.2
License: GPLv2 or later

== Descrição ==
Plugin para WordPress que permite gerenciar vagas de emprego, currículos de candidatos, candidaturas e exportação de currículo em PDF. Candidatos podem se cadastrar, preencher currículo, acompanhar suas inscrições e se candidatar a vagas. Administradores podem cadastrar vagas, visualizar candidaturas e currículos recebidos.

== Instalação ==
1. Vá em Plugins > Adicionar novo > Enviar plugin.
2. Selecione e envie o arquivo `sistema-vagas-curriculos.zip`.
3. Ative o plugin.
4. O plugin criará automaticamente:
   - O post type personalizado "vaga"
   - As páginas:
     - /vagas → [listar_vagas]
     - /cadastro-de-candidato → [formulario_candidato]
     - /meu-curriculo → [formulario_curriculo]
     - /painel-do-candidato → [painel_candidato]
     - /cadastrar-vaga → [formulario_vaga]

== Shortcodes ==
- `[listar_vagas]` — Lista todas as vagas com filtro por categoria e botão "Candidatar-se"
- `[formulario_candidato]` — Formulário de cadastro do candidato (CPF único)
- `[formulario_curriculo]` — Envio de currículo em PDF/Word
- `[painel_candidato]` — Painel com resumo, histórico de candidaturas e exportação para PDF
- `[formulario_vaga]` — Formulário para cadastro de vaga (apenas para administradores)

== Funcionalidades ==
🧑‍💼 Para candidatos:
- Cadastro com CPF único
- Upload e atualização de currículo (PDF, DOC, DOCX)
- Painel com perfil e histórico de candidaturas
- Exportação de currículo em PDF
- Visualização e candidatura a vagas com filtro por área

🛠️ Para administradores:
- Cadastro e gerenciamento de vagas com categorias
- Formulário de cadastro de vaga via shortcode (restrito)
- Visualização de currículos e candidaturas no admin
- Notificações por e-mail ao receber candidaturas
- Criação automática das páginas necessárias na ativação

== Licença ==
Este plugin é licenciado sob a GPLv2 ou posterior.
