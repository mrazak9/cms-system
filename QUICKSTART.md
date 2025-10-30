# Quick Start Checklist - SimpleCMS

**Before You Start Coding: Read This First! ✅**

---

## 📋 Pre-Development Checklist

### 1. Environment Setup
- [ ] Windows 10/11 installed
- [ ] Install Laragon/XAMPP (choose one)
- [ ] Install PHP 8.1+
- [ ] Install MySQL 8.0+
- [ ] Install Composer
- [ ] Install Node.js & NPM
- [ ] Install Git
- [ ] Install VS Code (or preferred editor)

### 2. VS Code Extensions (Recommended)
- [ ] Laravel Extension Pack
- [ ] PHP Intelephense
- [ ] Blade Formatter
- [ ] GitLens
- [ ] Better Comments
- [ ] Path Intellisense

### 3. Read Documentation
- [ ] Read TECH_SPEC.md (understand features & architecture)
- [ ] Read DATABASE_SCHEMA.md (understand database structure)
- [ ] Read SETUP_GUIDE.md (setup instructions)
- [ ] Read DEVELOPMENT_GUIDE.md (coding standards)
- [ ] Skim through API_DOCUMENTATION.md
- [ ] Review PROJECT_TIMELINE.md (know the schedule)

---

## 🚀 Initial Setup Steps

### Step 1: Create Laravel Project
```bash
# Navigate to your web root
cd C:\laragon\www  # or C:\xampp\htdocs

# Create new Laravel project
composer create-project laravel/laravel simplecms

# Navigate to project
cd simplecms
```

### Step 2: Install Dependencies
```bash
# Install required packages
composer require intervention/image
composer require spatie/laravel-permission

# Install frontend dependencies
npm install
npm install sortablejs --save
```

### Step 3: Configure Environment
```bash
# Copy environment file
copy .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Database Setup
- [ ] Create database `simplecms` in MySQL
- [ ] Update `.env` file with database credentials:
```env
DB_DATABASE=simplecms
DB_USERNAME=root
DB_PASSWORD=
```

### Step 5: Verify Installation
```bash
# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();

# If successful, exit tinker
>>> exit
```

---

## 📁 Project Structure Setup

### Create Directory Structure
```bash
# Create additional directories
mkdir app\Services
mkdir app\Repositories
mkdir resources\views\components\sections
mkdir public\uploads\images
mkdir public\assets\section-thumbnails
```

### Download Stisla Template
1. Download Stisla from: https://getstisla.com
2. Extract files
3. Copy to `public/assets/admin/`
   - Copy `css/` folder
   - Copy `js/` folder
   - Copy `img/` folder

---

## 🗄️ Database Setup

### Create Migrations (Week 1, Day 3-4)

Create migrations in this order:

```bash
# 1. Users (already exists in Laravel)

# 2. Pages
php artisan make:migration create_pages_table

# 3. Section Templates
php artisan make:migration create_section_templates_table

# 4. Page Sections
php artisan make:migration create_page_sections_table

# 5. Categories
php artisan make:migration create_categories_table

# 6. Posts
php artisan make:migration create_posts_table

# 7. Menus
php artisan make:migration create_menus_table

# 8. Menu Items
php artisan make:migration create_menu_items_table

# 9. Themes
php artisan make:migration create_themes_table

# 10. Media
php artisan make:migration create_media_table

# 11. Settings
php artisan make:migration create_settings_table
```

### Run Migrations
```bash
# Run all migrations
php artisan migrate
```

---

## 🎨 Frontend Setup

### Compile Assets
```bash
# Development mode
npm run dev

# Watch for changes (during development)
npm run watch

# Production build (later)
npm run build
```

---

## 👤 User Authentication Setup

### Install Laravel Breeze (Week 1, Day 5)
```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install
npm run dev
php artisan migrate
```

### Install Spatie Permission
```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

---

## 🧪 Testing Setup

### Configure PHPUnit
Edit `phpunit.xml`:
```xml
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

### Run Tests
```bash
php artisan test
```

---

## ✅ First Day Checklist

### Day 1 Morning
- [ ] Setup complete development environment
- [ ] Laravel project created
- [ ] Database connected
- [ ] Git repository initialized
- [ ] All documentation read

### Day 1 Afternoon
- [ ] Stisla template downloaded and placed
- [ ] Basic admin layout created
- [ ] Authentication installed (Breeze)
- [ ] First commit pushed to Git

### Day 1 Evening
- [ ] Test that everything works:
  - [ ] Can access homepage
  - [ ] Can register/login
  - [ ] Can access admin panel
  - [ ] Database migrations run

---

## 🎯 Week 1 Goals Reminder

By end of Week 1, you should have:
- ✅ Laravel project setup complete
- ✅ Database migrations created & run
- ✅ Models with relationships
- ✅ Authentication working
- ✅ Stisla admin template integrated
- ✅ Basic admin dashboard

---

## 📝 Important Files to Create First

### Models (Create in this order)
```bash
php artisan make:model Page
php artisan make:model PageSection
php artisan make:model SectionTemplate
php artisan make:model Post
php artisan make:model Category
php artisan make:model Menu
php artisan make:model MenuItem
php artisan make:model Theme
php artisan make:model Media
php artisan make:model Setting
```

### Controllers
```bash
# Admin Controllers
php artisan make:controller Admin/DashboardController
php artisan make:controller Admin/PageController --resource
php artisan make:controller Admin/PostController --resource
php artisan make:controller Admin/MenuController --resource
php artisan make:controller Admin/ThemeController
php artisan make:controller Admin/SettingController

# Frontend Controllers
php artisan make:controller Frontend/HomeController
php artisan make:controller Frontend/PageController
php artisan make:controller Frontend/PostController
```

---

## 🔧 Configuration Files to Edit

### config/app.php
```php
'timezone' => 'Asia/Jakarta',
'locale' => 'id',
'fallback_locale' => 'en',
```

### config/filesystems.php
```php
'disks' => [
    'public' => [
        'driver' => 'local',
        'root' => storage_path('app/public'),
        'url' => env('APP_URL').'/storage',
        'visibility' => 'public',
    ],
],
```

---

## 🎨 Views Structure to Create

```
resources/views/
├── admin/
│   ├── layouts/
│   │   ├── app.blade.php          # Main admin layout
│   │   ├── sidebar.blade.php      # Sidebar navigation
│   │   └── navbar.blade.php       # Top navbar
│   ├── dashboard.blade.php        # Admin dashboard
│   ├── pages/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   └── edit.blade.php
│   └── posts/
│       ├── index.blade.php
│       ├── create.blade.php
│       └── edit.blade.php
├── frontend/
│   ├── layouts/
│   │   ├── app.blade.php          # Main frontend layout
│   │   ├── header.blade.php
│   │   └── footer.blade.php
│   ├── home.blade.php
│   ├── page.blade.php
│   └── blog/
│       ├── index.blade.php
│       └── show.blade.php
└── components/
    └── sections/
        ├── hero-1.blade.php
        ├── about-1.blade.php
        └── features-1.blade.php
```

---

## 🧪 First Feature to Test

After Week 1 setup, test this workflow:

1. **Create a Page**
   - Login to admin
   - Go to Pages
   - Click "Add New Page"
   - Fill in title and content
   - Save

2. **Verify Database**
   - Check page exists in `pages` table
   - Verify relationships work

3. **View Frontend**
   - Access the page via frontend
   - Verify it displays correctly

---

## 🐛 Common Issues & Solutions

### Issue: "Class not found"
```bash
composer dump-autoload
php artisan config:clear
```

### Issue: "SQLSTATE Connection Refused"
- Check MySQL is running
- Verify `.env` database credentials
- Test connection: `php artisan tinker` → `DB::connection()->getPdo();`

### Issue: "Permission denied" on storage
```bash
# Windows (run as Administrator)
icacls "C:\laragon\www\simplecms\storage" /grant Everyone:(OI)(CI)F /T
icacls "C:\laragon\www\simplecms\bootstrap\cache" /grant Everyone:(OI)(CI)F /T
```

### Issue: Port 80 already in use
```bash
# Use Laravel's built-in server instead
php artisan serve --port=8080
```

---

## 📞 Need Help?

### Resources
- Laravel Docs: https://laravel.com/docs/10.x
- Stack Overflow: https://stackoverflow.com/questions/tagged/laravel
- Laracasts Forum: https://laracasts.com/discuss
- Laravel Discord: https://discord.gg/laravel

### Team Support
- Check PROJECT_TIMELINE.md for meeting schedule
- Ask questions in team chat
- Review DEVELOPMENT_GUIDE.md for best practices

---

## 🎉 You're Ready!

Once all checkboxes above are complete, you're ready to start Week 1 development!

**Good luck and happy coding! 💻✨**

---

## 📅 Daily Development Routine

### Start of Day
- [ ] Pull latest changes: `git pull`
- [ ] Check PROJECT_TIMELINE.md for today's tasks
- [ ] Review any blockers from yesterday

### During Development
- [ ] Follow coding standards from DEVELOPMENT_GUIDE.md
- [ ] Commit frequently with clear messages
- [ ] Test features as you build them
- [ ] Ask for help when stuck (don't spend > 30 min on one issue)

### End of Day
- [ ] Push your commits: `git push`
- [ ] Update task status in project board
- [ ] Note any blockers for tomorrow
- [ ] Clear cache if needed: `php artisan optimize:clear`

---

## 🔄 Git Best Practices

### Initial Setup
```bash
git init
git add .
git commit -m "Initial commit: Laravel setup"
git branch -M main
git remote add origin https://github.com/yourusername/simplecms.git
git push -u origin main
```

### Daily Workflow
```bash
# Start new feature
git checkout -b feature/page-builder

# Work on feature...
git add .
git commit -m "feat: add page list view"

# Push to remote
git push origin feature/page-builder

# When complete, merge to main
git checkout main
git merge feature/page-builder
git push origin main
```

---

## 📊 Progress Tracking

### Use This Template for Weekly Updates

```markdown
## Week X Progress Report

**Date:** [Start] - [End]
**Status:** On Track / Delayed / Ahead

### Completed Tasks
- [x] Task 1
- [x] Task 2

### In Progress
- [ ] Task 3 (80% complete)

### Blockers
- None / Issue description

### Next Week Goals
- [ ] Task 4
- [ ] Task 5
```

---

## ✨ Final Reminder

> **"The secret of getting ahead is getting started."** - Mark Twain

You have everything you need to build SimpleCMS. Follow the timeline, stick to the plan, and don't be afraid to ask for help. 

**Let's build something amazing! 🚀**

---

**Document Version:** 1.0  
**Created:** October 30, 2025  
**Last Updated:** October 30, 2025
