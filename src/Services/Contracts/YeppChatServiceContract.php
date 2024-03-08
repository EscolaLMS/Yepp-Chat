<?php

namespace EscolaLms\YeppChat\Services\Contracts;

use EscolaLms\Courses\Models\Lesson;
use EscolaLms\YeppChat\ValueObjects\ConversationMessage;

interface YeppChatServiceContract
{
    public function getChat(Lesson $lesson, string $question, ?string $conversationId): ConversationMessage;
}
