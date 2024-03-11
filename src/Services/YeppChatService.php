<?php

namespace EscolaLms\YeppChat\Services;

use EscolaLms\Courses\Models\Lesson;
use EscolaLms\YeppChat\EscolaLmsYeppChatServiceProvider;
use EscolaLms\YeppChat\Exceptions\LessonHasNotChatException;
use EscolaLms\YeppChat\Exceptions\YeppChatDisabledException;
use EscolaLms\YeppChat\Exceptions\YeppChatServiceException;
use EscolaLms\YeppChat\Services\Contracts\YeppChatServiceContract;
use EscolaLms\YeppChat\ValueObjects\ConversationMessage;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class YeppChatService implements YeppChatServiceContract
{

    /**
     * @throws LessonHasNotChatException
     * @throws YeppChatDisabledException
     * @throws YeppChatServiceException
     */
    public function getChat(Lesson $lesson, string $question, ?string $conversationId): ConversationMessage
    {
        if (!config(EscolaLmsYeppChatServiceProvider::CONFIG_KEY . '.enabled')) {
            throw new YeppChatDisabledException();
        }

        $assistantId = $lesson->assistant_id;

        if (!$assistantId) {
            throw new LessonHasNotChatException();
        }

        try {
            $response = $this->getYeppChatHttpClient()
                ->post('/chat', [
                    'q' => $question,
                    'assistantId' => $assistantId,
                    'conversationId' => $conversationId
                ])
                ->throw()
                ->object();
        } catch (RequestException $requestException) {
            throw throw new YeppChatServiceException($requestException->getMessage(), $requestException->getCode());
        }

        return new ConversationMessage($response->ans, $conversationId);
    }

    public function getYeppChatHttpClient(): PendingRequest
    {
        $http = Http::baseUrl(config(EscolaLmsYeppChatServiceProvider::CONFIG_KEY . '.url'));

        if (config(EscolaLmsYeppChatServiceProvider::CONFIG_KEY . '.auth.enabled') && config(EscolaLmsYeppChatServiceProvider::CONFIG_KEY . '.auth.key')) {
            $http->withToken(config(EscolaLmsYeppChatServiceProvider::CONFIG_KEY . '.auth.key'), null);
        }

        return $http;
    }
}
