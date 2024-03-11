<?php

namespace EscolaLms\YeppChat\ValueObjects;

class ConversationMessage
{
    private string $answer;

    private ?string $conversationId;

    public function __construct(string $answer, ?string $conversationId = null)
    {
        $this->answer = $answer;
        $this->conversationId = $conversationId;
    }

    public function getAnswer(): string
    {
        return $this->answer;
    }

    public function getConversationId(): ?string
    {
        return $this->conversationId;
    }
}
