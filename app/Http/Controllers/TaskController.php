<?php

namespace App\Http\Controllers;

use Exception;
use App\Services\TaskService;
use App\Services\Utils\ApiException;
use App\Services\Utils\ResponseServices;
use Illuminate\Http\Request;

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

    public function store(Request $request)
    {
        try {
            $result = $this->taskService->createModel($request->all());
            $data = $result['data'];
            $message = $result['message'];

            ray(1);

            return ResponseServices::success($message)
                ->data($data)
                ->toJson();
        } catch (ApiException $exp) {
            ray(2);
            return ResponseServices::error($exp->getMessage())
                ->toJson();
        } catch (Exception $exp) {
            return ResponseServices::error($exp->getMessage())
                ->data($exp->validator->errors())
                ->toJson();
        }
    }
}
