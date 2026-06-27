<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Return a successful JSON response.
     */
    protected function success(
        mixed $data = null,
        string $message = 'Success',
        int $status = 200,
        array $meta = []
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
            'meta'    => empty($meta) ? null : $meta,
        ], $status);
    }

    /**
     * Return an error JSON response.
     */
    protected function error(
        string $message,
        int $status = 400,
        mixed $errors = null
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors,
        ], $status);
    }

    /**
     * Return a 201 Created response.
     */
    protected function created(
        mixed $data,
        string $message = 'Created successfully.'
    ): JsonResponse {
        return $this->success(
            data: $data,
            message: $message,
            status: 201
        );
    }

    /**
     * Return a 404 Not Found response.
     */
    protected function notFound(
        string $message = 'Resource not found.'
    ): JsonResponse {
        return $this->error(
            message: $message,
            status: 404
        );
    }

    /**
     * Return a 403 Forbidden response.
     */
    protected function forbidden(
        string $message = 'Forbidden.'
    ): JsonResponse {
        return $this->error(
            message: $message,
            status: 403
        );
    }

    /**
     * Return a 422 Validation Error response.
     */
    protected function validationError(
        mixed $errors,
        string $message = 'Validation failed.'
    ): JsonResponse {
        return $this->error(
            message: $message,
            status: 422,
            errors: $errors
        );
    }
}
