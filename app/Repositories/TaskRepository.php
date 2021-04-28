<?php

namespace App\Repositories;

use App\Models\Task;

class TaskRepository
{
    public static function findAll($sorts, $filters = [], $pageSize = 20)
    {
        return Task::all();
    }

    public static function createModel($data)
    {
        return Task::create($data);
    }
}
