<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ownedTeams = auth()->user()->ownedTeams;
        $teams = auth()->user()->teams;

        return view('teams.index', compact('ownedTeams', 'teams'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('teams.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $team = Team::create([
            'name' => $request->name,
            'description' => $request->description,
            'owner_id' => auth()->id(),
        ]);

        return redirect()->route('teams.show', $team)
            ->with('success', 'Team created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {
        if (! $team->hasUser(auth()->user())) {
            abort(403);
        }

        $projects = $team->projects;
        $members = $team->users;

        return view('teams.show', compact('team', 'projects', 'members'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team)
    {
        dd($team);
        if (! Gate::allows('update', $team)) {
            abort(403);
        }

        return view('teams.edit', compact('team'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team)
    {
        if (! Gate::allows('update', $team)) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $team->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('teams.show', $team)
            ->with('success', 'Team updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        if (! Gate::allows('delete', $team)) {
            abort(403);
        }

        $team->delete();

        return redirect()->route('teams.index')
            ->with('success', 'Team deleted successfully.');
    }

    /**
     * Show the form for inviting a user to the team.
     */
    public function invite(Team $team)
    {
        if (! Gate::allows('update', $team)) {
            abort(403);
        }

        return view('teams.invite', compact('team'));
    }

    /**
     * Send an invitation to the user.
     */
    public function sendInvite(Request $request, Team $team)
    {
        if (! Gate::allows('update', $team)) {
            abort(403);
        }

        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if ($team->hasUser($user)) {
            return back()->withErrors([
                'email' => 'This user is already a member of the team.',
            ]);
        }

        $team->users()->attach($user);

        // Notification is sent via event listener

        return redirect()->route('teams.show', $team)
            ->with('success', 'User invited successfully.');
    }

    /**
     * Remove a user from the team.
     */
    public function removeUser(Request $request, Team $team)
    {
        if (! Gate::allows('update', $team)) {
            abort(403);
        }

        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $user = User::findOrFail($request->user_id);

        if ($team->owner_id === $user->id) {
            return back()->withErrors([
                'user' => 'You cannot remove the team owner.',
            ]);
        }

        $team->users()->detach($user);

        return redirect()->route('teams.show', $team)
            ->with('success', 'User removed successfully.');
    }
}
