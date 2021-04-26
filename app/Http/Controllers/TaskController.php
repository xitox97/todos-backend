<?php

namespace App\Http\Controllers;

use Exception;
use App\Services\TaskService;
use App\Services\Utils\ApiException;
use App\Services\Utils\ResponseServices;

class TaskController extends Controller
{
    private $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index()
    {
        try {
            $result = $this->taskService->index();
            return ResponseServices::success()->data($result)->toJson();
        } catch (ApiException $exp) {
            return ResponseServices::error($exp->getMessage())->toJson();
        } catch (Exception $exp) {
            return ResponseServices::error($exp->getMessage())->toJson();
        }
    }
}
