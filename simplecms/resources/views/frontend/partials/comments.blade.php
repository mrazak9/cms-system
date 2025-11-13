{{-- Comments Section --}}
<div class="comments-section mt-5">
    <h3 class="fw-bold mb-4">
        <i class="fas fa-comments me-2"></i>
        Comments ({{ $post->approvedComments()->topLevel()->count() }})
    </h3>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Comment Form --}}
    @if($commentsEnabled ?? true)
        <div class="comment-form mb-5">
            <h4 class="mb-3">Leave a Comment</h4>
            <form action="{{ route('comments.store', $post->id) }}" method="POST" id="commentForm">
                @csrf

                @if(!auth()->check())
                    {{-- Guest User Fields --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="author_name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('author_name') is-invalid @enderror"
                                   id="author_name"
                                   name="author_name"
                                   value="{{ old('author_name') }}"
                                   required>
                            @error('author_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="author_email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email"
                                   class="form-control @error('author_email') is-invalid @enderror"
                                   id="author_email"
                                   name="author_email"
                                   value="{{ old('author_email') }}"
                                   required>
                            @error('author_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                @else
                    {{-- Logged In User Info --}}
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-user me-2"></i>
                        Commenting as <strong>{{ auth()->user()->name }}</strong>
                    </div>
                @endif

                <div class="mb-3">
                    <label for="content" class="form-label">Comment <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('content') is-invalid @enderror"
                              id="content"
                              name="content"
                              rows="4"
                              placeholder="Share your thoughts..."
                              required>{{ old('content') }}</textarea>
                    <small class="form-text text-muted">Minimum 3 characters</small>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <input type="hidden" name="parent_id" id="parent_id" value="">

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane me-2"></i>
                    Submit Comment
                </button>
                <button type="button" class="btn btn-secondary d-none" id="cancelReply">
                    Cancel Reply
                </button>
            </form>
        </div>
    @else
        <div class="alert alert-warning">
            <i class="fas fa-info-circle me-2"></i>
            Comments are currently disabled for this post.
        </div>
    @endif

    {{-- Comments List --}}
    @php
        $topLevelComments = $post->approvedComments()->topLevel()->with('replies')->orderBy('created_at', 'desc')->get();
    @endphp

    @if($topLevelComments->count() > 0)
        <div class="comments-list">
            @foreach($topLevelComments as $comment)
                @include('frontend.partials.comment-item', ['comment' => $comment, 'depth' => 0])
            @endforeach
        </div>
    @else
        <div class="alert alert-light text-center">
            <i class="far fa-comments fa-3x text-muted mb-3"></i>
            <p class="mb-0">No comments yet. Be the first to comment!</p>
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Reply functionality
    function replyToComment(commentId, authorName) {
        document.getElementById('parent_id').value = commentId;
        document.getElementById('content').placeholder = 'Replying to ' + authorName + '...';
        document.getElementById('cancelReply').classList.remove('d-none');
        document.getElementById('content').focus();

        // Scroll to form
        document.getElementById('commentForm').scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // Cancel reply
    document.getElementById('cancelReply')?.addEventListener('click', function() {
        document.getElementById('parent_id').value = '';
        document.getElementById('content').placeholder = 'Share your thoughts...';
        this.classList.add('d-none');
    });
</script>
@endpush

@push('styles')
<style>
    .comment-item {
        padding: 1.25rem;
        background-color: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 1rem;
        border-left: 3px solid #dee2e6;
    }

    .comment-item.comment-reply {
        margin-left: 3rem;
        background-color: #ffffff;
        border-left-color: #007bff;
    }

    .comment-author {
        font-weight: 600;
        color: #333;
    }

    .comment-date {
        font-size: 0.875rem;
        color: #6c757d;
    }

    .comment-content {
        margin-top: 0.75rem;
        line-height: 1.6;
    }

    .reply-button {
        font-size: 0.875rem;
        padding: 0.25rem 0.75rem;
    }

    @media (max-width: 768px) {
        .comment-item.comment-reply {
            margin-left: 1.5rem;
        }
    }
</style>
@endpush
