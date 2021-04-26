<?php

namespace App\Services;

use App\Repositories\TaskRepository;

class TaskService
{
    public function __construct()
    {
    }

    public function index($sorts  = [], $filters = [], $pageSize = 20)
    {
        $tasks = TaskRepository::findAll($sorts, $filters, $pageSize);

        return $tasks;
    }
}
