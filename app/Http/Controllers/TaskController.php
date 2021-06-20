<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Services\Utils\ApiException;
use App\Services\Utils\ResponseServices;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TaskController extends Controller
{
    private $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * @OA\Get(
     * path="/api/task",
     * summary="Return all task from the system",
     * description="Return all task",
     * operationId="index",
     * tags={"Task"},
     * @OA\Response(
     *    response=200,
     *    description="successful operation",
     *    @OA\JsonContent(
     *      @OA\Property(
     *      	property="data",
     *          type="array",
     *      	@OA\Items(
     *       	     @OA\Property(
     *       	         property="id",
     *       	         description="id",
     *       	         type="number",
     *       	         example="1"
     *       	      ),
     *       	      @OA\Property(
     *       	         property="description",
     *       	       	 description="task description",
     *       	         type="string",
     *       	         example="Learn ReactJs."
     *       	      ),
     *       	      @OA\Property(
     *       	         property="completed",
     *       	         description="task complete or not",
     *       	         type="boolean",
     *       	         example=true
     *       	       ),
     *         	),
     * 		),
     *
     *    )
     * )
     * )
     */
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

    /**
    * @OA\Post(
    *      path="/products/save",
    *      tags={"Product"},
    *      operationId="products",
    *      summary="Post bulk products",
    *      description="Return bulk products",
    *   	@OA\RequestBody(
    *       	required=true,
    *       	@OA\MediaType(
    *       	    mediaType="application/json",
    *       	    @OA\Schema(
    *       	        type="object",
    * 					@OA\Property(
    *      					property="products",
    *                       type="array",
    *      				    @OA\Items(
    *       	        		@OA\Property(
    *       	        		    property="first_name",
    *       	        		    description="First Name",
    *       	        		    type="string",
    *       	        		    example="Jhon"
    *       	        		),
    *       	        		@OA\Property(
    *       	        		    property="last_name",
    *       	        		    description="Last Name",
    *       	        		    type="string",
    *       	        		    example="Doe"
    *       	        		),
    *       	        		@OA\Property(
    *       	        		    property="email",
    *       	        		    description="Eamil",
    *       	        		    type="string",
    *       	        		    example="john@gmail.com"
    *       	        		),
    *       	        		@OA\Property(
    *       	        		    property="phone",
    *       	        		    description="Phone Number",
    *       	        		    type="string",
    *       	        		    example="+123456789"
    *       	        		),
    *       	        		@OA\Property(
    *       	        		    property="resume",
    *       	        		    description="Resume Base64",
    *       	        		    type="file",
    *       	        		    format="byte",
    *       	        		    example="base64"
    *       	        		),
    *         				),
    * 					),
    *       	    )
    *       	)
    *   	),
    *     	@OA\Response(
    *         response=200,
    *         description="Successful operation",
    *     ),
    * )
    */
    public function store(Request $request)
    {
        try {
            $result = $this->taskService->createModel($request->all());
            $data = $result['data'];
            $message = $result['message'];

            return ResponseServices::success($message)
                ->data($data)
                ->toJson();
        } catch (ApiException $exp) {
            return ResponseServices::error($exp->getMessage())
                ->toJson();
        } catch (Exception $exp) {
            return ResponseServices::error($exp->getMessage())
                ->data($exp->validator->errors())
                ->toJson();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $result = $this->taskService->updateModel($request->only(['completed', 'description']), $id);

            return ResponseServices::success($result['message'])
                ->toJson();
        } catch (ApiException $exp) {
            return ResponseServices::error($exp->getMessage())
                ->toJson();
        } catch (Exception $exp) {
            if($exp instanceof ModelNotFoundException) {
                return ResponseServices::error("Task with Id {$id} is not exists.")
                    ->toJson();
            }

            return ResponseServices::error($exp->getMessage())
                ->data($exp->getMessage())
                ->toJson();
        }
    }

    public function delete($id)
    {
        try {
            $result = $this->taskService->deleteModel($id);

            return ResponseServices::success($result['message'])
                ->toJson();
        } catch (ApiException $exp) {
            return ResponseServices::error($exp->getMessage())
                ->toJson();
        } catch (Exception $exp) {
            if($exp instanceof ModelNotFoundException) {
                return ResponseServices::error("Task with Id {$id} is not exists.")
                    ->toJson();
            }

            return ResponseServices::error($exp->getMessage())
                ->data($exp->getMessage())
                ->toJson();
        }
    }
}
