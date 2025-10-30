# Development Guide - SimpleCMS

## Coding Standards, Best Practices & Workflow

**Version:** 1.0  
**Framework:** Laravel 10.x  
**PHP Version:** 8.1

---

## Table of Contents
1. [Coding Standards](#1-coding-standards)
2. [Project Structure](#2-project-structure)
3. [Naming Conventions](#3-naming-conventions)
4. [Development Workflow](#4-development-workflow)
5. [Git Workflow](#5-git-workflow)
6. [Testing Guidelines](#6-testing-guidelines)
7. [Security Best Practices](#7-security-best-practices)
8. [Performance Optimization](#8-performance-optimization)

---

## 1. Coding Standards

### 1.1 PHP Coding Style
Follow **PSR-12** coding standard.

#### Indentation & Spacing
```php
// ✅ Good
class PageController extends Controller
{
    public function index()
    {
        $pages = Page::all();
        return view('admin.pages.index', compact('pages'));
    }
}

// ❌ Bad
class PageController extends Controller{
  public function index(){
    $pages=Page::all();
    return view('admin.pages.index',compact('pages'));
  }
}
```

#### Line Length
- Maximum 120 characters per line
- Break long method chains

```php
// ✅ Good
$pages = Page::where('is_published', true)
    ->with(['sections.template'])
    ->orderBy('created_at', 'desc')
    ->paginate(10);

// ❌ Bad
$pages = Page::where('is_published', true)->with(['sections.template'])->orderBy('created_at', 'desc')->paginate(10);
```

### 1.2 Laravel Best Practices

#### Use Type Hints
```php
// ✅ Good
public function store(PageRequest $request): RedirectResponse
{
    $page = Page::create($request->validated());
    return redirect()->route('admin.pages.index');
}

// ❌ Bad
public function store($request)
{
    $page = Page::create($request->all());
    return redirect()->route('admin.pages.index');
}
```

#### Use Eloquent Properly
```php
// ✅ Good - Eager Loading
$pages = Page::with('sections')->get();

// ❌ Bad - N+1 Problem
$pages = Page::all();
foreach ($pages as $page) {
    $sections = $page->sections; // N+1 query
}
```

#### Use Form Requests for Validation
```php
// ✅ Good
// app/Http/Requests/PageRequest.php
class PageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:pages,slug,' . $this->page,
            'meta_description' => 'nullable|string|max:160',
        ];
    }
}

// Controller
public function store(PageRequest $request)
{
    Page::create($request->validated());
}

// ❌ Bad - Validation in controller
public function store(Request $request)
{
    $request->validate([
        'title' => 'required',
        'slug' => 'required',
    ]);
}
```

### 1.3 Blade Templates

#### Use Components
```blade
{{-- ✅ Good --}}
<x-admin.card title="Page List">
    <x-slot name="actions">
        <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">
            Add New Page
        </a>
    </x-slot>
    
    {{-- Content --}}
</x-admin.card>

{{-- ❌ Bad - Inline HTML everywhere --}}
<div class="card">
    <div class="card-header">Page List</div>
    <div class="card-body">
        {{-- Content --}}
    </div>
</div>
```

#### Avoid Logic in Views
```blade
{{-- ✅ Good --}}
{{-- Controller: $publishedPages = Page::published()->get(); --}}
@foreach($publishedPages as $page)
    <li>{{ $page->title }}</li>
@endforeach

{{-- ❌ Bad --}}
@foreach(Page::where('is_published', 1)->get() as $page)
    <li>{{ $page->title }}</li>
@endforeach
```

---

## 2. Project Structure

### 2.1 Directory Organization

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/              # Admin panel controllers
│   │   │   ├── DashboardController.php
│   │   │   ├── PageController.php
│   │   │   ├── PostController.php
│   │   │   └── SectionController.php
│   │   └── Frontend/           # Public-facing controllers
│   │       ├── HomeController.php
│   │       └── PageController.php
│   ├── Requests/               # Form requests
│   │   ├── PageRequest.php
│   │   ├── PostRequest.php
│   │   └── SectionRequest.php
│   └── Middleware/
│       └── AdminMiddleware.php
├── Models/
│   ├── Page.php
│   ├── PageSection.php
│   ├── SectionTemplate.php
│   ├── Post.php
│   ├── Category.php
│   └── Menu.php
├── Services/                   # Business logic
│   ├── PageService.php
│   ├── SectionService.php
│   └── MediaService.php
├── Repositories/               # Data access layer (optional)
│   └── PageRepository.php
└── View/
    └── Components/
        ├── Admin/
        │   ├── Card.php
        │   └── DataTable.php
        └── Sections/
            ├── Hero1.php
            └── About1.php
```

### 2.2 File Naming

| Type | Convention | Example |
|------|------------|---------|
| Controller | PascalCase + Controller | `PageController.php` |
| Model | PascalCase (singular) | `Page.php`, `PageSection.php` |
| Migration | snake_case | `2024_10_30_create_pages_table.php` |
| View | kebab-case | `pages/index.blade.php` |
| Component | PascalCase | `Hero1.php` |
| Request | PascalCase + Request | `PageRequest.php` |
| Service | PascalCase + Service | `PageService.php` |

---

## 3. Naming Conventions

### 3.1 Variables & Functions

```php
// ✅ Good - Descriptive names
$publishedPages = Page::published()->get();
$totalPosts = Post::count();

public function getPublishedPages()
{
    return Page::where('is_published', true)->get();
}

// ❌ Bad - Unclear names
$p = Page::all();
$t = Post::count();

public function get()
{
    return Page::all();
}
```

### 3.2 Database Tables & Columns

| Element | Convention | Example |
|---------|------------|---------|
| Table | snake_case (plural) | `pages`, `page_sections` |
| Column | snake_case | `is_published`, `created_at` |
| Foreign Key | singular_id | `page_id`, `section_template_id` |
| Pivot Table | singular_singular | `page_section` (alphabetical) |

### 3.3 Routes

```php
// ✅ Good - RESTful naming
Route::resource('pages', PageController::class);
// GET /pages - index
// GET /pages/create - create
// POST /pages - store
// GET /pages/{page} - show
// GET /pages/{page}/edit - edit
// PUT /pages/{page} - update
// DELETE /pages/{page} - destroy

// Custom routes
Route::post('pages/{page}/publish', [PageController::class, 'publish'])
    ->name('pages.publish');

// ❌ Bad - Inconsistent naming
Route::get('page-list', [PageController::class, 'list']);
Route::get('page-create-new', [PageController::class, 'create']);
```

---

## 4. Development Workflow

### 4.1 Feature Development Steps

#### Step 1: Create Migration
```bash
php artisan make:migration create_pages_table
```

```php
// database/migrations/2024_10_30_create_pages_table.php
public function up()
{
    Schema::create('pages', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->string('slug')->unique();
        $table->text('meta_description')->nullable();
        $table->boolean('is_published')->default(false);
        $table->timestamps();
        
        $table->index('is_published');
    });
}
```

#### Step 2: Create Model
```bash
php artisan make:model Page
```

```php
// app/Models/Page.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'meta_description',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    // Relationships
    public function sections(): HasMany
    {
        return $this->hasMany(PageSection::class)->orderBy('order');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    // Accessors
    public function getUrlAttribute(): string
    {
        return url('/' . $this->slug);
    }
}
```

#### Step 3: Create Controller
```bash
php artisan make:controller Admin/PageController --resource
```

```php
// app/Http/Controllers/Admin/PageController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PageRequest;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PageController extends Controller
{
    public function index(): View
    {
        $pages = Page::with('sections')
            ->latest()
            ->paginate(15);
            
        return view('admin.pages.index', compact('pages'));
    }

    public function create(): View
    {
        return view('admin.pages.create');
    }

    public function store(PageRequest $request): RedirectResponse
    {
        $page = Page::create($request->validated());
        
        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('success', 'Page created successfully!');
    }

    public function edit(Page $page): View
    {
        $page->load(['sections.template']);
        
        return view('admin.pages.edit', compact('page'));
    }

    public function update(PageRequest $request, Page $page): RedirectResponse
    {
        $page->update($request->validated());
        
        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('success', 'Page updated successfully!');
    }

    public function destroy(Page $page): RedirectResponse
    {
        $page->delete();
        
        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Page deleted successfully!');
    }
}
```

#### Step 4: Create Form Request
```bash
php artisan make:request PageRequest
```

```php
// app/Http/Requests/PageRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $pageId = $this->route('page')?->id;
        
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('pages', 'slug')->ignore($pageId),
            ],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'is_published' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Page title is required.',
            'slug.unique' => 'This slug is already taken.',
            'slug.alpha_dash' => 'Slug can only contain letters, numbers, dashes and underscores.',
        ];
    }
}
```

#### Step 5: Create Routes
```php
// routes/web.php
use App\Http\Controllers\Admin\PageController;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::resource('pages', PageController::class);
    
    // Additional routes
    Route::post('pages/{page}/publish', [PageController::class, 'publish'])
        ->name('pages.publish');
    Route::post('pages/{page}/duplicate', [PageController::class, 'duplicate'])
        ->name('pages.duplicate');
});
```

#### Step 6: Create Views
```blade
{{-- resources/views/admin/pages/index.blade.php --}}
@extends('admin.layouts.app')

@section('content')
<div class="section-header">
    <h1>Pages</h1>
    <div class="section-header-button">
        <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Page
        </a>
    </div>
</div>

<div class="section-body">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pages as $page)
                        <tr>
                            <td>{{ $page->title }}</td>
                            <td><code>{{ $page->slug }}</code></td>
                            <td>
                                @if($page->is_published)
                                    <span class="badge badge-success">Published</span>
                                @else
                                    <span class="badge badge-warning">Draft</span>
                                @endif
                            </td>
                            <td>{{ $page->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('admin.pages.edit', $page) }}" 
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.pages.destroy', $page) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No pages found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{ $pages->links() }}
        </div>
    </div>
</div>
@endsection
```

### 4.2 Service Layer (Optional but Recommended)

For complex business logic, use services:

```php
// app/Services/PageService.php
namespace App\Services;

use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Support\Str;

class PageService
{
    public function createPage(array $data): Page
    {
        // Auto-generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }
        
        return Page::create($data);
    }
    
    public function addSectionToPage(Page $page, int $templateId): PageSection
    {
        $template = SectionTemplate::findOrFail($templateId);
        $maxOrder = $page->sections()->max('order') ?? -1;
        
        return $page->sections()->create([
            'section_template_id' => $template->id,
            'order' => $maxOrder + 1,
            'content' => $template->default_fields,
        ]);
    }
    
    public function reorderSections(Page $page, array $sectionIds): void
    {
        foreach ($sectionIds as $index => $sectionId) {
            PageSection::where('id', $sectionId)
                ->where('page_id', $page->id)
                ->update(['order' => $index]);
        }
    }
}

// Usage in Controller
public function addSection(Request $request, Page $page, PageService $service)
{
    $section = $service->addSectionToPage($page, $request->template_id);
    
    return redirect()->back()->with('success', 'Section added!');
}
```

---

## 5. Git Workflow

### 5.1 Branch Strategy

```
main/master         - Production-ready code
└── develop         - Development branch
    ├── feature/page-builder
    ├── feature/menu-system
    └── bugfix/section-order
```

### 5.2 Commit Messages

Follow **Conventional Commits**:

```
feat: add page builder with drag & drop
fix: resolve section ordering bug
docs: update setup guide
style: format PageController code
refactor: extract section logic to service
test: add tests for page creation
chore: update dependencies
```

### 5.3 Git Commands

```bash
# Create feature branch
git checkout -b feature/page-builder

# Make changes and commit
git add .
git commit -m "feat: add section drag & drop functionality"

# Push to remote
git push origin feature/page-builder

# Merge to develop
git checkout develop
git merge feature/page-builder

# Delete feature branch
git branch -d feature/page-builder
```

---

## 6. Testing Guidelines

### 6.1 Feature Tests

```php
// tests/Feature/PageTest.php
namespace Tests\Feature;

use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_create_page()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->post(route('admin.pages.store'), [
                'title' => 'Test Page',
                'slug' => 'test-page',
                'meta_description' => 'Test description',
                'is_published' => true,
            ]);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('pages', [
            'title' => 'Test Page',
            'slug' => 'test-page',
        ]);
    }

    /** @test */
    public function page_slug_must_be_unique()
    {
        $user = User::factory()->create();
        Page::factory()->create(['slug' => 'existing-slug']);
        
        $response = $this->actingAs($user)
            ->post(route('admin.pages.store'), [
                'title' => 'New Page',
                'slug' => 'existing-slug',
            ]);
        
        $response->assertSessionHasErrors('slug');
    }
}
```

### 6.2 Running Tests

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter PageTest

# Run with coverage
php artisan test --coverage
```

---

## 7. Security Best Practices

### 7.1 Input Validation
Always validate user input:

```php
// ✅ Good
public function store(PageRequest $request)
{
    $validated = $request->validated();
    Page::create($validated);
}

// ❌ Bad
public function store(Request $request)
{
    Page::create($request->all()); // Dangerous!
}
```

### 7.2 Mass Assignment Protection
```php
// Model
protected $fillable = ['title', 'slug', 'meta_description'];

// Or use $guarded
protected $guarded = ['id', 'created_at', 'updated_at'];
```

### 7.3 XSS Protection
```blade
{{-- ✅ Good - Auto-escaped --}}
{{ $page->title }}

{{-- ⚠️ Only when needed - Raw HTML --}}
{!! $page->content !!}
```

### 7.4 CSRF Protection
```blade
{{-- Forms --}}
<form method="POST" action="{{ route('admin.pages.store') }}">
    @csrf
    {{-- form fields --}}
</form>

{{-- AJAX --}}
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
```

### 7.5 SQL Injection Prevention
```php
// ✅ Good - Use Eloquent/Query Builder
Page::where('slug', $slug)->first();

// ❌ Bad - Raw queries without bindings
DB::select("SELECT * FROM pages WHERE slug = '$slug'");

// ✅ Good - If raw query needed, use bindings
DB::select("SELECT * FROM pages WHERE slug = ?", [$slug]);
```

---

## 8. Performance Optimization

### 8.1 Database Optimization

#### Eager Loading
```php
// ✅ Good - Eager load relationships
$pages = Page::with(['sections.template', 'creator'])->get();

// ❌ Bad - Lazy loading causes N+1
$pages = Page::all();
foreach ($pages as $page) {
    echo $page->sections; // N+1 query
}
```

#### Query Optimization
```php
// ✅ Good - Select only needed columns
Page::select('id', 'title', 'slug')->get();

// ❌ Bad - Select all columns
Page::all();
```

#### Use Indexes
```php
// Migration
$table->index('slug');
$table->index('is_published');
$table->index(['page_id', 'order']); // Composite index
```

### 8.2 Caching

```php
// Cache settings
$settings = Cache::remember('site_settings', 3600, function () {
    return Setting::pluck('value', 'key');
});

// Cache menu
$menu = Cache::remember('menu_header', 3600, function () {
    return Menu::where('location', 'header')
        ->with('items')
        ->first();
});

// Clear cache when updated
public function update(Request $request, Setting $setting)
{
    $setting->update($request->validated());
    Cache::forget('site_settings');
    return redirect()->back();
}
```

### 8.3 Asset Optimization

```bash
# Compile assets for production
npm run build

# Optimize images
# Use intervention/image package
```

---

## 9. Code Review Checklist

Before submitting code for review:

- [ ] Code follows PSR-12 standards
- [ ] All variables/functions have descriptive names
- [ ] Type hints used for parameters and return types
- [ ] Input validation implemented
- [ ] No N+1 query problems
- [ ] Security best practices followed
- [ ] Error handling implemented
- [ ] Comments added for complex logic
- [ ] Tests written (if applicable)
- [ ] No console.log() or dd() left in code
- [ ] Git commit messages are clear
- [ ] Documentation updated if needed

---

## 10. Useful Laravel Commands

```bash
# Code Generation
php artisan make:model Page -mcr       # Model, Migration, Controller (resource)
php artisan make:request PageRequest   # Form request
php artisan make:component Sections/Hero1
php artisan make:seeder PageSeeder

# Database
php artisan migrate                    # Run migrations
php artisan migrate:fresh --seed       # Reset & seed
php artisan db:seed                    # Run seeders

# Cache
php artisan config:cache               # Cache config
php artisan route:cache                # Cache routes
php artisan view:cache                 # Cache views
php artisan optimize:clear             # Clear all caches

# Debugging
php artisan route:list                 # List all routes
php artisan tinker                     # Interactive shell
php artisan tail                       # Tail log files
```

---

## Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0 | 2025-10-30 | Initial development guide | Development Team |

---

**Last Updated:** October 30, 2025
