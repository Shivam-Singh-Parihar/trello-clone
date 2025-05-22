<div class="modal-dialog modal-xl" style="max-width: 90vw;">
    @if ($showModal)
        <div class="modal-content" style="background: #f4f5f7; border: none; border-radius: 8px;">
            <!-- Trello-style header with task title -->
            <div class="modal-header" style="background: #fff; border-bottom: 1px solid #ddd; border-radius: 8px 8px 0 0; padding: 16px 20px;">
                <div class="d-flex align-items-center w-100">
                    <div class="me-3">
                        <i class="fas fa-credit-card" style="color: #6b778c; font-size: 18px;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="modal-title mb-1" style="color: #172b4d; font-weight: 600; font-size: 20px;">{{ $task->title }}</h4>
                        <div class="text-muted" style="font-size: 14px;">
                            in list <span style="text-decoration: underline; cursor: pointer;">{{ $task->list->name }}</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="margin: 0;"></button>
                </div>
            </div>

            <div class="modal-body" style="padding: 0; background: #f4f5f7;">
                <div class="container-fluid">
                    <div class="row g-0">
                        <!-- Main Content Area -->
                        <div class="col-lg-8">
                            <div style="padding: 20px 24px;">
                                <!-- Back to Board Link -->
                                <a href="{{ route('projects.show', $task->project) }}"
                                   class="text-decoration-none mb-3 d-inline-block"
                                   style="color: #6b778c; font-size: 14px;">
                                    <i class="fas fa-arrow-left me-1"></i> Back to {{ $task->project->name }}
                                </a>

                                <!-- Assignee and Due Date Row -->
                                @if($task->assignee || $task->due_date)
                                <div class="row mb-4">
                                    @if($task->assignee)
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <h6 style="color: #5e6c84; font-size: 12px; font-weight: 600; text-transform: uppercase; margin-bottom: 8px;">
                                                <i class="fas fa-user me-1"></i> Members
                                            </h6>
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center me-2"
                                                     style="width: 32px; height: 32px; background-color: #{{ substr(md5($task->assignee->name), 0, 6) }}; color: white; font-size: 14px; font-weight: 600;">
                                                    {{ strtoupper(substr($task->assignee->name, 0, 1)) }}
                                                </div>
                                                <span style="color: #172b4d; font-size: 14px;">{{ $task->assignee->name }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @if($task->due_date)
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <h6 style="color: #5e6c84; font-size: 12px; font-weight: 600; text-transform: uppercase; margin-bottom: 8px;">
                                                <i class="fas fa-clock me-1"></i> Due Date
                                            </h6>
                                            <div class="badge {{ $task->due_date < now() ? 'bg-danger' : 'bg-light text-dark' }}"
                                                 style="font-size: 12px; padding: 6px 12px;">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $task->due_date->format('M d, Y') }}
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                @endif

                                <!-- Description Section -->
                                <div class="mb-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-align-left me-3" style="color: #6b778c; font-size: 18px;"></i>
                                        <h5 style="color: #172b4d; font-weight: 600; font-size: 16px; margin: 0;">Description</h5>
                                    </div>
                                    <div style="margin-left: 42px;">
                                        @if($task->description)
                                            <div style="background: #fff; border-radius: 3px; padding: 12px; color: #172b4d; font-size: 14px; line-height: 1.4;">
                                                {{ $task->description }}
                                            </div>
                                        @else
                                            <div style="background: #fff; border-radius: 3px; padding: 12px; color: #6b778c; font-size: 14px; cursor: pointer;"
                                                 onclick="document.getElementById('editTaskBtn').click()">
                                                Add a more detailed description...
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Attachments Section -->
                                <div class="mb-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-paperclip me-3" style="color: #6b778c; font-size: 18px;"></i>
                                        <h5 style="color: #172b4d; font-weight: 600; font-size: 16px; margin: 0;">Attachments</h5>
                                    </div>
                                    <div style="margin-left: 42px;">
                                        <!-- Dropzone -->
                                        <div class="dropzone mb-3" id="attachmentsDropzone"
                                             style="background: #fff; border: 2px dashed #dfe1e6; border-radius: 3px; padding: 20px; text-align: center;">
                                            <div class="dz-message">
                                                <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #6b778c;"></i>
                                                <p class="mb-0" style="color: #172b4d; font-size: 14px;">Drop files here or click to upload</p>
                                                <p class="small" style="color: #6b778c; font-size: 12px;">(Max file size: 10MB)</p>
                                            </div>
                                        </div>

                                        <!-- Attachment List -->
                                        @if ($attachments->count() > 0)
                                            @foreach ($attachments as $attachment)
                                                <div class="d-flex align-items-center p-2 mb-2"
                                                     style="background: #fff; border-radius: 3px; border: 1px solid #dfe1e6;">
                                                    <div class="me-3">
                                                        @php
                                                            $iconClass = 'fa-file';
                                                            $iconColor = '#6b778c';
                                                            if (str_contains($attachment->mime_type, 'image')) {
                                                                $iconClass = 'fa-file-image';
                                                                $iconColor = '#0079bf';
                                                            } elseif (str_contains($attachment->mime_type, 'pdf')) {
                                                                $iconClass = 'fa-file-pdf';
                                                                $iconColor = '#d93651';
                                                            } elseif (str_contains($attachment->mime_type, 'word')) {
                                                                $iconClass = 'fa-file-word';
                                                                $iconColor = '#0079bf';
                                                            } elseif (str_contains($attachment->mime_type, 'excel') || str_contains($attachment->mime_type, 'spreadsheet')) {
                                                                $iconClass = 'fa-file-excel';
                                                                $iconColor = '#519839';
                                                            }
                                                        @endphp
                                                        <i class="fas {{ $iconClass }}" style="color: {{ $iconColor }}; font-size: 18px;"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div style="color: #172b4d; font-size: 14px; font-weight: 500;">{{ $attachment->filename }}</div>
                                                        <div style="color: #6b778c; font-size: 12px;">
                                                            {{ number_format($attachment->size / 1024, 2) }} KB •
                                                            Added {{ $attachment->created_at->diffForHumans() }} by {{ $attachment->user->name }}
                                                        </div>
                                                    </div>
                                                    <div class="ms-2">
                                                        <a href="{{ route('attachments.download', $attachment) }}"
                                                           class="btn btn-sm me-1"
                                                           style="background: #f4f5f7; border: 1px solid #dfe1e6; color: #172b4d; padding: 4px 8px;">
                                                            <i class="fas fa-download" style="font-size: 12px;"></i>
                                                        </a>
                                                        <form action="{{ route('attachments.destroy', $attachment) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                    class="btn btn-sm"
                                                                    style="background: #f4f5f7; border: 1px solid #dfe1e6; color: #d93651; padding: 4px 8px;"
                                                                    onclick="return confirm('Are you sure?')">
                                                                <i class="fas fa-trash" style="font-size: 12px;"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <p style="color: #6b778c; font-size: 14px;">No attachments yet</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Comments Section -->
                                <div class="mb-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-comment me-3" style="color: #6b778c; font-size: 18px;"></i>
                                        <h5 style="color: #172b4d; font-weight: 600; font-size: 16px; margin: 0;">Comments</h5>
                                    </div>
                                    <div style="margin-left: 42px;">
                                        <!-- Add Comment Form -->
                                        <div class="mb-4">
                                            <form action="{{ route('comments.store', $task) }}" method="POST">
                                                @csrf
                                                <div class="d-flex">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                                         style="width: 32px; height: 32px; background-color: #{{ substr(md5(auth()->user()->name), 0, 6) }}; color: white; font-size: 14px; font-weight: 600; flex-shrink: 0;">
                                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <textarea class="form-control mb-2"
                                                                  id="comment-body"
                                                                  name="body"
                                                                  rows="3"
                                                                  placeholder="Write a comment..."
                                                                  style="border: 1px solid #dfe1e6; border-radius: 3px; font-size: 14px; resize: vertical;"
                                                                  required></textarea>
                                                        <button type="submit"
                                                                class="btn btn-sm"
                                                                style="background: #0079bf; color: white; border: none; padding: 6px 12px; border-radius: 3px; font-size: 14px;">
                                                            Save
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                        <!-- Comments List -->
                                        @if ($comments->count() > 0)
                                            @foreach ($comments as $comment)
                                                <div class="d-flex mb-3" id="comment-{{ $comment->id }}">
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                                         style="width: 32px; height: 32px; background-color: #{{ substr(md5($comment->user->name), 0, 6) }}; color: white; font-size: 14px; font-weight: 600; flex-shrink: 0;">
                                                        {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div style="background: #fff; border: 1px solid #dfe1e6; border-radius: 3px; padding: 12px;">
                                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                                <span style="color: #172b4d; font-weight: 600; font-size: 14px;">{{ $comment->user->name }}</span>
                                                                <span style="color: #6b778c; font-size: 12px;">{{ $comment->created_at->diffForHumans() }}</span>
                                                            </div>
                                                            <div style="color: #172b4d; font-size: 14px; line-height: 1.4;">{{ $comment->body }}</div>
                                                        </div>
                                                        @if ($comment->user_id === auth()->id())
                                                            <div class="mt-1">
                                                                <button class="btn btn-sm btn-link edit-comment-btn"
                                                                        data-comment-id="{{ $comment->id }}"
                                                                        data-comment-body="{{ $comment->body }}"
                                                                        style="color: #6b778c; font-size: 12px; text-decoration: underline; padding: 0; border: none;">
                                                                    Edit
                                                                </button>
                                                                <span style="color: #6b778c; font-size: 12px;"> • </span>
                                                                <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                            class="btn btn-sm btn-link"
                                                                            style="color: #6b778c; font-size: 12px; text-decoration: underline; padding: 0; border: none;"
                                                                            onclick="return confirm('Are you sure?')">
                                                                        Delete
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <p style="color: #6b778c; font-size: 14px;">No comments yet. Be the first to comment!</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar -->
                        <div class="col-lg-4" style="background: #f4f5f7; border-left: 1px solid #dfe1e6;">
                            <div style="padding: 20px 16px;">
                                <!-- Actions -->
                                <div class="mb-4">
                                    <h6 style="color: #5e6c84; font-size: 12px; font-weight: 600; text-transform: uppercase; margin-bottom: 12px;">Actions</h6>
                                    <div class="d-grid gap-2">
                                        <button type="button"
                                                class="btn btn-sm d-flex align-items-center"
                                                id="editTaskBtn"
                                                style="background: #091e420a; border: none; color: #172b4d; text-align: left; padding: 8px 12px; border-radius: 3px; font-size: 14px;">
                                            <i class="fas fa-pencil-alt me-2" style="width: 16px;"></i>
                                            Edit
                                        </button>
                                        <a href="{{ route('tasks.edit', $task) }}"
                                           class="btn btn-sm d-flex align-items-center"
                                           style="background: #091e420a; border: none; color: #172b4d; text-decoration: none; text-align: left; padding: 8px 12px; border-radius: 3px; font-size: 14px;">
                                            <i class="fas fa-user me-2" style="width: 16px;"></i>
                                            Members
                                        </a>
                                        <button type="button"
                                                class="btn btn-sm d-flex align-items-center"
                                                style="background: #091e420a; border: none; color: #172b4d; text-align: left; padding: 8px 12px; border-radius: 3px; font-size: 14px;">
                                            <i class="fas fa-tag me-2" style="width: 16px;"></i>
                                            Labels
                                        </button>
                                        <button type="button"
                                                class="btn btn-sm d-flex align-items-center"
                                                style="background: #091e420a; border: none; color: #172b4d; text-align: left; padding: 8px 12px; border-radius: 3px; font-size: 14px;">
                                            <i class="fas fa-clock me-2" style="width: 16px;"></i>
                                            Dates
                                        </button>
                                        <button type="button"
                                                class="btn btn-sm d-flex align-items-center"
                                                onclick="document.getElementById('attachmentsDropzone').click()"
                                                style="background: #091e420a; border: none; color: #172b4d; text-align: left; padding: 8px 12px; border-radius: 3px; font-size: 14px;">
                                            <i class="fas fa-paperclip me-2" style="width: 16px;"></i>
                                            Attachment
                                        </button>
                                    </div>
                                </div>

                                <!-- Activity -->
                                <div>
                                    <h6 style="color: #5e6c84; font-size: 12px; font-weight: 600; text-transform: uppercase; margin-bottom: 12px;">
                                        <i class="fas fa-list-ul me-1"></i> Activity
                                    </h6>
                                    <div style="max-height: 400px; overflow-y: auto;">
                                        @php
                                            $activities = Spatie\Activitylog\Models\Activity::where(function ($query) use ($task) {
                                                $query->where('subject_type', get_class($task))
                                                      ->where('subject_id', $task->id);
                                            })
                                            ->orWhere(function ($query) use ($task) {
                                                $query->where('subject_type', 'App\Models\Comment')
                                                      ->whereIn('subject_id', $task->comments->pluck('id'));
                                            })
                                            ->orWhere(function ($query) use ($task) {
                                                $query->where('subject_type', 'App\Models\Attachment')
                                                      ->whereIn('subject_id', $task->attachments->pluck('id'));
                                            })
                                            ->orderBy('created_at', 'desc')
                                            ->take(10)
                                            ->get();
                                        @endphp

                                        @if ($activities->count() > 0)
                                            @foreach ($activities as $activity)
                                                <div class="d-flex mb-3">
                                                    <div class="me-2">
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                                                             style="width: 24px; height: 24px; background: #dfe1e6; flex-shrink: 0;">
                                                            <i class="fas fa-check" style="color: #6b778c; font-size: 10px;"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div style="color: #172b4d; font-size: 13px; line-height: 1.3;">{{ $activity->description }}</div>
                                                        <div style="color: #6b778c; font-size: 11px;">{{ $activity->created_at->diffForHumans() }}</div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <p style="color: #6b778c; font-size: 13px;">No activity recorded yet</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Comment Modal -->
        <div class="modal fade" id="editCommentModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="border-radius: 8px;">
                    <div class="modal-header" style="border-bottom: 1px solid #dfe1e6;">
                        <h5 class="modal-title" style="color: #172b4d; font-weight: 600;">Edit Comment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editCommentForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="edit-comment-body" class="form-label" style="color: #172b4d; font-weight: 600;">Comment</label>
                                <textarea class="form-control"
                                          id="edit-comment-body"
                                          name="body"
                                          rows="3"
                                          style="border: 1px solid #dfe1e6; border-radius: 3px;"
                                          required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer" style="border-top: 1px solid #dfe1e6;">
                            <button type="button"
                                    class="btn btn-outline-secondary"
                                    data-bs-dismiss="modal"
                                    style="border-color: #dfe1e6; color: #6b778c;">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="btn"
                                    style="background: #0079bf; color: white; border: none;">
                                Update Comment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Task Modal -->
        <div class="modal fade" id="editTaskModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" style="border-radius: 8px;">
                    <div class="modal-header" style="border-bottom: 1px solid #dfe1e6;">
                        <h5 class="modal-title" style="color: #172b4d; font-weight: 600;">Edit Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editTaskForm" method="POST" action="{{ route('tasks.update', $task) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="edit-task-title" class="form-label" style="color: #172b4d; font-weight: 600;">Title</label>
                                <input type="text"
                                       class="form-control"
                                       id="edit-task-title"
                                       name="title"
                                       style="border: 1px solid #dfe1e6; border-radius: 3px;"
                                       required>
                            </div>
                            <div class="mb-3">
                                <label for="edit-task-description" class="form-label" style="color: #172b4d; font-weight: 600;">Description</label>
                                <textarea class="form-control"
                                          id="edit-task-description"
                                          name="description"
                                          rows="4"
                                          style="border: 1px solid #dfe1e6; border-radius: 3px;"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit-task-list" class="form-label" style="color: #172b4d; font-weight: 600;">List</label>
                                        <select class="form-select"
                                                id="edit-task-list"
                                                name="list_id"
                                                style="border: 1px solid #dfe1e6; border-radius: 3px;"
                                                required>
                                            @foreach ($task->project->lists as $list)
                                                <option value="{{ $list->id }}">{{ $list->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit-task-due-date" class="form-label" style="color: #172b4d; font-weight: 600;">Due Date</label>
                                        <input type="date"
                                               class="form-control"
                                               id="edit-task-due-date"
                                               name="due_date"
                                               style="border: 1px solid #dfe1e6; border-radius: 3px;">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="edit-task-assignee" class="form-label" style="color: #172b4d; font-weight: 600;">Assigned To</label>
                                <select class="form-select"
                                        id="edit-task-assignee"
                                        name="assignee_id"
                                        style="border: 1px solid #dfe1e6; border-radius: 3px;">
                                    <option value="">Unassigned</option>
                                    @foreach ($task->project->team->users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer" style="border-top: 1px solid #dfe1e6;">
                            <button type="button"
                                    class="btn btn-outline-secondary"
                                    data-bs-dismiss="modal"
                                    style="border-color: #dfe1e6; color: #6b778c;">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="btn"
                                    style="background: #0079bf; color: white; border: none;">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <style>
/* Trello-inspired styles */
.btn:hover {
    background-color: #091e4214 !important;
}

.form-control:focus, .form-select:focus {
    border-color: #0079bf !important;
    box-shadow: 0 0 0 0.2rem rgba(0, 121, 191, 0.25) !important;
}

.dropzone {
    transition: border-color 0.2s ease;
}

.dropzone:hover {
    border-color: #0079bf !important;
}

/* Custom scrollbar for activity section */
div::-webkit-scrollbar {
    width: 8px;
}

div::-webkit-scrollbar-track {
    background: #f4f5f7;
}

div::-webkit-scrollbar-thumb {
    background: #dfe1e6;
    border-radius: 4px;
}

div::-webkit-scrollbar-thumb:hover {
    background: #c1c7d0;
}
</style>
</div>
