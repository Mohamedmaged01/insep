# INSEP LMS/ERP - Laravel Backend

## Quick Start (Deployment on Hostinger)

### 1. Upload Files
Upload the entire `backend-laravel` folder to your Hostinger hosting.

### 2. Set Document Root
Point your domain's document root to the `public/` folder.

### 3. Create Storage Directories
```bash
php setup_dirs.php
```

### 4. Install Dependencies
```bash
composer install --optimize-autoloader --no-dev
```

### 5. Configure Environment
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` with your MySQL database credentials:
```
DB_HOST=your_host
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
JWT_SECRET=your-secret-key
```

### 6. Run Migrations
```bash
php artisan migrate
```

### 7. Seed Database (Optional)
```bash
php artisan db:seed
```

### 8. Set Permissions
```bash
chmod -R 775 storage bootstrap/cache
```

## Default Admin Login
- **Email**: `admin@insep.net`
- **Password**: `654321@`

## API Base URL
All API routes are prefixed with `/api/`. Example:
- `POST /api/auth/login`
- `GET /api/courses`

## Tech Stack
- **PHP** 8.1+
- **Laravel** 10.x
- **MySQL** 5.7+
- **JWT Auth** (tymon/jwt-auth)
