# Phase 1: Security & Testing - Checklist

**Date**: 2025-10-31
**Status**: Testing Phase

---

## ✅ Completed: Controller Authorization

All controllers now have permission middleware:

- ✅ **PageController** - pages.view, pages.create, pages.edit, pages.delete
- ✅ **PostController** - posts.view, posts.create, posts.edit, posts.delete
- ✅ **CategoryController** - categories.view, categories.create, categories.edit, categories.delete
- ✅ **MediaController** - media.view, media.upload, media.edit, media.delete
- ✅ **MenuController** - menus.view, menus.create, menus.edit, menus.delete
- ✅ **ThemeController** - themes.view, themes.activate, themes.upload, themes.delete
- ✅ **SettingController** - settings.view, settings.edit

---

## 📋 Manual Testing Checklist

### 1. Admin Role Testing

#### Login as Admin
```
Email: admin@simplecms.test
Password: admin123
```

**Expected**: Full access to all features

- [ ] Dashboard loads correctly
- [ ] All menu items visible in sidebar
  - [ ] Pages
  - [ ] Posts & Categories
  - [ ] Menus
  - [ ] Themes
  - [ ] Media Library
  - [ ] Users
  - [ ] Roles & Permissions
  - [ ] Settings

#### Pages Module
- [ ] Can view pages list (`/admin/pages`)
- [ ] Can create new page (`/admin/pages/create`)
- [ ] Can edit page (`/admin/pages/{id}/edit`)
- [ ] Can delete page
- [ ] Can add sections to page

#### Posts Module
- [ ] Can view posts list (`/admin/posts`)
- [ ] Can create new post (`/admin/posts/create`)
- [ ] Can edit any post (`/admin/posts/{id}/edit`)
- [ ] Can delete any post
- [ ] Can publish/unpublish post

#### Categories Module
- [ ] Can view categories list (`/admin/categories`)
- [ ] Can create new category
- [ ] Can edit category
- [ ] Can delete category

#### Media Module
- [ ] Can view media library (`/admin/media`)
- [ ] Can upload new media
- [ ] Can edit media metadata
- [ ] Can delete media

#### Menus Module
- [ ] Can view menus list (`/admin/menus`)
- [ ] Can create new menu
- [ ] Can edit menu
- [ ] Can add/remove menu items
- [ ] Can reorder menu items
- [ ] Can delete menu

#### Themes Module
- [ ] Can view themes list (`/admin/themes`)
- [ ] Can activate theme
- [ ] Can edit theme content (`/admin/themes/{id}/settings`)
- [ ] Can upload new theme (if implemented)
- [ ] Can delete theme (non-active)

#### Users Module
- [ ] Can view users list (`/admin/users`)
- [ ] Can create new user
- [ ] Can edit user
- [ ] Can change user role
- [ ] Can delete user (not self, not last admin)

#### Roles Module
- [ ] Can view roles list (`/admin/roles`)
- [ ] Can create new role
- [ ] Can edit role and permissions
- [ ] Can delete role (not admin role, not roles with users)

#### Settings Module
- [ ] Can view settings (`/admin/settings`)
- [ ] Can edit settings
- [ ] Can save settings successfully

---

### 2. Editor Role Testing

#### Login as Editor
```
Email: editor@simplecms.test
Password: editor123
```

**Expected**: Content management access only (25 permissions)

#### Sidebar Visibility
- [ ] ✅ Pages - Should be visible
- [ ] ✅ Posts & Categories - Should be visible
- [ ] ✅ Menus - Should be visible
- [ ] ✅ Themes - Should NOT be visible
- [ ] ✅ Media Library - Should be visible
- [ ] ❌ Users - Should NOT be visible
- [ ] ❌ Roles & Permissions - Should NOT be visible
- [ ] ❌ Settings - Should NOT be visible

#### Dashboard Capability Cards
- [ ] Shows: Posts, Pages, Categories, Media, Menus
- [ ] Does NOT show: Users, Roles, Settings

#### Access Testing
**Should Have Access**:
- [ ] Can access `/admin/pages`
- [ ] Can access `/admin/posts`
- [ ] Can access `/admin/categories`
- [ ] Can access `/admin/media`
- [ ] Can access `/admin/menus`

**Should Be BLOCKED (403)**:
- [ ] Cannot access `/admin/users`
- [ ] Cannot access `/admin/roles`
- [ ] Cannot access `/admin/settings`
- [ ] Cannot access `/admin/themes`

#### CRUD Testing
**Pages**:
- [ ] Can create page
- [ ] Can edit page
- [ ] Can delete page

**Posts**:
- [ ] Can create post
- [ ] Can edit any post (has posts.edit-all)
- [ ] Can delete any post (has posts.delete-all)
- [ ] Can publish post

**Media**:
- [ ] Can upload media
- [ ] Can edit any media (has media.edit-all)
- [ ] Can delete any media (has media.delete-all)

---

### 3. Author Role Testing

#### Login as Author
```
Email: author@simplecms.test
Password: author123
```

**Expected**: Limited content creation (8 permissions)

#### Sidebar Visibility
- [ ] ✅ Posts & Categories - Should be visible
- [ ] ✅ Media Library - Should be visible
- [ ] ❌ Pages - Should NOT be visible
- [ ] ❌ Menus - Should NOT be visible
- [ ] ❌ Themes - Should NOT be visible
- [ ] ❌ Users - Should NOT be visible
- [ ] ❌ Roles - Should NOT be visible
- [ ] ❌ Settings - Should NOT be visible

#### Dashboard Capability Cards
- [ ] Shows: Posts, Media only
- [ ] Does NOT show: Pages, Users, Roles, Settings

#### Access Testing
**Should Have Access**:
- [ ] Can access `/admin/posts`
- [ ] Can access `/admin/media`

**Should Be BLOCKED (403)**:
- [ ] Cannot access `/admin/pages`
- [ ] Cannot access `/admin/categories/create` (can view but not edit)
- [ ] Cannot access `/admin/menus`
- [ ] Cannot access `/admin/themes`
- [ ] Cannot access `/admin/users`
- [ ] Cannot access `/admin/roles`
- [ ] Cannot access `/admin/settings`

#### Posts Testing
- [ ] Can create new post
- [ ] Can edit OWN post only (test with another author's post)
- [ ] Can delete OWN post only
- [ ] Cannot edit other author's posts
- [ ] Cannot delete other author's posts
- [ ] Cannot publish (no posts.publish permission)

#### Media Testing
- [ ] Can upload media
- [ ] Can edit OWN media only
- [ ] Can delete OWN media only
- [ ] Cannot edit other user's media
- [ ] Cannot delete other user's media

---

### 4. Protection Mechanisms Testing

#### User Management Protection
- [ ] Admin cannot delete self
- [ ] Cannot delete last admin user
- [ ] Can delete non-admin users
- [ ] Can delete other admins (if more than one)

#### Role Management Protection
- [ ] Cannot delete 'admin' role
- [ ] Cannot rename 'admin' role
- [ ] Cannot delete role with assigned users
- [ ] Can delete empty custom roles

#### Theme Protection
- [ ] Cannot delete active theme
- [ ] Can delete inactive themes

---

### 5. Permission Directives Testing

#### Blade @can Directives
Test these in various views:

**Pages Index** (`admin/pages/index.blade.php`):
- [ ] @can('pages.create') - Shows "Create Page" button
- [ ] @can('pages.edit') - Shows "Edit" button
- [ ] @can('pages.delete') - Shows "Delete" button

**Posts Index** (`admin/posts/index.blade.php`):
- [ ] @can('posts.create') - Shows "Create Post" button
- [ ] @can('posts.edit') - Shows "Edit" button
- [ ] @can('posts.delete') - Shows "Delete" button

**Sidebar** (`admin/layouts/sidebar.blade.php`):
- [ ] @can('users.view') - Shows "Users" menu
- [ ] @can('roles.view') - Shows "Roles" menu

---

### 6. Security Audit

#### CSRF Protection
- [ ] All forms have @csrf token
- [ ] POST requests fail without CSRF token

#### XSS Prevention
- [ ] User input is escaped in views using `{{ }}`
- [ ] Raw HTML only used where necessary with `{!! !!}`
- [ ] Test script injection in:
  - [ ] Post content
  - [ ] Page content
  - [ ] Category names
  - [ ] User names

#### File Upload Validation
- [ ] Only allowed file types can be uploaded
- [ ] File size limits enforced
- [ ] Malicious files rejected

#### SQL Injection
- [ ] All queries use Eloquent or prepared statements
- [ ] No raw SQL with user input

#### Password Security
- [ ] Passwords hashed with bcrypt
- [ ] Password confirmation required on user create
- [ ] Cannot view passwords in database

#### Session Security
- [ ] Session expires after inactivity
- [ ] Logout clears session properly
- [ ] Cannot access admin after logout

---

### 7. Edge Cases Testing

#### Permission Edge Cases
- [ ] User with no roles - cannot access admin
- [ ] User with subscriber role - limited dashboard access
- [ ] User with multiple roles (if supported)

#### Route Access
- [ ] Direct URL access blocked without permission
  - Try accessing `/admin/pages/create` as author
  - Try accessing `/admin/users` as editor
  - Try accessing `/admin/settings` as author

#### Middleware Chain
- [ ] Must be authenticated to access admin routes
- [ ] Must have admin role to access admin routes
- [ ] Must have specific permission to access protected actions

---

### 8. Error Handling

#### Authorization Errors
- [ ] 403 Forbidden shown when accessing without permission
- [ ] Error page is user-friendly
- [ ] Can navigate back from error page

#### Validation Errors
- [ ] Form validation works on all forms
- [ ] Error messages displayed correctly
- [ ] Old input preserved on validation error

---

## 🐛 Bugs Found

### Critical Bugs
- [ ] None found

### Major Bugs
- [ ] None found

### Minor Bugs
- [ ] None found

---

## 📝 Notes

### Observations:


### Improvements Needed:


### Performance Issues:


---

## ✅ Sign-off

- [ ] All critical tests passed
- [ ] All major tests passed
- [ ] Bugs documented
- [ ] Ready for Phase 2

**Tested By**: _______________
**Date**: _______________
**Status**: ☐ PASS / ☐ FAIL / ☐ CONDITIONAL PASS

---

## Next Steps

After completing this checklist:

1. **If PASS**: Proceed to Phase 2 (Author Ownership)
2. **If FAIL**: Fix critical bugs first
3. **If CONDITIONAL PASS**: Document known issues, proceed with caution

See [NEXT_DEVELOPMENT_ROADMAP.md](NEXT_DEVELOPMENT_ROADMAP.md) for Phase 2 details.
