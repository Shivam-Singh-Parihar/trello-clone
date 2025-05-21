
@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 px-3">
        <div>
            <a href="{{ route('teams.show', $project->team) }}" class="text-decoration-none mb-2 d-inline-block">
                <i class="fas fa-arrow-left me-1"></i> Back to {{ $project->team->name }}
            </a>
            <h1 class="h3 mb-0">{{ $project->name }}</h1>
            <p class="text-muted mb-0">{{ $project->description }}</p>
        </div>
        <div class="d-flex">
            <button type="button" class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#addListModal">
                <i class="fas fa-plus me-1"></i> Add List
            </button>
            @if(auth()->id() === $project->team->owner_id)
                <a href="{{ route('projects.edit', $project) }}" class="btn btn-outline-primary">
                    <i class="fas fa-edit me-1"></i> Edit Project
                </a>
            @endif
        </div>
    </div>

    <div class="kanban-board px-3">
        @foreach($lists as $list)
            <div class="kanban-column" data-list-id="{{ $list->id }}">
                <div class="kanban-column-header">
                    <div class="d-flex align-items-center">
                        <span>{{ $list->name }}</span>
                        <span class="badge bg-light text-dark ms-2">{{ $list->tasks->count() }}</span>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-link text-muted" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('tasks.create', $list) }}">
                                    <i class="fas fa-plus me-1"></i> Add Task
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item edit-list-btn" href="#" data-list-id="{{ $list->id }}" data-list-name="{{ $list->name }}">
                                    <i class="fas fa-edit me-1"></i> Edit List
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('lists.destroy', $list) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure? All tasks in this list will be deleted.')">
                                        <i class="fas fa-trash me-1"></i> Delete List
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="kanban-column-body" id="list-{{ $list->id }}">
                    @foreach($list->tasks as $task)
                        <div class="kanban-task" data-task-id="{{ $task->id }}">
                            <div class="kanban-task-title">{{ $task->title }}</div>
                            @if($task->description)
                                <div class="small text-muted">{{ Str::limit($task->description, 60) }}</div>
                            @endif
                            <div class="kanban-task-footer">
                                <div class="task-badges">
                                    @if($task->attachments->count() > 0)
                                        <span class="badge bg-light text-dark" data-bs-toggle="tooltip" title="{{ $task->attachments->count() }} attachments">
                                            <i class="fas fa-paperclip"></i> {{ $task->attachments->count() }}
                                        </span>
                                    @endif
                                    @if($task->comments->count() > 0)
                                        <span class="badge bg-light text-dark" data-bs-toggle="tooltip" title="{{ $task->comments->count() }} comments">
                                            <i class="fas fa-comment"></i> {{ $task->comments->count() }}
                                        </span>
                                    @endif
                                    @if($task->due_date)
                                        <span class="badge {{ $task->due_date < now() ? 'bg-danger' : 'bg-light text-dark' }}" data-bs-toggle="tooltip" title="Due {{ $task->due_date->format('M d, Y') }}">
                                            <i class="fas fa-calendar"></i> {{ $task->due_date->format('M d') }}
                                        </span>
                                    @endif
                                </div>
                                @if($task->assignee)
                                    <div class="avatar avatar-sm" style="background-color: #{{ substr(md5($task->assignee->name), 0, 6) }}" data-bs-toggle="tooltip" title="Assigned to {{ $task->assignee->name }}">
                                        {{ strtoupper(substr($task->assignee->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <button class="edit-task-btn" data-task-id="{{ $task->id }}"></button>
                        </div>
                    @endforeach

                    <a href="{{ route('tasks.create', $list) }}" class="btn btn-sm btn-outline-secondary w-100 mt-2">
                        <i class="fas fa-plus me-1"></i> Add Task
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Add List Modal -->
    <div class="modal fade" id="addListModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New List</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('lists.store', $project) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="list-name" class="form-label">List Name</label>
                            <input type="text" class="form-control" id="list-name" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create List</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit List Modal -->
    <div class="modal fade" id="editListModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit List</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editListForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit-list-name" class="form-label">List Name</label>
                            <input type="text" class="form-control" id="edit-list-name" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update List</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Task Modal -->
    <div class="modal fade" id="editTaskModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center p-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading task details...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .kanban-board {
        display: flex;
        overflow-x: auto;
        padding-bottom: 1rem;
        min-height: calc(100vh - 230px);
    }

    .kanban-column {
        min-width: 300px;
        width: 300px;
        background-color: #f8f9fa;
        border-radius: 0.25rem;
        margin-right: 1rem;
        display: flex;
        flex-direction: column;
    }

    .kanban-column-header {
        padding: 0.75rem;
        background-color: #f1f3f5;
        border-top-left-radius: 0.25rem;
        border-top-right-radius: 0.25rem;
        border-bottom: 1px solid #dee2e6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .kanban-column-body {
        padding: 0.75rem;
        flex-grow: 1;
        overflow-y: auto;
        max-height: calc(100vh - 300px);
    }

    .kanban-task {
        padding: 0.75rem;
        background-color: white;
        border-radius: 0.25rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 0.75rem;
        position: relative;
        cursor: grab;
    }

    .kanban-task:active {
        cursor: grabbing;
    }

    .kanban-task-title {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .task-badges {
        display: flex;
        gap: 0.5rem;
    }

    .kanban-task-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 0.5rem;
    }

    .avatar {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 12px;
    }

    .edit-task-btn {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: transparent;
        border: none;
        cursor: pointer;
        z-index: 2;
    }

    /* Dragula specific styles */
    .gu-mirror {
        position: fixed !important;
        margin: 0 !important;
        z-index: 9999 !important;
        opacity: 0.8;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }

    .gu-hide {
        display: none !important;
    }

    .gu-unselectable {
        -webkit-user-select: none !important;
        -moz-user-select: none !important;
        -ms-user-select: none !important;
        user-select: none !important;
    }

    .gu-transit {
        opacity: 0.4;
        background-color: #f8f9fa;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.3/dragula.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Initialize dragula for kanban board
        const drake = dragula(
            Array.from(document.querySelectorAll('.kanban-column-body')),
            {
                moves: function(el, container, handle) {
                    return el.classList.contains('kanban-task');
                },
                accepts: function(el, target, source, sibling) {
                    // Accept cards from any column to any column
                    return true;
                }
            }
        );

        // Handle drag and drop events
        drake.on('drop', function(el, target, source, sibling) {
            // Get the task ID
            const taskId = el.getAttribute('data-task-id');

            // Get the new list ID
            const newListId = target.id.replace('list-', '');

            // Prepare the tasks to update positions
            const tasks = Array.from(target.querySelectorAll('.kanban-task')).map((task, index) => {
                return {
                    id: task.getAttribute('data-task-id'),
                    position: index,
                    list_id: newListId
                };
            });

            // Send the update to the server
            fetch('{{ route("tasks.update-positions") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ tasks: tasks })
            })
            .then(response => response.json())
            .catch(error => {
                console.error('Error updating task positions:', error);
            });
        });

        // Edit list functionality
        const editListModal = new bootstrap.Modal(document.getElementById('editListModal'));
        const editListButtons = document.querySelectorAll('.edit-list-btn');

        editListButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                const listId = this.getAttribute('data-list-id');
                const listName = this.getAttribute('data-list-name');

                document.getElementById('edit-list-name').value = listName;
                document.getElementById('editListForm').action = `/lists/${listId}`;

                editListModal.show();
            });
        });

        // Edit task functionality
        const editTaskModal = new bootstrap.Modal(document.getElementById('editTaskModal'));
        const editTaskButtons = document.querySelectorAll('.edit-task-btn');

        editTaskButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const taskId = this.getAttribute('data-task-id');

                // Show the modal first
                editTaskModal.show();

                // Fetch task details
                fetch(`/tasks/${taskId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    // Load the form into the modal
                    document.querySelector('#editTaskModal .modal-body').innerHTML = html;

                    // Initialize any dynamic components in the form
                    if (typeof initializeFormComponents === 'function') {
                        initializeFormComponents();
                    }
                })
                .catch(error => {
                    console.error('Error loading task details:', error);
                    document.querySelector('#editTaskModal .modal-body').innerHTML =
                        '<div class="alert alert-danger">Error loading task details. Please try again.</div>';
                });
            });
        });

        // Setup presence channel for real-time collaboration
        @if(isset($project))
            window.Echo.join(`project.${@json($project->id)}`)
                .here((users) => {
                    console.log('Users present:', users);
                })
                .joining((user) => {
                    console.log('User joined:', user);
                })
                .leaving((user) => {
                    console.log('User left:', user);
                });
        @endif
    });
</script>
@endpush
