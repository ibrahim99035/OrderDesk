# OrderDesk

OrderDesk is a custom-built PHP web application for managing orders, products and users in a
cafeteria / small-restaurant setting. It follows a clean **MVC** architecture with a repository
pattern and role-based access.

## Team

> Group effort: database, architecture, repositories and middlewares

- **Ahmed Tarek** — Checks management
- **Ibrahim Mohamed Eita** — User management
- **Maryam Abdelrheem Elkhwaga** — Home pages management
- **Mohamed Ahmed Halawa** — Product management
- **Mohamed Tarek Kandil** — Order management

---

## Tech stack

- **Backend:** PHP 8.x (custom MVC framework, built from scratch)
- **Database:** MySQL **or** SQLite (automatic local fallback)
- **Frontend:** HTML5, Tailwind CSS, JavaScript
- **Dependencies:** Composer (`vlucas/phpdotenv`), PSR-4 autoloading
- **Security:** session CSRF, BCRYPT password hashing, input sanitization

---

## Setup & run

```bash
cd OrderDesk
composer install
php -S localhost:8080 index.php
```

Open **http://localhost:8080/login**.

### Database

`app/core/Database.php` connects to MySQL when the `DB_*` variables are set in `.env`;
otherwise it **falls back to a local SQLite file** (`orderdesk.sqlite`) and auto-creates the
schema from `schema.sqlite`. The app therefore runs locally with **no MySQL server** required.

`.env` (all optional — leave the `DB_*` values empty to use the SQLite fallback):

```dotenv
APP_NAME=OrderDesk
APP_URL=http://localhost

# Optional MySQL (leave DB_HOST empty to use local SQLite)
DB_HOST=
DB_NAME=
DB_USER=
DB_PASS=
```

---

## Admin account

Login: **http://localhost:8080/login**

| Email            | Password    |
|------------------|-------------|
| `admin@admin.com`| `Admin@1234`|

> The `INSERT`s in `seed.sql` are commented out, so a fresh SQLite database starts with **no users**.
> Create the admin account once (run this from the project root, after the first request has
> created `orderdesk.sqlite`):
>
> ```bash
> php -r '$d=new PDO("sqlite:orderdesk.sqlite");$d->exec("CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY AUTOINCREMENT,name TEXT,email TEXT UNIQUE,password TEXT,role TEXT DEFAULT \"user\",room_id INTEGER,extension TEXT,image TEXT,is_active INTEGER DEFAULT 1,created_at DATETIME DEFAULT CURRENT_TIMESTAMP)");$h=password_hash("Admin@1234",PASSWORD_BCRYPT);$s=$d->prepare("INSERT OR IGNORE INTO users (name,email,password,role,is_active) VALUES (?,?,?,\"admin\",1)");$s->execute(["Super Admin","admin@admin.com",$h]);echo "admin ready\n";'
> ```

Roles: `user` (regular customer), `admin` (full access), plus an office-boy flow for deliveries.

---

## Core architecture

- **Custom MVC framework** — built from scratch for learning and flexibility
- **PSR-4 autoloading** via Composer
- **Repository pattern** to decouple database logic
- **Custom routing** handling dynamic URLs and HTTP methods
- **Middleware** for auth (`AuthMiddleware`) and admin (`AdminMiddleware`)
