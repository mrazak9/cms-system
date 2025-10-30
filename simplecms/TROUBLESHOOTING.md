# SimpleCMS - Troubleshooting Guide

## ✅ Quick Fixes

### Homepage Error 500
**Problem:** Error 500 saat akses homepage

**Solution:**
```bash
cd simplecms
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### Login Page 404
**Problem:** `/login` tidak ditemukan

**Causes:**
- Auth routes tidak ter-load
- Route cache issue

**Solution:**
```bash
php artisan route:clear
php artisan route:list | grep login
```

Pastikan file `routes/auth.php` ada dan di-include di `routes/web.php`

### View Not Found
**Problem:** `View [frontend.xxx] not found`

**Solution:**
1. Check file ada di `resources/views/frontend/xxx.blade.php`
2. Clear view cache: `php artisan view:clear`
3. Check kapitalisasi nama file (case-sensitive di Linux)

### Database Connection Error
**Problem:** SQLSTATE[HY000] Connection refused

**Solution:**
1. Check MySQL/MariaDB running
2. Verify `.env` database credentials:
   ```
   DB_DATABASE=simplecms
   DB_USERNAME=root
   DB_PASSWORD=
   ```
3. Test connection: `php artisan tinker` → `DB::connection()->getPdo();`

### Migration Errors
**Problem:** Foreign key constraint fails

**Solution:**
Reset database (⚠️ destroys all data):
```bash
php artisan migrate:fresh --seed
```

### Storage/Media Not Working
**Problem:** Uploaded images tidak muncul

**Solution:**
```bash
php artisan storage:link
```

Check `public/storage` symlink exists

### Permission Errors
**Problem:** Unable to write to storage/logs

**Solution (Windows):**
1. Right-click `storage` folder → Properties
2. Security tab → Edit
3. Add "Users" with Full Control
4. Apply to all subfolders

### Admin Panel Access Denied
**Problem:** 403 Forbidden di `/admin`

**Causes:**
1. User tidak punya role admin
2. Middleware tidak ter-register

**Solution:**
```bash
php artisan tinker
>>> $user = \App\Models\User::first();
>>> $user->assignRole('admin');
```

### Clear All Caches
Run ini untuk clear semua cache:
```bash
php artisan optimize:clear
```

Atau satu per satu:
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear
```

## 🔍 Debugging

### Check Error Logs
```bash
# Windows
type storage\logs\laravel.log

# View last 50 lines
php -r "echo implode('', array_slice(file('storage/logs/laravel.log'), -50));"
```

### Enable Debug Mode
Edit `.env`:
```
APP_DEBUG=true
APP_ENV=local
```

### Check Routes
```bash
php artisan route:list
php artisan route:list --path=admin
php artisan route:list --path=blog
```

### Check Database Tables
```bash
php artisan tinker
>>> DB::select('SHOW TABLES');
>>> \App\Models\User::count();
>>> \App\Models\Page::count();
```

### Test Authentication
```bash
php artisan tinker
>>> $user = \App\Models\User::first();
>>> $user->email
>>> $user->roles
```

## 🚨 Common Errors & Solutions

### "Class 'App\Models\XXX' not found"
```bash
composer dump-autoload
php artisan config:clear
```

### "Target class [XXXController] does not exist"
Check namespace in controller and routes match

### "CSRF token mismatch"
1. Clear browser cookies
2. Clear Laravel cache
3. Check `APP_URL` in `.env` matches actual URL

### "Too few arguments to function"
Check method signatures in controllers match route definitions

### "Call to undefined method"
Check model relationships and scope methods exist

## 📋 Verification Checklist

### After Fresh Install
- [ ] `php artisan migrate` successful
- [ ] `php artisan db:seed` successful
- [ ] `php artisan storage:link` successful
- [ ] Can access homepage (/)
- [ ] Can access login (/login)
- [ ] Can login with admin credentials
- [ ] Can access admin panel (/admin)
- [ ] Can create a page
- [ ] Can upload media

### If Something Doesn't Work
1. ✅ Check error logs: `storage/logs/laravel.log`
2. ✅ Clear all caches: `php artisan optimize:clear`
3. ✅ Verify database connection
4. ✅ Check file permissions (storage, bootstrap/cache)
5. ✅ Verify `.env` configuration
6. ✅ Check routes exist: `php artisan route:list`

## 🔧 Advanced Troubleshooting

### Regenerate Autoload
```bash
composer dump-autoload
```

### Recreate Config Cache
```bash
php artisan config:cache
```

### Rebuild Route Cache
```bash
php artisan route:cache
```

### Reset Permissions (Laravel)
```bash
php artisan db:seed --class=RolesAndPermissionsSeeder
```

### Check PHP Version
```bash
php -v
# Should be 8.1 or higher
```

### Check Composer Version
```bash
composer --version
# Should be 2.x
```

### Verify All Packages Installed
```bash
composer install
npm install
```

## 📞 Still Having Issues?

### Gather Information
```bash
# System info
php artisan about

# Environment
php artisan env

# List all migrations
php artisan migrate:status
```

### Create Debug Report
```bash
echo "=== PHP Version ===" && php -v
echo "=== Laravel Version ===" && php artisan --version
echo "=== Database Connection ===" && php artisan tinker --execute="DB::connection()->getPdo(); echo 'OK';"
echo "=== User Count ===" && php artisan tinker --execute="\App\Models\User::count()"
echo "=== Routes Count ===" && php artisan route:list --json | find /c /v ""
```

Save output dan share jika butuh bantuan.

## 🎯 Prevention Tips

1. Always clear cache after code changes
2. Use `php artisan serve` for development, not production
3. Keep `.env` file secure, never commit to git
4. Regular backups of database
5. Test in development before production
6. Keep Laravel and packages updated
7. Monitor error logs regularly

---

**Last Updated:** October 30, 2025
