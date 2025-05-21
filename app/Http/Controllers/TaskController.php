<?php
namespace App\Http\Controllers;
use App\Events\TaskAssigned;
use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
class TaskController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(TaskList $list)
    {
        if (!$list->project->team->hasUser(auth()->user())) {
            abort(403);
        }
        $teamMembers = $list->project->team->users;
        return view('tasks.create', compact('list', 'teamMembers'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, TaskList $list)
    {
        if (!$list->project->team->hasUser(auth()->user())) {
            abort(403);
        }
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
            'assignee_id' => ['nullable', 'exists:users,id'],
        ]);
        // Get the highest position
        $maxPosition = $list->tasks()->max('position') ?? -1;
        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'assignee_id' => $request->assignee_id,
            'list_id' => $list->id,
            'project_id' => $list->project_id,
            'position' => $maxPosition + 1,
        ]);
        // If task is assigned to someone, fire event
        if ($task->assignee_id) {
            event(new TaskAssigned($task));
        }
        if ($request->wantsJson()) {
            return response()->json($task);
        }
        return redirect()->route('projects.show', $list->project)
            ->with('success', 'Task created successfully.');
    }
    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        if (!$task->project->team->hasUser(auth()->user())) {
            abort(403);
        }
        $comments = $task->comments()->with('user')->latest()->get();
        $attachments = $task->attachments()->with('user')->latest()->get();
        $teamMembers = $task->project->team->users;
        return view('tasks.show', compact('task', 'comments', 'attachments', 'teamMembers'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task, Request $rquest)
    {
        if (!$task->project->team->hasUser(auth()->user())) {
            abort(403);
        }
        if ($rquest->ajax()) {
            $teamMembers = $task->project->team->users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'avatar' => $user->avatar_url, // or any other user data you need
                ];
            });
            $lists = $task->project->lists->map(function ($list) {
                return [
                    'id' => $list->id,
                    'name' => $list->name,
                ];
            });
            return response()->json([
                'success' => true,
                'html' => view('tasks.partials.edit-form', [
                    'task' => $task,
                    'teamMembers' => $teamMembers,
                    'lists' => $lists,
                ])->render(),
                'task' => [
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'due_date' => optional($task->due_date)->format('Y-m-d'),
                    'list_id' => $task->list_id,
                    'assignee_id' => $task->assignee_id,
                    // include any other task fields you need
                ],
                'teamMembers' => $teamMembers,
                'lists' => $lists,
            ]);
        }
        abort(404);
        // return view('tasks.edit', compact('task', 'teamMembers'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        if (!$task->project->team->hasUser(auth()->user())) {
            abort(403);
        }
        $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
            'assignee_id' => ['nullable', 'exists:users,id'],
            'list_id' => ['sometimes', 'exists:task_lists,id'],
            'position' => ['sometimes', 'integer', 'min:0'],
        ]);
        $oldAssigneeId = $task->assignee_id;
        $task->update($request->only(['title', 'description', 'due_date', 'assignee_id', 'list_id', 'position']));
        // If assignee changed and not null, fire event
        if ($task->assignee_id && $task->assignee_id !== $oldAssigneeId) {
            event(new TaskAssigned($task));
        }
        if ($request->wantsJson()) {
            return response()->json($task);
        }
        return redirect()->route('tasks.show', $task)
            ->with('success', 'Task updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        if (!$task->project->team->hasUser(auth()->user())) {
            abort(403);
        }
        $project = $task->project;
        $task->delete();
        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }
        return redirect()->route('projects.show', $project)
            ->with('success', 'Task deleted successfully.');
    }
    /**
     * Update positions of multiple tasks.
     */
    public function updatePositions(Request $request)
    {
        $request->validate([
            'tasks' => ['required', 'array'],
            'tasks.*.id' => ['required', 'exists:tasks,id'],
            'tasks.*.position' => ['required', 'integer', 'min:0'],
            'tasks.*.list_id' => ['required', 'exists:task_lists,id'],
        ]);
        foreach ($request->tasks as $taskData) {
            $task = Task::find($taskData['id']);
            if (!$task->project->team->hasUser(auth()->user())) {
                abort(403);
            }
            $task->update([
                'position' => $taskData['position'],
                'list_id' => $taskData['list_id'],
            ]);
        }
        return response()->json(['success' => true]);
    }
}
