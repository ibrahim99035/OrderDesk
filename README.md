# OrderDesk

OrderDesk is a custom-built PHP web application designed for managing orders, products, and users. It follows a clean Model-View-Controller (MVC) architecture and provides a robust administrative interface for managing a cafeteria or small restaurant-like environment.

## 🚀 Features

### Admin Management
- **User Management:** Create, update, delete, and list users with profile images and room assignments.
- **Product Management:** Full CRUD operations for products, including category assignment, pricing, and availability toggles.
- **Order Tracking:** Monitor and update order statuses (Processing, Out for Delivery, Done, Cancelled).
- **Checks & Reports:** (Planned/Implemented) View order history and financial summaries.

### User Features
- **Easy Ordering:** Simple interface for users to browse products and place orders.
- **Order History:** Users can view their past orders and current status.
- **Profile:** Manage personal information and room details.

### Core Architecture
- **Custom MVC Framework:** Built from scratch for learning and flexibility.
- **PSR-4 Autoloading:** Modern PHP standards for class loading via Composer.
- **Repository Pattern:** Decoupled database logic for better maintainability.
- **Custom Routing:** Handles dynamic URLs and HTTP methods.
- **Security:** CSRF protection (via session), password hashing (BCRYPT), and input sanitization.

## 🛠️ Tech Stack
- **Backend:** PHP 8.x
- **Database:** MySQL
- **Frontend:** HTML5, CSS3, JavaScript
- **Dependency Management:** Composer
- **Environment Management:** `vlucas/phpdotenv`

## 📁 Project Structure

```text
OrderDesk/
├── app/                # Core application logic
│   ├── controllers/    # Request handlers
│   ├── core/           # Framework core (Route, Database, View, etc.)
│   ├── models/         # Data structures
│   ├── repositories/   # Database abstraction layer
│   └── Middleware/     # Authentication and security
├── config/             # Configuration files
├── public/             # Entry point and static assets (JS, CSS, Images)
├── views/              # PHP templates (Admin and User)
├── schema.sql          # Database structure
└── routes.php          # Application route definitions
```

## ⚙️ Installation

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   cd OrderDesk
   ```

2. **Install dependencies:**
   ```bash
   composer install
   ```

3. **Database Setup:**
   - Create a MySQL database named `order_desk`.
   - Import `schema.sql` to create the tables.
   - (Optional) Import `seed.sql` for sample data.

4. **Configuration:**
   - Rename `.env.example` (if available) to `.env` or create one.
   - Update the database credentials:
     ```env
     DB_HOST=localhost
     DB_NAME=order_desk
     DB_USER=root
     DB_PASS=your_password
     ```

5. **Run the application:**
   - You can use the built-in PHP server:
     ```bash
     php -S localhost:8000
     ```
   - Or configure it with Apache/Nginx.

## 📄 License
This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
