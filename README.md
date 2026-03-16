# OrderDesk

OrderDesk is a custom-built PHP web application designed for managing orders, products, and users. It follows a clean Model-View-Controller (MVC) architecture and provides a robust administrative interface for managing a cafeteria or small restaurant-like environment.

## Team
> Group Effort: Building the database, building the Architecture, Repositories and Middlewares

### Ahmed Tarek
#### Checks Management
### Ibrahim Mohamed Eita
#### User Management
### Maryam Abdelrheem Elkhwaga
#### Home Pages Management
### Mohamed Ahmed Halawa
#### Product Management
### Mohamed Tarek Kandil 
#### Order Management

## Environment

```.env
DB_HOST=sql.freedb.tech
DB_NAME=freedb_OrderDesk
DB_USER=freedb_OrderDeskITI
DB_PASS=c**?x3!GNV&tb37
```
Stand on the root directory on any terminal, Install the required packages and run:

```bash
composer update
composer install
php -S localhost:8080 index.php
```

The database deployed and allowes 100 requst per hour

### Admin Account
| Email           | Password   |
| --------------- | ---------- |
| admin@admin.com | Admin@1234 |

## Core Architecture
- **Custom MVC Framework:** Built from scratch for learning and flexibility.
- **PSR-4 Autoloading:** Modern PHP standards for class loading via Composer.
- **Repository Pattern:** Decoupled database logic for better maintainability.
- **Custom Routing:** Handles dynamic URLs and HTTP methods.
- **Security:** CSRF protection (via session), password hashing (BCRYPT), and input sanitization.

## Tech Stack
- **Backend:** PHP 8.x
- **Database:** MySQL
- **Frontend:** HTML5, TailwindCSS, JavaScript
- **Dependency Management:** Composer
- **Environment Management:** `vlucas/phpdotenv`


