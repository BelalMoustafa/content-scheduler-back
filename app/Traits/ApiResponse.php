<?php

namespace App\Traits;

trait ApiResponse
{
    public function okResponse(string $message = 'Operation successful.', $data = null)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'errors' => null,
        ], 200);
    }

    public function errorResponse(string $message = 'An error occurred.', array $errors = null)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => null,
            'errors' => $errors,
        ], 400);
    }

    public function notFoundResponse(string $message = 'Resource not found.')
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => null,
            'errors' => ['error' => 'Not Found'],
        ], 404);
    }

    public function unauthorizedResponse(string $message = 'Unauthorized access.')
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => null,
            'errors' => ['error' => 'Unauthorized'],
        ], 401);
    }

    public function validationErrorResponse(string $message = 'Validation failed.', array $errors = [])
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => null,
            'errors' => $errors,
        ], 422);
    }
}
