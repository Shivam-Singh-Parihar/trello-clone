<?php

namespace App\Http\Controllers;

use App\Events\CommentAdded;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
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
            'body' => ['required', 'string'],
        ]);

        $comment = Comment::create([
            'body' => $request->body,
            'task_id' => $task->id,
            'user_id' => auth()->id(),
        ]);

        // Load user relationship
        $comment->load('user');

        // Fire event for real-time updates
        event(new CommentAdded($comment));

        if ($request->wantsJson()) {
            return response()->json($comment);
        }

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Comment added successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        if (! Gate::allows('update', $comment)) {
            abort(403);
        }

        $request->validate([
            'body' => ['required', 'string'],
        ]);

        $comment->update([
            'body' => $request->body,
        ]);

        if ($request->wantsJson()) {
            return response()->json($comment);
        }

        return redirect()->route('tasks.show', $comment->task)
            ->with('success', 'Comment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        if (! Gate::allows('delete', $comment)) {
            abort(403);
        }

        $task = $comment->task;
        $comment->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('tasks.show', $task)
            ->with('success', 'Comment deleted successfully.');
    }
}