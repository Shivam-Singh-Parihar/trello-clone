<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Task $task)
    {
        if (! $task->project->team->hasUser(auth()->user())) {
            abort(403);
        }

        $request->validate([
            'file' => ['required', 'file', 'max:10240'], // 10MB max
        ]);

        $file = $request->file('file');
        $path = $file->store('attachments/' . $task->id, 'public');

        $attachment = Attachment::create([
            'filename' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'task_id' => $task->id,
            'user_id' => auth()->id(),
        ]);

        // Load user relationship
        $attachment->load('user');

        if ($request->wantsJson()) {
            return response()->json($attachment);
        }

        return redirect()->route('tasks.show', $task)
            ->with('success', 'File uploaded successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attachment $attachment)
    {
        if (! Gate::allows('delete', $attachment)) {
            abort(403);
        }

        $task = $attachment->task;

        // Delete the file
        Storage::disk('public')->delete($attachment->path);

        $attachment->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Attachment deleted successfully.');
    }

    /**
     * Download the specified resource.
     */
    public function download(Attachment $attachment)
    {
        if (! $attachment->task->project->team->hasUser(auth()->user())) {
            abort(403);
        }

        return Storage::disk('public')->download(
            $attachment->path,
            $attachment->filename
        );
    }
}
