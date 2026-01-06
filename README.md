# 📝 To-Do App

Aplicação web simples e intuitiva para gestão de tarefas (To-Do List), desenvolvida com **Laravel**, **Tailwind CSS** e **Vue.js** (uso pontual), com foco em boa UX, código limpo e arquitetura organizada.

---

## 🎯 Objetivo

Permitir que os utilizadores organizem as suas tarefas diárias de forma eficiente, com suporte a prioridades, datas de vencimento, filtros e edição rápida através de um modal.

---

## 🏗️ Arquitetura

A aplicação segue as boas práticas do **Laravel MVC**, com algumas decisões arquiteturais importantes:

- **MVC (Model–View–Controller)** para separação de responsabilidades
- **Repository Pattern** para desacoplar a lógica de acesso a dados
- **Enums** para estados, prioridades e vencimentos (evita strings mágicas)
- **Blade** como motor principal de views
- **Vue.js** usado apenas para componentes interativos (modal e toast), não sendo uma SPA

Estrutura pensada para ser simples, escalável e fácil de manter.

---

## ✨ Funcionalidades

### 🆕 Criação de tarefas
- Criar tarefas com:
  - Título (obrigatório)
  - Descrição (opcional)
  - Prioridade: baixa, média ou alta
  - Data de vencimento (opcional)

### 📋 Listagem de tarefas
- Visualização de todas as tarefas
- Filtros por:
  - Estado (pendente, concluída)
  - Prioridade
  - Data de vencimento
- Estados vazios (empty states) com mensagens contextuais

### ✏️ Edição de tarefas
- Edição via **modal**
- Campos editáveis:
  - Título
  - Descrição
  - Prioridade
  - Data de vencimento
- Atualização via JSON (AJAX)

### ✅ Gestão de estado
- Marcar tarefas como concluídas
- Reabrir tarefas concluídas

### 🗑️ Exclusão
- Eliminação individual de tarefas com confirmação

### 🎨 UX / UI
- Design limpo e responsivo
- Feedback visual:
  - Toasts (ex: “Tarefa atualizada ✔️”)
  - Mensagem flash de boas-vindas
- Animações subtis
- Modal acessível (ESC, foco automático, overlay)

### 🔐 Autenticação
- Proteção da rota `/tasks`
- Utilizadores não autenticados são redirecionados para login

---

## 🖥️ Tech Stack

### Backend
- **Laravel 12**
- PHP 8+
- MySQL
- PHPUnit

### Frontend
- **Tailwind CSS**
- Blade
- Vue.js 3 (uso pontual)
- JavaScript básico

---

## 🗄️ Base de Dados

Tabela `tasks` com os seguintes campos principais:
- `title`
- `description`
- `status`
- `priority`
- `due_date`
- `completed`

Uso de:
- Enums para estados e prioridades
- Casts no model
- Queries otimizadas no repositório

---

## 🧪 Testes

A aplicação inclui testes **unitários** e **de integração**, cobrindo:

- Estados da tarefa
- Criação de tarefas via HTTP
- Proteção de rotas
- Atualização de tarefas via JSON

### Executar testes relacionados com tarefas:
```bash
php artisan test --filter=Task

---

## Deploy

Este projeto foi desenvolvido e testado localmente.

Para colocar em produção num servidor:
- Clonar o repositório
- Configurar o ficheiro `.env`
- Executar migrations
- Compilar assets
- Configurar servidor web (Apache/Nginx)

O deploy final não está incluído neste projeto.
