<?php

namespace App\Services;

use App\Repositories\TaskRepository;
use Illuminate\Support\Facades\Validator;

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

    public function createModel($data)
    {
        Validator::make($data, [
            'description' => 'required|min:5',
            'completed' => 'boolean',
        ])->validate();

        $model = TaskRepository::createModel($data);

        $message = __('Task succesfully created.');

        return [
            'data' => $model->id,
            'message' => $message
        ];
    }

    public function updateModel($data, $id)
    {
        TaskRepository::updateModelByPk($data, $id);
        $message = __('Task succesfully updated.');

        return [
            'message' => $message
        ];
    }
}
