<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function get_all_tasks_success()
    {
        $task1 = [
            'description' => 'Study reactJs.',
            'completed' => false
        ];

        $task2 = [
            'description' => 'Write test case.',
            'completed' => true
        ];

        Task::factory()->create($task1);
        Task::factory()->create($task2);

        $response = $this->json('GET', '/api/task');

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    $task1,
                    [
                        'description' => 'Write test case.',
                        'completed' => true
                    ]
                ],
                'success' => true
            ]);
    }

    /** @test */
    public function create_new_task_success()
    {
        $data = [
            'description' => 'Create CRUD API.',
            'completed' => false
        ];

        $response = $this->postJson('/api/task', $data);

        $task = Task::first();

        $response->assertStatus(200)
            ->assertJson([
                'data' => $task->id,
                'success' => true
            ]);

        $this->assertDatabaseHas('tasks', [
            'description' => 'Create CRUD API.',
            'completed' => false
        ]);
    }
}
