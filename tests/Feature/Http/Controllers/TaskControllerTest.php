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
        //Create data
        $data = [
            'description' => 'Create CRUD API.',
            'completed' => false
        ];

        //Send data to store api
        $response = $this->postJson('/api/task', $data);

        //Get first data that exists in DB
        $task = Task::first();

        //Check the response
        $response->assertStatus(200)
            ->assertJson([
                'data' => $task->id,
                'success' => true
            ]);

        //Check data exists in DB
        $this->assertDatabaseHas('tasks', [
            'description' => 'Create CRUD API.',
            'completed' => false
        ]);
    }

    /** @test */
    public function update_task_success()
    {
        //Create new task
        $task = Task::factory()->create([
            'description' => 'Create CRUD API.',
            'completed' => false
        ]);

        //Update data
        $data = [
            'description' => 'Update CRUD API.',
            'completed' => true
        ];

        //send data to the api
        $this->postJson('/api/task/'.$task->id, $data)
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => "Task succesfully updated."
            ]);

        //Check record not exists in DB
        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
            'description' => 'Create CRUD API.',
            'completed' => false
        ]);

        //Check record exists in DB
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'description' => 'Update CRUD API.',
            'completed' => true
        ]);
    }

    /**
     * Fail Test
     */

    /** @test */
    public function create_task_fail_due_to_invalid_input()
    {
        //Create data
        $data = [
            'description' => 'aa.',
            'completed' => false
        ];

        //Send to store api
        $this->postJson('/api/task', $data)
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'description' => ['The description must be at least 5 characters.']
                ],
                'success' => false
            ]);

        //Check newly create data not exists in DB
        $this->assertDatabaseMissing('tasks', [
            'description' => 'aa.',
            'completed' => false
        ]);
    }


    /** @test */
    public function update_task_failed_due_to_task_not_exists()
    {
        //Update data
        $data = [
            'description' => 'Update task not exists in db.',
            'completed' => true
        ];

        //send data to the api
        $this->postJson('/api/task/123', $data)
            ->assertStatus(200)
            ->assertJson([
                'success' => false,
                'message' => "Task with Id 123 is not exists."
            ]);

        //Check record not exists in DB
        $this->assertDatabaseMissing('tasks', [
            'description' => 'Update task not exists in db.',
            'completed' => true
        ]);
    }
}
