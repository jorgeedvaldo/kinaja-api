# 🍔 Kinaja API

**Food Delivery System Backend** — A RESTful API built with Laravel 9 & Sanctum for a food delivery platform (similar to Uber Eats / Tupuca).

**Backend de Sistema de Entrega de Comida** — Uma API RESTful construída com Laravel 9 & Sanctum para uma plataforma de entrega de comida (semelhante ao Uber Eats / Tupuca).

---

## 📑 Table of Contents / Índice

- [Overview / Visão Geral](#-overview--visão-geral)
- [Tech Stack / Stack Tecnológica](#-tech-stack--stack-tecnológica)
- [Installation / Instalação](#-installation--instalação)
- [Environment Variables / Variáveis de Ambiente](#-environment-variables--variáveis-de-ambiente)
- [Authentication / Autenticação](#-authentication--autenticação)
- [Database Schema / Esquema da Base de Dados](#-database-schema--esquema-da-base-de-dados)
- [Entity Relationship Diagram / Diagrama ER](#-entity-relationship-diagram--diagrama-er)
- [Models & Relationships / Modelos & Relacionamentos](#-models--relationships--modelos--relacionamentos)
- [Enums & Status Flows / Enums & Fluxos de Estado](#-enums--status-flows--enums--fluxos-de-estado)
- [API Endpoints Reference / Referência dos Endpoints](#-api-endpoints-reference--referência-dos-endpoints)
- [Request & Response Examples / Exemplos de Request & Response](#-request--response-examples--exemplos-de-request--response)
- [Error Handling / Tratamento de Erros](#-error-handling--tratamento-de-erros)
- [Role-Based Access / Acesso Baseado em Roles](#-role-based-access--acesso-baseado-em-roles)
- [File Structure / Estrutura de Ficheiros](#-file-structure--estrutura-de-ficheiros)

---

## 🌍 Overview / Visão Geral

**EN:** Kinaja API serves as the backend for a food delivery ecosystem comprising three frontend clients:

| Client | Description |
|--------|-------------|
| 📱 **Client App** | Customers browse restaurants, add products to cart, place orders, track delivery |
| 🛵 **Driver App** | Delivery drivers go online, accept orders, update delivery status in real-time |
| 🖥️ **Admin Panel** | Admins & restaurant owners manage restaurants, menus, orders, and users |

**PT:** A Kinaja API serve como backend para um ecossistema de entrega de comida composto por três frontends:

| Cliente | Descrição |
|---------|-----------|
| 📱 **App Cliente** | Clientes navegam restaurantes, adicionam produtos ao carrinho, fazem pedidos, acompanham entregas |
| 🛵 **App Entregador** | Entregadores ficam online, aceitam pedidos, atualizam estado da entrega em tempo real |
| 🖥️ **Painel Admin** | Admins e donos de restaurantes gerem restaurantes, menus, pedidos e utilizadores |

---

## 🛠 Tech Stack / Stack Tecnológica

| Technology | Version | Purpose / Finalidade |
|------------|---------|----------------------|
| **PHP** | ^8.0.2 | Runtime language / Linguagem |
| **Laravel** | ^9.19 | Backend framework |
| **Laravel Sanctum** | ^2.14.1 | Token-based API authentication / Autenticação por tokens |
| **MySQL** | 5.7+ / 8.0 | Database / Base de dados |
| **Eloquent ORM** | — | Database abstraction / Abstração da base de dados |

---

## 🚀 Installation / Instalação

```bash
# 1. Clone the repository / Clonar o repositório
git clone <repository-url> kinaja-api
cd kinaja-api

# 2. Install dependencies / Instalar dependências
composer install

# 3. Configure environment / Configurar ambiente
cp .env.example .env
php artisan key:generate

# 4. Configure your database in .env (see section below)
# Configurar a base de dados no .env (ver secção abaixo)

# 5. Run migrations / Executar migrations
php artisan migrate

# 6. (Optional) Seed the database / (Opcional) Popular a base de dados
php artisan db:seed

# 7. Start the server / Iniciar o servidor
php artisan serve
# API available at / API disponível em: http://localhost:8000/api
```

---

## 🔐 Environment Variables / Variáveis de Ambiente

Create a `.env` file from `.env.example` and configure:

Criar ficheiro `.env` a partir do `.env.example` e configurar:

```env
APP_NAME=Kinaja
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kinaja_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

---

## 🔑 Authentication / Autenticação

**EN:** The API uses **Laravel Sanctum** for token-based authentication. All protected endpoints require a Bearer token in the `Authorization` header.

**PT:** A API usa **Laravel Sanctum** para autenticação baseada em tokens. Todos os endpoints protegidos requerem um token Bearer no header `Authorization`.

### Authentication Flow / Fluxo de Autenticação

```
┌──────────┐     POST /api/register      ┌──────────┐
│  Client  │ ───────────────────────────► │  Server  │
│  (App)   │ ◄─────────────────────────── │  (API)   │
│          │     { user, token }          │          │
│          │                              │          │
│          │     POST /api/login          │          │
│          │ ───────────────────────────► │          │
│          │ ◄─────────────────────────── │          │
│          │     { user, token }          │          │
│          │                              │          │
│          │  GET /api/resource           │          │
│          │  Authorization: Bearer <tk>  │          │
│          │ ───────────────────────────► │          │
│          │ ◄─────────────────────────── │          │
│          │     { data }                 │          │
└──────────┘                              └──────────┘
```

### Headers Required / Headers Necessários

```http
Authorization: Bearer {your-sanctum-token}
Accept: application/json
Content-Type: application/json
```

### Register / Registo

```http
POST /api/register
Content-Type: application/json

{
    "name": "João Silva",
    "phone": "+244923456789",
    "email": "joao@email.com",       // optional / opcional
    "password": "password123",
    "password_confirmation": "password123",
    "role": "client"                  // admin | client | driver | restaurant_owner
}
```

**Response:**
```json
{
    "user": {
        "id": 1,
        "name": "João Silva",
        "phone": "+244923456789",
        "email": "joao@email.com",
        "role": "client",
        "created_at": "2026-04-08T19:00:00.000000Z",
        "updated_at": "2026-04-08T19:00:00.000000Z"
    },
    "token": "1|abc123def456ghi789..."
}
```

### Login

```http
POST /api/login
Content-Type: application/json

{
    "phone": "+244923456789",
    "password": "password123"
}
```

**Response:**
```json
{
    "user": {
        "id": 1,
        "name": "João Silva",
        "phone": "+244923456789",
        "email": "joao@email.com",
        "role": "client"
    },
    "token": "2|xyz789abc123def456..."
}
```

### Logout

```http
POST /api/logout
Authorization: Bearer {token}
```

**Response:**
```json
{
    "message": "Logged out successfully"
}
```

---

## 🗄 Database Schema / Esquema da Base de Dados

### Migration Execution Order / Ordem de Execução das Migrations

Migrations must run in this exact dependency order:

As migrations devem correr nesta ordem exata de dependência:

| Order | Migration File | Table | Dependencies |
|-------|---------------|-------|--------------|
| 1 | `2014_10_12_000000_create_users_table.php` | `users` | — |
| 2 | `2014_10_12_100000_create_password_resets_table.php` | `password_resets` | — |
| 3 | `2019_08_19_000000_create_failed_jobs_table.php` | `failed_jobs` | — |
| 4 | `2019_12_14_000001_create_personal_access_tokens_table.php` | `personal_access_tokens` | — |
| 5 | `2026_04_08_000001_create_drivers_table.php` | `drivers` | `users` |
| 6 | `2026_04_08_000002_create_restaurants_table.php` | `restaurants` | `users` |
| 7 | `2026_04_08_000003_create_categories_table.php` | `categories` | — |
| 8 | `2026_04_08_000004_create_products_table.php` | `products` | `restaurants`, `categories` |
| 9 | `2026_04_08_000005_create_orders_table.php` | `orders` | `users`, `restaurants`, `drivers` |
| 10 | `2026_04_08_000006_create_order_items_table.php` | `order_items` | `orders`, `products` |

---

### Table: `users`

Central authentication table for all user roles.

Tabela central de autenticação para todos os tipos de utilizador.

| Column | Type | Constraints | Description (EN) | Descrição (PT) |
|--------|------|-------------|------------------|----------------|
| `id` | `bigint unsigned` | PK, auto-increment | Unique identifier | Identificador único |
| `name` | `varchar(255)` | required | Full name | Nome completo |
| `phone` | `varchar(255)` | required, **unique** | Phone number (primary login) | Telefone (login principal) |
| `email` | `varchar(255)` | nullable, unique | Email address | Endereço de email |
| `email_verified_at` | `timestamp` | nullable | Email verification timestamp | Data de verificação do email |
| `password` | `varchar(255)` | required | Hashed password (bcrypt) | Password encriptada (bcrypt) |
| `role` | `enum` | required, default: `'client'` | User role type | Tipo de papel do utilizador |
| `remember_token` | `varchar(100)` | nullable | Session remember token | Token de sessão |
| `created_at` | `timestamp` | auto | Record creation time | Data de criação |
| `updated_at` | `timestamp` | auto | Record update time | Data de atualização |

**Role enum values:** `'admin'`, `'client'`, `'driver'`, `'restaurant_owner'`

---

### Table: `drivers`

Extended profile for users with role `driver`.

Perfil estendido para utilizadores com role `driver`.

| Column | Type | Constraints | Description (EN) | Descrição (PT) |
|--------|------|-------------|------------------|----------------|
| `id` | `bigint unsigned` | PK, auto-increment | Unique identifier | Identificador único |
| `user_id` | `bigint unsigned` | FK → `users.id`, **cascade delete** | Associated user account | Conta de utilizador associada |
| `vehicle_type` | `enum` | required | Type of delivery vehicle | Tipo de veículo de entrega |
| `license_plate` | `varchar(255)` | required | Vehicle license plate number | Número da matrícula do veículo |
| `current_lat` | `decimal(10,7)` | nullable | Current GPS latitude | Latitude GPS atual |
| `current_lng` | `decimal(10,7)` | nullable | Current GPS longitude | Longitude GPS atual |
| `is_online` | `boolean` | default: `false` | Driver availability status | Estado de disponibilidade |
| `created_at` | `timestamp` | auto | Record creation time | Data de criação |
| `updated_at` | `timestamp` | auto | Record update time | Data de atualização |

**Vehicle type enum values:** `'mota'` (motorcycle), `'carro'` (car)

---

### Table: `restaurants`

Restaurants managed by users with role `restaurant_owner`.

Restaurantes geridos por utilizadores com role `restaurant_owner`.

| Column | Type | Constraints | Description (EN) | Descrição (PT) |
|--------|------|-------------|------------------|----------------|
| `id` | `bigint unsigned` | PK, auto-increment | Unique identifier | Identificador único |
| `user_id` | `bigint unsigned` | FK → `users.id`, **cascade delete** | Owner/manager user | Utilizador dono/gestor |
| `name` | `varchar(255)` | required | Restaurant name | Nome do restaurante |
| `cuisine_type` | `varchar(255)` | nullable | Type of cuisine (e.g. "Italiana", "Angolana") | Tipo de cozinha |
| `cover_image` | `varchar(255)` | nullable | Path/URL to cover image | Caminho/URL da imagem de capa |
| `rating` | `decimal(3,1)` | default: `0` | Average customer rating (0.0–5.0) | Classificação média (0.0–5.0) |
| `prep_time_mins` | `integer` | default: `30` | Average preparation time (minutes) | Tempo médio de preparação (minutos) |
| `is_open` | `boolean` | default: `false` | Whether restaurant is currently open | Se o restaurante está aberto |
| `created_at` | `timestamp` | auto | Record creation time | Data de criação |
| `updated_at` | `timestamp` | auto | Record update time | Data de atualização |

---

### Table: `categories`

Menu categories used to organize products.

Categorias de menu usadas para organizar produtos.

| Column | Type | Constraints | Description (EN) | Descrição (PT) |
|--------|------|-------------|------------------|----------------|
| `id` | `bigint unsigned` | PK, auto-increment | Unique identifier | Identificador único |
| `name` | `varchar(255)` | required | Category name (e.g. "Pizzas", "Bebidas") | Nome da categoria |
| `created_at` | `timestamp` | auto | Record creation time | Data de criação |
| `updated_at` | `timestamp` | auto | Record update time | Data de atualização |

---

### Table: `products`

Menu items belonging to a restaurant and category.

Itens do menu pertencentes a um restaurante e categoria.

| Column | Type | Constraints | Description (EN) | Descrição (PT) |
|--------|------|-------------|------------------|----------------|
| `id` | `bigint unsigned` | PK, auto-increment | Unique identifier | Identificador único |
| `restaurant_id` | `bigint unsigned` | FK → `restaurants.id`, **cascade delete** | Restaurant that sells this product | Restaurante que vende este produto |
| `category_id` | `bigint unsigned` | FK → `categories.id`, **cascade delete** | Menu category | Categoria do menu |
| `name` | `varchar(255)` | required | Product name | Nome do produto |
| `description` | `text` | nullable | Product description | Descrição do produto |
| `price` | `decimal(10,2)` | required | Unit price in local currency | Preço unitário em moeda local |
| `image` | `varchar(255)` | nullable | Path/URL to product image | Caminho/URL da imagem do produto |
| `is_available` | `boolean` | default: `true` | Whether product is currently available | Se o produto está disponível |
| `created_at` | `timestamp` | auto | Record creation time | Data de criação |
| `updated_at` | `timestamp` | auto | Record update time | Data de atualização |

---

### Table: `orders`

Customer orders linking client, restaurant, and driver.

Pedidos de clientes ligando cliente, restaurante e entregador.

| Column | Type | Constraints | Description (EN) | Descrição (PT) |
|--------|------|-------------|------------------|----------------|
| `id` | `bigint unsigned` | PK, auto-increment | Unique identifier | Identificador único |
| `client_id` | `bigint unsigned` | FK → `users.id`, **cascade delete** | Customer who placed the order | Cliente que fez o pedido |
| `restaurant_id` | `bigint unsigned` | FK → `restaurants.id`, **cascade delete** | Restaurant the order was placed at | Restaurante onde o pedido foi feito |
| `driver_id` | `bigint unsigned` | FK → `drivers.id`, **nullable**, **set null on delete** | Assigned delivery driver | Entregador atribuído |
| `total_amount` | `decimal(10,2)` | required | Total order amount (products) | Valor total do pedido (produtos) |
| `delivery_fee` | `decimal(10,2)` | default: `0` | Delivery fee charged | Taxa de entrega cobrada |
| `status` | `enum` | default: `'pending'` | Current order status | Estado atual do pedido |
| `created_at` | `timestamp` | auto | Record creation time | Data de criação |
| `updated_at` | `timestamp` | auto | Record update time | Data de atualização |

**Status enum values:** `'pending'`, `'accepted'`, `'preparing'`, `'ready'`, `'in_transit'`, `'delivered'`, `'cancelled'`

---

### Table: `order_items`

Individual line items within an order.

Itens individuais dentro de um pedido.

| Column | Type | Constraints | Description (EN) | Descrição (PT) |
|--------|------|-------------|------------------|----------------|
| `id` | `bigint unsigned` | PK, auto-increment | Unique identifier | Identificador único |
| `order_id` | `bigint unsigned` | FK → `orders.id`, **cascade delete** | Parent order | Pedido pai |
| `product_id` | `bigint unsigned` | FK → `products.id`, **cascade delete** | Product ordered | Produto pedido |
| `quantity` | `integer` | required | Number of units | Número de unidades |
| `unit_price` | `decimal(10,2)` | required | Price per unit at time of order | Preço unitário no momento do pedido |
| `notes` | `varchar(255)` | nullable | Special instructions (e.g. "sem cebola") | Instruções especiais |
| `created_at` | `timestamp` | auto | Record creation time | Data de criação |
| `updated_at` | `timestamp` | auto | Record update time | Data de atualização |

---

## 📊 Entity Relationship Diagram / Diagrama ER

```
┌──────────────────────┐
│        USERS         │
│──────────────────────│
│ id (PK)              │
│ name                 │
│ phone (unique)       │
│ email (nullable)     │
│ password             │
│ role (enum)          │
└───┬──────────┬───────┘
    │          │
    │ 1:1      │ 1:N
    ▼          ▼
┌──────────┐  ┌───────────────┐
│ DRIVERS  │  │  RESTAURANTS  │
│──────────│  │───────────────│
│ id (PK)  │  │ id (PK)       │
│ user_id  │◄─│ user_id (FK)  │
│ vehicle  │  │ name          │
│ lat/lng  │  │ cuisine_type  │
│ is_online│  │ rating        │
└────┬─────┘  │ is_open       │
     │        └───┬───────────┘
     │            │
     │            │ 1:N
     │            ▼
     │      ┌──────────────┐     ┌────────────┐
     │      │   PRODUCTS   │     │ CATEGORIES │
     │      │──────────────│     │────────────│
     │      │ id (PK)      │     │ id (PK)    │
     │      │ restaurant_id│◄────│ name       │
     │      │ category_id  │────►│            │
     │      │ name         │     └────────────┘
     │      │ price        │
     │      │ is_available │
     │      └──────┬───────┘
     │             │
     │    N:1      │ 1:N
     ▼             ▼
┌──────────────────────────┐
│         ORDERS           │
│──────────────────────────│
│ id (PK)                  │
│ client_id (FK → users)   │
│ restaurant_id (FK)       │
│ driver_id (FK, nullable) │
│ total_amount             │
│ delivery_fee             │
│ status (enum)            │
└───────────┬──────────────┘
            │
            │ 1:N
            ▼
┌──────────────────────────┐
│       ORDER_ITEMS        │
│──────────────────────────│
│ id (PK)                  │
│ order_id (FK, cascade)   │
│ product_id (FK)          │
│ quantity                 │
│ unit_price               │
│ notes (nullable)         │
└──────────────────────────┘
```

---

## 🔗 Models & Relationships / Modelos & Relacionamentos

### User (`App\Models\User`)

**File / Ficheiro:** `app/Models/User.php`

**Fillable:** `name`, `phone`, `email`, `password`, `role`

**Hidden:** `password`, `remember_token`

**Casts:** `email_verified_at` → `datetime`

**Traits:** `HasApiTokens`, `HasFactory`, `Notifiable`

| Relationship | Method | Type | Related Model | Foreign Key |
|-------------|--------|------|---------------|-------------|
| Driver Profile | `driver()` | `HasOne` | `Driver` | `drivers.user_id` |
| Restaurants | `restaurants()` | `HasMany` | `Restaurant` | `restaurants.user_id` |
| Orders (as client) | `orders()` | `HasMany` | `Order` | `orders.client_id` |

**Helper Methods:**
```php
$user->isAdmin();            // Returns true if role === 'admin'
$user->isClient();           // Returns true if role === 'client'
$user->isDriver();           // Returns true if role === 'driver'
$user->isRestaurantOwner();  // Returns true if role === 'restaurant_owner'
```

---

### Driver (`App\Models\Driver`)

**File / Ficheiro:** `app/Models/Driver.php`

**Fillable:** `user_id`, `vehicle_type`, `license_plate`, `current_lat`, `current_lng`, `is_online`

**Casts:** `current_lat` → `decimal:7`, `current_lng` → `decimal:7`, `is_online` → `boolean`

| Relationship | Method | Type | Related Model | Foreign Key |
|-------------|--------|------|---------------|-------------|
| User Account | `user()` | `BelongsTo` | `User` | `drivers.user_id` |
| Assigned Orders | `orders()` | `HasMany` | `Order` | `orders.driver_id` |

---

### Restaurant (`App\Models\Restaurant`)

**File / Ficheiro:** `app/Models/Restaurant.php`

**Fillable:** `user_id`, `name`, `cuisine_type`, `cover_image`, `rating`, `prep_time_mins`, `is_open`

**Casts:** `rating` → `decimal:1`, `prep_time_mins` → `integer`, `is_open` → `boolean`

| Relationship | Method | Type | Related Model | Foreign Key |
|-------------|--------|------|---------------|-------------|
| Owner/Manager | `user()` | `BelongsTo` | `User` | `restaurants.user_id` |
| Owner (alias) | `owner()` | `BelongsTo` | `User` | `restaurants.user_id` |
| Menu Products | `products()` | `HasMany` | `Product` | `products.restaurant_id` |
| Received Orders | `orders()` | `HasMany` | `Order` | `orders.restaurant_id` |

---

### Category (`App\Models\Category`)

**File / Ficheiro:** `app/Models/Category.php`

**Fillable:** `name`

| Relationship | Method | Type | Related Model | Foreign Key |
|-------------|--------|------|---------------|-------------|
| Products | `products()` | `HasMany` | `Product` | `products.category_id` |

---

### Product (`App\Models\Product`)

**File / Ficheiro:** `app/Models/Product.php`

**Fillable:** `restaurant_id`, `category_id`, `name`, `description`, `price`, `image`, `is_available`

**Casts:** `price` → `decimal:2`, `is_available` → `boolean`

| Relationship | Method | Type | Related Model | Foreign Key |
|-------------|--------|------|---------------|-------------|
| Restaurant | `restaurant()` | `BelongsTo` | `Restaurant` | `products.restaurant_id` |
| Category | `category()` | `BelongsTo` | `Category` | `products.category_id` |
| Order Items | `orderItems()` | `HasMany` | `OrderItem` | `order_items.product_id` |

---

### Order (`App\Models\Order`)

**File / Ficheiro:** `app/Models/Order.php`

**Fillable:** `client_id`, `restaurant_id`, `driver_id`, `total_amount`, `delivery_fee`, `status`

**Casts:** `total_amount` → `decimal:2`, `delivery_fee` → `decimal:2`

| Relationship | Method | Type | Related Model | Foreign Key |
|-------------|--------|------|---------------|-------------|
| Client (Customer) | `client()` | `BelongsTo` | `User` | `orders.client_id` |
| Restaurant | `restaurant()` | `BelongsTo` | `Restaurant` | `orders.restaurant_id` |
| Delivery Driver | `driver()` | `BelongsTo` | `Driver` | `orders.driver_id` |
| Line Items | `items()` | `HasMany` | `OrderItem` | `order_items.order_id` |

---

### OrderItem (`App\Models\OrderItem`)

**File / Ficheiro:** `app/Models/OrderItem.php`

**Fillable:** `order_id`, `product_id`, `quantity`, `unit_price`, `notes`

**Casts:** `quantity` → `integer`, `unit_price` → `decimal:2`

| Relationship | Method | Type | Related Model | Foreign Key |
|-------------|--------|------|---------------|-------------|
| Parent Order | `order()` | `BelongsTo` | `Order` | `order_items.order_id` |
| Product | `product()` | `BelongsTo` | `Product` | `order_items.product_id` |

---

## 🔄 Enums & Status Flows / Enums & Fluxos de Estado

### User Roles

| Value | Description (EN) | Descrição (PT) | Permissions |
|-------|------------------|----------------|-------------|
| `admin` | System administrator | Administrador do sistema | Full access to all resources |
| `client` | Customer / end user | Cliente / utilizador final | Browse restaurants, place orders, track deliveries |
| `driver` | Delivery driver | Entregador / estafeta | Go online, accept/deliver orders, update location |
| `restaurant_owner` | Restaurant manager | Dono de restaurante | Manage restaurant, menu, accept/prepare orders |

### Vehicle Types (Driver)

| Value | Description (EN) | Descrição (PT) |
|-------|------------------|----------------|
| `mota` | Motorcycle | Motocicleta |
| `carro` | Car | Carro |

### Order Status Flow / Fluxo de Estado do Pedido

```
  ┌─────────┐
  │ pending  │ ──── Client places order / Cliente faz pedido
  └────┬─────┘
       │
       ▼
  ┌──────────┐
  │ accepted │ ──── Restaurant accepts / Restaurante aceita
  └────┬─────┘
       │
       ▼
  ┌───────────┐
  │ preparing │ ──── Kitchen starts cooking / Cozinha começa a preparar
  └────┬──────┘
       │
       ▼
  ┌─────────┐
  │  ready   │ ──── Food is ready for pickup / Comida pronta para recolha
  └────┬─────┘
       │
       ▼
  ┌────────────┐
  │ in_transit │ ──── Driver picked up & delivering / Entregador recolheu e a entregar
  └────┬───────┘
       │
       ▼
  ┌───────────┐
  │ delivered │ ──── Order complete / Pedido completo ✅
  └───────────┘

  Any state ──► ┌───────────┐
                │ cancelled │ ──── Order cancelled / Pedido cancelado ❌
                └───────────┘
```

| Status | Triggered By (EN) | Acionado Por (PT) | Next Valid States |
|--------|-------------------|-------------------|-------------------|
| `pending` | Client places order | Cliente faz pedido | `accepted`, `cancelled` |
| `accepted` | Restaurant accepts | Restaurante aceita | `preparing`, `cancelled` |
| `preparing` | Restaurant starts cooking | Restaurante começa a preparar | `ready`, `cancelled` |
| `ready` | Restaurant finishes | Restaurante termina | `in_transit`, `cancelled` |
| `in_transit` | Driver picks up order | Entregador recolhe o pedido | `delivered`, `cancelled` |
| `delivered` | Driver confirms delivery | Entregador confirma entrega | — (final) |
| `cancelled` | Any party cancels | Qualquer parte cancela | — (final) |

---

## 📡 API Endpoints Reference / Referência dos Endpoints

> **Base URL:** `http://localhost:8000/api`
>
> **Note / Nota:** The endpoints below represent the planned API structure. Routes are being implemented progressively.
>
> Os endpoints abaixo representam a estrutura planeada da API. As rotas estão sendo implementadas progressivamente.

### 🔓 Public Endpoints (No Auth) / Endpoints Públicos (Sem Auth)

| Method | Endpoint | Description (EN) | Descrição (PT) |
|--------|----------|------------------|----------------|
| `POST` | `/register` | Register a new user | Registar novo utilizador |
| `POST` | `/login` | Login & get token | Login & obter token |

### 🔒 Protected Endpoints (Auth Required) / Endpoints Protegidos (Auth Necessária)

#### User / Utilizador

| Method | Endpoint | Description (EN) | Descrição (PT) | Role |
|--------|----------|------------------|----------------|------|
| `GET` | `/user` | Get authenticated user profile | Obter perfil do utilizador autenticado | Any |
| `POST` | `/logout` | Revoke current token | Revogar token atual | Any |

#### Restaurants / Restaurantes

| Method | Endpoint | Description (EN) | Descrição (PT) | Role |
|--------|----------|------------------|----------------|------|
| `GET` | `/restaurants` | List all open restaurants | Listar restaurantes abertos | `client` |
| `GET` | `/restaurants/{id}` | Get restaurant details + menu | Detalhes do restaurante + menu | `client` |
| `POST` | `/restaurants` | Create a new restaurant | Criar novo restaurante | `restaurant_owner` |
| `PUT` | `/restaurants/{id}` | Update restaurant details | Atualizar detalhes do restaurante | `restaurant_owner` |
| `DELETE` | `/restaurants/{id}` | Delete a restaurant | Apagar restaurante | `restaurant_owner`, `admin` |

#### Products / Produtos

| Method | Endpoint | Description (EN) | Descrição (PT) | Role |
|--------|----------|------------------|----------------|------|
| `GET` | `/restaurants/{id}/products` | List products of a restaurant | Listar produtos do restaurante | `client` |
| `POST` | `/restaurants/{id}/products` | Add product to restaurant | Adicionar produto ao restaurante | `restaurant_owner` |
| `PUT` | `/products/{id}` | Update product | Atualizar produto | `restaurant_owner` |
| `DELETE` | `/products/{id}` | Delete product | Apagar produto | `restaurant_owner` |

#### Categories / Categorias

| Method | Endpoint | Description (EN) | Descrição (PT) | Role |
|--------|----------|------------------|----------------|------|
| `GET` | `/categories` | List all categories | Listar todas as categorias | Any |
| `POST` | `/categories` | Create category | Criar categoria | `admin` |
| `PUT` | `/categories/{id}` | Update category | Atualizar categoria | `admin` |
| `DELETE` | `/categories/{id}` | Delete category | Apagar categoria | `admin` |

#### Orders / Pedidos

| Method | Endpoint | Description (EN) | Descrição (PT) | Role |
|--------|----------|------------------|----------------|------|
| `POST` | `/orders` | Place a new order | Fazer novo pedido | `client` |
| `GET` | `/orders` | List user's orders | Listar pedidos do utilizador | `client`, `driver`, `restaurant_owner` |
| `GET` | `/orders/{id}` | Get order details | Detalhes do pedido | Any (owner) |
| `PATCH` | `/orders/{id}/status` | Update order status | Atualizar estado do pedido | `restaurant_owner`, `driver` |
| `PATCH` | `/orders/{id}/cancel` | Cancel an order | Cancelar pedido | `client`, `restaurant_owner`, `admin` |

#### Driver / Entregador

| Method | Endpoint | Description (EN) | Descrição (PT) | Role |
|--------|----------|------------------|----------------|------|
| `POST` | `/driver/profile` | Create driver profile | Criar perfil de entregador | `driver` |
| `PUT` | `/driver/profile` | Update driver profile | Atualizar perfil de entregador | `driver` |
| `PATCH` | `/driver/toggle-online` | Toggle online/offline status | Alternar estado online/offline | `driver` |
| `PATCH` | `/driver/location` | Update GPS location | Atualizar localização GPS | `driver` |
| `GET` | `/driver/available-orders` | List available orders for pickup | Listar pedidos disponíveis | `driver` |
| `PATCH` | `/driver/orders/{id}/accept` | Accept an order | Aceitar pedido | `driver` |

#### Admin

| Method | Endpoint | Description (EN) | Descrição (PT) | Role |
|--------|----------|------------------|----------------|------|
| `GET` | `/admin/users` | List all users | Listar todos os utilizadores | `admin` |
| `GET` | `/admin/orders` | List all orders | Listar todos os pedidos | `admin` |
| `GET` | `/admin/dashboard` | Dashboard statistics | Estatísticas do dashboard | `admin` |

---

## 📋 Request & Response Examples / Exemplos de Request & Response

### Create Order / Criar Pedido

**Request:**
```http
POST /api/orders
Authorization: Bearer {token}
Content-Type: application/json

{
    "restaurant_id": 1,
    "delivery_fee": 500.00,
    "items": [
        {
            "product_id": 3,
            "quantity": 2,
            "notes": "Sem cebola"
        },
        {
            "product_id": 7,
            "quantity": 1,
            "notes": null
        }
    ]
}
```

**Response (201 Created):**
```json
{
    "data": {
        "id": 42,
        "client_id": 1,
        "restaurant_id": 1,
        "driver_id": null,
        "total_amount": "3500.00",
        "delivery_fee": "500.00",
        "status": "pending",
        "created_at": "2026-04-08T19:30:00.000000Z",
        "updated_at": "2026-04-08T19:30:00.000000Z",
        "items": [
            {
                "id": 101,
                "order_id": 42,
                "product_id": 3,
                "quantity": 2,
                "unit_price": "1500.00",
                "notes": "Sem cebola"
            },
            {
                "id": 102,
                "order_id": 42,
                "product_id": 7,
                "quantity": 1,
                "unit_price": "500.00",
                "notes": null
            }
        ],
        "restaurant": {
            "id": 1,
            "name": "Restaurante Luanda Grill"
        },
        "client": {
            "id": 1,
            "name": "João Silva"
        }
    }
}
```

### Update Order Status / Atualizar Estado do Pedido

**Request:**
```http
PATCH /api/orders/42/status
Authorization: Bearer {token}
Content-Type: application/json

{
    "status": "accepted"
}
```

**Response (200 OK):**
```json
{
    "data": {
        "id": 42,
        "status": "accepted",
        "updated_at": "2026-04-08T19:31:00.000000Z"
    },
    "message": "Order status updated successfully"
}
```

### Update Driver Location / Atualizar Localização do Entregador

**Request:**
```http
PATCH /api/driver/location
Authorization: Bearer {token}
Content-Type: application/json

{
    "current_lat": -8.8383333,
    "current_lng": 13.2344444
}
```

**Response (200 OK):**
```json
{
    "data": {
        "id": 5,
        "current_lat": "-8.8383333",
        "current_lng": "13.2344444",
        "is_online": true
    },
    "message": "Location updated successfully"
}
```

### List Restaurant Products / Listar Produtos do Restaurante

**Response (200 OK):**
```json
{
    "data": [
        {
            "id": 1,
            "restaurant_id": 1,
            "category_id": 2,
            "name": "Pizza Margherita",
            "description": "Tomate, mozzarella e manjericão",
            "price": "2500.00",
            "image": "/storage/products/pizza-margherita.jpg",
            "is_available": true,
            "category": {
                "id": 2,
                "name": "Pizzas"
            }
        },
        {
            "id": 2,
            "restaurant_id": 1,
            "category_id": 3,
            "name": "Coca-Cola 330ml",
            "description": null,
            "price": "300.00",
            "image": null,
            "is_available": true,
            "category": {
                "id": 3,
                "name": "Bebidas"
            }
        }
    ]
}
```

---

## ❌ Error Handling / Tratamento de Erros

All errors return a consistent JSON structure:

Todos os erros retornam uma estrutura JSON consistente:

```json
{
    "message": "Human-readable error message",
    "errors": {
        "field_name": [
            "Specific validation error for this field"
        ]
    }
}
```

### HTTP Status Codes

| Code | Meaning (EN) | Significado (PT) | When Used |
|------|-------------|-------------------|-----------|
| `200` | OK | Sucesso | Successful GET, PUT, PATCH requests |
| `201` | Created | Criado | Successful POST (resource created) |
| `204` | No Content | Sem Conteúdo | Successful DELETE |
| `400` | Bad Request | Pedido Inválido | Malformed request body |
| `401` | Unauthorized | Não Autorizado | Missing or invalid token |
| `403` | Forbidden | Proibido | Valid token but insufficient role/permissions |
| `404` | Not Found | Não Encontrado | Resource does not exist |
| `422` | Unprocessable Entity | Entidade Inprocessável | Validation errors |
| `500` | Internal Server Error | Erro Interno | Unexpected server error |

### Validation Error Example / Exemplo de Erro de Validação

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "phone": [
            "The phone field is required.",
            "The phone has already been taken."
        ],
        "password": [
            "The password must be at least 8 characters."
        ]
    }
}
```

### Authentication Error / Erro de Autenticação

```json
{
    "message": "Unauthenticated."
}
```

---

## 🛡 Role-Based Access / Acesso Baseado em Roles

The following matrix shows which roles can access which resources:

A seguinte matriz mostra quais roles podem aceder a quais recursos:

| Resource / Recurso | `admin` | `client` | `driver` | `restaurant_owner` |
|---------------------|---------|----------|----------|---------------------|
| View restaurants | ✅ | ✅ | ❌ | ✅ (own) |
| Manage restaurants | ✅ | ❌ | ❌ | ✅ (own) |
| Browse menu / products | ✅ | ✅ | ❌ | ✅ (own) |
| Manage products | ✅ | ❌ | ❌ | ✅ (own) |
| Place orders | ❌ | ✅ | ❌ | ❌ |
| View own orders | ✅ | ✅ | ✅ | ✅ |
| Accept orders (restaurant) | ❌ | ❌ | ❌ | ✅ |
| Accept orders (delivery) | ❌ | ❌ | ✅ | ❌ |
| Update delivery status | ❌ | ❌ | ✅ | ❌ |
| Update preparation status | ❌ | ❌ | ❌ | ✅ |
| Cancel orders | ✅ | ✅ (own) | ❌ | ✅ (own) |
| Manage categories | ✅ | ❌ | ❌ | ❌ |
| Manage all users | ✅ | ❌ | ❌ | ❌ |
| Toggle online status | ❌ | ❌ | ✅ | ❌ |
| Update GPS location | ❌ | ❌ | ✅ | ❌ |

---

## 📂 File Structure / Estrutura de Ficheiros

```
kinaja-api/
├── app/
│   ├── Models/
│   │   ├── User.php              # User model (auth, roles, relationships)
│   │   ├── Driver.php            # Driver profile model (GPS, vehicle, status)
│   │   ├── Restaurant.php        # Restaurant model (menu, rating, status)
│   │   ├── Category.php          # Menu category model
│   │   ├── Product.php           # Product/dish model (price, availability)
│   │   ├── Order.php             # Order model (client, restaurant, driver, status)
│   │   └── OrderItem.php         # Order line item model (product, qty, price)
│   ├── Http/
│   │   ├── Controllers/          # API controllers (to be implemented)
│   │   └── Middleware/           # Auth & role middleware
│   └── Providers/
├── database/
│   ├── migrations/
│   │   ├── 2014_10_12_000000_create_users_table.php
│   │   ├── 2014_10_12_100000_create_password_resets_table.php
│   │   ├── 2019_08_19_000000_create_failed_jobs_table.php
│   │   ├── 2019_12_14_000001_create_personal_access_tokens_table.php
│   │   ├── 2026_04_08_000001_create_drivers_table.php
│   │   ├── 2026_04_08_000002_create_restaurants_table.php
│   │   ├── 2026_04_08_000003_create_categories_table.php
│   │   ├── 2026_04_08_000004_create_products_table.php
│   │   ├── 2026_04_08_000005_create_orders_table.php
│   │   └── 2026_04_08_000006_create_order_items_table.php
│   ├── factories/                # Model factories for testing
│   └── seeders/                  # Database seeders
├── routes/
│   ├── api.php                   # API route definitions
│   └── web.php                   # Web routes (unused for API)
├── config/
│   ├── sanctum.php               # Sanctum auth configuration
│   └── ...
├── .env.example                  # Environment template
├── composer.json                 # PHP dependencies
└── README.md                     # This file / Este ficheiro
```

---

## 🔧 Useful Artisan Commands / Comandos Artisan Úteis

```bash
# Run all migrations / Executar todas as migrations
php artisan migrate

# Reset & re-run all migrations / Resetar e re-executar todas as migrations
php artisan migrate:fresh

# Seed the database / Popular a base de dados
php artisan db:seed

# List all registered routes / Listar todas as rotas registadas
php artisan route:list

# Start development server / Iniciar servidor de desenvolvimento
php artisan serve

# Create a new controller / Criar novo controller
php artisan make:controller Api/RestaurantController --api

# Create a new form request / Criar novo form request
php artisan make:request StoreOrderRequest

# Clear all caches / Limpar todas as caches
php artisan optimize:clear
```

---

## 📌 Important Notes for AI Agents / Notas Importantes para Agentes de IA

1. **Authentication is token-based** — Use `Authorization: Bearer {token}` header for all protected endpoints.
2. **Phone is the primary login field**, not email. Email is optional/nullable.
3. **The `role` field on `users`** determines access control throughout the system.
4. **Order status transitions** follow a strict flow: `pending → accepted → preparing → ready → in_transit → delivered`. Any state can transition to `cancelled`.
5. **`driver_id` on orders is nullable** — it's set when a driver accepts the delivery.
6. **`order_items` cascade delete** — deleting an order automatically removes its items.
7. **`unit_price` on `order_items`** captures the price at time of order, not the current product price. This is critical for order history accuracy.
8. **GPS coordinates** use `decimal(10,7)` precision — sufficient for ~1cm accuracy.
9. **All monetary values** use `decimal(10,2)` — suitable for currency amounts up to 99,999,999.99.
10. **The `cover_image` and `image` fields** store file paths/URLs, not binary data.

---

**Built with ❤️ using Laravel 9 & Sanctum**

**Construído com ❤️ usando Laravel 9 & Sanctum**
