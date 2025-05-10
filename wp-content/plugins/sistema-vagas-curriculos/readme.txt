
=== Sistema de Vagas e Currículos ===
Contributors: Seu Nome
Tags: vagas, currículos, emprego, recrutamento
Requires at least: 5.0
Tested up to: 6.5
Stable tag: 1.1
License: GPLv2 or later

== Descrição ==
Este plugin permite o gerenciamento de vagas de emprego e cadastro de currículos por candidatos, incluindo importação de dados de currículos em PDF e Word.

== Instalação ==
1. Envie o arquivo .zip do plugin via painel do WordPress em **Plugins > Adicionar novo > Enviar plugin**.
2. Ative o plugin após o upload.
3. O plugin criará automaticamente as tabelas no banco de dados.

== Shortcodes ==

**[formulario_candidato]**  
Exibe o formulário de cadastro de candidato.

**[formulario_curriculo]**  
Exibe o formulário de envio e extração automática de currículo em PDF ou Word.

**[painel_candidato]**  
Painel do candidato logado. Mostra botão "Preencher currículo" ou "Editar currículo", dependendo se ele já enviou ou não seu currículo.

== Como usar ==

1. Crie uma página chamada **Cadastro de Candidato** e insira o shortcode:
   [formulario_candidato]

2. Crie uma página chamada **Meu Currículo** e insira o shortcode:
   [formulario_curriculo]

3. Crie uma página chamada **Painel do Candidato** e insira o shortcode:
   [painel_candidato]

   > Importante: a URL da página “Meu Currículo” deve ser `/meu-curriculo` ou altere no código o link do botão no `painel_candidato`.

== Recursos ==

- Criação automática das tabelas
- Cadastro de candidatos com CPF único
- Upload de currículo em .pdf ou .docx
- Extração automática de nome, e-mail, telefone, experiências e cursos
- Bootstrap 4 e validação de formulário
- Interface de administração no painel do WordPress

== Licença ==
GPLv2 ou posterior.
