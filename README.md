# Orly – Laravel E-Commerce Backend API

## Overview

**Orly** is a RESTful backend API built with **Laravel** for an e-commerce platform.
It provides authentication using **Laravel Passport**, product and category management, shopping cart functionality, order processing, and payment handling.

This backend is designed to be consumed by Web, Mobile, or Desktop clients.

---

## Tech Stack

* PHP 8.x
* Laravel 10+
* Laravel Passport (OAuth2)
* MySQL
* Eloquent ORM
* RESTful API architecture

---

## Features

### Authentication & Authorization

* User registration and login
* OAuth2 authentication with Laravel Passport
* Token-based access (`auth:api`)
* Role-based access control (`staff` middleware)

### Products & Categories

* Public product and category listing
* Full CRUD operations for staff users

### Cart System

* One cart per authenticated user
* Add, update, and remove cart items

### Orders & Payments

* Place orders from cart
* View order details
* Process payments per order

---

## Project Structure (Simplified)

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── ProductController.php
│   │   ├── CategoryController.php
│   │   ├── CartController.php
│   │   ├── OrderController.php
│   │   └── UserController.php
│
├── Models/
│   ├── User.php
│   ├── Product.php
│   ├── Category.php
│   ├── Cart.php
│   ├── CartItem.php
│   ├── Order.php
│   └── OrderItem.php
│
database/
├── migrations/
└── seeders/
```

---

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/your-username/orly.git
cd orly
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

Configure your database:

```env
DB_DATABASE=orly_laravel
DB_USERNAME=root
DB_PASSWORD=
```

---

## Database & Passport Setup

### 4. Run Migrations & Seeders

```bash
php artisan migrate:fresh --seed
```

### 5. Install Passport

```bash
php artisan passport:install
```

---

## API Authentication

All protected endpoints require a **Bearer Token**.

```
Authorization: Bearer {access_token}
```

---

## API Endpoints

Base URL:

```
/api
```

---

### Public Endpoints (No Authentication)

#### Authentication

```
POST   /register
POST   /login
```

#### Categories

```
GET    /categories
GET    /categories/{id}
```

#### Products

```
GET    /products
GET    /products/{id}
```

---

### Authenticated User Endpoints (`auth:api`)

#### Authentication

```
POST   /logout
GET    /me
```

#### Cart

```
GET    /cart
POST   /cart/items
PUT    /cart/items/{itemId}
DELETE /cart/items/{itemId}
```

#### Orders

```
POST   /orders
GET    /orders/{orderId}
```

#### Payments

```
POST   /orders/{orderId}/payment
```

---

### Staff-Only Endpoints (`auth:api + staff`)

#### Categories Management

```
POST   /categories
PUT    /categories/{id}
DELETE /categories/{id}
```

#### Products Management

```
POST   /products
PUT    /products/{id}
DELETE /products/{id}
```

#### Users Management

```
GET    /users
GET    /users/{id}
POST   /users
PUT    /users/{id}
DELETE /users/{id}
```

---

## Middleware

* `auth:api` → Ensures authenticated access using Passport
* `staff` → Restricts access to staff/admin users only

---

## License

This project is intended for educational and professional use.
You are free to modify and extend it as needed.

