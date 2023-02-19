<?php

namespace App\Traits;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;

trait ApiResponser
{
    protected function successResponse($data, $code = 200)
    {
        return response()->json(
            $data,
            $code
        );
    }

    protected function errorResponse(string $message, $code = 500)
    {
        return response()->json(
            [
                'data' => [
                    'error' => $message,
                    'code' => $code,
                ]
            ],
            $code
        );
    }

    protected function showAll(object $collection, $code = 200)
    {

        return $this->successResponse(
            [
                'data' => $collection
            ],
            $code
        );
    }

    protected function showOne(object $model, $message = 'Charged successfully', $code = 200)
    {
        return $this->successResponse(
            [
                'data' => $model,
                'message' => $message,
            ],
            $code
        );
    }

    protected function showNone(object $error)
    {
        return $this->successResponse(
            [
                'message' => 'Not found a response',
                'error' => $error
            ],
            404
        );
    }

    public function successDelete(object $model, $message, $code = 200)
    {
        return $this->successResponse(
            [
                'data' => $model,
                'message' => $message . ' ' . 'deleted successfully',
            ],
            $code
        );
    }

    protected function showMessage($message = 'Charged successfully', $code = 200)
    {
        return $this->successResponse(
            [
                'message' => $message,
            ],
            $code
        );
    }

    protected function showLoginInfo(array $loginData)
    {
        return $this->successResponse(
            [
                'data' => $loginData,
            ],
            200
        );
    }
}
