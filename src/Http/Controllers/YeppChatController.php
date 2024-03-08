<?php

namespace EscolaLms\YeppChat\Http\Controllers;

use EscolaLms\Core\Http\Controllers\EscolaLmsBaseController;
use EscolaLms\YeppChat\Http\Controllers\Swagger\YeppChatControllerSwagger;
use EscolaLms\YeppChat\Http\Requests\GetYeppChatRequest;
use EscolaLms\YeppChat\Http\Resources\YeppChatResource;
use EscolaLms\YeppChat\Services\Contracts\YeppChatServiceContract;
use Illuminate\Http\JsonResponse;

class YeppChatController extends EscolaLmsBaseController implements YeppChatControllerSwagger
{

    public function __construct(private YeppChatServiceContract $yeppChatService)
    {
    }

    public function get(GetYeppChatRequest $request): JsonResponse
    {
        $result = $this->yeppChatService
            ->getChat($request->getLesson(), $request->getQuestion(), $request->getConversationId());

        return $this->sendResponseForResource(YeppChatResource::make($result));
    }
}
