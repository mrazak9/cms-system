<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Revision;
use App\Models\Post;
use App\Models\Page;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class RevisionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:revisions.view')->only(['index', 'show', 'compare']);
        $this->middleware('permission:revisions.restore')->only(['restore']);
    }

    /**
     * Display revisions for a specific model
     */
    public function index($type, $id)
    {
        // Get the model
        $model = $this->getModel($type, $id);

        if (!$model) {
            abort(404, 'Model not found');
        }

        // Check authorization
        $this->authorize('update', $model);

        // Get all revisions
        $revisions = $model->revisions()->with('user')->paginate(20);

        return view('admin.revisions.index', compact('model', 'revisions', 'type', 'id'));
    }

    /**
     * Show a specific revision
     */
    public function show($type, $id, $version)
    {
        $model = $this->getModel($type, $id);

        if (!$model) {
            abort(404, 'Model not found');
        }

        $this->authorize('update', $model);

        $revision = $model->getRevision($version);

        if (!$revision) {
            abort(404, 'Revision not found');
        }

        return view('admin.revisions.show', compact('model', 'revision', 'type', 'id'));
    }

    /**
     * Compare two revisions
     */
    public function compare($type, $id, Request $request)
    {
        $model = $this->getModel($type, $id);

        if (!$model) {
            abort(404, 'Model not found');
        }

        $this->authorize('update', $model);

        $version1 = $request->input('version1');
        $version2 = $request->input('version2');

        $revision1 = $model->getRevision($version1);
        $revision2 = $model->getRevision($version2);

        if (!$revision1 || !$revision2) {
            return back()->with('error', 'One or both revisions not found');
        }

        $diff = $model->compareRevisions($version1, $version2);

        return view('admin.revisions.compare', compact('model', 'revision1', 'revision2', 'diff', 'type', 'id'));
    }

    /**
     * Restore a specific revision
     */
    public function restore($type, $id, $version)
    {
        $model = $this->getModel($type, $id);

        if (!$model) {
            abort(404, 'Model not found');
        }

        $this->authorize('update', $model);

        $success = $model->restoreToRevision($version);

        if ($success) {
            // Log activity
            ActivityLog::log(
                ActivityLog::TYPE_UPDATE,
                "Restored " . class_basename($model) . " '{$model->title}' to version {$version}",
                $model
            );

            return redirect()->route('admin.revisions.index', ['type' => $type, 'id' => $id])
                ->with('success', 'Successfully restored to version ' . $version);
        }

        return back()->with('error', 'Failed to restore revision');
    }

    /**
     * Get the model instance
     */
    protected function getModel($type, $id)
    {
        switch ($type) {
            case 'post':
                return Post::find($id);
            case 'page':
                return Page::find($id);
            default:
                return null;
        }
    }

    /**
     * Get the edit route for a model
     */
    protected function getEditRoute($model)
    {
        if ($model instanceof Post) {
            return route('admin.posts.edit', $model->id);
        } elseif ($model instanceof Page) {
            return route('admin.pages.edit', $model->id);
        }

        return route('admin.dashboard');
    }
}
