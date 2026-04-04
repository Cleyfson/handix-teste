# Handix Contact Manager

Sistema de gestão de contatos desenvolvido como avaliação técnica Handix.

---

## Stack

| Camada | Tecnologia |
|--------|-----------|
| Backend | Laravel 11 (PHP 8.3) |
| Autenticação | JWT (`php-open-source-saver/jwt-auth`) |
| Frontend | Vue 3 + Vite + Pinia + Vue Router 4 + Tailwind CSS v4 |
| Banco | MySQL 8.0 |
| Servidor | Nginx 1.25 |
| Ambiente | Docker + Docker Compose |
| Testes | PHPUnit |

---

## Pré-requisitos

- [Docker](https://docs.docker.com/get-docker/) ≥ 24
- [Docker Compose](https://docs.docker.com/compose/) ≥ 2

Não é necessário PHP, Node ou Composer instalados na máquina.

---

## Como rodar

### 1. Clone o repositório

```bash
git clone https://github.com/Cleyfson/handix-teste.git
cd handix-teste
```

### 2. Configure as variáveis de ambiente

```bash
cp api/.env.example api/.env
cp frontend/.env.example frontend/.env
```

### 3. Suba os containers

```bash
docker compose up -d
```

### 4. Gere a APP_KEY e o segredo JWT

```bash
docker compose exec app php artisan key:generate
docker compose exec app php artisan jwt:secret
```

### 5. Execute as migrations e popule o banco

```bash
docker compose exec app php artisan migrate --seed
```

O comando já executa os seeders automaticamente, criando usuários e contatos de exemplo.

### 6. Acesse a aplicação

| Serviço | URL |
|---------|-----|
| Frontend (Vue 3) | http://localhost:5173 |
| API (Laravel) | http://localhost:8080/api |

### Usuários de teste (criados pelo seeder)

| Nome | E-mail | Senha |
|------|--------|-------|
| Admin | `admin@handix.com` | `password` |
| Demo User | `demo@handix.com` | `password` |

O seeder também insere 10 contatos de exemplo para facilitar a navegação na interface.

---

## Executar os testes

```bash
docker compose exec app php artisan test --testsuite=Unit
```

---

## Endpoints da API

### Autenticação (públicos)

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| `POST` | `/api/auth/register` | Cadastrar usuário |
| `POST` | `/api/auth/login` | Autenticar e obter token JWT |

### Autenticação (requer token)

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| `POST` | `/api/auth/logout` | Invalidar token |
| `GET` | `/api/auth/me` | Dados do usuário autenticado |

### Contatos (requer token)

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| `GET` | `/api/contacts` | Listar contatos (suporta `?search=`) |
| `POST` | `/api/contacts` | Criar contato |
| `GET` | `/api/contacts/{id}` | Buscar contato por ID |
| `PUT` | `/api/contacts/{id}` | Atualizar contato |
| `DELETE` | `/api/contacts/{id}` | Remover contato |

### Exemplos

```bash
# Registrar
curl -X POST http://localhost:8080/api/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"name":"João Silva","email":"joao@example.com","password":"password123","password_confirmation":"password123"}'

# Login
curl -X POST http://localhost:8080/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"joao@example.com","password":"password123"}'

# Criar contato (com token)
curl -X POST http://localhost:8080/api/contacts \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer <token>" \
  -d '{"name":"Maria Souza","email":"maria@example.com","phone":"11912345678"}'

# Busca
curl "http://localhost:8080/api/contacts?search=maria" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer <token>"
```

---

## Arquitetura

O backend adota DDD simplificado com camadas bem delimitadas:

```
api/app/
 ├── Domain/                  # Sem dependência de framework
 │   ├── Entities/
 │   │   ├── Contact.php
 │   │   └── User.php
 │   ├── Repositories/
 │   │   ├── ContactRepositoryInterface.php
 │   │   └── UserRepositoryInterface.php
 │   └── Exceptions/
 │       ├── DuplicateEmailException.php
 │       └── InvalidCredentialsException.php
 ├── Application/
 │   ├── Contracts/           # Interfaces para serviços de infraestrutura
 │   │   ├── HasherInterface.php
 │   │   └── JwtAuthInterface.php
 │   └── UseCases/
 │       ├── Contact/
 │       │   ├── CreateContactUseCase.php
 │       │   ├── ListContactsUseCase.php
 │       │   ├── ShowContactUseCase.php
 │       │   ├── UpdateContactUseCase.php
 │       │   └── DeleteContactUseCase.php
 │       └── Auth/
 │           ├── RegisterUserUseCase.php
 │           └── LoginUserUseCase.php
 ├── Infra/                   # Implementações concretas
 │   ├── Repositories/
 │   │   ├── EloquentContactRepository.php
 │   │   └── EloquentUserRepository.php
 │   ├── Auth/
 │   │   └── LaravelJwtAuth.php
 │   └── Hash/
 │       └── LaravelHasher.php
 └── Http/
     ├── Controllers/
     │   ├── ContactController.php
     │   └── AuthController.php
     └── Requests/
         ├── StoreContactRequest.php
         ├── UpdateContactRequest.php
         ├── RegisterRequest.php
         └── LoginRequest.php
```

### Regras da arquitetura

- **Domain** não depende do Eloquent nem do Laravel — entidades POPO com validação nos setters, interfaces PHP puras e exceções sem framework
- **Application/Contracts** define interfaces (`HasherInterface`, `JwtAuthInterface`) que isolam as use cases de qualquer implementação de infraestrutura
- **Application/UseCases** dependem exclusivamente de interfaces — nada concreto
- **Infra** é o único lugar onde Eloquent, `Hash::make()` e `auth('api')` aparecem
- **Http** delega 100% a UseCases; controllers não contêm regras de negócio
- **Todos os bindings** (`Interface → Implementação`) são feitos no `AppServiceProvider`

---

## Testes unitários

Os testes cobrem a camada Application (use cases) e a entidade de domínio. Todos usam `PHPUnit\Framework\TestCase` puro — sem container Laravel ou banco de dados.

```
tests/Unit/
 ├── Domain/
 │   └── ContactEntityTest.php
 └── Application/
     ├── Auth/
     │   ├── RegisterUserUseCaseTest.php
     │   └── LoginUserUseCaseTest.php
     └── Contact/
         ├── CreateContactUseCaseTest.php
         ├── ListContactsUseCaseTest.php
         ├── ShowContactUseCaseTest.php
         ├── UpdateContactUseCaseTest.php
         └── DeleteContactUseCaseTest.php
```

---

## Decisões técnicas

### DDD simplificado
Sem Value Objects ou Domain Events desnecessários. O foco é na separação clara de responsabilidades: Domain define contratos, Application orquestra, Infra implementa, Http entrega.

### Exceções de domínio
`DuplicateEmailException` e `InvalidCredentialsException` são exceções PHP puras (`extends RuntimeException`) — sem dependência de `ValidationException` do Laravel. O handler global em `bootstrap/app.php` as mapeia para respostas JSON 422. Isso permite testar use cases sem container.

### JWT stateless
Autenticação via `php-open-source-saver/jwt-auth`. O guard `api` usa driver JWT. Tokens são enviados como `Authorization: Bearer <token>` e invalidados via `logout`.

### Eloquent apenas na Infra
O Domain define contratos. A Infra os implementa. Trocar MySQL por outra fonte de dados não exige mudanças em Domain ou Application.

### Docker como plataforma principal
Nenhuma dependência do ambiente local. Todo o ciclo — build, scaffold, migrate, dev, testes — é executado via containers.

### Vue 3 + Composition API + Pinia + Vue Router 4
Estado global em stores Pinia (`contactStore`, `authStore`). Rotas com meta `requiresAuth` e `guest` — guard de navegação redireciona automaticamente. O `api.js` injeta `Bearer token` via interceptor de request e redireciona para `/login` no 401.

### UI com Tailwind CSS v4
Via plugin `@tailwindcss/vite` — sem `tailwind.config.js`. Inclui `ConfirmDialog` e `ToastNotification` com barra de progresso empilhável.

---

## Estrutura de diretórios

```
handix-teste/
 ├── api/              # Backend Laravel 11
 ├── frontend/         # Frontend Vue 3 + Vite
 ├── docker/
 │   ├── php/Dockerfile
 │   └── nginx/default.conf
 └── docker-compose.yml
```
