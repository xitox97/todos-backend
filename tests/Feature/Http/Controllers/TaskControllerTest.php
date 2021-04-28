<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Success Test
     */

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

    /**
     * Fail Test
     */

    /** @test */
    public function create_task_fail_due_to_invalid_input()
    {
        $data = [
            'description' => 'aa.',
            'completed' => false
        ];

        $this->postJson('/api/task', $data)
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'description' => ['The description must be at least 5 characters.']
                ],
                'success' => false
            ]);

        $this->assertDatabaseMissing('tasks', [
            'description' => 'aa.',
            'completed' => false
        ]);
    }
}
