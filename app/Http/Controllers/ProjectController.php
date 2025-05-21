<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Team;
use App\Models\TaskList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Team $team)
    {

        if (! $team->hasUser(auth()->user())) {
            abort(403);
        }

        return view('projects.create', compact('team'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Team $team)
    {
        if (! $team->hasUser(auth()->user())) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'team_id' => $team->id,
        ]);

        // Create default lists
        TaskList::create([
            'name' => 'To Do',
            'position' => 0,
            'project_id' => $project->id,
        ]);

        TaskList::create([
            'name' => 'In Progress',
            'position' => 1,
            'project_id' => $project->id,
        ]);

        TaskList::create([
            'name' => 'Done',
            'position' => 2,
            'project_id' => $project->id,
        ]);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        if (! $project->team->hasUser(auth()->user())) {
            abort(403);
        }

        $lists = $project->lists()->with(['tasks' => function ($query) {
            $query->orderBy('position');
        }])->orderBy('position')->get();

        $teamMembers = $project->team->users;

        return view('projects.show', compact('project', 'lists', 'teamMembers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(Project $project)
    // {
    //     if (! Gate::allows('update', $project)) {
    //         abort(403);
    //     }

    //     return view('projects.edit', compact('project'));
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        if (! Gate::allows('update', $project)) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $project->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        if (! Gate::allows('delete', $project)) {
            abort(403);
        }

        $project->delete();

        return redirect()->route('teams.show', $project->team)
            ->with('success', 'Project deleted successfully.');
    }
}
