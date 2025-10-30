# SimpleCMS - Getting Started Guide

## 🎉 Welcome to SimpleCMS!

Your SimpleCMS installation is now complete! This guide will help you get started.

---

## 📋 What's Been Set Up

✅ **Laravel 10.49.1** - Modern PHP framework
✅ **Database Structure** - All tables migrated
✅ **Initial Data** - Roles, permissions, admin user, templates, themes, settings
✅ **Admin Panel** - Full-featured admin interface with Stisla template
✅ **Frontend** - Beautiful, responsive frontend views
✅ **Authentication** - Laravel Breeze with role-based access
✅ **File Management** - Media library with upload support
✅ **Page Builder** - Section-based page building system
✅ **Blog System** - Posts with categories and tags
✅ **Menu Builder** - Hierarchical menu management
✅ **Theme System** - Multiple theme support
✅ **Settings** - Global site settings management

---

## 🚀 Quick Start

### 1. Start the Development Server

```bash
cd simplecms
php artisan serve
```

Your site will be available at: **http://localhost:8000**

### 2. Access the Admin Panel

**URL:** http://localhost:8000/login

**Default Credentials:**
- Email: `admin@simplecms.test`
- Password: `admin123`

⚠️ **Important:** Change this password immediately after first login!

### 3. Visit the Frontend

**URL:** http://localhost:8000

This is your public-facing website.

---

## 📁 Project Structure

```
simplecms/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin panel controllers
│   │   │   └── Frontend/       # Public website controllers
│   │   └── Middleware/
│   │       └── Admin.php       # Admin access middleware
│   └── Models/                 # All database models
├── database/
│   ├── migrations/             # Database structure
│   └── seeders/                # Initial data
├── public/
│   ├── storage/                # Symlinked to storage/app/public
│   └── uploads/                # User uploads
├── resources/
│   └── views/
│       ├── admin/              # Admin panel views
│       └── frontend/           # Public website views
└── routes/
    └── web.php                 # All application routes
```

---

## 🎨 Admin Panel Features

### Dashboard
- Statistics overview (pages, posts, users, media)
- Recent activity
- Quick actions

### Pages Management
- Create/Edit/Delete pages
- Section-based page builder
- SEO settings per page
- Set homepage
- Theme selection

### Posts & Categories
- Full blog management
- Featured images
- Category organization
- SEO optimization
- View counter

### Menus
- Create multiple menus
- Hierarchical structure (parent-child)
- Link to pages, posts, categories, or custom URLs
- Drag & drop ordering

### Themes
- Switch between themes
- Default, Business, and Creative themes included

### Media Library
- Upload images and files
- Organize media
- Alt text and titles
- Search and filter

### Settings
Groups:
- General (site name, tagline, logo)
- Contact (email, phone, address)
- Social (Facebook, Twitter, Instagram, etc.)
- SEO (meta tags, analytics)
- Email configuration
- Maintenance mode

---

## 🎯 Common Tasks

### Creating Your First Page

1. Login to admin panel
2. Go to **Pages > Create New**
3. Enter page title (slug auto-generates)
4. Add sections using the section templates
5. Configure each section's content
6. Check "Is Published"
7. Click **Save**

### Creating a Blog Post

1. Go to **Posts > Create New**
2. Fill in title, content, and excerpt
3. Select a category
4. Upload featured image
5. Set SEO fields
6. Check "Is Published"
7. Set publish date
8. Click **Publish**

### Managing Navigation Menus

1. Go to **Menus**
2. Edit your primary menu
3. Add menu items:
   - Select type (Page, Post, Category, Custom)
   - Choose the item or enter URL
   - Set title and order
   - Save

### Changing Site Settings

1. Go to **Settings**
2. Update fields in each group
3. Click **Save Settings**
4. Changes apply immediately

### Uploading Media

1. Go to **Media Library**
2. Click **Upload Files**
3. Select one or more files
4. Add alt text and titles
5. Media is now available for use

---

## 🔒 User Roles & Permissions

### Admin Role
Full access to everything:
- Manage pages, posts, menus
- Change themes
- Modify settings
- Manage users
- Access media library

### Editor Role (Future)
Limited access:
- Create/edit pages and posts
- Upload media
- Cannot change settings or themes

### Viewer Role (Future)
Read-only access to admin panel

---

## 🎨 Section Templates Available

### Hero Sections
- **Hero 1** - Full width hero with background
- **Hero 2** - Hero with video background

### About Sections
- **About 1** - Two column with image
- **About 2** - Single column text

### Features
- **Features 1** - 3 column grid with icons
- **Features 2** - Icon list layout

### Other Sections
- **Services** - Service cards
- **Testimonials** - Customer testimonials carousel
- **Contact** - Contact form with info
- **Gallery** - Image gallery grid
- **CTA** - Call to action banner
- **Stats** - Statistics counter
- **FAQ** - Accordion-style FAQs

Each section has customizable content (headings, images, buttons, colors, etc.)

---

## 🌐 Frontend Features

### Homepage
- Dynamic content based on "Is Homepage" page
- Latest blog posts
- Featured content
- Category listing

### Blog
- Post listing with pagination
- Category filtering
- Single post view with related posts
- Author information
- Social sharing buttons

### Pages
- Section-based rendering
- SEO optimized
- Breadcrumb navigation
- Responsive design

### Navigation
- Dynamic menus from admin
- Mobile responsive
- Hierarchical dropdowns

---

## ⚙️ Configuration

### Environment Variables

Key settings in `.env`:

```env
APP_NAME=SimpleCMS
APP_URL=http://localhost:8000

DB_DATABASE=simplecms
DB_USERNAME=root
DB_PASSWORD=

MAIL_FROM_ADDRESS=noreply@simplecms.test
```

### Changing Site Name

1. Admin: **Settings > General > Site Name**
2. Or update `.env`: `APP_NAME=YourSiteName`

### Changing Admin Email

1. Go to **Users** (when implemented)
2. Or update directly in database `users` table

---

## 🔧 Troubleshooting

### Can't Login to Admin
- Check credentials: `admin@simplecms.test` / `admin123`
- Verify database has users and roles
- Run: `php artisan db:seed --class=AdminUserSeeder`

### Page Not Found (404)
- Clear route cache: `php artisan route:clear`
- Check if route exists: `php artisan route:list`

### Images Not Showing
- Verify storage link: `php artisan storage:link`
- Check file permissions on `storage/` folder

### Cache Issues
Clear all caches:
```bash
php artisan optimize:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Database Errors
Reset database (⚠️ destroys all data):
```bash
php artisan migrate:fresh --seed
```

---

## 📱 Testing

### Admin Panel
1. ✅ Login with admin credentials
2. ✅ Create a test page
3. ✅ Create a test blog post
4. ✅ Upload an image
5. ✅ Change site settings
6. ✅ Create a menu

### Frontend
1. ✅ Visit homepage
2. ✅ View a page you created
3. ✅ Browse blog posts
4. ✅ Test navigation menu
5. ✅ Check mobile responsiveness

---

## 🚀 Next Steps

### Immediate Actions
1. ✅ Change admin password
2. ✅ Update site name and settings
3. ✅ Create your homepage
4. ✅ Add your first blog post
5. ✅ Configure navigation menu
6. ✅ Upload your logo

### Content Creation
1. Plan your site structure
2. Create main pages (About, Contact, Services, etc.)
3. Write blog posts
4. Upload images and media
5. Configure SEO settings

### Customization
1. Choose or customize a theme
2. Add custom CSS if needed
3. Configure social media links
4. Set up email settings
5. Add Google Analytics

### Going Live
1. Update `.env` for production
2. Set `APP_ENV=production`
3. Set `APP_DEBUG=false`
4. Configure proper database
5. Set up backup system
6. Configure mail server
7. Set up SSL certificate

---

## 📚 Documentation Reference

- **INDEX.md** - Documentation navigation
- **README.md** - Project overview
- **QUICKSTART.md** - Setup checklist
- **SETUP_GUIDE.md** - Installation guide
- **DATABASE_SCHEMA.md** - Database structure
- **API_DOCUMENTATION.md** - Routes reference
- **DEVELOPMENT_GUIDE.md** - Coding standards
- **PROJECT_TIMELINE.md** - Development timeline
- **TECH_SPEC.md** - Technical specifications

---

## 🆘 Support

### Resources
- Laravel Docs: https://laravel.com/docs/10.x
- Stack Overflow: https://stackoverflow.com/questions/tagged/laravel
- Stisla Docs: https://getstisla.com/docs.html

### Common Commands

```bash
# Development
php artisan serve              # Start server
php artisan migrate            # Run migrations
php artisan db:seed            # Seed database
php artisan tinker             # Interactive console

# Cache Management
php artisan cache:clear        # Clear cache
php artisan config:clear       # Clear config
php artisan route:clear        # Clear routes
php artisan view:clear         # Clear compiled views

# Code Generation
php artisan make:controller    # Create controller
php artisan make:model         # Create model
php artisan make:migration     # Create migration

# Maintenance
php artisan down               # Enable maintenance mode
php artisan up                 # Disable maintenance mode
php artisan optimize           # Optimize for production
```

---

## ✨ Features Roadmap

### Completed ✅
- Page builder with sections
- Blog system with categories
- Menu builder
- Theme system
- Media library
- User authentication
- Role-based access control
- Settings management
- SEO optimization

### Coming Soon 🚧
- Comment system
- User management interface
- Advanced permissions
- Widget system
- Contact form submissions
- Email templates
- Advanced search
- Multi-language support
- Custom post types

---

## 🎊 Congratulations!

You now have a fully functional CMS! Start creating content and building your website.

**Happy Building! 💻🚀**

---

**Version:** 1.0.0
**Last Updated:** October 30, 2025
**Developed with:** Laravel 10, Bootstrap 5, Stisla Admin Template
