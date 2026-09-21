<?php

namespace App\Exceptions;

use App\Enums\ApiErrorCode;
use App\Support\ApiResponse;
use Exception;
use Illuminate\Contracts\Support\Responsable;

class ApiException extends Exception implements Responsable
{
    public function __construct(
        public readonly ApiErrorCode $errorCode,
        string $message = '',
        public readonly array $details = [],
        public readonly ?int $status = null,
        ?Exception $previous = null,
    ) {
        parent::__construct($message !== '' ? $message : $errorCode->value, 0, $previous);
    }

    public static function make(
        ApiErrorCode $code,
        string $message = '',
        array $details = [],
        ?int $status = null,
    ): static {
        return new static($code, $message, $details, $status);
    }

    public function errorCode(): ApiErrorCode
    {
        return $this->errorCode;
    }

    public function toResponse($request): \Illuminate\Http\JsonResponse
    {
        return ApiResponse::error(
            code: $this->errorCode,
            message: $this->getMessage(),
            details: $this->details,
            status: $this->status ?? $this->errorCode->statusCode(),
        );
    }
}