<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\TaskList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TaskListController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Project $project)
    {
        if (! Gate::allows('update', $project)) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        // Get the highest position
        $maxPosition = $project->lists()->max('position') ?? -1;

        $list = TaskList::create([
            'name' => $request->name,
            'position' => $maxPosition + 1,
            'project_id' => $project->id,
        ]);

        if ($request->wantsJson()) {
            return response()->json($list);
        }

        return redirect()->route('projects.show', $project)
            ->with('success', 'List created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaskList $list)
    {
        if (! Gate::allows('update', $list->project)) {
            abort(403);
        }

        $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'position' => ['sometimes', 'required', 'integer', 'min:0'],
        ]);

        $list->update($request->only(['name', 'position']));

        if ($request->wantsJson()) {
            return response()->json($list);
        }

        return redirect()->route('projects.show', $list->project)
            ->with('success', 'List updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaskList $list)
    {
        if (! Gate::allows('update', $list->project)) {
            abort(403);
        }

        $project = $list->project;
        $list->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('projects.show', $project)
            ->with('success', 'List deleted successfully.');
    }

    /**
     * Update positions of multiple lists.
     */
    public function updatePositions(Request $request)
    {
        $request->validate([
            'lists' => ['required', 'array'],
            'lists.*.id' => ['required', 'exists:task_lists,id'],
            'lists.*.position' => ['required', 'integer', 'min:0'],
        ]);

        foreach ($request->lists as $listData) {
            $list = TaskList::find($listData['id']);
            
            if (! Gate::allows('update', $list->project)) {
                abort(403);
            }

            $list->update(['position' => $listData['position']]);
        }

        return response()->json(['success' => true]);
    }
}