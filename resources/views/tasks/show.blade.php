    @extends('layouts.app')

    @section('content')
        <div class="container py-4">
            <div class="row">
                <div class="col-md-8">
                    <a href="{{ route('projects.show', $task->project) }}" class="text-decoration-none mb-3 d-inline-block">
                        <i class="fas fa-arrow-left me-1"></i> Back to Board
                    </a>

                    <div class="card shadow-sm mb-4 fade-in">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h1 class="h3 mb-0">{{ $task->title }}</h1>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-link text-muted" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('tasks.edit', $task) }}">
                                                <i class="fas fa-edit me-1"></i> Edit Task
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger"
                                                    onclick="return confirm('Are you sure you want to delete this task?')">
                                                    <i class="fas fa-trash me-1"></i> Delete Task
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="text-muted mb-2">
                                    <small>
                                        <i class="fas fa-list me-1"></i> List: <strong>{{ $task->list->name }}</strong>
                                        &nbsp;|&nbsp;
                                        <i class="fas fa-project-diagram me-1"></i> Project:
                                        <strong>{{ $task->project->name }}</strong>
                                    </small>
                                </div>
                                <p>{{ $task->description ?: 'No description provided.' }}</p>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <h5 class="h6 text-muted mb-2">Assigned To</h5>
                                        @if ($task->assignee)
                                            <div class="d-flex align-items-center">
                                                <div class="avatar me-2"
                                                    style="background-color: #{{ substr(md5($task->assignee->name), 0, 6) }}">
                                                    {{ strtoupper(substr($task->assignee->name, 0, 1)) }}
                                                </div>
                                                <span>{{ $task->assignee->name }}</span>
                                            </div>
                                        @else
                                            <p class="text-muted">Unassigned</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <h5 class="h6 text-muted mb-2">Due Date</h5>
                                        @if ($task->due_date)
                                            <p class="{{ $task->due_date < now() ? 'text-danger' : '' }}">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $task->due_date->format('F d, Y') }}
                                            </p>
                                        @else
                                            <p class="text-muted">No due date</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="task-detail-section">
                                <h5 class="mb-3">Attachments</h5>
                                <div class="dropzone mb-3" id="attachmentsDropzone">
                                    <div class="dz-message">
                                        <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                        <p class="mb-0">Drop files here or click to upload</p>
                                        <p class="small text-muted">(Max file size: 10MB)</p>
                                    </div>
                                </div>

                                <div class="attachments">
                                    @if ($attachments->count() > 0)
                                        @foreach ($attachments as $attachment)
                                            <div class="attachment-item">
                                                <div class="attachment-icon">
                                                    @php
                                                        $iconClass = 'fa-file';
                                                        if (str_contains($attachment->mime_type, 'image')) {
                                                            $iconClass = 'fa-file-image';
                                                        } elseif (str_contains($attachment->mime_type, 'pdf')) {
                                                            $iconClass = 'fa-file-pdf';
                                                        } elseif (str_contains($attachment->mime_type, 'word')) {
                                                            $iconClass = 'fa-file-word';
                                                        } elseif (
                                                            str_contains($attachment->mime_type, 'excel') ||
                                                            str_contains($attachment->mime_type, 'spreadsheet')
                                                        ) {
                                                            $iconClass = 'fa-file-excel';
                                                        }
                                                    @endphp
                                                    <i class="fas {{ $iconClass }} fa-lg"></i>
                                                </div>
                                                <div class="attachment-details">
                                                    <div class="attachment-filename">{{ $attachment->filename }}</div>
                                                    <div class="attachment-info">
                                                        <span>{{ number_format($attachment->size / 1024, 2) }} KB</span>
                                                        &bull;
                                                        <span>Uploaded by {{ $attachment->user->name }}</span> &bull;
                                                        <span>{{ $attachment->created_at->diffForHumans() }}</span>
                                                    </div>
                                                </div>
                                                <div class="attachment-actions">
                                                    <a href="{{ route('attachments.download', $attachment) }}"
                                                        class="btn btn-sm btn-outline-primary me-1">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                    <form action="{{ route('attachments.destroy', $attachment) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('Are you sure?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted">No attachments yet</p>
                                    @endif
                                </div>
                            </div>

                            <div class="task-detail-section">
                                <h5 class="mb-3">Comments</h5>
                                <div class="comments-section">
                                    @if ($comments->count() > 0)
                                        @foreach ($comments as $comment)
                                            <div class="comment fade-in" id="comment-{{ $comment->id }}">
                                                <div class="comment-header">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar me-2"
                                                            style="background-color: #{{ substr(md5($comment->user->name), 0, 6) }}">
                                                            {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                                        </div>
                                                        <div class="comment-user">{{ $comment->user->name }}</div>
                                                    </div>
                                                    <div class="comment-time">{{ $comment->created_at->diffForHumans() }}
                                                    </div>
                                                </div>
                                                <div class="comment-body">
                                                    {{ $comment->body }}
                                                </div>
                                                @if ($comment->user_id === auth()->id())
                                                    <div class="mt-2 text-end">
                                                        <button class="btn btn-sm btn-link text-muted edit-comment-btn"
                                                            data-comment-id="{{ $comment->id }}"
                                                            data-comment-body="{{ $comment->body }}">Edit</button>
                                                        <form action="{{ route('comments.destroy', $comment) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-link text-danger"
                                                                onclick="return confirm('Are you sure?')">Delete</button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted">No comments yet</p>
                                    @endif
                                </div>

                                <div class="mt-4">
                                    <form action="{{ route('comments.store', $task) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="comment-body" class="form-label">Add a comment</label>
                                            <textarea class="form-control" id="comment-body" name="body" rows="3" required></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Post Comment</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm mb-4 fade-in">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-0">Activity</h5>
                        </div>
                        <div class="card-body p-0">
                            @php
                                $activities = Spatie\Activitylog\Models\Activity::where(function ($query) use ($task) {
                                    $query->where('subject_type', get_class($task))->where('subject_id', $task->id);
                                })
                                    ->orWhere(function ($query) use ($task) {
                                        $query
                                            ->where('subject_type', 'App\Models\Comment')
                                            ->whereIn('subject_id', $task->comments->pluck('id'));
                                    })
                                    ->orWhere(function ($query) use ($task) {
                                        $query
                                            ->where('subject_type', 'App\Models\Attachment')
                                            ->whereIn('subject_id', $task->attachments->pluck('id'));
                                    })
                                    ->orderBy('created_at', 'desc')
                                    ->take(10)
                                    ->get();
                            @endphp

                            @if ($activities->count() > 0)
                                <ul class="list-group list-group-flush">
                                    @foreach ($activities as $activity)
                                        <li class="list-group-item">
                                            <div class="d-flex">
                                                <div class="me-3">
                                                    <div class="bg-light rounded-circle p-2">
                                                        <i class="fas fa-check text-primary"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="fw-medium">{{ $activity->description }}</div>
                                                    <div class="small text-muted">
                                                        {{ $activity->created_at->diffForHumans() }}</div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="text-center py-4">
                                    <p class="text-muted">No activity recorded yet</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Comment Modal -->
            <div class="modal fade" id="editCommentModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog ">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Comment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <form id="editCommentForm" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="edit-comment-body" class="form-label">Comment</label>
                                    <textarea class="form-control" id="edit-comment-body" name="body" rows="3" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Update Comment</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>




            <!-- Edit Task Modal -->
            <div class="modal fade" id="editTaskModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Task</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <form id="editTaskForm" method="POST" action="{{ route('tasks.update', $task) }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="edit-task-title" class="form-label">Title</label>
                                    <input type="text" class="form-control" id="edit-task-title" name="title"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-task-description" class="form-label">Description</label>
                                    <textarea class="form-control" id="edit-task-description" name="description" rows="4"></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edit-task-list" class="form-label">List</label>
                                            <select class="form-select" id="edit-task-list" name="list_id" required>
                                                @foreach ($task->project->lists as $list)
                                                    <option value="{{ $list->id }}">{{ $list->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edit-task-due-date" class="form-label">Due Date</label>
                                            <input type="date" class="form-control" id="edit-task-due-date"
                                                name="due_date">
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-task-assignee" class="form-label">Assigned To</label>
                                    <select class="form-select" id="edit-task-assignee" name="assignee_id">
                                        <option value="">Unassigned</option>
                                        @foreach ($task->project->team->users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
        <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize Dropzone
                Dropzone.autoDiscover = false;

                new Dropzone("#attachmentsDropzone", {
                    url: "{{ route('attachments.store', $task) }}",
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    paramName: "file",
                    maxFilesize: 10, // MB
                    addRemoveLinks: true,
                    dictDefaultMessage: "Drop files here or click to upload",
                    init: function() {
                        this.on("success", function(file, response) {
                            // Reload page to show new attachment
                            window.location.reload();
                        });
                    }
                });

                // Edit comment functionality
                const editCommentModal = new bootstrap.Modal(document.getElementById('editCommentModal'));
                const editCommentButtons = document.querySelectorAll('.edit-comment-btn');

                editCommentButtons.forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();

                        const commentId = this.getAttribute('data-comment-id');
                        const commentBody = this.getAttribute('data-comment-body');

                        document.getElementById('edit-comment-body').value = commentBody;
                        document.getElementById('editCommentForm').action = `/comments/${commentId}`;

                        editCommentModal.show();
                    });
                });

                // Setup presence channel for real-time comments
                window.Echo.join(`task.{{ $task->id }}`)
                    .here((users) => {
                        console.log('Users viewing this task:', users);
                    })
                    .joining((user) => {
                        console.log('User joined:', user);
                    })
                    .leaving((user) => {
                        console.log('User left:', user);
                    })
                    .listen('.comment.added', (data) => {
                        // Check if the comment is not already in the list (to avoid duplicates)
                        if (!document.getElementById(`comment-${data.id}`)) {
                            const commentsSection = document.querySelector('.comments-section');

                            // If there's a "No comments yet" message, remove it
                            const noCommentsMessage = commentsSection.querySelector('p.text-muted');
                            if (noCommentsMessage) {
                                noCommentsMessage.remove();
                            }

                            // Create a new comment element
                            const commentElement = document.createElement('div');
                            commentElement.classList.add('comment', 'fade-in');
                            commentElement.id = `comment-${data.id}`;
                            commentElement.innerHTML = `
                            <div class="comment-header">
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-2" style="background-color: #${data.user.avatar || '0079BF'}">
                                        ${data.user.name.charAt(0).toUpperCase()}
                                    </div>
                                    <div class="comment-user">${data.user.name}</div>
                                </div>
                                <div class="comment-time">just now</div>
                            </div>
                            <div class="comment-body">
                                ${data.body}
                            </div>
                        `;

                            commentsSection.appendChild(commentElement);
                        }
                    });


                // Edit task functionality
                const editTaskModal = new bootstrap.Modal(document.getElementById('editTaskModal'));
                const editTaskButton = document.querySelector('a[href="{{ route('tasks.edit', $task) }}"]');

                if (editTaskButton) {
                    editTaskButton.addEventListener('click', function(e) {
                        e.preventDefault();

                        // Fetch current task data
                        const taskId = {{ $task->id }};
                        const taskTitle = "{{ $task->title }}";
                        const taskDescription = `{{ $task->description ?: '' }}`;
                        const taskListId = {{ $task->list_id }};
                        const taskAssigneeId = {{ $task->assignee_id ?: 'null' }};
                        const taskDueDate = "{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}";

                        // Set form action to use the named route
                        document.getElementById('editTaskForm').action = "{{ route('tasks.update', $task) }}";

                        // Populate form fields
                        document.getElementById('edit-task-title').value = taskTitle;
                        document.getElementById('edit-task-description').value = taskDescription;

                        // Set select values
                        const listSelect = document.getElementById('edit-task-list');
                        if (listSelect) {
                            for (let i = 0; i < listSelect.options.length; i++) {
                                if (listSelect.options[i].value == taskListId) {
                                    listSelect.options[i].selected = true;
                                    break;
                                }
                            }
                        }

                        const assigneeSelect = document.getElementById('edit-task-assignee');
                        if (assigneeSelect) {
                            if (taskAssigneeId === null) {
                                assigneeSelect.querySelector('option[value=""]').selected = true;
                            } else {
                                for (let i = 0; i < assigneeSelect.options.length; i++) {
                                    if (assigneeSelect.options[i].value == taskAssigneeId) {
                                        assigneeSelect.options[i].selected = true;
                                        break;
                                    }
                                }
                            }
                        }

                        // Set due date
                        document.getElementById('edit-task-due-date').value = taskDueDate;

                        // Show modal
                        editTaskModal.show();
                    });
                }

                // Handle form submission
                document.getElementById('editTaskForm').addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);

                    fetch(this.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                // Success message
                                const alertDiv = document.createElement('div');
                                alertDiv.className = 'alert alert-success fade-in';
                                alertDiv.innerHTML = 'Task updated successfully!';
                                document.querySelector('.container').prepend(alertDiv);

                                // Hide alert after 3 seconds
                                setTimeout(() => {
                                    alertDiv.remove();
                                }, 3000);

                                // Close modal
                                editTaskModal.hide();

                                // Reload page to show updated task
                                window.location.reload();
                            } else {
                                throw new Error('Failed to update task');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);

                            // Show error message
                            const alertDiv = document.createElement('div');
                            alertDiv.className = 'alert alert-danger fade-in';
                            alertDiv.innerHTML = 'There was an error updating the task. Please try again.';
                            document.getElementById('editTaskForm').prepend(alertDiv);

                            // Hide alert after 3 seconds
                            setTimeout(() => {
                                alertDiv.remove();
                            }, 3000);
                        });
                });
            });
        </script>
    @endpush
