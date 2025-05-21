@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-3">
            <div class="sidebar p-3 bg-white rounded shadow-sm">
                <div class="d-flex align-items-center pb-3 mb-3 border-bottom">
                    <span class="fs-5 fw-semibold">Dashboard</span>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i> Overview
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('teams.index') }}">
                            <i class="fas fa-users me-2"></i> My Teams
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-tasks me-2"></i> My Tasks
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-bell me-2"></i> Notifications
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-cog me-2"></i> Settings
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-md-9">
            <div class="bg-white p-4 rounded shadow-sm mb-4 fade-in">
                <h1 class="h3 mb-0">Welcome, {{ Auth::user()->name }}</h1>
                <p class="text-muted">Here's an overview of your recent activity</p>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-4 slide-up delay-1">
                    <div class="bg-white p-4 rounded shadow-sm h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h2 class="h5 mb-0">My Teams</h2>
                            <a href="{{ route('teams.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i></a>
                        </div>
                        @if(Auth::user()->teams->count() + Auth::user()->ownedTeams->count() > 0)
                            <ul class="list-group list-group-flush">
                                @foreach(Auth::user()->ownedTeams as $team)
                                    <li class="list-group-item px-0">
                                        <a href="{{ route('teams.show', $team) }}" class="text-decoration-none text-dark">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar me-3" style="background-color: #{{ substr(md5($team->name), 0, 6) }}">
                                                    {{ strtoupper(substr($team->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <strong>{{ $team->name }}</strong>
                                                    <div class="small text-muted">Owner</div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                                @foreach(Auth::user()->teams as $team)
                                    <li class="list-group-item px-0">
                                        <a href="{{ route('teams.show', $team) }}" class="text-decoration-none text-dark">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar me-3" style="background-color: #{{ substr(md5($team->name), 0, 6) }}">
                                                    {{ strtoupper(substr($team->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <strong>{{ $team->name }}</strong>
                                                    <div class="small text-muted">Member</div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-center py-3">
                                <p class="text-muted mb-3">You don't have any teams yet</p>
                                <a href="{{ route('teams.create') }}" class="btn btn-primary">Create a Team</a>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-8 slide-up delay-2">
                    <div class="bg-white p-4 rounded shadow-sm h-100">
                        <h2 class="h5 mb-3">Recent Activity</h2>
                        <div class="activity-timeline">
                            @php
                                $activities = Spatie\Activitylog\Models\Activity::causedBy(Auth::user())
                                    ->orderBy('created_at', 'desc')
                                    ->take(5)
                                    ->get();
                            @endphp

                            @if($activities->count() > 0)
                                <ul class="list-group list-group-flush">
                                    @foreach($activities as $activity)
                                        <li class="list-group-item px-0">
                                            <div class="d-flex">
                                                <div class="me-3">
                                                    <div class="bg-light rounded-circle p-2">
                                                        <i class="fas fa-check text-primary"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="fw-medium">{{ $activity->description }}</div>
                                                    <div class="small text-muted">{{ $activity->created_at->diffForHumans() }}</div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="text-center py-3">
                                    <p class="text-muted">No recent activities</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6 slide-up delay-3">
                    <div class="bg-white p-4 rounded shadow-sm h-100">
                        <h2 class="h5 mb-3">My Tasks</h2>
                        @php
                            $myTasks = Auth::user()->tasks()->with('project')->take(5)->get();
                        @endphp

                        @if($myTasks->count() > 0)
                            <ul class="list-group list-group-flush">
                                @foreach($myTasks as $task)
                                    <li class="list-group-item px-0">
                                        <a href="{{ route('tasks.show', $task) }}" class="text-decoration-none text-dark">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <div class="fw-medium">{{ $task->title }}</div>
                                                    <div class="small text-muted">{{ $task->project->name }}</div>
                                                </div>
                                                @if($task->due_date)
                                                    <div class="small {{ $task->due_date < now() ? 'text-danger' : 'text-muted' }}">
                                                        Due {{ $task->due_date->format('M d') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-center py-3">
                                <p class="text-muted">No tasks assigned to you</p>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-6 slide-up delay-4">
                    <div class="bg-white p-4 rounded shadow-sm h-100">
                        <h2 class="h5 mb-3">Quick Actions</h2>
                        <div class="d-grid gap-2">
                            <a href="{{ route('teams.create') }}" class="btn btn-outline-primary">
                                <i class="fas fa-users me-2"></i> Create a New Team
                            </a>
                            @if(Auth::user()->ownedTeams->count() > 0)
                                <a href="{{ route('projects.create', Auth::user()->ownedTeams->first()) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-project-diagram me-2"></i> Create a New Project
                                </a>
                            @endif
                            <a href="{{ route('teams.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-tasks me-2"></i> View All Teams
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection