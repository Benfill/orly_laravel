# Orly – Laravel E-Commerce Backend API

## Overview

**Orly** is a RESTful backend API built with **Laravel** for an e-commerce platform.
It provides authentication using **Laravel Passport**, product and category management, shopping cart functionality, order processing, and payment tracking.

This backend is designed to be consumed by a frontend application (Web, Mobile, or Desktop).

---

## Tech Stack

* **PHP** 8.x
* **Laravel** 10+
* **Laravel Passport** (OAuth2 authentication)
* **MySQL**
* **Eloquent ORM**
* **REST API architecture**

---

## Features

### Authentication & Authorization

* User registration and login
* OAuth2 authentication with Laravel Passport
* Access tokens & refresh tokens
* Automatic `Customer` creation on user registration

### Products & Categories

* CRUD operations for products
* Product categorization
* Stock & pricing management

### Cart System

* User cart creation
* Add / update / remove cart items
* One active cart per customer

### Orders

* Convert cart to order
* Order items persistence
* Order status management

### Payments

* Payment records linked to orders
* Payment status tracking

---

## Project Structure (Simplified)

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── ProductController.php
│   │   ├── CartController.php
│   │   ├── OrderController.php
│   │   └── PaymentController.php
│
├── Models/
│   ├── User.php
│   ├── Customer.php
│   ├── Product.php
│   ├── Category.php
│   ├── Cart.php
│   ├── CartItem.php
│   ├── Order.php
│   ├── OrderItem.php
│   └── Payment.php
│
├── Providers/
│   └── AppServiceProvider.php
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

Update `.env` with your database credentials:

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

If you are using custom key storage:

```php
// AppServiceProvider.php
Passport::loadKeysFrom(__DIR__.'/../secrets/oauth');
```

---

## API Authentication

All protected routes require a **Bearer Token**.

### Example Header

```
Authorization: Bearer {access_token}
```

---

## Main API Endpoints

### Authentication

```
POST   /api/register
POST   /api/login
POST   /api/logout
```

### Products

```
GET    /api/products
GET    /api/products/{id}
POST   /api/products
PUT    /api/products/{id}
DELETE /api/products/{id}
```

### Cart

```
GET    /api/cart
POST   /api/cart/items
PUT    /api/cart/items/{id}
DELETE /api/cart/items/{id}
```

### Orders

```
POST   /api/orders
GET    /api/orders
GET    /api/orders/{id}
```

### Payments

```
POST   /api/payments
GET    /api/payments/{order_id}
```

---

## License

This project is for educational and professional use.
You are free to modify and extend it.
