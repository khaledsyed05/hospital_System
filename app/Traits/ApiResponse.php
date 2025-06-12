<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponse
{
    /**
     * Return a success JSON response.
     *
     * @param  mixed  $data
     * @param  string|null  $message
     * @param  int  $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successResponse($data = null, ?string $message = 'Operation completed successfully.', int $statusCode = Response::HTTP_OK): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if ($data instanceof LengthAwarePaginator) {
            // If data is paginated, structure it nicely
            $response['data'] = $data->items();
            $response['pagination'] = [
                'total' => $data->total(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem(),
            ];
        } elseif ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Return an error JSON response.
     *
     * @param  string  $message
     * @param  int  $statusCode
     * @param  array|null  $errors  (For validation errors)
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse(string $message, int $statusCode = Response::HTTP_BAD_REQUEST, ?array $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Specifically for validation errors.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return \Illuminate\Http\JsonResponse
     */
    protected function validationErrorResponse(\Illuminate\Contracts\Validation\Validator $validator): JsonResponse
    {
        return $this->errorResponse(
            'The given data was invalid.',
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $validator->errors()->toArray()
        );
    }

    /**
     * Response for a created resource.
     *
     * @param mixed $data
     * @param string|null $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createdResponse($data = null, ?string $message = 'Resource created successfully.'): JsonResponse
    {
        return $this->successResponse($data, $message, Response::HTTP_CREATED);
    }

    /**
     * Response for a resource that was updated.
     *
     * @param mixed $data
     * @param string|null $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function updatedResponse($data = null, ?string $message = 'Resource updated successfully.'): JsonResponse
    {
        return $this->successResponse($data, $message, Response::HTTP_OK);
    }

    /**
     * Response for a resource that was deleted.
     *
     * @param string|null $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function deletedResponse(?string $message = 'Resource deleted successfully.'): JsonResponse
    {
        // Typically, a 204 No Content response is returned for delete,
        // but if we want to return a message, 200 OK with a body is also acceptable.
        // Or, we can return 204 and the controller can choose not to return a body.
        // For consistency with the message structure, we'll use 200 OK here.
        return $this->successResponse(null, $message, Response::HTTP_OK);
    }

    /**
     * Response for "Not Found" errors.
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function notFoundResponse(string $message = 'Resource not found.'): JsonResponse
    {
        return $this->errorResponse($message, Response::HTTP_NOT_FOUND);
    }

    /**
     * Response for authorization errors.
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function forbiddenResponse(string $message = 'This action is unauthorized.'): JsonResponse
    {
        return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
    }
}
