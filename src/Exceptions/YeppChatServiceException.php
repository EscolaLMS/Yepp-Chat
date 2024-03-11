<?php

namespace EscolaLms\YeppChat\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class YeppChatServiceException extends Exception
{
    public function __construct(string $message = null, ?int $code = Response::HTTP_BAD_REQUEST) {
        parent::__construct($message ?? __('Yepp chat service exception.'));
        $this->code = $code;
    }

    public function render(): JsonResponse
    {
        return response()->json(['message' => $this->getMessage()], $this->code);
    }
}

