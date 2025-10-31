# Roles & Permissions Implementation Plan

**Date**: 2025-10-31
**Status**: Planning Phase

---

## Current State Analysis ✅

### What's Already Installed:
- ✅ Spatie Laravel Permission (v6.22.0)
- ✅ Permission tables migrated
- ✅ User model uses `HasRoles` trait
- ✅ Admin middleware checking `admin` role
- ✅ Routes protected with `auth` and `admin` middleware

### What's Missing:
- ❌ No roles seeded in database
- ❌ No permissions defined
- ❌ No admin interface for role management
- ❌ No user management interface
- ❌ Dashboard page not designed/functional
- ❌ No way to assign roles to users

---

## Proposed Roles Structure

### 1. **Admin Role**
**Description**: Full system access
**Permissions**:
- Manage all content (pages, posts, categories)
- Manage users and roles
- Manage themes and settings
- Manage menus
- Access media library
- View analytics/dashboard

### 2. **Editor Role**
**Description**: Content management
**Permissions**:
- Create, edit, delete pages
- Create, edit, delete posts
- Manage categories
- Access media library
- Cannot manage users, roles, themes, or settings

### 3. **Author Role**
**Description**: Create content
**Permissions**:
- Create and edit own posts
- Upload media
- Cannot delete posts
- Cannot manage pages, users, or settings

### 4. **Subscriber Role** (Optional)
**Description**: Basic access
**Permissions**:
- View dashboard
- Edit own profile
- Read-only access

---

## Permissions List

### Content Management
```
pages.view        - View pages list
pages.create      - Create pages
pages.edit        - Edit pages
pages.delete      - Delete pages

posts.view        - View posts list
posts.create      - Create posts
posts.edit        - Edit own posts
posts.edit-all    - Edit all posts
posts.delete      - Delete own posts
posts.delete-all  - Delete all posts
posts.publish     - Publish posts

categories.view   - View categories
categories.create - Create categories
categories.edit   - Edit categories
categories.delete - Delete categories
```

### Media Management
```
media.view        - View media library
media.upload      - Upload media
media.edit        - Edit media metadata
media.delete      - Delete media
```

### Menu Management
```
menus.view        - View menus
menus.create      - Create menus
menus.edit        - Edit menus
menus.delete      - Delete menus
```

### Theme Management
```
themes.view       - View themes
themes.activate   - Activate themes
themes.upload     - Upload new themes
themes.delete     - Delete themes
themes.settings   - Edit theme content
```

### System Settings
```
settings.view     - View settings
settings.edit     - Edit settings
```

### User Management
```
users.view        - View users list
users.create      - Create users
users.edit        - Edit users
users.delete      - Delete users
users.assign-roles - Assign roles to users
```

### Role Management
```
roles.view        - View roles list
roles.create      - Create roles
roles.edit        - Edit roles
roles.delete      - Delete roles
roles.permissions - Manage role permissions
```

---

## Implementation Plan

### Phase 1: Database & Seeding ✅
**Files to Create**:
1. `database/seeders/RolesAndPermissionsSeeder.php` - Seed roles and permissions
2. `database/seeders/AdminUserSeeder.php` - Create default admin user

**Tasks**:
- Define all permissions
- Create roles with assigned permissions
- Create default admin user

### Phase 2: Role Management Interface
**Files to Create**:
1. `app/Http/Controllers/Admin/RoleController.php` - CRUD for roles
2. `resources/views/admin/roles/index.blade.php` - List roles
3. `resources/views/admin/roles/create.blade.php` - Create role
4. `resources/views/admin/roles/edit.blade.php` - Edit role & assign permissions
5. Update `routes/web.php` - Add role routes

**Features**:
- View all roles with permission counts
- Create new roles
- Edit role name and permissions (checkbox list)
- Delete roles (except admin)
- Assign/revoke permissions

### Phase 3: User Management Interface
**Files to Create**:
1. `app/Http/Controllers/Admin/UserController.php` - CRUD for users
2. `resources/views/admin/users/index.blade.php` - List users
3. `resources/views/admin/users/create.blade.php` - Create user
4. `resources/views/admin/users/edit.blade.php` - Edit user & assign roles
5. Update `routes/web.php` - Add user routes

**Features**:
- View all users with roles
- Create new users with role assignment
- Edit user details and roles
- Delete users (with confirmation)
- Bulk actions (optional)

### Phase 4: Permission Middleware
**Files to Create**:
1. `app/Http/Middleware/Permission.php` - Check specific permission
2. Update `app/Http/Kernel.php` - Register middleware alias

**Usage**:
```php
Route::middleware(['auth', 'permission:posts.create'])->group(...);
```

### Phase 5: Update Routes with Permissions
**Files to Modify**:
1. `routes/web.php` - Add permission middleware to routes

**Example**:
```php
// Only users with 'pages.view' permission
Route::get('pages', ...)->middleware('permission:pages.view');

// Only users with 'pages.create' permission
Route::post('pages', ...)->middleware('permission:pages.create');
```

### Phase 6: User Dashboard Design
**Files to Create/Modify**:
1. `resources/views/dashboard.blade.php` - User dashboard layout
2. `app/Http/Controllers/DashboardController.php` - User dashboard logic

**Features**:
- Welcome message
- Quick stats (own posts, recent activity)
- Quick actions based on role
- Profile overview
- Role-based menu/navigation

### Phase 7: Admin Sidebar Updates
**Files to Modify**:
1. `resources/views/admin/layouts/sidebar.blade.php` - Add role management

**New Menu Items**:
- Users (with permission check)
- Roles & Permissions (with permission check)

---

## Database Schema (Already Exists via Spatie)

Tables created by Spatie Permission:
```
roles
- id
- name
- guard_name
- created_at
- updated_at

permissions
- id
- name
- guard_name
- created_at
- updated_at

model_has_roles
- role_id
- model_type
- model_id

model_has_permissions
- permission_id
- model_type
- model_id

role_has_permissions
- permission_id
- role_id
```

---

## Role-Permission Matrix

| Permission Group      | Admin | Editor | Author | Subscriber |
|-----------------------|-------|--------|--------|------------|
| **Pages**             |       |        |        |            |
| pages.view            | ✅    | ✅     | ❌     | ❌         |
| pages.create          | ✅    | ✅     | ❌     | ❌         |
| pages.edit            | ✅    | ✅     | ❌     | ❌         |
| pages.delete          | ✅    | ✅     | ❌     | ❌         |
| **Posts**             |       |        |        |            |
| posts.view            | ✅    | ✅     | ✅     | ❌         |
| posts.create          | ✅    | ✅     | ✅     | ❌         |
| posts.edit            | ✅    | ✅     | own    | ❌         |
| posts.edit-all        | ✅    | ✅     | ❌     | ❌         |
| posts.delete          | ✅    | ✅     | own    | ❌         |
| posts.delete-all      | ✅    | ✅     | ❌     | ❌         |
| posts.publish         | ✅    | ✅     | ❌     | ❌         |
| **Categories**        |       |        |        |            |
| categories.*          | ✅    | ✅     | ❌     | ❌         |
| **Media**             |       |        |        |            |
| media.view            | ✅    | ✅     | ✅     | ❌         |
| media.upload          | ✅    | ✅     | ✅     | ❌         |
| media.edit            | ✅    | ✅     | own    | ❌         |
| media.delete          | ✅    | ✅     | own    | ❌         |
| **Menus**             |       |        |        |            |
| menus.*               | ✅    | ✅     | ❌     | ❌         |
| **Themes**            |       |        |        |            |
| themes.*              | ✅    | ❌     | ❌     | ❌         |
| **Settings**          |       |        |        |            |
| settings.*            | ✅    | ❌     | ❌     | ❌         |
| **Users**             |       |        |        |            |
| users.*               | ✅    | ❌     | ❌     | ❌         |
| **Roles**             |       |        |        |            |
| roles.*               | ✅    | ❌     | ❌     | ❌         |

---

## Usage Examples

### In Controllers
```php
// Check if user has permission
if (auth()->user()->can('posts.create')) {
    // Allow creating post
}

// Authorize or abort
$this->authorize('posts.edit', $post);

// In constructor
public function __construct()
{
    $this->middleware('permission:posts.create')->only(['create', 'store']);
    $this->middleware('permission:posts.edit')->only(['edit', 'update']);
}
```

### In Blade Views
```blade
@can('posts.create')
    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
        Create Post
    </a>
@endcan

@role('admin')
    <div class="admin-only-section">
        <!-- Admin only content -->
    </div>
@endrole

@hasanyrole('admin|editor')
    <!-- Show to admin or editor -->
@endhasanyrole
```

### In Routes
```php
// Single permission
Route::get('posts/create', [PostController::class, 'create'])
    ->middleware('permission:posts.create');

// Multiple permissions (OR)
Route::get('posts/{post}/edit', [PostController::class, 'edit'])
    ->middleware('permission:posts.edit|posts.edit-all');

// Role-based
Route::middleware(['role:admin'])->group(function() {
    // Admin only routes
});
```

---

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── RoleController.php         [NEW]
│   │   │   ├── UserController.php         [NEW]
│   │   │   └── DashboardController.php    [UPDATE]
│   │   └── DashboardController.php        [NEW - User Dashboard]
│   └── Middleware/
│       └── Permission.php                 [NEW]
└── Models/
    └── User.php                           [OK - Already has HasRoles]

database/
└── seeders/
    ├── RolesAndPermissionsSeeder.php      [NEW]
    └── AdminUserSeeder.php                [NEW]

resources/
└── views/
    ├── dashboard.blade.php                [UPDATE - Design layout]
    └── admin/
        ├── roles/
        │   ├── index.blade.php            [NEW]
        │   ├── create.blade.php           [NEW]
        │   └── edit.blade.php             [NEW]
        ├── users/
        │   ├── index.blade.php            [NEW]
        │   ├── create.blade.php           [NEW]
        │   └── edit.blade.php             [NEW]
        └── layouts/
            └── sidebar.blade.php          [UPDATE - Add menu items]

routes/
└── web.php                                [UPDATE - Add routes]
```

---

## Testing Checklist

### Role Creation & Management
- [ ] Create new role via admin interface
- [ ] Assign permissions to role
- [ ] Edit role permissions
- [ ] Delete role
- [ ] Prevent deleting admin role

### User Management
- [ ] Create user with role
- [ ] Edit user and change role
- [ ] Delete user
- [ ] View users list with roles

### Permission Testing
- [ ] Admin can access all routes
- [ ] Editor can access content routes only
- [ ] Author can access own posts only
- [ ] Unauthorized users get 403

### Dashboard
- [ ] Admin redirected to /admin/dashboard
- [ ] Regular user sees /dashboard
- [ ] Dashboard shows role-appropriate content
- [ ] Quick actions work

### Blade Directives
- [ ] @can directive works
- [ ] @role directive works
- [ ] @hasanyrole directive works
- [ ] Sidebar menu items show/hide correctly

---

## Quick Commands

```bash
# Create seeder
php artisan make:seeder RolesAndPermissionsSeeder

# Run seeders
php artisan db:seed --class=RolesAndPermissionsSeeder
php artisan db:seed --class=AdminUserSeeder

# Clear permission cache
php artisan permission:cache-reset

# Create controllers
php artisan make:controller Admin/RoleController --resource
php artisan make:controller Admin/UserController --resource

# Create middleware
php artisan make:middleware Permission
```

---

## Next Steps

1. ✅ **Analysis Complete** - Current state documented
2. **Create Seeders** - Define all roles and permissions
3. **Create Role Management** - Admin interface for roles
4. **Create User Management** - Admin interface for users
5. **Update Routes** - Add permission middleware
6. **Design Dashboard** - User dashboard layout
7. **Test Everything** - Comprehensive testing

---

## Benefits After Implementation

### For Admin:
- Full control over who can do what
- Easy user management
- Role-based delegation
- Security through permissions

### For Editors:
- Content management without system access
- Safe collaboration
- Clear responsibilities

### For Authors:
- Create content without breaking things
- Limited but sufficient access
- Focus on writing

### For System:
- Better security
- Audit trail capability
- Scalable user management
- Professional CMS structure

---

**Status**: Ready to implement
**Estimated Time**: 4-6 hours
**Priority**: High (Core CMS feature)
