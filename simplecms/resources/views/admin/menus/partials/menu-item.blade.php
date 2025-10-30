<div class="menu-item" data-id="{{ $item->id }}" data-parent-id="{{ $item->parent_id ?? '' }}" style="margin-left: {{ $level * 30 }}px;">
    <div class="menu-item-content">
        <div class="d-flex justify-content-between align-items-center p-3 bg-white border rounded mb-2">
            <div class="flex-grow-1">
                <i class="fas fa-grip-vertical text-muted mr-2 drag-handle" style="cursor: move;"></i>
                @if ($level > 0)
                    <i class="fas fa-level-up-alt text-muted mr-1" style="transform: rotate(90deg);"></i>
                @endif
                <strong>{{ $item->title }}</strong>
                <br>
                <small class="text-muted ml-4">
                    @if ($item->type == 'page')
                        <i class="fas fa-file-alt"></i> Page: {{ $item->url }}
                    @elseif($item->type == 'post')
                        <i class="fas fa-newspaper"></i> Post: {{ $item->url }}
                    @elseif($item->type == 'category')
                        <i class="fas fa-folder"></i> Category: {{ $item->url }}
                    @else
                        <i class="fas fa-link"></i> Custom: {{ $item->url }}
                    @endif
                    @if ($item->parent_id)
                        <span class="badge badge-secondary ml-2">Sub-item</span>
                    @endif
                </small>
            </div>
            <div>
                <button type="button" class="btn btn-sm btn-info"
                    onclick="editMenuItem({{ $item->id }})">
                    <i class="fas fa-edit"></i>
                </button>
                <button type="button" class="btn btn-sm btn-danger"
                    onclick="deleteMenuItem({{ $item->id }})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>

    @if ($item->children && $item->children->count() > 0)
        <div class="menu-item-children">
            @foreach ($item->children->sortBy('order') as $child)
                @include('admin.menus.partials.menu-item', ['item' => $child, 'level' => $level + 1])
            @endforeach
        </div>
    @endif
</div>
