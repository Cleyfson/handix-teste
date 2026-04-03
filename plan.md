# 📌 Handix - Plano de Desenvolvimento (Fullstack com DDD + Eloquent + MCP)

## 🎯 Objetivo

Desenvolver um sistema de gestão de contatos com:

* Backend em Laravel (API REST)
* Arquitetura baseada em DDD (simplificada)
* Uso de Eloquent ORM
* Frontend (Vue ou React)
* Ambiente Docker
* Estrutura pensada para uso com IA (MCP)

---

# 🧠 Visão Geral da Arquitetura

* Domain → regras de negócio
* Application → casos de uso
* Infra → acesso a dados (Eloquent)
* Http → controllers e requests

---

# 🚀 FASE 1 — Setup inicial + MCP

## 1.1 Criar repositório

Estrutura inicial:

```
/api
/frontend
/docker
```

---

## 1.2 Configurar MCP

Criar arquivo:

```
/api/mcp-context.json
```

### Conteúdo inicial:

```json
{
  "project": "Handix Contact Manager",
  "architecture": "DDD",
  "backend": "Laravel 11",
  "frontend": "Vue 3",
  "domains": [
    {
      "name": "Contact",
      "description": "Gerenciamento de contatos"
    }
  ]
}
```

---

## 1.3 Expandir contexto MCP

```json
{
  "entities": {
    "Contact": {
      "fields": ["id", "name", "email", "phone", "notes"],
      "rules": {
        "name": "required",
        "email": "required|unique"
      }
    }
  },
  "use_cases": [
    "CreateContact",
    "ListContacts",
    "UpdateContact",
    "DeleteContact"
  ]
}
```

---

# 🐳 FASE 2 — Docker + Laravel

## 2.1 Criar projeto Laravel

```
cd api
laravel new .
```

---

## 2.2 Criar docker-compose

Serviços:

* app (php-fpm)
* nginx
* mysql

---

## 2.3 Validar ambiente

* Subir containers
* Testar acesso
* Validar conexão com banco

---

# 🧱 FASE 3 — Modelagem com Eloquent

## 3.1 Migration

Tabela `contacts`:

* id
* name
* email (unique)
* phone (nullable)
* notes (nullable)
* timestamps

---

## 3.2 Model

```php
class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'notes'
    ];
}
```

---

# 🧠 FASE 4 — Estrutura DDD

## Estrutura:

```
app/
 ├── Domain/
 ├── Application/
 ├── Infra/
 ├── Http/
```

---

## Regra importante

* Domain NÃO usa Eloquent
* Infra usa Eloquent

---

# 🧩 FASE 5 — Repository

## Interface

```
Domain/Repositories/ContactRepositoryInterface.php
```

Métodos:

* create
* update
* delete
* findAll
* findById
* findByEmail

---

## Implementação

```
Infra/Repositories/ContactRepository.php
```

Usando Eloquent

---

# ⚙️ FASE 6 — UseCases

Local:

```
Application/UseCases/Contact/
```

---

## Casos de uso

### CreateContactUseCase

* valida email único
* cria contato

### ListContactsUseCase

* lista contatos
* aplica busca

### UpdateContactUseCase

* atualiza dados

### DeleteContactUseCase

* remove contato

---

# 🌐 FASE 7 — HTTP Layer

## Requests

* StoreContactRequest
* UpdateContactRequest

Validações:

```
name: required
email: required|email|unique
```

---

## Controller

Responsabilidades:

* receber request
* chamar use case
* retornar response

---

## Rotas

```php
Route::apiResource('contacts', ContactController::class);
```

---

# 🔎 FASE 8 — Busca

## Implementação

Query param:

```
GET /contacts?search=joao
```

---

## Lógica

* buscar por nome
* buscar por email

---

# ⚛️ FASE 9 — Frontend

## 9.1 Criar projeto

```
frontend/
```

Sugestão:

* Vue 3 + Vite

---

## 9.2 Estrutura

```
views/
components/
services/api.js
```

---

## 9.3 Funcionalidades

* listar contatos
* criar contato
* editar contato
* excluir contato
* buscar contato

---

## 9.4 Integração API

Endpoints:

```
GET /contacts
POST /contacts
PUT /contacts/{id}
DELETE /contacts/{id}
```

---

# 🔗 FASE 10 — Integração

Testar fluxo completo:

1. Criar contato
2. Listar
3. Buscar
4. Editar
5. Deletar

---

# ✨ FASE 11 — Refinamento

## 11.1 Tratamento de erros

Formato padrão:

```json
{
  "message": "Erro"
}
```

---

## 11.2 UX

* loading
* feedback visual

---

## 11.3 Clean Code

* sem regra em controller
* nomes claros
* separação de responsabilidades

---

# 📄 FASE 12 — README

## Deve conter:

### Descrição

Sistema de gestão de contatos com Laravel + Vue

---

### Arquitetura

* DDD simplificado
* separação de camadas

---

### Como rodar

```
docker-compose up -d
```

---

### Decisões técnicas

* uso de Eloquent no Infra
* uso de UseCases
* estrutura modular

---

# 🧠 Uso com IA

Este plano pode ser usado para:

* gerar código por etapas
* guiar agentes de desenvolvimento
* manter consistência arquitetural

---

# 🚀 Resultado Esperado

* API REST organizada
* DDD aplicado corretamente
* Frontend funcional
* Docker configurado
* Código limpo e profissional

---

# 📌 Observações finais

* Evitar overengineering
* Priorizar clareza e organização
* Foco em qualidade e não quantidade
* Demonstrar domínio da stack

---
# 📌 Handix - Plano de Desenvolvimento (Fullstack com DDD + Eloquent + MCP)

## 🎯 Objetivo

Desenvolver um sistema de gestão de contatos com:

* Backend em Laravel (API REST)
* Arquitetura baseada em DDD (simplificada)
* Uso de Eloquent ORM
* Frontend (Vue ou React)
* Ambiente Docker
* Estrutura pensada para uso com IA (MCP)

---

# 🧠 Visão Geral da Arquitetura

* Domain → regras de negócio
* Application → casos de uso
* Infra → acesso a dados (Eloquent)
* Http → controllers e requests

---

# 🚀 FASE 1 — Setup inicial + MCP

## 1.1 Criar repositório

Estrutura inicial:

```
/api
/frontend
/docker
```

---

## 1.2 Configurar MCP

Criar arquivo:

```
/api/mcp-context.json
```

### Conteúdo inicial:

```json
{
  "project": "Handix Contact Manager",
  "architecture": "DDD",
  "backend": "Laravel 11",
  "frontend": "Vue 3",
  "domains": [
    {
      "name": "Contact",
      "description": "Gerenciamento de contatos"
    }
  ]
}
```

---

## 1.3 Expandir contexto MCP

```json
{
  "entities": {
    "Contact": {
      "fields": ["id", "name", "email", "phone", "notes"],
      "rules": {
        "name": "required",
        "email": "required|unique"
      }
    }
  },
  "use_cases": [
    "CreateContact",
    "ListContacts",
    "UpdateContact",
    "DeleteContact"
  ]
}
```

---

# 🐳 FASE 2 — Docker + Laravel

## 2.1 Criar projeto Laravel

```
cd api
laravel new .
```

---

## 2.2 Criar docker-compose

Serviços:

* app (php-fpm)
* nginx
* mysql

---

## 2.3 Validar ambiente

* Subir containers
* Testar acesso
* Validar conexão com banco

---

# 🧱 FASE 3 — Modelagem com Eloquent

## 3.1 Migration

Tabela `contacts`:

* id
* name
* email (unique)
* phone (nullable)
* notes (nullable)
* timestamps

---

## 3.2 Model

```php
class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'notes'
    ];
}
```

---

# 🧠 FASE 4 — Estrutura DDD

## Estrutura:

```
app/
 ├── Domain/
 ├── Application/
 ├── Infra/
 ├── Http/
```

---

## Regra importante

* Domain NÃO usa Eloquent
* Infra usa Eloquent

---

# 🧩 FASE 5 — Repository

## Interface

```
Domain/Repositories/ContactRepositoryInterface.php
```

Métodos:

* create
* update
* delete
* findAll
* findById
* findByEmail

---

## Implementação

```
Infra/Repositories/ContactRepository.php
```

Usando Eloquent

---

# ⚙️ FASE 6 — UseCases

Local:

```
Application/UseCases/Contact/
```

---

## Casos de uso

### CreateContactUseCase

* valida email único
* cria contato

### ListContactsUseCase

* lista contatos
* aplica busca

### UpdateContactUseCase

* atualiza dados

### DeleteContactUseCase

* remove contato

---

# 🌐 FASE 7 — HTTP Layer

## Requests

* StoreContactRequest
* UpdateContactRequest

Validações:

```
name: required
email: required|email|unique
```

---

## Controller

Responsabilidades:

* receber request
* chamar use case
* retornar response

---

## Rotas

```php
Route::apiResource('contacts', ContactController::class);
```

---

# 🔎 FASE 8 — Busca

## Implementação

Query param:

```
GET /contacts?search=joao
```

---

## Lógica

* buscar por nome
* buscar por email

---

# ⚛️ FASE 9 — Frontend

## 9.1 Criar projeto

```
frontend/
```

Sugestão:

* Vue 3 + Vite

---

## 9.2 Estrutura

```
views/
components/
services/api.js
```

---

## 9.3 Funcionalidades

* listar contatos
* criar contato
* editar contato
* excluir contato
* buscar contato

---

## 9.4 Integração API

Endpoints:

```
GET /contacts
POST /contacts
PUT /contacts/{id}
DELETE /contacts/{id}
```

---

# 🔗 FASE 10 — Integração

Testar fluxo completo:

1. Criar contato
2. Listar
3. Buscar
4. Editar
5. Deletar

---

# ✨ FASE 11 — Refinamento

## 11.1 Tratamento de erros

Formato padrão:

```json
{
  "message": "Erro"
}
```

---

## 11.2 UX

* loading
* feedback visual

---

## 11.3 Clean Code

* sem regra em controller
* nomes claros
* separação de responsabilidades

---

# 📄 FASE 12 — README

## Deve conter:

### Descrição

Sistema de gestão de contatos com Laravel + Vue

---

### Arquitetura

* DDD simplificado
* separação de camadas

---

### Como rodar

```
docker-compose up -d
```

---

### Decisões técnicas

* uso de Eloquent no Infra
* uso de UseCases
* estrutura modular

---

# 🧠 Uso com IA

Este plano pode ser usado para:

* gerar código por etapas
* guiar agentes de desenvolvimento
* manter consistência arquitetural

---

# 🚀 Resultado Esperado

* API REST organizada
* DDD aplicado corretamente
* Frontend funcional
* Docker configurado
* Código limpo e profissional

---

# 📌 Observações finais

* Evitar overengineering
* Priorizar clareza e organização
* Foco em qualidade e não quantidade
* Demonstrar domínio da stack

---
