<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;
use Livewire\Attributes\On;

class TaskModal extends Component
{
    public $task;
    public $comments;
    public $attachments;
    public $teamMembers;
    public $showModal = false;
    public $activeTab = 'details';

    #[On('show-task-modal')]
    public function loadTask($taskId)
    {
        // dd($taskId);
        $this->task = Task::with([
            'project.team.users',
            'list',
            'assignee',
            'comments.user',
            'attachments.user'
        ])->findOrFail($taskId);

        // dd($this->task);
        // Authorization check
        if (!$this->task->project->team->hasUser(auth()->user())) {
            abort(403);
        }

        $this->comments = $this->task->comments()->latest()->get();
        $this->attachments = $this->task->attachments()->latest()->get();
        $this->teamMembers = $this->task->project->team->users;
        $this->showModal = true;

        // dd($this->task);
    }

    public function closeModal()
    {
        $this->reset();
        // $this->showModal = false;
    }

    public function changeTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.task-modal');
    }
}
