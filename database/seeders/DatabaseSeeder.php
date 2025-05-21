<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskList;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $memberRole = Role::create(['name' => 'member']);

        // Create permissions
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'manage teams']);
        Permission::create(['name' => 'manage projects']);
        Permission::create(['name' => 'manage all tasks']);
        Permission::create(['name' => 'manage assigned tasks']);

        // Assign permissions to roles
        $adminRole->givePermissionTo([
            'manage users',
            'manage teams',
            'manage projects',
            'manage all tasks',
            'manage assigned tasks',
        ]);

        $memberRole->givePermissionTo([
            'manage assigned tasks',
        ]);

        // Create an admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin123'), // Use bcrypt for password hashing
        ]);
        $admin->assignRole('admin');

        // Create a regular user
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        $user->assignRole('member');

        // Create more users
        $users = User::factory(5)->create();
        foreach ($users as $user) {
            $user->assignRole('member');
        }

        // Create a team
        $team = Team::create([
            'name' => 'Demo Team',
            'description' => 'This is a demo team',
            'owner_id' => $admin->id,
        ]);

        // Add users to team
        $team->users()->attach($users->pluck('id'));

        // Create a project
        $project = Project::create([
            'name' => 'Demo Project',
            'description' => 'This is a demo project',
            'team_id' => $team->id,
        ]);

        // Create task lists
        $todoList = TaskList::create([
            'name' => 'To Do',
            'position' => 0,
            'project_id' => $project->id,
        ]);

        $inProgressList = TaskList::create([
            'name' => 'In Progress',
            'position' => 1,
            'project_id' => $project->id,
        ]);

        $doneList = TaskList::create([
            'name' => 'Done',
            'position' => 2,
            'project_id' => $project->id,
        ]);

        // Create tasks
        Task::create([
            'title' => 'Set up the project',
            'description' => 'Initialize the project and install dependencies',
            'project_id' => $project->id,
            'list_id' => $todoList->id,
            'position' => 0,
            'assignee_id' => $users[0]->id,
        ]);

        Task::create([
            'title' => 'Create database schema',
            'description' => 'Design and implement database tables',
            'project_id' => $project->id,
            'list_id' => $todoList->id,
            'position' => 1,
            'assignee_id' => $users[1]->id,
        ]);

        Task::create([
            'title' => 'Implement authentication',
            'description' => 'Set up user authentication and authorization',
            'project_id' => $project->id,
            'list_id' => $inProgressList->id,
            'position' => 0,
            'assignee_id' => $users[2]->id,
        ]);

        Task::create([
            'title' => 'Design UI mockups',
            'description' => 'Create wireframes and UI designs',
            'project_id' => $project->id,
            'list_id' => $doneList->id,
            'position' => 0,
            'assignee_id' => $users[3]->id,
        ]);
    }
}
