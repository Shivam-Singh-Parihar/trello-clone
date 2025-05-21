<form id="editTaskForm" method="POST" action="{{ route('tasks.update', $task) }}">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control" id="title" name="title" required>
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="due_date" class="form-label">Due Date</label>
            <input type="date" class="form-control" id="due_date" name="due_date">
        </div>

        <div class="col-md-6 mb-3">
            <label for="list_id" class="form-label">List</label>
            <select class="form-select" id="list_id" name="list_id" required>
                @foreach($lists as $list)
                    <option value="{{ $list['id'] }}">{{ $list['name'] }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mb-3">
        <label for="assignee_id" class="form-label">Assignee</label>
        <select class="form-select" id="assignee_id" name="assignee_id">
            <option value="">Unassigned</option>
            @foreach($teamMembers as $member)
                <option value="{{ $member['id'] }}">{{ $member['name'] }}</option>
            @endforeach
        </select>
    </div>

    <div class="d-flex justify-content-end">
        <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle form submission
        const form = document.getElementById('editTaskForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(form);
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.innerHTML;

                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Saving...
                `;

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close modal and refresh task card
                        bootstrap.Modal.getInstance(document.getElementById('editTaskModal')).hide();
                        // You might want to refresh the task card or show a success message
                        window.location.reload(); // or implement a more specific update
                    } else {
                        alert(data.message || 'Error saving task');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error saving task');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                });
            });
        }
    });
</script>
