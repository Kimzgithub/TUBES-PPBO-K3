<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    protected function success(mixed $data = null, string $msg = 'Berhasil.', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $msg,
            'data' => $data,
        ], $code);
    }

    protected function created(mixed $data = null, string $msg = 'Data berhasil dibuat.'): JsonResponse
    {
        return $this->success($data, $msg, 201);
    }

    protected function error(string $msg = 'Terjadi kesalahan.', int $code = 400, mixed $errors = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $msg,
            'errors' => $errors,
        ], $code);
    }
}