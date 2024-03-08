<?php

namespace EscolaLms\YeppChat\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class YeppChatDisabledException extends Exception
{
    public function __construct(string $message = null) {
        parent::__construct($message ?? __('Yepp chat is disabled.'));
    }

    public function render(): JsonResponse
    {
        return response()->json(['message' => $this->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}

