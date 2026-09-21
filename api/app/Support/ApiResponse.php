<?php

namespace App\Support;

use App\Enums\ApiErrorCode;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function success(mixed $data = [], array $meta = [], int $status = 200): JsonResponse
    {
        $payload = ['success' => true];

        if ($data !== null) {
            $payload['data'] = $data;
        }

        if ($meta !== []) {
            $payload['meta'] = $meta;
        }

        return response()->json($payload, $status);
    }

    public static function error(
        ApiErrorCode $code,
        string $message,
        array $details = [],
        ?int $status = null,
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'error' => [
                'code' => $code->value,
                'message' => $message,
                'details' => (object) $details,
            ],
        ], $status ?? $code->statusCode());
    }

    public static function created(mixed $data = [], array $meta = []): JsonResponse
    {
        return self::success($data, $meta, 201);
    }

    public static function paginate(LengthAwarePaginator $paginator): JsonResponse
    {
        return self::success(
            data: $paginator->items(),
            meta: [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ],
        );
    }
}