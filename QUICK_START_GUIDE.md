# SimpleCMS - Quick Start Guide

**Version**: 1.0
**Last Updated**: 2025-10-31

---

## 📋 Table of Contents

1. [Current Features](#current-features)
2. [Login Credentials](#login-credentials)
3. [Quick Commands](#quick-commands)
4. [Common Tasks](#common-tasks)
5. [Troubleshooting](#troubleshooting)
6. [File Structure](#file-structure)

---

## Current Features

### ✅ Completed Features

#### Content Management
- ✅ **Pages**: Create, edit, delete pages with sections
- ✅ **Posts**: Blog post management with categories
- ✅ **Categories**: Organize blog posts
- ✅ **Media Library**: Upload and manage images/files
- ✅ **Menus**: Dynamic menu builder

#### Appearance
- ✅ **Themes**: Multiple Crafto themes (Business, Corporate, Digital Agency)
- ✅ **Theme Settings**: Edit theme content via admin panel
- ✅ **Theme Switching**: Activate different themes

#### User Management
- ✅ **Users**: Full CRUD for user accounts
- ✅ **Roles**: Admin, Editor, Author, Subscriber
- ✅ **Permissions**: 46 granular permissions across 9 modules
- ✅ **Role Management**: Assign permissions to roles

#### System
- ✅ **Settings**: Site configuration
- ✅ **Dashboard**: Role-based capability cards
- ✅ **Authentication**: Login, register, password reset

---

## Login Credentials

### Test Accounts

```
🔴 Admin (Full Access)
Email: admin@simplecms.test
Password: admin123
Permissions: All 46 permissions

🟡 Editor (Content Manager)
Email: editor@simplecms.test
Password: editor123
Permissions: Pages, Posts, Categories, Media, Menus (25 permissions)

🟢 Author (Content Creator)
Email: author@simplecms.test
Password: author123
Permissions: Posts (own), Media (own) (8 permissions)
```

### Access URLs

```
Frontend: http://localhost:8000
Admin Panel: http://localhost:8000/admin
Login: http://localhost:8000/login
Register: http://localhost:8000/register
```

---

## Quick Commands

### Development

```bash
# Start development server
cd simplecms
php artisan serve

# Run on specific port
php artisan serve --port=8080
```

### Database

```bash
# Run migrations
php artisan migrate

# Fresh migration with seed
php artisan migrate:fresh --seed

# Seed specific seeder
php artisan db:seed --class=RolesAndPermissionsSeeder
php artisan db:seed --class=AdminUserSeeder
php artisan db:seed --class=ThemeContentSeeder

# Check migration status
php artisan migrate:status
```

### Cache

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Clear permission cache (important after role changes)
php artisan permission:cache-reset

# Optimize for production
php artisan optimize
php artisan route:cache
php artisan config:cache
php artisan view:cache
```

### Tinker (Database Testing)

```bash
php artisan tinker

# Check users
>>> User::all()
>>> User::find(1)->roles
>>> User::find(1)->getAllPermissions()

# Check roles
>>> \Spatie\Permission\Models\Role::all()
>>> \Spatie\Permission\Models\Role::with('permissions')->find(1)

# Check permissions
>>> \Spatie\Permission\Models\Permission::count()

# Check themes
>>> \App\Models\Theme::where('is_active', true)->first()
```

---

## Common Tasks

### 1. Create New User

**Via Admin Panel**:
1. Login as Admin
2. Go to **Admin → Users**
3. Click **"Add New User"**
4. Fill form (name, email, password, role)
5. Click **"Create User"**

**Via Tinker**:
```php
$user = User::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => Hash::make('password123'),
    'email_verified_at' => now(),
]);

$user->assignRole('editor');
```

### 2. Create New Role

**Via Admin Panel**:
1. Login as Admin
2. Go to **Admin → Roles & Permissions**
3. Click **"Add New Role"**
4. Enter role name (lowercase)
5. Select permissions
6. Click **"Create Role"**

**Via Tinker**:
```php
$role = \Spatie\Permission\Models\Role::create(['name' => 'manager']);
$role->givePermissionTo(['pages.view', 'pages.create', 'pages.edit']);
```

### 3. Assign Permissions to Role

**Via Admin Panel**:
1. Go to **Admin → Roles & Permissions**
2. Click **Edit** on role
3. Check/uncheck permissions
4. Click **"Update Role"**

**Via Tinker**:
```php
$role = \Spatie\Permission\Models\Role::findByName('editor');
$role->givePermissionTo('posts.publish');
```

### 4. Change User Role

**Via Admin Panel**:
1. Go to **Admin → Users**
2. Click **Edit** on user
3. Select new role from dropdown
4. Click **"Update User"**

**Via Tinker**:
```php
$user = User::find(2);
$user->syncRoles(['author']); // Remove old roles and assign new
```

### 5. Activate Theme

**Via Admin Panel**:
1. Go to **Admin → Themes**
2. Click **"Activate"** on desired theme
3. Theme changes immediately

**Via Tinker**:
```php
\App\Models\Theme::query()->update(['is_active' => false]);
$theme = \App\Models\Theme::where('slug', 'business')->first();
$theme->is_active = true;
$theme->save();
```

### 6. Edit Theme Content

**Via Admin Panel**:
1. Go to **Admin → Themes**
2. Click **"Edit Content"** on active theme
3. Edit field values (hero titles, service descriptions, etc.)
4. Click **"Save All Changes"**
5. View changes on homepage

### 7. Create New Page

1. Go to **Admin → Pages**
2. Click **"Create Page"**
3. Fill in title, slug, content
4. Add sections (optional)
5. Select theme (or use global)
6. Set status (published/draft)
7. Click **"Save Page"**

### 8. Create Blog Post

1. Go to **Admin → Posts & Categories → All Posts**
2. Click **"Create Post"**
3. Fill in title, content
4. Select category
5. Upload featured image
6. Set status (published/draft)
7. Click **"Save Post"**

### 9. Create Menu

1. Go to **Admin → Menus**
2. Click **"Create Menu"**
3. Enter menu name and location
4. Add menu items (pages, posts, custom links)
5. Drag to reorder
6. Click **"Save Menu"**

---

## Troubleshooting

### Issue: Cannot Login

**Solutions**:
```bash
# 1. Check user exists
php artisan tinker
>>> User::where('email', 'admin@simplecms.test')->first()

# 2. Reset password
>>> $user = User::where('email', 'admin@simplecms.test')->first();
>>> $user->password = Hash::make('admin123');
>>> $user->save();

# 3. Clear sessions
php artisan cache:clear
```

### Issue: 403 Forbidden on Admin Routes

**Cause**: User doesn't have admin role

**Solutions**:
```bash
php artisan tinker
>>> $user = User::find(1);
>>> $user->assignRole('admin');

# Or check current roles
>>> $user->roles
```

### Issue: Permissions Not Working

**Solutions**:
```bash
# 1. Clear permission cache
php artisan permission:cache-reset

# 2. Clear all caches
php artisan cache:clear
php artisan config:clear

# 3. Check permissions exist
php artisan tinker
>>> \Spatie\Permission\Models\Permission::count()

# 4. Re-seed if needed
php artisan db:seed --class=RolesAndPermissionsSeeder
```

### Issue: Theme Changes Not Showing

**Solutions**:
```bash
# 1. Clear view cache
php artisan view:clear

# 2. Hard refresh browser (Ctrl+F5)

# 3. Check theme is active
php artisan tinker
>>> \App\Models\Theme::where('is_active', true)->first()
```

### Issue: Database Errors

**Solutions**:
```bash
# 1. Check database connection
php artisan tinker
>>> DB::connection()->getPdo()

# 2. Run migrations
php artisan migrate

# 3. Check .env file
# Make sure DB_CONNECTION, DB_DATABASE, etc. are correct
```

### Issue: Menu Not Showing

**Cause**: Menu not assigned to correct location

**Solutions**:
1. Go to **Admin → Menus**
2. Edit menu
3. Check **Location** field matches (primary/footer)
4. Save menu

---

## File Structure

### Important Directories

```
simplecms/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/           # Admin controllers
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── PageController.php
│   │   │   │   ├── PostController.php
│   │   │   │   ├── UserController.php      # NEW
│   │   │   │   ├── RoleController.php      # NEW
│   │   │   │   └── ...
│   │   │   ├── Frontend/        # Frontend controllers
│   │   │   └── HomeController.php
│   │   └── Middleware/
│   │       └── Admin.php         # Admin role check
│   │
│   ├── Models/
│   │   ├── User.php              # With HasRoles trait
│   │   ├── Page.php
│   │   ├── Post.php
│   │   ├── Theme.php
│   │   ├── ThemeSetting.php      # NEW
│   │   └── ...
│   │
│   └── Providers/
│       └── AppServiceProvider.php # Theme settings shared
│
├── database/
│   ├── migrations/
│   │   ├── *_create_permission_tables.php
│   │   ├── *_create_theme_settings_table.php
│   │   └── ...
│   │
│   └── seeders/
│       ├── RolesAndPermissionsSeeder.php   # NEW
│       ├── AdminUserSeeder.php             # NEW
│       ├── ThemeContentSeeder.php
│       └── ...
│
├── resources/
│   └── views/
│       ├── admin/
│       │   ├── layouts/
│       │   │   └── sidebar.blade.php   # With roles menu
│       │   ├── roles/                   # NEW
│       │   │   ├── index.blade.php
│       │   │   ├── create.blade.php
│       │   │   └── edit.blade.php
│       │   ├── users/                   # NEW
│       │   │   ├── index.blade.php
│       │   │   ├── create.blade.php
│       │   │   └── edit.blade.php
│       │   ├── themes/
│       │   │   ├── index.blade.php
│       │   │   └── settings.blade.php  # Theme content editor
│       │   └── ...
│       │
│       ├── frontend/
│       │   └── layouts/
│       │       └── crafto.blade.php
│       │
│       ├── dashboard.blade.php          # Role-based dashboard
│       ├── home-business.blade.php
│       ├── home-corporate.blade.php
│       └── ...
│
├── routes/
│   └── web.php                          # All routes
│
└── public/
    └── crafto/
        └── demos/                       # Theme CSS files
            ├── business/
            ├── corporate/
            └── ...
```

### Key Files to Know

**Controllers**:
- `Admin/UserController.php` - User CRUD
- `Admin/RoleController.php` - Role & permission management
- `Admin/ThemeSettingController.php` - Theme content editor
- `Admin/DashboardController.php` - Admin dashboard
- `HomeController.php` - Frontend homepage

**Models**:
- `User.php` - Uses HasRoles trait
- `ThemeSetting.php` - Theme content storage

**Views**:
- `admin/roles/*` - Role management UI
- `admin/users/*` - User management UI
- `admin/themes/settings.blade.php` - Theme content editor
- `dashboard.blade.php` - User dashboard

**Configuration**:
- `routes/web.php` - All routes defined here
- `app/Providers/AppServiceProvider.php` - Shares theme settings

---

## Next Steps

See [NEXT_DEVELOPMENT_ROADMAP.md](NEXT_DEVELOPMENT_ROADMAP.md) for:
- Phase 1: Security & Testing
- Phase 2: Author Ownership
- Phase 3: Dashboard Statistics
- Phase 4: SEO Optimization
- Phase 5: Contact Form
- And more...

---

## Documentation Files

- **ROLES_IMPLEMENTATION_COMPLETE.md** - Complete roles system documentation
- **ROLES_PERMISSIONS_PLAN.md** - Original planning document
- **NEXT_DEVELOPMENT_ROADMAP.md** - Future development plans
- **PROJECT_TIMELINE.md** - Project history
- **PROGRESS.md** - Current progress tracking

---

## Support

### Common Checks

```bash
# Check Laravel version
php artisan --version

# Check installed packages
composer show

# Check routes
php artisan route:list

# Check permissions
php artisan tinker
>>> auth()->user()->getAllPermissions()
>>> auth()->user()->roles
```

### Useful Links

- Laravel Docs: https://laravel.com/docs
- Spatie Permission: https://spatie.be/docs/laravel-permission
- Crafto Theme: Already integrated

---

**Happy Coding! 🚀**
