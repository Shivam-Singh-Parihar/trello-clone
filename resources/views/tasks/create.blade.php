<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($task) ? 'Edit Task' : 'Create Task' }} | Task Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
        body {
            background-color: #f9fafc;
        }
        .card {
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #f1f3f5;
            border-bottom: 1px solid #e9ecef;
        }
        .form-control:focus, .form-select:focus {
            border-color: #7aa7ff;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
        }
        .task-form {
            max-width: 750px;
            margin: 0 auto;
        }
        .btn-primary {
            background-color: #0079bf;
            border-color: #0079bf;
        }
        .btn-outline-secondary {
            color: #6c757d;
            border-color: #ced4da;
        }
        .due-date-input {
            position: relative;
        }
        .due-date-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="task-form"
            <!-- Form Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">{{ isset($task) ? 'Edit Task' : 'Create Task' }}</h2>
                <a href="{{ isset($task) ? route('tasks.show', $task) : route('projects.show', $list->project) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i>
                </a>
            </div>

            <!-- Task Form Card -->
            <div class="card">
                <div class="card-header py-3">
                    <h5 class="mb-0">{{ isset($task) ? 'Edit Task Details' : 'New Task in "' . $list->name . '"' }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ isset($task) ? route('tasks.update', $task) : route('tasks.store', $list) }}" method="POST">
                        @csrf
                        @if(isset($task))
                            @method('PUT')
                        @endif

                        <!-- Title Field -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Task Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                id="title" name="title" value="{{ $task->title ?? old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description Field -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                id="description" name="description" rows="4">{{ $task->description ?? old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <!-- Due Date Field -->
                            <div class="col-md-6 mb-3">
                                <label for="due_date" class="form-label">Due Date</label>
                                <div class="due-date-input">
                                    <input type="date" class="form-control @error('due_date') is-invalid @enderror"
                                        id="due_date" name="due_date" value="{{ isset($task) && $task->due_date ? date('Y-m-d', strtotime($task->due_date)) : old('due_date') }}">
                                    <span class="due-date-icon">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div>
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Assignee Field -->
                            <div class="col-md-6 mb-3">
                                <label for="assignee_id" class="form-label">Assign To</label>
                                <select class="form-select @error('assignee_id') is-invalid @enderror" id="assignee_id" name="assignee_id">
                                    <option value="">Unassigned</option>
                                    @foreach($teamMembers as $member)
                                        <option value="{{ $member->id }}"
                                            {{ (isset($task) && $task->assignee_id == $member->id) || old('assignee_id') == $member->id ? 'selected' : '' }}>
                                            {{ $member->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('assignee_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        @if(isset($task) && isset($lists))
                        <!-- List Field (only for edit) -->
                        <div class="mb-3">
                            <label for="list_id" class="form-label">List</label>
                            <select class="form-select @error('list_id') is-invalid @enderror" id="list_id" name="list_id">
                                @foreach($lists as $taskList)
                                    <option value="{{ $taskList->id }}"
                                        {{ $task->list_id == $taskList->id ? 'selected' : '' }}>
                                        {{ $taskList->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('list_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @endif

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ isset($task) ? route('tasks.show', $task) : route('projects.show', $list->project) }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                {{ isset($task) ? 'Update Task' : 'Create Task' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if(isset($task))
            <!-- Delete Task Option (only for edit) -->
            <div class="mt-4">
                <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="fas fa-trash-alt me-1"></i> Delete Task
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
