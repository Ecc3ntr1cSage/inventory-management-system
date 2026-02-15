<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Inventory Management System

A Laravel-based inventory management application using Livewire for dynamic interfaces. It manages stocks, assets, and user roles (admin, staff, user).

## Prerequisites

- **PHP 8.2+**: Download from [php.net](https://www.php.net/downloads). Enable extensions: `pdo_sqlite`, `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `gd`.
- **Composer**: Install from [getcomposer.org](https://getcomposer.org/download/).
- **Node.js 18+ and npm**: Install from [nodejs.org](https://nodejs.org/).
- **Git**: For cloning the repository.
- **Database**: SQLite (default, file-based) or MySQL/MariaDB.

## Local Setup (Windows)

Follow these steps to run the application locally on Windows.

### 1. Clone the Repository
```bash
git clone <repository-url> inventory-management-system
cd inventory-management-system
```

### 2. Install PHP Dependencies
Run Composer to install Laravel dependencies:
```bash
composer install
```

### 3. Configure Environment
- Copy `.env.example` to `.env`:
  ```bash
  cp .env.example .env
  ```
- Edit `.env`:
  - For SQLite (default):
    ```
    DB_CONNECTION=sqlite
    DB_DATABASE=database/database.sqlite
    ```
  - For MySQL:
    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=invms
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```
  - Set other values like `APP_NAME=Inventory Management System`, `APP_URL=http://localhost:8000`.

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Set Up Database
- If using SQLite, create the database file and run migrations:
  ```bash
  php artisan migrate --seed
  ```
  This populates initial data (users: admin@gmail.com/12345678, staff@gmail.com/12345678, user@gmail.com/12345678; stocks and assets from [database/seeders/DatabaseSeeder.php](database/seeders/DatabaseSeeder.php)).
- If using MySQL, create the database manually, then run:
  ```bash
  php artisan migrate --seed
  ```

### 6. Install and Build Frontend Assets
```bash
npm install
npm run build  # For production, or npm run dev for development
```

### 7. Start the Development Server
```bash
php artisan serve --host=127.0.0.1 --port=8000
```
- Access the app at `http://127.0.0.1:8000`.
- Log in with seeded users (e.g., admin role for full access).

### 8. Optional: Run in Development Mode
- For hot reloading assets: `npm run dev` in a separate terminal.
- Clear caches if needed: `php artisan config:clear`, `php artisan cache:clear`.

## Deployment on Windows Server (On-Premises)

Deploy to any Windows Server version (e.g., 2012, 2016, 2019, 2022) using IIS. Assumes administrative access.

### 1. Prepare the Server
- Install IIS: Via Server Manager > Add Roles and Features > Web Server (IIS).
- Install required IIS modules: CGI, URL Rewrite (download from [iis.net](https://www.iis.net/downloads/microsoft/url-rewrite)).
- Install PHP 8.2+ (non-thread-safe): Extract to `C:\php`, add to PATH. Enable extensions in `php.ini`.
- Install Composer and Node.js (as above).
- Install database: SQLite (no setup needed) or MySQL/MariaDB.

### 2. Deploy the Application
- Clone or upload code to `C:\inetpub\wwwroot\inventory-management-system`:
  ```bash
  git clone <repository-url> C:\inetpub\wwwroot\inventory-management-system
  cd C:\inetpub\wwwroot\inventory-management-system
  ```
- Install dependencies:
  ```bash
  composer install --no-dev --optimize-autoloader
  npm install && npm run build
  ```
- Configure `.env` (as in local setup, but set `APP_ENV=production`, `APP_URL=https://your-domain.com`).
- Generate key and set up database:
  ```bash
  php artisan key:generate
  php artisan migrate --seed
  ```
- Cache for production:
  ```bash
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  ```

### 3. Configure IIS
- Create a new site in IIS Manager pointing to `C:\inetpub\wwwroot\inventory-management-system\public`.
- Add handler mapping: `*.php` to `C:\php\php-cgi.exe`.
- Add URL Rewrite rule: Rewrite `^(.*)$` to `index.php/$1`.
- Set default document to `index.php`.
- Grant IIS_IUSRS read/write to `storage/`, `bootstrap/cache/`, and database file (if SQLite).

### 4. Additional Configurations
- **SSL**: Install certificate via IIS Manager for HTTPS.
- **Queues**: If using queues, schedule `php artisan queue:work` via Task Scheduler.
- **Firewall**: Allow ports 80/443.
- **Backup**: Schedule backups for database and code.

### 5. Testing
- Access via server IP/domain.
- Test login and features (e.g., inventory entry, asset requests).
- Monitor logs in `storage/logs/laravel.log`.

## Troubleshooting
- **PHP Errors**: Check `php.ini` extensions and IIS logs.
- **Database Issues**: Verify credentials and service status.
- **Assets Not Loading**: Run `npm run build` and clear browser cache.
- **Permissions**: Ensure IIS has access to files.

For more, see [Laravel Docs](https://laravel.com/docs).

