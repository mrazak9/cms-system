# Technical Specification - SimpleCMS

## 1. Project Overview

### 1.1 Project Name
**SimpleCMS** - Content Management System yang mudah digunakan untuk non-technical users

### 1.2 Project Goals
- Membuat CMS yang lebih sederhana dan user-friendly dibanding WordPress
- Memungkinkan admin non-teknis untuk membuat dan mengelola website dengan mudah
- Menyediakan sistem section-based page builder dengan drag & drop
- Menyediakan template sections siap pakai yang dapat dicustomize

### 1.3 Target Users
- Admin non-teknis yang perlu mengelola content website
- Small business owners
- Marketing teams tanpa background teknis
- UMKM yang butuh website presence

---

## 2. Technical Stack

### 2.1 Core Technologies
- **PHP Version:** 8.1
- **Framework:** Laravel 10.x
- **Database:** MySQL 8.0+
- **Template Engine:** Blade
- **CSS Framework:** Bootstrap 5.3
- **Admin Template:** Stisla (Free Bootstrap Admin Template)

### 2.2 Frontend Dependencies
- **Bootstrap 5.3:** UI Framework untuk landing pages
- **SortableJS:** Drag & drop functionality
- **Alpine.js (Optional):** Reactive components
- **Font Awesome 6:** Icons
- **jQuery 3.x:** Required for Stisla components

### 2.3 Backend Dependencies (Composer)
```json
{
    "php": "^8.1",
    "laravel/framework": "^10.0",
    "laravel/tinker": "^2.8",
    "intervention/image": "^2.7",
    "spatie/laravel-permission": "^5.10"
}
```

### 2.4 Development Environment
- **OS:** Windows 10/11
- **Local Server:** Laragon / XAMPP / Laravel Herd
- **Code Editor:** VS Code
- **Version Control:** Git
- **Package Manager:** Composer 2.x, NPM 9.x

---

## 3. System Architecture

### 3.1 Application Structure
```
SimpleCMS/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── PageController.php
│   │   │   │   ├── PostController.php
│   │   │   │   ├── MenuController.php
│   │   │   │   ├── SectionTemplateController.php
│   │   │   │   ├── ThemeController.php
│   │   │   │   └── SettingController.php
│   │   │   └── Frontend/
│   │   │       ├── HomeController.php
│   │   │       ├── PageController.php
│   │   │       └── PostController.php
│   │   └── Middleware/
│   │       └── AdminMiddleware.php
│   ├── Models/
│   │   ├── Page.php
│   │   ├── PageSection.php
│   │   ├── SectionTemplate.php
│   │   ├── Post.php
│   │   ├── Category.php
│   │   ├── Menu.php
│   │   ├── MenuItem.php
│   │   ├── Theme.php
│   │   └── Setting.php
│   └── View/
│       └── Components/
│           └── Sections/
│               ├── Hero1.php
│               ├── Hero2.php
│               ├── About1.php
│               ├── Features1.php
│               └── ... (other sections)
├── resources/
│   ├── views/
│   │   ├── admin/
│   │   │   ├── layouts/
│   │   │   │   ├── app.blade.php
│   │   │   │   ├── sidebar.blade.php
│   │   │   │   └── navbar.blade.php
│   │   │   ├── dashboard.blade.php
│   │   │   ├── pages/
│   │   │   ├── posts/
│   │   │   ├── menus/
│   │   │   ├── sections/
│   │   │   └── themes/
│   │   ├── frontend/
│   │   │   ├── layouts/
│   │   │   │   ├── app.blade.php
│   │   │   │   ├── header.blade.php
│   │   │   │   └── footer.blade.php
│   │   │   ├── home.blade.php
│   │   │   ├── page.blade.php
│   │   │   └── post.blade.php
│   │   └── components/
│   │       └── sections/
│   │           ├── hero-1.blade.php
│   │           ├── hero-2.blade.php
│   │           ├── about-1.blade.php
│   │           └── ... (other section components)
│   └── css/
│       ├── admin.css
│       └── frontend.css
├── public/
│   ├── assets/
│   │   ├── admin/ (Stisla files)
│   │   └── frontend/
│   ├── uploads/
│   │   ├── images/
│   │   └── files/
│   └── themes/
│       ├── default/
│       ├── business/
│       └── portfolio/
└── database/
    ├── migrations/
    └── seeders/
```

### 3.2 Database Architecture
See `DATABASE_SCHEMA.md` for detailed database structure.

### 3.3 MVC Flow

**Admin Flow:**
```
User → Route → AdminMiddleware → Controller → Model → Database
                                      ↓
                                   Blade View (Stisla)
```

**Frontend Flow:**
```
User → Route → Controller → Model → Database
                  ↓
              Blade View (Custom Theme) → Section Components
```

---

## 4. Core Features & Modules

### 4.1 Authentication & User Management
- **Login/Logout:** Laravel Breeze/UI
- **Roles:** Admin, Editor, Viewer (using Spatie Permission)
- **Permissions:** Granular control per module

### 4.2 Dashboard (Admin)
- Total pages, posts, visitors statistics
- Recent activity log
- Quick actions (Create page, Create post)

### 4.3 Page Management
**Features:**
- Create/Read/Update/Delete pages
- Page meta (title, slug, meta description, meta keywords)
- Section-based builder
- Publish/Draft status
- Page preview before publish

**Page Builder Workflow:**
1. Create new page → Set title & slug
2. Click "Add Section" → Modal with section library
3. Choose section template → Added to page
4. Drag & drop to reorder sections
5. Click "Edit" on section → Edit content in modal/inline
6. Save & Preview
7. Publish

### 4.4 Section Template System
**Section Categories:**
- Hero Sections (5 templates)
- About Sections (3 templates)
- Features/Services (4 templates)
- Testimonials (2 templates)
- Pricing Tables (2 templates)
- Gallery/Portfolio (3 templates)
- Contact Forms (2 templates)
- CTA (Call-to-Action) (3 templates)
- Footer (3 templates)

**Section Structure:**
```php
[
    'id' => 1,
    'name' => 'Hero Style 1',
    'category' => 'hero',
    'blade_view' => 'hero-1',
    'thumbnail' => '/assets/section-thumbnails/hero-1.jpg',
    'default_fields' => [
        'heading' => 'Default Heading',
        'subheading' => 'Default subheading text',
        'button_text' => 'Learn More',
        'button_link' => '#',
        'image' => '/assets/default-images/hero.jpg',
        'background_color' => '#ffffff'
    ]
]
```

### 4.5 Post Management (Blog)
- Create/Edit/Delete posts
- Categories & Tags
- Featured image
- Excerpt & Content (WYSIWYG Editor)
- Publish date & status
- Post listing with pagination

### 4.6 Menu Builder
- Create custom menus
- Drag & drop menu items
- Add pages, posts, custom links
- Nested menu (max 2 levels)
- Multiple menu locations (Header, Footer, etc)

### 4.7 Theme System
**3 Main Themes:**
1. **Default Theme** - General purpose, clean design
2. **Business Theme** - Corporate/company profile
3. **Portfolio Theme** - Creative/agency portfolio

**Theme Structure:**
```
themes/
└── default/
    ├── css/
    │   └── theme.css
    ├── js/
    │   └── theme.js
    ├── layouts/
    │   ├── app.blade.php
    │   ├── header.blade.php
    │   └── footer.blade.php
    └── sections/
        ├── hero-1.blade.php
        └── ... (theme-specific section variants)
```

### 4.8 Settings
- **Site Settings:** Site name, tagline, logo, favicon
- **Contact Info:** Email, phone, address, social media links
- **SEO Settings:** Default meta tags, Google Analytics
- **Email Settings:** SMTP configuration
- **Appearance:** Active theme selection

### 4.9 Media Library
- Upload images/files
- Image optimization (Intervention/Image)
- Browse & select media
- Delete unused media

---

## 5. User Interface Specifications

### 5.1 Admin Panel (Stisla)
**Layout:**
- Left sidebar navigation
- Top navbar (user dropdown, notifications)
- Breadcrumb navigation
- Content area

**Color Scheme:**
- Primary: #6777ef (Stisla default blue)
- Success: #28a745
- Danger: #dc3545
- Warning: #ffc107

**Components:**
- Cards for content sections
- DataTables for listings
- Modal dialogs for forms
- Toast notifications for feedback

### 5.2 Frontend (Custom Themes)
**Responsive Design:**
- Mobile-first approach
- Breakpoints: 576px, 768px, 992px, 1200px

**Common Elements:**
- Sticky header
- Hero section with CTA
- Feature sections
- Footer with sitemap & contact

---

## 6. API Endpoints (Internal)

### 6.1 Admin Routes
```
GET     /admin                          - Dashboard
GET     /admin/pages                    - List pages
GET     /admin/pages/create             - Create page form
POST    /admin/pages                    - Store page
GET     /admin/pages/{id}/edit          - Edit page form
PUT     /admin/pages/{id}               - Update page
DELETE  /admin/pages/{id}               - Delete page
POST    /admin/pages/{id}/add-section   - Add section to page
PUT     /admin/pages/{id}/reorder       - Reorder sections
PUT     /admin/sections/{id}            - Update section content
DELETE  /admin/sections/{id}            - Delete section

GET     /admin/posts                    - List posts
POST    /admin/posts                    - Store post
GET     /admin/posts/{id}/edit          - Edit post
PUT     /admin/posts/{id}               - Update post

GET     /admin/menus                    - Menu builder
POST    /admin/menus                    - Save menu
PUT     /admin/menus/{id}/reorder       - Reorder menu items

GET     /admin/themes                   - Theme list
POST    /admin/themes/activate          - Activate theme

GET     /admin/settings                 - Settings page
PUT     /admin/settings                 - Update settings
```

### 6.2 Frontend Routes
```
GET     /                               - Homepage
GET     /{slug}                         - Dynamic page
GET     /blog                           - Blog listing
GET     /blog/{slug}                    - Single post
GET     /category/{slug}                - Posts by category
```

---

## 7. Database Performance

### 7.1 Indexes
- Primary keys on all tables
- Index on `slug` columns (pages, posts)
- Index on `order` column (page_sections, menu_items)
- Index on `is_published` (pages, posts)
- Foreign key indexes

### 7.2 Caching Strategy
- Cache active theme
- Cache menu structure
- Cache site settings
- Cache published pages (optional)

### 7.3 Query Optimization
- Eager loading relationships
- Select only needed columns
- Pagination for large datasets
- Use DB transactions for multi-table operations

---

## 8. Security Considerations

### 8.1 Authentication
- Password hashing (bcrypt)
- CSRF protection (Laravel built-in)
- Session management
- Remember me functionality

### 8.2 Authorization
- Role-based access control (Spatie Permission)
- Middleware protection for admin routes
- Policy-based authorization for resources

### 8.3 Input Validation
- Server-side validation (Laravel Validation)
- XSS protection (Blade auto-escaping)
- SQL injection prevention (Eloquent ORM)
- File upload validation (type, size, extension)

### 8.4 File Security
- Sanitize file names
- Store uploads outside public directory (use symlink)
- Validate image files
- Limit file upload size

---

## 9. Development Phases

### Phase 1: MVP (Minimum Viable Product) - 4 weeks
**Week 1:**
- Setup Laravel project
- Database migration & models
- Authentication & admin layout (Stisla)

**Week 2:**
- Page CRUD
- Basic section template system (5 sections)
- Section add/edit functionality

**Week 3:**
- Post CRUD
- Category management
- Simple menu builder

**Week 4:**
- Theme switcher (1 theme)
- Settings page
- Frontend rendering

### Phase 2: Enhancement - 3 weeks
**Week 5:**
- Drag & drop for sections & menus
- More section templates (15 total)
- Media library

**Week 6:**
- Additional 2 themes
- SEO features
- Preview functionality

**Week 7:**
- Performance optimization
- Security hardening
- Testing & bug fixes

### Phase 3: Polish - 2 weeks
**Week 8:**
- Documentation
- Admin user guide
- Code cleanup

**Week 9:**
- Final testing
- Deployment preparation
- Production launch

---

## 10. Testing Strategy

### 10.1 Unit Testing
- Model methods
- Helper functions
- Validation rules

### 10.2 Feature Testing
- CRUD operations
- Authentication flows
- File uploads
- Frontend rendering

### 10.3 Browser Testing
- Chrome, Firefox, Edge
- Mobile browsers (iOS Safari, Chrome Mobile)
- Responsive design validation

### 10.4 Manual Testing Checklist
- [ ] User registration/login
- [ ] Page creation workflow
- [ ] Section add/edit/delete
- [ ] Drag & drop functionality
- [ ] Post publishing
- [ ] Menu creation
- [ ] Theme switching
- [ ] Media upload
- [ ] Settings save
- [ ] Frontend display
- [ ] SEO meta tags
- [ ] Form submissions
- [ ] Error handling

---

## 11. Deployment Requirements

### 11.1 Server Requirements
- **PHP:** 8.1 or higher
- **Web Server:** Apache 2.4+ or Nginx 1.18+
- **Database:** MySQL 8.0+ or MariaDB 10.5+
- **RAM:** Minimum 512MB, Recommended 1GB
- **Storage:** Minimum 500MB

### 11.2 PHP Extensions Required
- OpenSSL
- PDO
- Mbstring
- Tokenizer
- XML
- Ctype
- JSON
- BCMath
- Fileinfo
- GD or Imagick

### 11.3 Deployment Steps
1. Clone repository to server
2. Run `composer install --optimize-autoloader --no-dev`
3. Copy `.env.example` to `.env`
4. Configure database credentials
5. Run `php artisan key:generate`
6. Run `php artisan migrate --seed`
7. Run `php artisan storage:link`
8. Set permissions: `storage/` and `bootstrap/cache/` to 775
9. Configure web server virtual host
10. Setup SSL certificate (Let's Encrypt)

### 11.4 Production Optimization
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

---

## 12. Maintenance & Updates

### 12.1 Backup Strategy
- Daily database backup
- Weekly full backup (files + database)
- Store backups off-site

### 12.2 Monitoring
- Server uptime monitoring
- Error log monitoring
- Performance metrics (page load time)
- Database size monitoring

### 12.3 Update Plan
- Monthly security updates
- Quarterly feature updates
- Laravel version upgrades annually

---

## 13. Success Metrics

### 13.1 Performance KPIs
- Page load time < 2 seconds
- Time to first byte < 500ms
- Admin panel response < 1 second

### 13.2 User Experience KPIs
- Average time to create a page < 10 minutes
- User satisfaction rating > 4/5
- Support ticket reduction vs WordPress

### 13.3 Technical KPIs
- Code coverage > 70%
- Zero critical security vulnerabilities
- 99.9% uptime

---

## 14. Future Enhancements (Post-MVP)

### 14.1 Advanced Features
- Multi-language support
- Advanced SEO tools (sitemap, robots.txt editor)
- Form builder with submissions
- Email templates
- Analytics dashboard
- A/B testing for sections

### 14.2 Integrations
- Google Analytics 4
- Google Search Console
- WhatsApp Business API
- Payment gateways (for e-commerce)
- Email marketing (Mailchimp, SendGrid)

### 14.3 Developer Features
- REST API for headless CMS
- Webhook system
- Custom field builder
- Plugin system

---

## 15. Documentation Deliverables

- [x] Technical Specification (this document)
- [x] Database Schema (DATABASE_SCHEMA.md)
- [ ] API Documentation
- [ ] User Manual (Admin)
- [ ] Developer Guide
- [ ] Deployment Guide
- [ ] Changelog

---

## 16. Team & Responsibilities

### 16.1 Development Team
- **Backend Developer:** Laravel development, database design
- **Frontend Developer:** Blade templates, section components
- **UI/UX Designer:** Theme design, admin interface
- **QA Tester:** Testing & bug reporting

### 16.2 Stakeholders
- **Product Owner:** Feature prioritization
- **Project Manager:** Timeline & resource management
- **End Users:** Feedback & testing

---

## 17. Risk Management

### 17.1 Technical Risks
| Risk | Impact | Mitigation |
|------|--------|------------|
| Database performance issues | High | Proper indexing, query optimization |
| Security vulnerabilities | Critical | Regular updates, security audits |
| Browser compatibility | Medium | Cross-browser testing |
| File storage limitations | Medium | Cloud storage integration (S3) |

### 17.2 Project Risks
| Risk | Impact | Mitigation |
|------|--------|------------|
| Scope creep | High | Strict MVP definition |
| Timeline delays | Medium | Buffer time in schedule |
| Resource unavailability | Medium | Cross-training team members |

---

## 18. License & Legal

### 18.1 Software License
- Open source: MIT License (recommended)
- Or proprietary license for commercial use

### 18.2 Third-party Licenses
- Laravel: MIT License
- Bootstrap: MIT License
- Stisla: MIT License
- Font Awesome: CC BY 4.0 & Font Awesome Free License

### 18.3 Terms of Use
- Define acceptable use policy
- Data privacy policy (GDPR compliance if applicable)
- Content ownership terms

---

## 19. Glossary

- **CMS:** Content Management System
- **CRUD:** Create, Read, Update, Delete
- **MVP:** Minimum Viable Product
- **Section:** Reusable content block with specific layout
- **Page Builder:** Tool for constructing pages from sections
- **Slug:** URL-friendly version of a title
- **Blade:** Laravel's templating engine
- **Eloquent:** Laravel's ORM (Object-Relational Mapping)
- **Migration:** Database version control
- **Seeder:** Database initial data population

---

## Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0 | 2025-10-30 | Initial technical specification | Development Team |

---

**Document Status:** Draft  
**Last Updated:** October 30, 2025  
**Next Review:** November 13, 2025
