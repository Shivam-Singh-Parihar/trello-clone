@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="d-flex align-items-center">
                <div class="avatar me-3" style="background-color: #{{ substr(md5($team->name), 0, 6) }}">
                    {{ strtoupper(substr($team->name, 0, 1)) }}
                </div>
                <h1 class="h3 mb-0">{{ $team->name }}</h1>
            </div>
            <p class="text-muted mb-0 mt-1">{{ $team->description }}</p>
        </div>
        <div class="d-flex">
            @if(auth()->id() === $team->owner_id)
                <a href="{{ route('teams.invite', $team) }}" class="btn btn-outline-primary me-2">
                    <i class="fas fa-user-plus me-1"></i> Invite Members
                </a>
                <a href="{{ route('teams.edit', $team) }}" class="btn btn-outline-primary me-2">
                    <i class="fas fa-edit me-1"></i> Edit Team
                </a>
            @endif
            <a href="{{ route('projects.create', $team) }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> New Project
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4 fade-in">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Projects</h5>
                </div>
                <div class="card-body">
                    @if($projects->count() > 0)
                        <div class="row">
                            @foreach($projects as $project)
                                <div class="col-md-6 mb-3 slide-up delay-{{ $loop->iteration }}">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $project->name }}</h5>
                                            <p class="card-text">{{ Str::limit($project->description, 100) ?: 'No description provided.' }}</p>
                                            <div class="d-flex align-items-center mb-3">
                                                <i class="fas fa-tasks me-2 text-muted"></i>
                                                <span>{{ $project->tasks->count() }} {{ Str::plural('task', $project->tasks->count()) }}</span>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-white border-top-0">
                                            <a href="{{ route('projects.show', $project) }}" class="btn btn-sm btn-outline-primary">View Board</a>
                                            @if(auth()->id() === $team->owner_id)
                                                <a href="{{ route('projects.edit', $project) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-project-diagram fa-3x text-muted mb-3"></i>
                            <h3 class="h5 mb-3">No Projects Yet</h3>
                            <p class="text-muted mb-4">This team doesn't have any projects yet.</p>
                            <a href="{{ route('projects.create', $team) }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Create Project
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm mb-4 fade-in">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Team Members</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3" style="background-color: #{{ substr(md5($team->owner->name), 0, 6) }}">
                                    {{ strtoupper(substr($team->owner->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div>{{ $team->owner->name }}</div>
                                    <small class="text-muted">{{ $team->owner->email }}</small>
                                </div>
                            </div>
                            <span class="badge bg-primary">Owner</span>
                        </li>
                        @foreach($members as $member)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3" style="background-color: #{{ substr(md5($member->name), 0, 6) }}">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div>{{ $member->name }}</div>
                                        <small class="text-muted">{{ $member->email }}</small>
                                    </div>
                                </div>
                                @if(auth()->id() === $team->owner_id)
                                    <form action="{{ route('teams.remove-user', $team) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="user_id" value="{{ $member->id }}">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to remove this member?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
                @if(auth()->id() === $team->owner_id)
                    <div class="card-footer bg-white">
                        <a href="{{ route('teams.invite', $team) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-user-plus me-1"></i> Invite More Members
                        </a>
                    </div>
                @endif
            </div>

            <div class="card shadow-sm fade-in">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Team Activity</h5>
                </div>
                <div class="card-body p-0">
                    @php
                        $activities = Spatie\Activitylog\Models\Activity::where(function($query) use ($team) {
                            $query->where('subject_type', get_class($team))
                                  ->where('subject_id', $team->id);
                        })->orWhere(function($query) use ($team) {
                            $query->where('subject_type', 'App\Models\Project')
                                  ->whereIn('subject_id', $team->projects->pluck('id'));
                        })->orderBy('created_at', 'desc')->take(5)->get();
                    @endphp

                    @if($activities->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($activities as $activity)
                                <li class="list-group-item">
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
                        <div class="text-center py-4">
                            <p class="text-muted">No recent activities</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection