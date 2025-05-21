@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">My Teams</h1>
        <a href="{{ route('teams.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Create Team
        </a>
    </div>

    <div class="row">
        @if($ownedTeams->count() > 0 || $teams->count() > 0)
            @foreach($ownedTeams as $team)
                <div class="col-md-6 col-lg-4 mb-4 fade-in">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3" style="background-color: #{{ substr(md5($team->name), 0, 6) }}">
                                    {{ strtoupper(substr($team->name, 0, 1)) }}
                                </div>
                                <h5 class="card-title mb-0">{{ $team->name }}</h5>
                            </div>
                            <span class="badge bg-primary">Owner</span>
                        </div>
                        <div class="card-body">
                            <p class="card-text">{{ $team->description ?: 'No description provided.' }}</p>
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-users me-2 text-muted"></i>
                                <span>{{ $team->users->count() + 1 }} {{ Str::plural('member', $team->users->count() + 1) }}</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-project-diagram me-2 text-muted"></i>
                                <span>{{ $team->projects->count() }} {{ Str::plural('project', $team->projects->count()) }}</span>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <div class="d-grid gap-2">
                                <a href="{{ route('teams.show', $team) }}" class="btn btn-outline-primary">View Team</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            @foreach($teams as $team)
                <div class="col-md-6 col-lg-4 mb-4 fade-in">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3" style="background-color: #{{ substr(md5($team->name), 0, 6) }}">
                                    {{ strtoupper(substr($team->name, 0, 1)) }}
                                </div>
                                <h5 class="card-title mb-0">{{ $team->name }}</h5>
                            </div>
                            <span class="badge bg-secondary">Member</span>
                        </div>
                        <div class="card-body">
                            <p class="card-text">{{ $team->description ?: 'No description provided.' }}</p>
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-user me-2 text-muted"></i>
                                <span>Owner: {{ $team->owner->name }}</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-project-diagram me-2 text-muted"></i>
                                <span>{{ $team->projects->count() }} {{ Str::plural('project', $team->projects->count()) }}</span>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <div class="d-grid gap-2">
                                <a href="{{ route('teams.show', $team) }}" class="btn btn-outline-primary">View Team</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h3 class="h4 mb-3">No Teams Yet</h3>
                        <p class="text-muted mb-4">You don't have any teams yet. Create your first team to start collaborating.</p>
                        <a href="{{ route('teams.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Create Team
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection