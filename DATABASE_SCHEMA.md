# Database Schema - SimpleCMS

## Overview
Database: MySQL 8.0+  
Character Set: utf8mb4  
Collation: utf8mb4_unicode_ci

---

## Table of Contents
1. [Users & Authentication](#1-users--authentication)
2. [Pages & Sections](#2-pages--sections)
3. [Posts & Categories](#3-posts--categories)
4. [Menus](#4-menus)
5. [Themes](#5-themes)
6. [Media](#6-media)
7. [Settings](#7-settings)
8. [Relationships Diagram](#8-relationships-diagram)

---

## 1. Users & Authentication

### Table: `users`
Stores user accounts for admin panel access.

| Column | Type | Attributes | Description |
|--------|------|------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | User ID |
| name | VARCHAR(255) | NOT NULL | Full name |
| email | VARCHAR(255) | NOT NULL, UNIQUE | Email address |
| email_verified_at | TIMESTAMP | NULLABLE | Email verification timestamp |
| password | VARCHAR(255) | NOT NULL | Hashed password |
| remember_token | VARCHAR(100) | NULLABLE | Remember me token |
| created_at | TIMESTAMP | NULLABLE | Creation timestamp |
| updated_at | TIMESTAMP | NULLABLE | Last update timestamp |

**Indexes:**
- PRIMARY KEY (id)
- UNIQUE INDEX (email)

**Initial Data (Seeder):**
```sql
INSERT INTO users (name, email, password) VALUES
('Admin', 'admin@simplecms.local', '$2y$10$...');  -- password: admin123
```

---

### Table: `roles`
User roles (from Spatie Permission package).

| Column | Type | Attributes | Description |
|--------|------|------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Role ID |
| name | VARCHAR(255) | NOT NULL | Role name (admin, editor, viewer) |
| guard_name | VARCHAR(255) | NOT NULL | Guard name (web) |
| created_at | TIMESTAMP | NULLABLE | Creation timestamp |
| updated_at | TIMESTAMP | NULLABLE | Last update timestamp |

**Initial Data:**
```sql
INSERT INTO roles (name, guard_name) VALUES
('admin', 'web'),
('editor', 'web'),
('viewer', 'web');
```

---

### Table: `permissions`
Permission definitions.

| Column | Type | Attributes | Description |
|--------|------|------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Permission ID |
| name | VARCHAR(255) | NOT NULL | Permission name |
| guard_name | VARCHAR(255) | NOT NULL | Guard name (web) |
| created_at | TIMESTAMP | NULLABLE | Creation timestamp |
| updated_at | TIMESTAMP | NULLABLE | Last update timestamp |

**Initial Permissions:**
- manage-pages
- manage-posts
- manage-menus
- manage-themes
- manage-settings
- manage-users

---

### Table: `model_has_roles`
Pivot table for user-role relationships.

| Column | Type | Attributes | Description |
|--------|------|------------|-------------|
| role_id | BIGINT UNSIGNED | NOT NULL | Role ID |
| model_type | VARCHAR(255) | NOT NULL | Model class (App\Models\User) |
| model_id | BIGINT UNSIGNED | NOT NULL | User ID |

**Indexes:**
- PRIMARY KEY (role_id, model_id, model_type)
- INDEX (model_id, model_type)

---

## 2. Pages & Sections

### Table: `pages`
Stores custom pages created by users.

| Column | Type | Attributes | Description |
|--------|------|------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Page ID |
| title | VARCHAR(255) | NOT NULL | Page title |
| slug | VARCHAR(255) | NOT NULL, UNIQUE | URL slug |
| meta_description | TEXT | NULLABLE | SEO meta description |
| meta_keywords | VARCHAR(255) | NULLABLE | SEO keywords |
| is_published | BOOLEAN | DEFAULT 0 | Published status |
| is_homepage | BOOLEAN | DEFAULT 0 | Set as homepage |
| theme_id | BIGINT UNSIGNED | NULLABLE | Associated theme |
| created_by | BIGINT UNSIGNED | NULLABLE | User who created |
| created_at | TIMESTAMP | NULLABLE | Creation timestamp |
| updated_at | TIMESTAMP | NULLABLE | Last update timestamp |

**Indexes:**
- PRIMARY KEY (id)
- UNIQUE INDEX (slug)
- INDEX (is_published)
- INDEX (is_homepage)
- FOREIGN KEY (theme_id) REFERENCES themes(id) ON DELETE SET NULL
- FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL

**Migration Code:**
```php
Schema::create('pages', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('slug')->unique();
    $table->text('meta_description')->nullable();
    $table->string('meta_keywords')->nullable();
    $table->boolean('is_published')->default(false);
    $table->boolean('is_homepage')->default(false);
    $table->foreignId('theme_id')->nullable()->constrained()->onDelete('set null');
    $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
    $table->timestamps();
    
    $table->index('is_published');
    $table->index('is_homepage');
});
```

---

### Table: `section_templates`
Predefined section templates available for use.

| Column | Type | Attributes | Description |
|--------|------|------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Template ID |
| name | VARCHAR(255) | NOT NULL | Template name (Hero Style 1) |
| category | VARCHAR(100) | NOT NULL | Category (hero, about, features, etc) |
| blade_view | VARCHAR(255) | NOT NULL | Blade component name (hero-1) |
| thumbnail | VARCHAR(255) | NULLABLE | Preview thumbnail path |
| default_fields | JSON | NOT NULL | Default field structure |
| is_active | BOOLEAN | DEFAULT 1 | Active status |
| created_at | TIMESTAMP | NULLABLE | Creation timestamp |
| updated_at | TIMESTAMP | NULLABLE | Last update timestamp |

**Indexes:**
- PRIMARY KEY (id)
- INDEX (category)
- INDEX (is_active)

**Example default_fields JSON:**
```json
{
    "heading": "Your Amazing Heading Here",
    "subheading": "Supporting text that describes your service or product",
    "button_text": "Get Started",
    "button_link": "#",
    "image": "/assets/default-images/hero-placeholder.jpg",
    "background_color": "#ffffff",
    "text_color": "#333333"
}
```

**Migration Code:**
```php
Schema::create('section_templates', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('category', 100);
    $table->string('blade_view');
    $table->string('thumbnail')->nullable();
    $table->json('default_fields');
    $table->boolean('is_active')->default(true);
    $table->timestamps();
    
    $table->index('category');
    $table->index('is_active');
});
```

**Seeder Data Examples:**
```sql
INSERT INTO section_templates (name, category, blade_view, thumbnail, default_fields) VALUES
('Hero Style 1', 'hero', 'hero-1', '/assets/thumbnails/hero-1.jpg', '{"heading":"Welcome","subheading":"Subtitle","button_text":"Learn More","button_link":"#","image":"/assets/default-images/hero.jpg"}'),
('About Us Style 1', 'about', 'about-1', '/assets/thumbnails/about-1.jpg', '{"heading":"About Us","content":"Company description","image":"/assets/default-images/about.jpg"}'),
('Features 3 Column', 'features', 'features-1', '/assets/thumbnails/features-1.jpg', '{"heading":"Our Features","features":[{"icon":"fa-star","title":"Feature 1","description":"Description"},{"icon":"fa-heart","title":"Feature 2","description":"Description"},{"icon":"fa-bolt","title":"Feature 3","description":"Description"}]}');
```

---

### Table: `page_sections`
Stores actual sections used in pages (pivot + content).

| Column | Type | Attributes | Description |
|--------|------|------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Section instance ID |
| page_id | BIGINT UNSIGNED | NOT NULL | Parent page |
| section_template_id | BIGINT UNSIGNED | NOT NULL | Template used |
| order | INTEGER | DEFAULT 0 | Display order (0=top) |
| content | JSON | NOT NULL | Customized content |
| is_visible | BOOLEAN | DEFAULT 1 | Visibility toggle |
| created_at | TIMESTAMP | NULLABLE | Creation timestamp |
| updated_at | TIMESTAMP | NULLABLE | Last update timestamp |

**Indexes:**
- PRIMARY KEY (id)
- INDEX (page_id, order)
- INDEX (is_visible)
- FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE
- FOREIGN KEY (section_template_id) REFERENCES section_templates(id) ON DELETE CASCADE

**Migration Code:**
```php
Schema::create('page_sections', function (Blueprint $table) {
    $table->id();
    $table->foreignId('page_id')->constrained()->onDelete('cascade');
    $table->foreignId('section_template_id')->constrained()->onDelete('cascade');
    $table->integer('order')->default(0);
    $table->json('content');
    $table->boolean('is_visible')->default(true);
    $table->timestamps();
    
    $table->index(['page_id', 'order']);
    $table->index('is_visible');
});
```

**Example content JSON:**
```json
{
    "heading": "Custom Heading for This Page",
    "subheading": "Modified subtitle",
    "button_text": "Contact Us",
    "button_link": "/contact",
    "image": "/uploads/images/custom-hero.jpg",
    "background_color": "#0066cc"
}
```

---

## 3. Posts & Categories

### Table: `categories`
Blog post categories.

| Column | Type | Attributes | Description |
|--------|------|------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Category ID |
| name | VARCHAR(255) | NOT NULL | Category name |
| slug | VARCHAR(255) | NOT NULL, UNIQUE | URL slug |
| description | TEXT | NULLABLE | Category description |
| created_at | TIMESTAMP | NULLABLE | Creation timestamp |
| updated_at | TIMESTAMP | NULLABLE | Last update timestamp |

**Indexes:**
- PRIMARY KEY (id)
- UNIQUE INDEX (slug)

**Migration Code:**
```php
Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->timestamps();
});
```

---

### Table: `posts`
Blog posts/articles.

| Column | Type | Attributes | Description |
|--------|------|------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Post ID |
| title | VARCHAR(255) | NOT NULL | Post title |
| slug | VARCHAR(255) | NOT NULL, UNIQUE | URL slug |
| excerpt | TEXT | NULLABLE | Short excerpt |
| content | LONGTEXT | NOT NULL | Post content (HTML) |
| featured_image | VARCHAR(255) | NULLABLE | Featured image path |
| category_id | BIGINT UNSIGNED | NULLABLE | Category |
| author_id | BIGINT UNSIGNED | NULLABLE | Author (user) |
| is_published | BOOLEAN | DEFAULT 0 | Published status |
| published_at | TIMESTAMP | NULLABLE | Publish date/time |
| meta_description | TEXT | NULLABLE | SEO meta description |
| meta_keywords | VARCHAR(255) | NULLABLE | SEO keywords |
| views_count | INTEGER | DEFAULT 0 | View counter |
| created_at | TIMESTAMP | NULLABLE | Creation timestamp |
| updated_at | TIMESTAMP | NULLABLE | Last update timestamp |

**Indexes:**
- PRIMARY KEY (id)
- UNIQUE INDEX (slug)
- INDEX (is_published)
- INDEX (published_at)
- INDEX (category_id)
- FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
- FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL

**Migration Code:**
```php
Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('slug')->unique();
    $table->text('excerpt')->nullable();
    $table->longText('content');
    $table->string('featured_image')->nullable();
    $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
    $table->foreignId('author_id')->nullable()->constrained('users')->onDelete('set null');
    $table->boolean('is_published')->default(false);
    $table->timestamp('published_at')->nullable();
    $table->text('meta_description')->nullable();
    $table->string('meta_keywords')->nullable();
    $table->integer('views_count')->default(0);
    $table->timestamps();
    
    $table->index('is_published');
    $table->index('published_at');
});
```

---

## 4. Menus

### Table: `menus`
Menu definitions.

| Column | Type | Attributes | Description |
|--------|------|------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Menu ID |
| name | VARCHAR(255) | NOT NULL | Menu name (Header Menu, Footer Menu) |
| location | VARCHAR(100) | NOT NULL | Location identifier (header, footer) |
| is_active | BOOLEAN | DEFAULT 1 | Active status |
| created_at | TIMESTAMP | NULLABLE | Creation timestamp |
| updated_at | TIMESTAMP | NULLABLE | Last update timestamp |

**Indexes:**
- PRIMARY KEY (id)
- INDEX (location)
- INDEX (is_active)

**Migration Code:**
```php
Schema::create('menus', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('location', 100);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
    
    $table->index('location');
    $table->index('is_active');
});
```

---

### Table: `menu_items`
Individual menu items with hierarchy support.

| Column | Type | Attributes | Description |
|--------|------|------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Menu item ID |
| menu_id | BIGINT UNSIGNED | NOT NULL | Parent menu |
| parent_id | BIGINT UNSIGNED | NULLABLE | Parent item (for nesting) |
| title | VARCHAR(255) | NOT NULL | Display text |
| url | VARCHAR(255) | NULLABLE | Custom URL |
| page_id | BIGINT UNSIGNED | NULLABLE | Link to page |
| post_id | BIGINT UNSIGNED | NULLABLE | Link to post |
| type | ENUM | NOT NULL | Type: page, post, custom, category |
| target | ENUM | DEFAULT '_self' | Link target: _self, _blank |
| order | INTEGER | DEFAULT 0 | Display order |
| css_class | VARCHAR(100) | NULLABLE | Custom CSS class |
| created_at | TIMESTAMP | NULLABLE | Creation timestamp |
| updated_at | TIMESTAMP | NULLABLE | Last update timestamp |

**Indexes:**
- PRIMARY KEY (id)
- INDEX (menu_id, order)
- INDEX (parent_id)
- FOREIGN KEY (menu_id) REFERENCES menus(id) ON DELETE CASCADE
- FOREIGN KEY (parent_id) REFERENCES menu_items(id) ON DELETE CASCADE
- FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE
- FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE

**Migration Code:**
```php
Schema::create('menu_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('menu_id')->constrained()->onDelete('cascade');
    $table->foreignId('parent_id')->nullable()->constrained('menu_items')->onDelete('cascade');
    $table->string('title');
    $table->string('url')->nullable();
    $table->foreignId('page_id')->nullable()->constrained()->onDelete('cascade');
    $table->foreignId('post_id')->nullable()->constrained()->onDelete('cascade');
    $table->enum('type', ['page', 'post', 'custom', 'category'])->default('custom');
    $table->enum('target', ['_self', '_blank'])->default('_self');
    $table->integer('order')->default(0);
    $table->string('css_class', 100)->nullable();
    $table->timestamps();
    
    $table->index(['menu_id', 'order']);
    $table->index('parent_id');
});
```

---

## 5. Themes

### Table: `themes`
Available themes.

| Column | Type | Attributes | Description |
|--------|------|------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Theme ID |
| name | VARCHAR(255) | NOT NULL | Theme name |
| slug | VARCHAR(255) | NOT NULL, UNIQUE | Theme identifier |
| description | TEXT | NULLABLE | Theme description |
| thumbnail | VARCHAR(255) | NULLABLE | Preview image |
| author | VARCHAR(255) | NULLABLE | Theme author |
| version | VARCHAR(50) | DEFAULT '1.0.0' | Theme version |
| is_active | BOOLEAN | DEFAULT 0 | Currently active theme |
| created_at | TIMESTAMP | NULLABLE | Creation timestamp |
| updated_at | TIMESTAMP | NULLABLE | Last update timestamp |

**Indexes:**
- PRIMARY KEY (id)
- UNIQUE INDEX (slug)
- INDEX (is_active)

**Migration Code:**
```php
Schema::create('themes', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->string('thumbnail')->nullable();
    $table->string('author')->nullable();
    $table->string('version', 50)->default('1.0.0');
    $table->boolean('is_active')->default(false);
    $table->timestamps();
    
    $table->index('is_active');
});
```

**Seeder Data:**
```sql
INSERT INTO themes (name, slug, description, author, is_active) VALUES
('Default Theme', 'default', 'Clean and minimal general-purpose theme', 'SimpleCMS Team', 1),
('Business Theme', 'business', 'Professional corporate theme', 'SimpleCMS Team', 0),
('Portfolio Theme', 'portfolio', 'Creative portfolio and agency theme', 'SimpleCMS Team', 0);
```

---

## 6. Media

### Table: `media`
Media library for uploaded files.

| Column | Type | Attributes | Description |
|--------|------|------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Media ID |
| filename | VARCHAR(255) | NOT NULL | Original filename |
| filepath | VARCHAR(255) | NOT NULL | Storage path |
| mime_type | VARCHAR(100) | NOT NULL | File MIME type |
| file_size | INTEGER | NOT NULL | File size in bytes |
| alt_text | VARCHAR(255) | NULLABLE | Alt text for images |
| title | VARCHAR(255) | NULLABLE | Media title |
| uploaded_by | BIGINT UNSIGNED | NULLABLE | User who uploaded |
| created_at | TIMESTAMP | NULLABLE | Upload timestamp |
| updated_at | TIMESTAMP | NULLABLE | Last update timestamp |

**Indexes:**
- PRIMARY KEY (id)
- INDEX (mime_type)
- INDEX (uploaded_by)
- FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL

**Migration Code:**
```php
Schema::create('media', function (Blueprint $table) {
    $table->id();
    $table->string('filename');
    $table->string('filepath');
    $table->string('mime_type', 100);
    $table->integer('file_size');
    $table->string('alt_text')->nullable();
    $table->string('title')->nullable();
    $table->foreignId('uploaded_by')->nullable()->constrained('users')->onDelete('set null');
    $table->timestamps();
    
    $table->index('mime_type');
});
```

---

## 7. Settings

### Table: `settings`
Global site settings (key-value pairs).

| Column | Type | Attributes | Description |
|--------|------|------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Setting ID |
| key | VARCHAR(255) | NOT NULL, UNIQUE | Setting key |
| value | TEXT | NULLABLE | Setting value |
| type | VARCHAR(50) | DEFAULT 'text' | Data type (text, boolean, json, etc) |
| group | VARCHAR(100) | DEFAULT 'general' | Setting group |
| created_at | TIMESTAMP | NULLABLE | Creation timestamp |
| updated_at | TIMESTAMP | NULLABLE | Last update timestamp |

**Indexes:**
- PRIMARY KEY (id)
- UNIQUE INDEX (key)
- INDEX (group)

**Migration Code:**
```php
Schema::create('settings', function (Blueprint $table) {
    $table->id();
    $table->string('key')->unique();
    $table->text('value')->nullable();
    $table->string('type', 50)->default('text');
    $table->string('group', 100)->default('general');
    $table->timestamps();
    
    $table->index('group');
});
```

**Seeder Data:**
```sql
INSERT INTO settings (`key`, `value`, `type`, `group`) VALUES
('site_name', 'SimpleCMS', 'text', 'general'),
('site_tagline', 'Easy Content Management', 'text', 'general'),
('site_logo', '/assets/logo.png', 'text', 'general'),
('site_favicon', '/assets/favicon.ico', 'text', 'general'),
('contact_email', 'info@example.com', 'text', 'contact'),
('contact_phone', '+62 812-3456-7890', 'text', 'contact'),
('contact_address', 'Jakarta, Indonesia', 'text', 'contact'),
('social_facebook', 'https://facebook.com/yourpage', 'text', 'social'),
('social_instagram', 'https://instagram.com/yourpage', 'text', 'social'),
('social_twitter', 'https://twitter.com/yourpage', 'text', 'social'),
('social_whatsapp', '+6281234567890', 'text', 'social'),
('meta_description', 'Default meta description', 'text', 'seo'),
('meta_keywords', 'cms, website, easy', 'text', 'seo'),
('google_analytics', '', 'text', 'seo'),
('items_per_page', '10', 'number', 'general'),
('timezone', 'Asia/Jakarta', 'text', 'general');
```

---

## 8. Relationships Diagram

```
┌──────────────────┐
│     users        │
└────────┬─────────┘
         │
         ├─────────────────────────────┐
         │                             │
         ▼                             ▼
┌──────────────────┐          ┌──────────────────┐
│      pages       │          │      posts       │
│  - created_by    │          │  - author_id     │
└────────┬─────────┘          └────────┬─────────┘
         │                             │
         │                             ▼
         │                    ┌──────────────────┐
         │                    │   categories     │
         │                    └──────────────────┘
         │
         ▼
┌──────────────────┐
│  page_sections   │◄───────┐
│  - page_id       │        │
│  - section_      │        │
│    template_id   │        │
└──────────────────┘        │
                            │
                   ┌────────┴────────┐
                   │ section_        │
                   │ templates       │
                   └─────────────────┘

┌──────────────────┐
│      menus       │
└────────┬─────────┘
         │
         ▼
┌──────────────────┐
│   menu_items     │
│  - menu_id       │
│  - parent_id     │
│  - page_id       │
│  - post_id       │
└──────────────────┘

┌──────────────────┐
│     themes       │
└────────┬─────────┘
         │
         │ (optional)
         ▼
┌──────────────────┐
│      pages       │
│  - theme_id      │
└──────────────────┘

┌──────────────────┐
│      media       │
│  - uploaded_by   │
└──────────────────┘
```

---

## 9. Sample Queries

### Get Page with All Sections (Ordered)
```sql
SELECT 
    p.id, p.title, p.slug,
    ps.id as section_id, ps.order, ps.content,
    st.name as section_name, st.blade_view
FROM pages p
LEFT JOIN page_sections ps ON p.id = ps.page_id
LEFT JOIN section_templates st ON ps.section_template_id = st.id
WHERE p.slug = 'about-us' AND p.is_published = 1
ORDER BY ps.order ASC;
```

### Get Menu with Items (Hierarchical)
```sql
SELECT 
    mi.*,
    p.slug as page_slug,
    po.slug as post_slug
FROM menu_items mi
LEFT JOIN pages p ON mi.page_id = p.id
LEFT JOIN posts po ON mi.post_id = po.id
WHERE mi.menu_id = 1 AND mi.parent_id IS NULL
ORDER BY mi.order ASC;
```

### Get Published Posts with Category
```sql
SELECT 
    p.*,
    c.name as category_name, c.slug as category_slug,
    u.name as author_name
FROM posts p
LEFT JOIN categories c ON p.category_id = c.id
LEFT JOIN users u ON p.author_id = u.id
WHERE p.is_published = 1 AND p.published_at <= NOW()
ORDER BY p.published_at DESC
LIMIT 10;
```

### Get All Settings by Group
```sql
SELECT `key`, `value`, `type`
FROM settings
WHERE `group` = 'general';
```

---

## 10. Database Optimization Tips

### Indexes Summary
Critical indexes for performance:
- `pages.slug` (UNIQUE)
- `pages.is_published`
- `posts.slug` (UNIQUE)
- `posts.is_published`
- `posts.published_at`
- `page_sections.page_id, order` (COMPOSITE)
- `menu_items.menu_id, order` (COMPOSITE)

### Query Optimization
1. Always use `WHERE is_published = 1` for frontend queries
2. Use `SELECT` with specific columns, avoid `SELECT *`
3. Use pagination for lists (`LIMIT` and `OFFSET`)
4. Use eager loading in Eloquent to prevent N+1 queries

### Maintenance
```sql
-- Optimize all tables
OPTIMIZE TABLE pages, posts, page_sections, menu_items;

-- Analyze table statistics
ANALYZE TABLE pages, posts;

-- Check table integrity
CHECK TABLE pages, posts, page_sections;
```

---

## 11. Backup & Restore

### Backup Command
```bash
# Full database backup
mysqldump -u root -p simplecms > backup_$(date +%Y%m%d_%H%M%S).sql

# Backup specific tables
mysqldump -u root -p simplecms pages page_sections posts > content_backup.sql
```

### Restore Command
```bash
mysql -u root -p simplecms < backup_20241030_120000.sql
```

---

## Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0 | 2025-10-30 | Initial database schema | Development Team |

---

**Last Updated:** October 30, 2025
