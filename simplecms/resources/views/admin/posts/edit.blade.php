@extends('admin.layouts.app')

@section('title', 'Edit Post')
@section('page-title', 'Edit Post')

@section('breadcrumb')
    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
    <div class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}">Posts</a></div>
    <div class="breadcrumb-item active">Edit: {{ $post->title }}</div>
@endsection

@section('content')
    <form action="{{ route('admin.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Post Content</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="title">Post Title <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control form-control-lg @error('title') is-invalid @enderror"
                                   id="title"
                                   name="title"
                                   value="{{ old('title', $post->title) }}"
                                   placeholder="Enter post title"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="slug">Slug <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-link"></i>
                                    </div>
                                </div>
                                <input type="text"
                                       class="form-control @error('slug') is-invalid @enderror"
                                       id="slug"
                                       name="slug"
                                       value="{{ old('slug', $post->slug) }}"
                                       placeholder="post-slug"
                                       required>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            @if($post->is_published)
                                <small class="form-text text-muted">
                                    <i class="fas fa-exclamation-triangle text-warning"></i>
                                    Current URL: <a href="{{ route('posts.show', $post->slug) }}" target="_blank">{{ route('posts.show', $post->slug) }}</a>
                                </small>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="excerpt">Excerpt</label>
                            <textarea class="form-control @error('excerpt') is-invalid @enderror"
                                      id="excerpt"
                                      name="excerpt"
                                      rows="3"
                                      placeholder="Brief summary of the post (optional)">{{ old('excerpt', $post->excerpt) }}</textarea>
                            @error('excerpt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Short description that appears in post listings
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="content">Content <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror"
                                      id="content"
                                      name="content"
                                      rows="15"
                                      required>{{ old('content', $post->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Main content of the post. You can use HTML or Markdown.
                            </small>
                        </div>
                    </div>
                </div>

                <!-- SEO Section -->
                <div class="card">
                    <div class="card-header">
                        <h4>SEO Settings</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="meta_description">Meta Description</label>
                            <textarea class="form-control @error('meta_description') is-invalid @enderror"
                                      id="meta_description"
                                      name="meta_description"
                                      rows="2"
                                      placeholder="SEO description (recommended 150-160 characters)">{{ old('meta_description', $post->meta_description) }}</textarea>
                            @error('meta_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <span id="meta-description-count">0</span> characters
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="meta_keywords">Meta Keywords</label>
                            <input type="text"
                                   class="form-control @error('meta_keywords') is-invalid @enderror"
                                   id="meta_keywords"
                                   name="meta_keywords"
                                   value="{{ old('meta_keywords', $post->meta_keywords) }}"
                                   placeholder="keyword1, keyword2, keyword3">
                            @error('meta_keywords')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Publishing Options -->
                <div class="card">
                    <div class="card-header">
                        <h4>Publish</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox"
                                       class="custom-control-input"
                                       id="is_published"
                                       name="is_published"
                                       value="1"
                                       {{ old('is_published', $post->is_published) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_published">
                                    <strong>Published</strong>
                                    <br>
                                    <small class="text-muted">Make this post publicly visible</small>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="published_at">Publish Date</label>
                            <input type="datetime-local"
                                   class="form-control @error('published_at') is-invalid @enderror"
                                   id="published_at"
                                   name="published_at"
                                   value="{{ old('published_at', $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}">
                            @error('published_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Schedule post publication
                            </small>
                        </div>

                        <hr>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary btn-lg btn-block">
                                <i class="fas fa-save"></i> Update Post
                            </button>
                            @if($post->is_published)
                                <a href="{{ route('posts.show', $post->slug) }}" target="_blank" class="btn btn-info btn-lg btn-block">
                                    <i class="fas fa-eye"></i> View Post
                                </a>
                            @endif
                            <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary btn-lg btn-block">
                                <i class="fas fa-arrow-left"></i> Back to Posts
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Category -->
                <div class="card">
                    <div class="card-header">
                        <h4>Category</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-0">
                            <label for="category_id">Select Category</label>
                            <select class="form-control @error('category_id') is-invalid @enderror"
                                    id="category_id"
                                    name="category_id">
                                <option value="">Uncategorized</option>
                                @foreach($categories ?? [] as $category)
                                    <option value="{{ $category->id }}"
                                            {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mt-2">
                            <a href="{{ route('admin.categories.index') }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-plus"></i> Manage Categories
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Featured Image -->
                <div class="card">
                    <div class="card-header">
                        <h4>Featured Image</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            @if($post->featured_image)
                                <div id="current-image" class="mb-3">
                                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="Featured Image" class="img-fluid rounded">
                                    <div class="mt-2">
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="remove_featured_image" value="1">
                                            <span class="custom-control-label text-danger">Remove featured image</span>
                                        </label>
                                    </div>
                                </div>
                            @endif

                            <div id="featured-image-preview" class="mb-3" style="display: none;">
                                <img src="" alt="New Featured Image" class="img-fluid rounded">
                                <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removeFeaturedImage()">
                                    <i class="fas fa-times"></i> Remove New Image
                                </button>
                            </div>

                            <label for="featured_image">{{ $post->featured_image ? 'Change Featured Image' : 'Upload Featured Image' }}</label>
                            <input type="file"
                                   class="form-control-file @error('featured_image') is-invalid @enderror"
                                   id="featured_image"
                                   name="featured_image"
                                   accept="image/*">
                            @error('featured_image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Recommended: 1200x630px (JPG, PNG)
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Post Statistics -->
                <div class="card">
                    <div class="card-header">
                        <h4>Statistics</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <td><i class="fas fa-eye text-primary"></i> <strong>Views:</strong></td>
                                <td>{{ $post->views ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-calendar text-success"></i> <strong>Created:</strong></td>
                                <td>{{ $post->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-clock text-warning"></i> <strong>Updated:</strong></td>
                                <td>{{ $post->updated_at->format('M d, Y H:i') }}</td>
                            </tr>
                            @if($post->author)
                                <tr>
                                    <td><i class="fas fa-user text-info"></i> <strong>Author:</strong></td>
                                    <td>{{ $post->author->name }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('custom-scripts')
<script>
    // Auto-generate slug from title
    $('#title').on('input', function() {
        let title = $(this).val();
        let slug = title.toLowerCase()
            .replace(/[^\w\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/--+/g, '-')
            .trim();
        $('#slug').val(slug);
    });

    // Count meta description characters
    $('#meta_description').on('input', function() {
        let count = $(this).val().length;
        $('#meta-description-count').text(count);

        if (count > 160) {
            $('#meta-description-count').addClass('text-danger');
        } else if (count > 150) {
            $('#meta-description-count').addClass('text-warning').removeClass('text-danger');
        } else {
            $('#meta-description-count').removeClass('text-warning text-danger');
        }
    });

    // Preview featured image
    $('#featured_image').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#featured-image-preview img').attr('src', e.target.result);
                $('#featured-image-preview').show();
            };
            reader.readAsDataURL(file);
        }
    });

    function removeFeaturedImage() {
        $('#featured_image').val('');
        $('#featured-image-preview').hide();
        $('#featured-image-preview img').attr('src', '');
    }

    // Trigger count on page load
    $('#meta_description').trigger('input');
</script>
@endpush
