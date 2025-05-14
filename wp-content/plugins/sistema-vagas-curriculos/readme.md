// Conteúdo de exemplo para readme.md
# Sistema de Vagas e Currículos

Plugin WordPress para gerenciamento de vagas de emprego e cadastro de currículos, com candidatura rápida, painel do candidato e administração completa.

---

## 🔧 Funcionalidades

- Cadastro de candidatos (com CPF único, validação e envio de e-mails)
- Cadastro e edição de currículo com campos dinâmicos
- Importação de currículo em PDF/Word
- Listagem pública de vagas com botão "Candidatar-se"
- Registro de candidaturas com controle de status
- Painel do Candidato: currículo, candidaturas, edição e exportação em PDF
- Administração de vagas e currículos pelo admin
- Criação automática de páginas e tabelas ao ativar o plugin
- Templates personalizados integrados ao tema

---

## ✅ Requisitos

- WordPress 5.0+
- PHP 7.4+
- Extensões: `mbstring`, `fileinfo`
- Tema com suporte a páginas personalizadas (ou use Astra)

---

## 🚀 Instalação

1. Extraia o plugin na pasta `/wp-content/plugins/sistema-vagas-curriculos`
2. Acesse **Plugins > Ativar**
3. Ao ativar:
   - Tabelas são criadas no banco
   - Páginas são geradas automaticamente

---

## 📃 Páginas Criadas Automaticamente

| Página                     | Shortcode                | Finalidade                          |
|----------------------------|--------------------------|--------------------------------------|
| Cadastro de Candidato      | `[formulario_candidato]` | Formulário de inscrição de candidatos |
| Login do Candidato         | `[login_candidato]`      | Tela de login (caso implementado)     |
| Cadastro de Currículo      | `[formulario_curriculo]` | Formulário completo do currículo     |
| Painel do Candidato        | `[painel_candidato]`     | Visualização do currículo + status   |
| Vagas Disponíveis          | `[listar_vagas]`         | Lista pública de vagas               |
| Cadastro de Vagas (admin)  | `[formulario_vaga]`      | Formulário para admins cadastrarem vagas |

---

## 🧩 Shortcodes

| Shortcode               | Descrição                          |
|------------------------|--------------------------------------|
| `[formulario_candidato]` | Cadastro inicial do candidato        |
| `[formulario_curriculo]` | Cadastro/edição de currículo         |
| `[listar_vagas]`         | Exibe vagas com botão de candidatura |
| `[painel_candidato]`     | Painel de controle do candidato      |
| `[formulario_vaga]`      | Cadastro de vagas (somente admin)    |

---

## 🗂️ Estrutura do Plugin

