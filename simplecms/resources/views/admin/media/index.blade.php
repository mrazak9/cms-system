@extends('admin.layouts.app')

@section('title', 'Media Library')
@section('page-title', 'Media Library')

@section('breadcrumb')
    <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
    <div class="breadcrumb-item active">Media</div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Media Files</h4>
                    <div class="card-header-action">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#uploadMediaModal">
                            <i class="fas fa-upload"></i> Upload Files
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filter and Search -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text"
                                       class="form-control"
                                       id="search-media"
                                       placeholder="Search media files...">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select class="form-control" id="filter-type">
                                <option value="">All Types</option>
                                <option value="image">Images</option>
                                <option value="document">Documents</option>
                                <option value="video">Videos</option>
                                <option value="audio">Audio</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="btn-group float-right">
                                <button class="btn btn-outline-primary" id="view-grid" data-view="grid">
                                    <i class="fas fa-th"></i>
                                </button>
                                <button class="btn btn-outline-primary active" id="view-list" data-view="list">
                                    <i class="fas fa-list"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    @if(isset($media) && count($media) > 0)
                        <!-- Grid View -->
                        <div id="media-grid" class="row">
                            @foreach($media as $item)
                                <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-4 media-item" data-type="{{ $item->type ?? 'unknown' }}">
                                    <div class="card h-100 shadow-sm">
                                        <div class="card-body p-2 text-center">
                                            @if(in_array($item->extension ?? '', ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']))
                                                <img src="{{ asset('storage/' . $item->path) }}"
                                                     alt="{{ $item->filename }}"
                                                     class="img-fluid mb-2"
                                                     style="max-height: 120px; object-fit: cover; cursor: pointer;"
                                                     onclick="viewMedia({{ $item->id }})">
                                            @elseif(in_array($item->extension ?? '', ['pdf']))
                                                <div class="text-center py-3" style="cursor: pointer;" onclick="viewMedia({{ $item->id }})">
                                                    <i class="far fa-file-pdf fa-4x text-danger"></i>
                                                </div>
                                            @elseif(in_array($item->extension ?? '', ['doc', 'docx']))
                                                <div class="text-center py-3" style="cursor: pointer;" onclick="viewMedia({{ $item->id }})">
                                                    <i class="far fa-file-word fa-4x text-primary"></i>
                                                </div>
                                            @elseif(in_array($item->extension ?? '', ['xls', 'xlsx']))
                                                <div class="text-center py-3" style="cursor: pointer;" onclick="viewMedia({{ $item->id }})">
                                                    <i class="far fa-file-excel fa-4x text-success"></i>
                                                </div>
                                            @elseif(in_array($item->extension ?? '', ['mp4', 'avi', 'mov', 'wmv']))
                                                <div class="text-center py-3" style="cursor: pointer;" onclick="viewMedia({{ $item->id }})">
                                                    <i class="far fa-file-video fa-4x text-info"></i>
                                                </div>
                                            @elseif(in_array($item->extension ?? '', ['mp3', 'wav', 'ogg']))
                                                <div class="text-center py-3" style="cursor: pointer;" onclick="viewMedia({{ $item->id }})">
                                                    <i class="far fa-file-audio fa-4x text-warning"></i>
                                                </div>
                                            @elseif(in_array($item->extension ?? '', ['zip', 'rar', '7z']))
                                                <div class="text-center py-3" style="cursor: pointer;" onclick="viewMedia({{ $item->id }})">
                                                    <i class="far fa-file-archive fa-4x text-secondary"></i>
                                                </div>
                                            @else
                                                <div class="text-center py-3" style="cursor: pointer;" onclick="viewMedia({{ $item->id }})">
                                                    <i class="far fa-file fa-4x text-muted"></i>
                                                </div>
                                            @endif

                                            <small class="d-block text-truncate" title="{{ $item->filename }}">
                                                {{ Str::limit($item->filename, 15) }}
                                            </small>
                                            <small class="text-muted">
                                                {{ $item->size_formatted ?? formatBytes($item->size ?? 0) }}
                                            </small>
                                        </div>
                                        <div class="card-footer p-1">
                                            <div class="btn-group btn-group-sm w-100">
                                                <button class="btn btn-info"
                                                        onclick="viewMedia({{ $item->id }})"
                                                        title="View">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-success"
                                                        onclick="copyUrl('{{ asset('storage/' . $item->path) }}')"
                                                        title="Copy URL">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                                <button class="btn btn-danger"
                                                        onclick="deleteMedia({{ $item->id }})"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                            <form id="delete-form-{{ $item->id }}"
                                                  action="{{ route('admin.media.destroy', $item->id) }}"
                                                  method="POST"
                                                  style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- List View (Hidden by default) -->
                        <div id="media-list" class="table-responsive" style="display: none;">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Preview</th>
                                        <th>Filename</th>
                                        <th>Type</th>
                                        <th>Size</th>
                                        <th>Uploaded</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($media as $item)
                                        <tr class="media-item" data-type="{{ $item->type ?? 'unknown' }}">
                                            <td style="width: 80px;">
                                                @if(in_array($item->extension ?? '', ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']))
                                                    <img src="{{ asset('storage/' . $item->path) }}"
                                                         alt="{{ $item->filename }}"
                                                         class="img-thumbnail"
                                                         style="max-width: 60px; max-height: 60px;">
                                                @else
                                                    <i class="far fa-file fa-2x text-muted"></i>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $item->filename }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $item->original_filename ?? $item->filename }}</small>
                                            </td>
                                            <td>
                                                <span class="badge badge-info">{{ strtoupper($item->extension ?? 'unknown') }}</span>
                                            </td>
                                            <td>{{ $item->size_formatted ?? formatBytes($item->size ?? 0) }}</td>
                                            <td>
                                                <div>{{ $item->created_at->format('M d, Y') }}</div>
                                                <small class="text-muted">{{ $item->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button class="btn btn-sm btn-info"
                                                            onclick="viewMedia({{ $item->id }})"
                                                            title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-success"
                                                            onclick="copyUrl('{{ asset('storage/' . $item->path) }}')"
                                                            title="Copy URL">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                    <a href="{{ asset('storage/' . $item->path) }}"
                                                       download
                                                       class="btn btn-sm btn-primary"
                                                       title="Download">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                    <button class="btn btn-sm btn-danger"
                                                            onclick="deleteMedia({{ $item->id }})"
                                                            title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="far fa-images fa-4x mb-3"></i>
                            <h5>No Media Files</h5>
                            <p>Upload your first media file to get started</p>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#uploadMediaModal">
                                <i class="fas fa-upload"></i> Upload Files
                            </button>
                        </div>
                    @endif
                </div>
                @if(isset($media) && $media->hasPages())
                    <div class="card-footer text-right">
                        {{ $media->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Upload Media Modal -->
    <div class="modal fade" id="uploadMediaModal" tabindex="-1" role="dialog" aria-labelledby="uploadMediaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadMediaModalLabel">Upload Media Files</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="media_files">Select Files <span class="text-danger">*</span></label>
                            <div class="custom-file">
                                <input type="file"
                                       class="custom-file-input @error('files') is-invalid @enderror"
                                       id="media_files"
                                       name="files[]"
                                       multiple
                                       required>
                                <label class="custom-file-label" for="media_files">Choose files</label>
                                @error('files')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">
                                You can select multiple files. Supported formats: images, documents, videos, audio files, archives.
                                Maximum size per file: 10MB
                            </small>
                        </div>

                        <div id="preview-container" class="row mt-3"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Upload Files
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Media Modal -->
    <div class="modal fade" id="viewMediaModal" tabindex="-1" role="dialog" aria-labelledby="viewMediaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewMediaModalLabel">Media Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="media-details-content">
                    <!-- Content loaded via JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
<script>
    // View toggle
    $('#view-grid, #view-list').click(function() {
        const view = $(this).data('view');
        $('#view-grid, #view-list').removeClass('active');
        $(this).addClass('active');

        if (view === 'grid') {
            $('#media-grid').show();
            $('#media-list').hide();
        } else {
            $('#media-grid').hide();
            $('#media-list').show();
        }
    });

    // Filter by type
    $('#filter-type').change(function() {
        const type = $(this).val();
        if (type === '') {
            $('.media-item').show();
        } else {
            $('.media-item').hide();
            $('.media-item[data-type="' + type + '"]').show();
        }
    });

    // Search functionality
    $('#search-media').on('input', function() {
        const search = $(this).val().toLowerCase();
        $('.media-item').each(function() {
            const filename = $(this).find('small').first().text().toLowerCase();
            if (filename.includes(search)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // File input change
    $('#media_files').on('change', function() {
        let files = $(this)[0].files;
        let fileNames = Array.from(files).map(f => f.name).join(', ');
        $(this).next('.custom-file-label').addClass("selected").html(
            files.length > 1 ? files.length + ' files selected' : fileNames
        );

        // Preview images
        let previewContainer = $('#preview-container');
        previewContainer.empty();

        Array.from(files).forEach(file => {
            if (file.type.startsWith('image/')) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    previewContainer.append(`
                        <div class="col-3 mb-2">
                            <img src="${e.target.result}" class="img-thumbnail" style="height: 100px; object-fit: cover;">
                        </div>
                    `);
                };
                reader.readAsDataURL(file);
            }
        });
    });

    // Copy URL to clipboard
    function copyUrl(url) {
        navigator.clipboard.writeText(url).then(function() {
            alert('URL copied to clipboard!');
        }, function(err) {
            console.error('Could not copy text: ', err);
        });
    }

    // Delete media
    function deleteMedia(id) {
        if (confirm('Are you sure you want to delete this media file? This action cannot be undone.')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }

    // View media details
    function viewMedia(id) {
        // This would load media details via AJAX
        $('#media-details-content').html('<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i></div>');
        $('#viewMediaModal').modal('show');

        // Simulated content - in real application, load via AJAX
        setTimeout(() => {
            $('#media-details-content').html(`
                <div class="text-center">
                    <p>Media ID: ${id}</p>
                    <p class="text-muted">Details would be loaded here via AJAX</p>
                </div>
            `);
        }, 500);
    }

    // Auto-show modal if there are validation errors
    @if($errors->any())
        $('#uploadMediaModal').modal('show');
    @endif
</script>
@endpush
