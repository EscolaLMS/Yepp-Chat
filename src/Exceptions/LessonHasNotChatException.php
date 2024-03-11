<?php

namespace EscolaLms\YeppChat\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class LessonHasNotChatException extends Exception
{
    public function __construct(string $message = null) {
        parent::__construct($message ?? __('The lesson has no defined chat.'));
    }

    public function render(): JsonResponse
    {
        return response()->json(['message' => $this->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}

