# API Documentation - SimpleCMS

## Internal Routes & Endpoints Reference

**Version:** 1.0  
**Base URL:** `http://simplecms.test` or `http://localhost:8000`

---

## Table of Contents
1. [Authentication](#1-authentication)
2. [Admin - Pages](#2-admin---pages)
3. [Admin - Sections](#3-admin---sections)
4. [Admin - Posts](#4-admin---posts)
5. [Admin - Menus](#5-admin---menus)
6. [Admin - Themes](#6-admin---themes)
7. [Admin - Settings](#7-admin---settings)
8. [Frontend Routes](#8-frontend-routes)

---

## 1. Authentication

### Login

**Endpoint:** `POST /login`

**Request Body:**
```json
{
    "email": "admin@simplecms.test",
    "password": "admin123",
    "remember": true
}
```

**Response (Success):**
```
HTTP 302 Redirect to /admin
```

**Response (Error):**
```json
{
    "message": "These credentials do not match our records.",
    "errors": {
        "email": ["These credentials do not match our records."]
    }
}
```

---

### Logout

**Endpoint:** `POST /logout`

**Headers:**
```
X-CSRF-TOKEN: {token}
```

**Response:**
```
HTTP 302 Redirect to /login
```

---

## 2. Admin - Pages

### List All Pages

**Endpoint:** `GET /admin/pages`

**Headers:**
```
Cookie: laravel_session={session_token}
```

**Query Parameters:**
- `page` (optional): Page number for pagination
- `per_page` (optional): Items per page (default: 15)

**Response (Success):**
```html
Blade View: admin.pages.index
```

**Data Available in View:**
```php
$pages = [
    'data' => [...],
    'current_page' => 1,
    'per_page' => 15,
    'total' => 50
]
```

---

### Create Page Form

**Endpoint:** `GET /admin/pages/create`

**Response:**
```html
Blade View: admin.pages.create
```

---

### Store New Page

**Endpoint:** `POST /admin/pages`

**Headers:**
```
Content-Type: application/x-www-form-urlencoded
X-CSRF-TOKEN: {token}
```

**Request Body:**
```
title=About Us
slug=about-us
meta_description=Learn more about our company
meta_keywords=about, company, team
is_published=1
is_homepage=0
```

**Response (Success):**
```
HTTP 302 Redirect to /admin/pages/{id}/edit
Flash Message: "Page created successfully!"
```

**Response (Validation Error):**
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "title": ["The title field is required."],
        "slug": ["The slug has already been taken."]
    }
}
```

---

### Show Single Page

**Endpoint:** `GET /admin/pages/{id}`

**Parameters:**
- `id`: Page ID

**Response:**
```html
Blade View: admin.pages.show
```

---

### Edit Page Form

**Endpoint:** `GET /admin/pages/{id}/edit`

**Parameters:**
- `id`: Page ID

**Response:**
```html
Blade View: admin.pages.edit
```

**Data Available:**
```php
$page = [
    'id' => 1,
    'title' => 'About Us',
    'slug' => 'about-us',
    'meta_description' => '...',
    'is_published' => true,
    'sections' => [...]
]

$availableTemplates = [
    'hero' => [...],
    'about' => [...],
    'features' => [...]
]
```

---

### Update Page

**Endpoint:** `PUT /admin/pages/{id}`  
or `POST /admin/pages/{id}` with `_method=PUT`

**Headers:**
```
Content-Type: application/x-www-form-urlencoded
X-CSRF-TOKEN: {token}
```

**Request Body:**
```
title=About Us - Updated
slug=about-us
meta_description=Updated description
is_published=1
_method=PUT
```

**Response (Success):**
```
HTTP 302 Redirect to /admin/pages/{id}/edit
Flash Message: "Page updated successfully!"
```

---

### Delete Page

**Endpoint:** `DELETE /admin/pages/{id}`  
or `POST /admin/pages/{id}` with `_method=DELETE`

**Headers:**
```
X-CSRF-TOKEN: {token}
```

**Response (Success):**
```
HTTP 302 Redirect to /admin/pages
Flash Message: "Page deleted successfully!"
```

---

### Publish/Unpublish Page

**Endpoint:** `POST /admin/pages/{id}/publish`

**Headers:**
```
Content-Type: application/json
X-CSRF-TOKEN: {token}
```

**Request Body:**
```json
{
    "is_published": true
}
```

**Response (Success):**
```json
{
    "success": true,
    "message": "Page published successfully",
    "page": {
        "id": 1,
        "is_published": true
    }
}
```

---

### Duplicate Page

**Endpoint:** `POST /admin/pages/{id}/duplicate`

**Response:**
```
HTTP 302 Redirect to /admin/pages/{new_id}/edit
Flash Message: "Page duplicated successfully!"
```

---

## 3. Admin - Sections

### Add Section to Page

**Endpoint:** `POST /admin/pages/{pageId}/sections`

**Headers:**
```
Content-Type: application/json
X-CSRF-TOKEN: {token}
```

**Request Body:**
```json
{
    "section_template_id": 3
}
```

**Response (Success):**
```json
{
    "success": true,
    "message": "Section added successfully",
    "section": {
        "id": 15,
        "page_id": 1,
        "section_template_id": 3,
        "order": 2,
        "content": {
            "heading": "Default Heading",
            "subheading": "Default subheading"
        }
    }
}
```

---

### Update Section Content

**Endpoint:** `PUT /admin/sections/{id}`

**Headers:**
```
Content-Type: application/json
X-CSRF-TOKEN: {token}
```

**Request Body:**
```json
{
    "content": {
        "heading": "Updated Heading",
        "subheading": "Updated subheading text",
        "button_text": "Learn More",
        "button_link": "/contact",
        "image": "/uploads/images/hero-bg.jpg",
        "background_color": "#0066cc"
    }
}
```

**Response (Success):**
```json
{
    "success": true,
    "message": "Section updated successfully",
    "section": {
        "id": 15,
        "content": {...}
    }
}
```

---

### Reorder Sections

**Endpoint:** `POST /admin/pages/{pageId}/sections/reorder`

**Headers:**
```
Content-Type: application/json
X-CSRF-TOKEN: {token}
```

**Request Body:**
```json
{
    "sections": [15, 12, 18, 9]
}
```

**Response (Success):**
```json
{
    "success": true,
    "message": "Sections reordered successfully"
}
```

---

### Toggle Section Visibility

**Endpoint:** `POST /admin/sections/{id}/toggle-visibility`

**Response (Success):**
```json
{
    "success": true,
    "is_visible": false,
    "message": "Section visibility updated"
}
```

---

### Delete Section

**Endpoint:** `DELETE /admin/sections/{id}`

**Response (Success):**
```json
{
    "success": true,
    "message": "Section deleted successfully"
}
```

---

### Duplicate Section

**Endpoint:** `POST /admin/sections/{id}/duplicate`

**Response (Success):**
```json
{
    "success": true,
    "message": "Section duplicated successfully",
    "section": {
        "id": 20,
        ...
    }
}
```

---

## 4. Admin - Posts

### List All Posts

**Endpoint:** `GET /admin/posts`

**Query Parameters:**
- `page`: Pagination page
- `category`: Filter by category ID
- `status`: Filter by status (published, draft)

**Response:**
```html
Blade View: admin.posts.index
```

---

### Create Post Form

**Endpoint:** `GET /admin/posts/create`

**Response:**
```html
Blade View: admin.posts.create
```

**Data Available:**
```php
$categories = Category::all();
```

---

### Store New Post

**Endpoint:** `POST /admin/posts`

**Request Body:**
```
title=My First Blog Post
slug=my-first-blog-post
excerpt=This is a short excerpt
content=<p>Full post content goes here...</p>
featured_image=/uploads/images/post-1.jpg
category_id=2
is_published=1
published_at=2024-10-30 10:00:00
meta_description=SEO description
_token={csrf_token}
```

**Response (Success):**
```
HTTP 302 Redirect to /admin/posts/{id}/edit
Flash Message: "Post created successfully!"
```

---

### Update Post

**Endpoint:** `PUT /admin/posts/{id}`

**Response:**
```
HTTP 302 Redirect to /admin/posts/{id}/edit
Flash Message: "Post updated successfully!"
```

---

### Delete Post

**Endpoint:** `DELETE /admin/posts/{id}`

**Response:**
```
HTTP 302 Redirect to /admin/posts
Flash Message: "Post deleted successfully!"
```

---

## 5. Admin - Menus

### List Menus

**Endpoint:** `GET /admin/menus`

**Response:**
```html
Blade View: admin.menus.index
```

**Data Available:**
```php
$menus = Menu::with('items')->get();
```

---

### Edit Menu

**Endpoint:** `GET /admin/menus/{id}/edit`

**Response:**
```html
Blade View: admin.menus.edit
```

**Data Available:**
```php
$menu = Menu::with('items')->find($id);
$pages = Page::published()->get();
$categories = Category::all();
```

---

### Create Menu Item

**Endpoint:** `POST /admin/menus/{menuId}/items`

**Request Body:**
```json
{
    "title": "About Us",
    "type": "page",
    "page_id": 5,
    "parent_id": null,
    "target": "_self",
    "order": 0
}
```

**Types:**
- `page`: Link to a page
- `post`: Link to a post
- `category`: Link to category
- `custom`: Custom URL

**Response (Success):**
```json
{
    "success": true,
    "message": "Menu item created",
    "item": {...}
}
```

---

### Update Menu Item

**Endpoint:** `PUT /admin/menu-items/{id}`

**Request Body:**
```json
{
    "title": "Updated Title",
    "url": "/custom-link",
    "parent_id": 2,
    "order": 1,
    "target": "_blank"
}
```

---

### Reorder Menu Items

**Endpoint:** `POST /admin/menus/{menuId}/reorder`

**Request Body:**
```json
{
    "items": [
        {"id": 1, "parent_id": null, "order": 0},
        {"id": 2, "parent_id": null, "order": 1},
        {"id": 3, "parent_id": 2, "order": 0}
    ]
}
```

---

### Delete Menu Item

**Endpoint:** `DELETE /admin/menu-items/{id}`

**Response:**
```json
{
    "success": true,
    "message": "Menu item deleted"
}
```

---

## 6. Admin - Themes

### List Themes

**Endpoint:** `GET /admin/themes`

**Response:**
```html
Blade View: admin.themes.index
```

**Data Available:**
```php
$themes = Theme::all();
$activeTheme = Theme::where('is_active', true)->first();
```

---

### Activate Theme

**Endpoint:** `POST /admin/themes/{id}/activate`

**Response (Success):**
```json
{
    "success": true,
    "message": "Theme activated successfully",
    "theme": {
        "id": 2,
        "name": "Business Theme",
        "slug": "business"
    }
}
```

**Side Effect:**
- Deactivates all other themes
- Clears cache

---

### Preview Theme

**Endpoint:** `GET /admin/themes/{id}/preview`

**Response:**
```html
Frontend page rendered with selected theme
```

---

## 7. Admin - Settings

### View Settings

**Endpoint:** `GET /admin/settings`

**Response:**
```html
Blade View: admin.settings.index
```

**Data Available:**
```php
$settings = Setting::all()->groupBy('group');
// Groups: general, contact, social, seo
```

---

### Update Settings

**Endpoint:** `PUT /admin/settings`

**Request Body:**
```
settings[site_name]=My Awesome Site
settings[site_tagline]=Welcome to our site
settings[contact_email]=info@example.com
settings[social_facebook]=https://facebook.com/page
settings[google_analytics]=UA-XXXXX-Y
_token={csrf_token}
_method=PUT
```

**Response (Success):**
```
HTTP 302 Redirect to /admin/settings
Flash Message: "Settings updated successfully!"
```

**Side Effect:**
- Clears settings cache

---

### Upload Media

**Endpoint:** `POST /admin/media/upload`

**Headers:**
```
Content-Type: multipart/form-data
X-CSRF-TOKEN: {token}
```

**Request Body:**
```
file: [binary]
```

**Response (Success):**
```json
{
    "success": true,
    "message": "File uploaded successfully",
    "media": {
        "id": 45,
        "filename": "image.jpg",
        "filepath": "/uploads/images/image.jpg",
        "url": "http://simplecms.test/storage/images/image.jpg",
        "mime_type": "image/jpeg",
        "file_size": 245678
    }
}
```

---

## 8. Frontend Routes

### Homepage

**Endpoint:** `GET /`

**Response:**
```html
Blade View: frontend.home or frontend.page (if homepage is set)
```

---

### Dynamic Page

**Endpoint:** `GET /{slug}`

**Parameters:**
- `slug`: Page slug (e.g., "about-us", "contact")

**Response (Success):**
```html
Blade View: frontend.page
```

**Data Available:**
```php
$page = [
    'id' => 1,
    'title' => 'About Us',
    'slug' => 'about-us',
    'sections' => [
        [
            'id' => 5,
            'order' => 0,
            'template' => [...],
            'content' => {...}
        ],
        ...
    ]
]
```

**Response (Not Found):**
```
HTTP 404
```

---

### Blog Index

**Endpoint:** `GET /blog`

**Query Parameters:**
- `page`: Pagination page
- `category`: Filter by category slug

**Response:**
```html
Blade View: frontend.blog.index
```

**Data Available:**
```php
$posts = Post::published()->paginate(10);
$categories = Category::all();
```

---

### Single Post

**Endpoint:** `GET /blog/{slug}`

**Parameters:**
- `slug`: Post slug

**Response:**
```html
Blade View: frontend.blog.show
```

**Data Available:**
```php
$post = Post::where('slug', $slug)->published()->first();
```

**Side Effect:**
- Increments `views_count`

---

### Category Posts

**Endpoint:** `GET /category/{slug}`

**Response:**
```html
Blade View: frontend.blog.category
```

**Data Available:**
```php
$category = Category::where('slug', $slug)->first();
$posts = $category->posts()->published()->paginate(10);
```

---

## 9. Error Responses

### Common HTTP Status Codes

| Code | Meaning | Description |
|------|---------|-------------|
| 200 | OK | Request successful |
| 302 | Redirect | Redirect after form submission |
| 401 | Unauthorized | Not authenticated |
| 403 | Forbidden | Not authorized |
| 404 | Not Found | Resource not found |
| 422 | Unprocessable Entity | Validation error |
| 500 | Server Error | Internal server error |

### Validation Error Format

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "field_name": [
            "Error message 1",
            "Error message 2"
        ]
    }
}
```

---

## 10. Middleware

### Applied Middleware

| Route Group | Middleware | Description |
|-------------|-----------|-------------|
| `/admin/*` | `auth` | Requires authentication |
| `/admin/*` | `admin` | Requires admin role |
| `*` | `web` | Web session, CSRF |

---

## 11. Rate Limiting

Currently no rate limiting implemented. Consider adding for:
- Login attempts: 5 per minute
- API endpoints: 60 per minute
- File uploads: 10 per minute

---

## 12. AJAX Examples

### Add Section via AJAX

```javascript
// Add section to page
$.ajax({
    url: '/admin/pages/1/sections',
    method: 'POST',
    data: {
        section_template_id: 3,
        _token: $('meta[name="csrf-token"]').attr('content')
    },
    success: function(response) {
        console.log('Section added:', response.section);
        // Update UI
    },
    error: function(xhr) {
        console.error('Error:', xhr.responseJSON.message);
    }
});
```

### Reorder Sections via AJAX

```javascript
// Get new order from SortableJS
const sectionIds = $('#sections-container .section-item').map(function() {
    return $(this).data('id');
}).get();

// Send to server
$.ajax({
    url: '/admin/pages/1/sections/reorder',
    method: 'POST',
    contentType: 'application/json',
    data: JSON.stringify({
        sections: sectionIds,
        _token: $('meta[name="csrf-token"]').attr('content')
    }),
    success: function(response) {
        console.log('Reordered successfully');
    }
});
```

### Update Section Content

```javascript
// Update section via AJAX
const sectionId = 15;
const updatedContent = {
    heading: 'New Heading',
    subheading: 'New subheading',
    button_text: 'Click Here'
};

$.ajax({
    url: `/admin/sections/${sectionId}`,
    method: 'PUT',
    contentType: 'application/json',
    data: JSON.stringify({
        content: updatedContent,
        _token: $('meta[name="csrf-token"]').attr('content')
    }),
    success: function(response) {
        console.log('Section updated');
    }
});
```

---

## Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0 | 2025-10-30 | Initial API documentation | Development Team |

---

**Last Updated:** October 30, 2025
