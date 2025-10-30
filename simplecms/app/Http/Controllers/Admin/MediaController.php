<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Admin Media Controller
 *
 * Handles media library upload, listing, and deletion
 */
class MediaController extends Controller
{
    /**
     * Display a listing of media files with pagination
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            $query = Media::with('uploader')->latest();

            // Filter by media type if specified
            if ($request->has('type')) {
                if ($request->type === 'images') {
                    $query->images();
                } elseif ($request->type === 'documents') {
                    $query->documents();
                }
            }

            // Search by filename
            if ($request->has('search') && !empty($request->search)) {
                $query->where('filename', 'like', '%' . $request->search . '%')
                      ->orWhere('title', 'like', '%' . $request->search . '%');
            }

            $media = $query->paginate(20);

            return view('admin.media.index', compact('media'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading media: ' . $e->getMessage());
        }
    }

    /**
     * Show the upload form
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        try {
            return view('admin.media.upload');
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading upload form: ' . $e->getMessage());
        }
    }

    /**
     * Store newly uploaded media
     * This method is called when using standard form submission
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Redirect to upload method which handles the actual upload logic
        return $this->upload($request);
    }

    /**
     * Handle file upload
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'files' => 'required|array',
            'files.*' => 'required|file|max:10240', // Max 10MB per file
            'alt_text' => 'nullable|array',
            'alt_text.*' => 'nullable|string|max:255',
            'title' => 'nullable|array',
            'title.*' => 'nullable|string|max:255',
        ]);

        try {
            $uploadedMedia = [];

            foreach ($request->file('files') as $index => $file) {
                // Store the file
                $path = $file->store('media', 'public');

                // Get file information
                $filename = $file->getClientOriginalName();
                $mimeType = $file->getMimeType();
                $fileSize = $file->getSize();

                // Get alt text and title if provided
                $altText = $request->alt_text[$index] ?? null;
                $title = $request->title[$index] ?? pathinfo($filename, PATHINFO_FILENAME);

                // Create media record
                $media = Media::create([
                    'filename' => $filename,
                    'filepath' => $path,
                    'mime_type' => $mimeType,
                    'file_size' => $fileSize,
                    'alt_text' => $altText,
                    'title' => $title,
                    'uploaded_by' => auth()->id(),
                ]);

                $uploadedMedia[] = $media;
            }

            // Check if it's an AJAX request
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => count($uploadedMedia) . ' file(s) uploaded successfully!',
                    'media' => $uploadedMedia,
                ]);
            }

            return redirect()->route('admin.media.index')
                ->with('success', count($uploadedMedia) . ' file(s) uploaded successfully!');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error uploading files: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Error uploading files: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified media
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        try {
            $media = Media::with('uploader')->findOrFail($id);

            return view('admin.media.show', compact('media'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading media: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing media metadata
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        try {
            $media = Media::findOrFail($id);

            return view('admin.media.edit', compact('media'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading media for editing: ' . $e->getMessage());
        }
    }

    /**
     * Update media metadata
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validate request
        $validated = $request->validate([
            'alt_text' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
        ]);

        try {
            $media = Media::findOrFail($id);

            // Update media metadata
            $media->update([
                'alt_text' => $validated['alt_text'] ?? null,
                'title' => $validated['title'] ?? $media->title,
            ]);

            return redirect()->route('admin.media.index')
                ->with('success', 'Media updated successfully!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error updating media: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified media file
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        try {
            $media = Media::findOrFail($id);

            // Delete the file from storage
            if (Storage::disk('public')->exists($media->filepath)) {
                Storage::disk('public')->delete($media->filepath);
            }

            // Delete the media record
            $media->delete();

            // Check if it's an AJAX request
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Media deleted successfully!',
                ]);
            }

            return redirect()->route('admin.media.index')
                ->with('success', 'Media deleted successfully!');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting media: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Error deleting media: ' . $e->getMessage());
        }
    }
}
