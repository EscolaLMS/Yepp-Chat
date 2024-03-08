<?php

namespace EscolaLms\YeppChat\Http\Resources;

use EscolaLms\YeppChat\ValueObjects\ConversationMessage;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *      schema="YeppChatResource",
 *      required={"answer"},
 *      @OA\Property(
 *          property="answer",
 *          description="answer",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="conversation_id",
 *          description="conversation_id",
 *          type="string"
 *      ),
 * )
 *
 */
class YeppChatResource extends JsonResource
{
    private ConversationMessage $postMessage;

    public function __construct(ConversationMessage $postMessage)
    {
        parent::__construct($postMessage);
        $this->postMessage = $postMessage;
    }

    public function toArray($request): array
    {
        return [
            'answer' => $this->postMessage->getAnswer(),
            'conversation_id' => $this->postMessage->getConversationId()
        ];
    }
}
