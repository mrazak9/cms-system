# Setup Guide - SimpleCMS
## Development Environment Setup for Windows

**Version:** 1.0  
**Target OS:** Windows 10/11  
**PHP Version:** 8.1  
**Database:** MySQL 8.0

---

## Table of Contents
1. [Prerequisites](#1-prerequisites)
2. [Development Environment Options](#2-development-environment-options)
3. [Installation Steps](#3-installation-steps)
4. [Project Setup](#4-project-setup)
5. [Database Setup](#5-database-setup)
6. [Running the Application](#6-running-the-application)
7. [Troubleshooting](#7-troubleshooting)

---

## 1. Prerequisites

Before starting, ensure you have:
- Windows 10 or Windows 11
- At least 4GB RAM
- 2GB free disk space
- Internet connection for downloading dependencies
- Basic command line knowledge

---

## 2. Development Environment Options

Choose ONE of the following options:

### Option A: Laragon (Recommended for Beginners)
✅ **Pros:** Easy to use, one-click installation, includes everything  
✅ **Best for:** Beginners, quick setup

### Option B: XAMPP + Composer
✅ **Pros:** Popular, lots of tutorials available  
✅ **Best for:** Those familiar with XAMPP

### Option C: Laravel Herd
✅ **Pros:** Official Laravel tool, modern, fast  
✅ **Best for:** Laravel developers, modern workflow

---

## 3. Installation Steps

### Option A: Setup with Laragon (Recommended)

#### Step 1: Download Laragon
1. Visit: https://laragon.org/download/
2. Download **Laragon Full** (includes PHP 8.1, MySQL, Apache)
3. Run installer: `laragon-wamp.exe`
4. Install to default location: `C:\laragon`

#### Step 2: Start Laragon
1. Open Laragon
2. Click **Start All**
3. Wait for services to start (Apache, MySQL)

#### Step 3: Verify Installation
1. Open browser: http://localhost
2. You should see Laragon welcome page

#### Step 4: Install Composer (if not included)
1. In Laragon, click **Menu** → **Tools** → **Quick add** → **Composer**
2. Or download from: https://getcomposer.org/Composer-Setup.exe
3. Run installer and follow prompts

#### Step 5: Verify Composer
```bash
# Open Laragon Terminal (Menu → Terminal)
composer --version
# Should show: Composer version 2.x.x
```

---

### Option B: Setup with XAMPP

#### Step 1: Download XAMPP
1. Visit: https://www.apachefriends.org/
2. Download XAMPP with PHP 8.1
3. Run installer: `xampp-windows-x64-8.1.x-installer.exe`
4. Install to: `C:\xampp`

#### Step 2: Start Services
1. Open XAMPP Control Panel
2. Start **Apache**
3. Start **MySQL**

#### Step 3: Install Composer
1. Download: https://getcomposer.org/Composer-Setup.exe
2. Run installer
3. When asked for PHP location, point to: `C:\xampp\php\php.exe`
4. Complete installation

#### Step 4: Verify Installation
```bash
# Open Command Prompt
php -v
# Should show: PHP 8.1.x

composer --version
# Should show: Composer version 2.x.x
```

---

### Option C: Setup with Laravel Herd

#### Step 1: Download Herd
1. Visit: https://herd.laravel.com/
2. Download Herd for Windows
3. Run installer
4. Follow setup wizard

#### Step 2: Configure Herd
1. Herd automatically installs PHP 8.1
2. MySQL is included (DnsMasq)
3. No additional setup needed

#### Step 3: Verify Installation
```bash
# Open Command Prompt or PowerShell
php -v
composer --version
```

---

## 4. Project Setup

### Step 1: Create Laravel Project

#### Option 1: Create New Laravel Project
```bash
# Navigate to web root
# Laragon: C:\laragon\www
# XAMPP: C:\xampp\htdocs
# Herd: ~/Herd

cd C:\laragon\www  # or your web root

# Create new Laravel project
composer create-project laravel/laravel simplecms

# Navigate to project
cd simplecms
```

#### Option 2: Clone from Git (if repository exists)
```bash
cd C:\laragon\www

# Clone repository
git clone https://github.com/yourusername/simplecms.git

# Navigate to project
cd simplecms

# Install dependencies
composer install
```

### Step 2: Install Required Packages
```bash
# Navigate to project directory
cd C:\laragon\www\simplecms

# Install Intervention Image (for image manipulation)
composer require intervention/image

# Install Spatie Permission (for roles & permissions)
composer require spatie/laravel-permission

# Update composer
composer update
```

### Step 3: Install Frontend Dependencies
```bash
# Install Node.js first if not installed
# Download from: https://nodejs.org/ (LTS version)

# After Node.js is installed, run:
npm install

# Install additional packages
npm install sortablejs --save
```

---

## 5. Database Setup

### Step 1: Create Database

#### For Laragon:
1. Open HeidiSQL (included with Laragon)
2. Connect to localhost (user: root, password: empty)
3. Right-click → Create new → Database
4. Name: `simplecms`
5. Collation: `utf8mb4_unicode_ci`
6. Click OK

#### For XAMPP:
1. Open browser: http://localhost/phpmyadmin
2. Click **New** (left sidebar)
3. Database name: `simplecms`
4. Collation: `utf8mb4_unicode_ci`
5. Click **Create**

#### For Herd:
1. Download TablePlus or DBeaver
2. Connect to localhost MySQL
3. Create database: `simplecms`

### Step 2: Configure Environment File
```bash
# Copy example environment file
copy .env.example .env

# Or on PowerShell:
Copy-Item .env.example .env
```

### Step 3: Edit .env File
Open `.env` in text editor (VS Code, Notepad++, etc.):

```env
APP_NAME=SimpleCMS
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://simplecms.test

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=simplecms
DB_USERNAME=root
DB_PASSWORD=

# For XAMPP, password is usually empty
# For Laragon, password is usually empty
# For Herd, password might be required

# Mail Configuration (optional for now)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=noreply@simplecms.test
MAIL_FROM_NAME="${APP_NAME}"
```

### Step 4: Generate Application Key
```bash
php artisan key:generate
```

### Step 5: Create Storage Link
```bash
php artisan storage:link
```

---

## 6. Running the Application

### Method 1: Using Laravel Artisan (All Options)
```bash
# Navigate to project
cd C:\laragon\www\simplecms

# Run migrations
php artisan migrate

# Run seeders (if available)
php artisan db:seed

# Start development server
php artisan serve

# Access at: http://localhost:8000
```

### Method 2: Using Virtual Host (Laragon)
1. In Laragon, right-click project folder
2. Select **Create Virtual Host**
3. Access at: http://simplecms.test

### Method 3: Using XAMPP
1. Access at: http://localhost/simplecms/public

---

## 7. Initial Setup Commands

After project is running, execute these commands:

### Create Admin User
```bash
# Create admin user via tinker
php artisan tinker

# In tinker console:
$user = new App\Models\User();
$user->name = 'Admin';
$user->email = 'admin@simplecms.test';
$user->password = bcrypt('admin123');
$user->save();

# Exit tinker
exit
```

### Run Migrations & Seeders
```bash
# Run all migrations
php artisan migrate

# Run seeders
php artisan db:seed

# Or run specific seeder
php artisan db:seed --class=SectionTemplateSeeder
php artisan db:seed --class=ThemeSeeder
php artisan db:seed --class=SettingSeeder
```

### Clear Cache (if needed)
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize for development
php artisan optimize:clear
```

---

## 8. Verify Installation

### Checklist:
- [ ] PHP 8.1 installed and working
- [ ] MySQL 8.0 running
- [ ] Composer installed
- [ ] Node.js & NPM installed
- [ ] Laravel project created
- [ ] Database created and connected
- [ ] Migrations run successfully
- [ ] Application accessible in browser
- [ ] Admin login works

### Test URLs:
```
Frontend: http://localhost:8000
Admin: http://localhost:8000/admin
Login: http://localhost:8000/login
```

---

## 9. Troubleshooting

### Issue: "Class not found" Error
**Solution:**
```bash
composer dump-autoload
php artisan config:clear
```

### Issue: "SQLSTATE Connection Refused"
**Solution:**
1. Check MySQL is running
2. Verify DB credentials in `.env`
3. Test connection:
```bash
php artisan tinker
DB::connection()->getPdo();
```

### Issue: "Permission Denied" on Storage
**Solution (Windows):**
1. Right-click `storage` folder
2. Properties → Security → Edit
3. Add "Users" group with Full Control
4. Apply to all subfolders

### Issue: Port 80 Already in Use
**Solution:**
1. Change Apache port in config
2. Or stop other services using port 80 (Skype, IIS)
3. Or use `php artisan serve --port=8080`

### Issue: Composer is Slow
**Solution:**
```bash
# Use different mirror
composer config -g repos.packagist composer https://packagist.org
```

### Issue: Migration Error "Table already exists"
**Solution:**
```bash
# Reset database
php artisan migrate:fresh

# Or drop all tables manually and re-run
php artisan migrate
```

---

## 10. Development Tools (Optional)

### Code Editor
- **VS Code** (Recommended): https://code.visualstudio.com/
  - Extensions: 
    - Laravel Extension Pack
    - PHP Intelephense
    - Blade Formatter

### Database Client
- **HeidiSQL** (Free, included with Laragon)
- **TablePlus** (Free tier available)
- **DBeaver** (Free, open source)

### Git Client
- **Git for Windows**: https://git-scm.com/download/win
- **GitHub Desktop**: https://desktop.github.com/

### API Testing
- **Postman**: https://www.postman.com/downloads/
- **Insomnia**: https://insomnia.rest/download

---

## 11. Next Steps

After successful setup:

1. **Read Documentation:**
   - [ ] TECH_SPEC.md
   - [ ] DATABASE_SCHEMA.md
   - [ ] DEVELOPMENT_GUIDE.md

2. **Setup Admin Panel:**
   - [ ] Install Stisla template files
   - [ ] Configure admin routes
   - [ ] Create admin controllers

3. **Create Section Templates:**
   - [ ] Design section components
   - [ ] Create Blade views
   - [ ] Seed template data

4. **Start Development:**
   - [ ] Follow development guide
   - [ ] Create first page
   - [ ] Test section builder

---

## 12. Useful Commands Reference

### Laravel Artisan Commands
```bash
# Development
php artisan serve                    # Start dev server
php artisan migrate                  # Run migrations
php artisan migrate:fresh            # Reset database
php artisan db:seed                  # Run seeders
php artisan tinker                   # Laravel REPL

# Cache
php artisan cache:clear              # Clear cache
php artisan config:clear             # Clear config cache
php artisan route:clear              # Clear route cache
php artisan view:clear               # Clear view cache

# Make Commands
php artisan make:model Page          # Create model
php artisan make:controller PageController --resource
php artisan make:migration create_pages_table
php artisan make:seeder PageSeeder
php artisan make:component Sections/Hero1

# Others
php artisan list                     # List all commands
php artisan route:list               # Show all routes
php artisan storage:link             # Create storage symlink
```

### Composer Commands
```bash
composer install                     # Install dependencies
composer update                      # Update dependencies
composer require package/name        # Add package
composer dump-autoload               # Regenerate autoload
composer show                        # List installed packages
```

### NPM Commands
```bash
npm install                          # Install packages
npm run dev                          # Compile assets (development)
npm run build                        # Build for production
npm run watch                        # Watch for changes
```

---

## 13. Environment URLs

Based on your setup:

| Environment | Base URL | Admin URL | Database |
|-------------|----------|-----------|----------|
| Laragon | http://simplecms.test | http://simplecms.test/admin | HeidiSQL |
| XAMPP | http://localhost/simplecms/public | http://localhost/simplecms/public/admin | phpMyAdmin |
| Herd | http://simplecms.test | http://simplecms.test/admin | TablePlus |
| Artisan Serve | http://localhost:8000 | http://localhost:8000/admin | Any client |

---

## 14. Common File Locations

```
Project Root: C:\laragon\www\simplecms\
Config Files: C:\laragon\www\simplecms\config\
Controllers: C:\laragon\www\simplecms\app\Http\Controllers\
Models: C:\laragon\www\simplecms\app\Models\
Views: C:\laragon\www\simplecms\resources\views\
Migrations: C:\laragon\www\simplecms\database\migrations\
Public: C:\laragon\www\simplecms\public\
Storage: C:\laragon\www\simplecms\storage\
```

---

## Support & Resources

- **Laravel Documentation:** https://laravel.com/docs/10.x
- **Laragon Forum:** https://forum.laragon.org/
- **Stack Overflow:** https://stackoverflow.com/questions/tagged/laravel
- **Laracasts:** https://laracasts.com/ (Video tutorials)

---

**Document Version:** 1.0  
**Last Updated:** October 30, 2025  
**Maintained By:** SimpleCMS Development Team
