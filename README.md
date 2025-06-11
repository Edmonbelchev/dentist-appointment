# Laravel Appointment App (Docker + MySQL + Vite)

This is a Laravel-based appointment management application pre-configured with Docker. It includes a MySQL database, Vite for frontend development, and Mailhog for email testing.

---

## 🚀 Quick Start

### 1. 📚 Clone the Repository

```bash
git clone <your-repo-url>
cd <project-folder>
```

### 2. 📝 Set up environment variables
```
cp .env.example .env
```

Make sure the following values in .env match the Docker setup:
```
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret

MAIL_HOST=mailhog
MAIL_PORT=1025
```

### 3. 📦 Install dependencies
```
composer install
npm install
```

### 4. 🏗 Build and run the Docker containers
```
docker-compose up -d --build
```
This will start the following services:
- app – Laravel backend (port 8000)
- vite – Frontend dev server (port 5173)
- db – MySQL database (port 3306)
- mailhog – Email testing service (SMTP on 1025, UI on 8025)

### 5. 🔑 Generate env key
```
php artisan key:generate
```

### 6. 📚 Run migrations
```
php artisan migrate
```
OR
```
php artisan migrate --seed
```
If you want to seed the database with sample data. You can use the seeded user with the following credentials:
```
Email: test@example.com
Password: password
```
Seeding the data would to the following:
- Create 50 sample appointment records
- With fake names like Пациент 1, Пациент 2, etc.
- Some patients will share the same EGN (randomly repeated)
- Random descriptions and notification methods (sms, email)

### 7. 📬 Email Testing with Mailhog
You can view sent emails in Mailhog:
👉 http://localhost:8025