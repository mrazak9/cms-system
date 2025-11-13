{{-- Individual Comment Item --}}
<div class="comment-item {{ $depth > 0 ? 'comment-reply' : '' }}">
    <div class="d-flex">
        {{-- Avatar --}}
        <div class="flex-shrink-0 me-3">
            <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                 style="width: 40px; height: 40px;">
                {{ strtoupper(substr($comment->author_name, 0, 1)) }}
            </div>
        </div>

        {{-- Comment Content --}}
        <div class="flex-grow-1">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <span class="comment-author">{{ $comment->author_name }}</span>
                    @if($comment->user_id && auth()->check() && $comment->user_id === auth()->id())
                        <span class="badge bg-secondary ms-2">You</span>
                    @endif
                </div>
                <span class="comment-date">
                    <i class="far fa-clock me-1"></i>
                    {{ $comment->created_at->diffForHumans() }}
                </span>
            </div>

            <div class="comment-content">
                {{ $comment->content }}
            </div>

            {{-- Reply Button --}}
            @if(($commentsEnabled ?? true) && $depth < 3)
                <div class="mt-3">
                    <button type="button"
                            class="btn btn-sm btn-outline-primary reply-button"
                            onclick="replyToComment({{ $comment->id }}, '{{ $comment->author_name }}')">
                        <i class="fas fa-reply me-1"></i>
                        Reply
                    </button>
                </div>
            @endif

            {{-- Nested Replies --}}
            @if($comment->approvedReplies()->count() > 0)
                <div class="replies mt-3">
                    @foreach($comment->approvedReplies as $reply)
                        @include('frontend.partials.comment-item', ['comment' => $reply, 'depth' => $depth + 1])
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
