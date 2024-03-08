<?php

namespace EscolaLms\YeppChat\Http\Requests;

use EscolaLms\Courses\Models\Lesson;
use EscolaLms\YeppChat\Enums\YeppChatPermissionEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;


/**
 * @OA\Schema(
 *      schema="GetYeppChatRequest",
 *      required={"question"},
 *      @OA\Property(
 *          property="question",
 *          description="Question",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="conversation_id",
 *          description="Conversation Id",
 *          type="string"
 *      ),
 * )
 *
 */
class GetYeppChatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows(YeppChatPermissionEnum::YEPP_CHAT_READ);
    }

    public function rules(): array
    {
        return [
            'question' => ['required', 'string', 'min:5', 'max:5000'],
            'conversation_id' => ['nullable', 'string']
        ];
    }

    public function getLessonId(): int
    {
        return (int) $this->route('lessonId');
    }

    public function getQuestion(): string
    {
        return $this->input('question');
    }

    public function getConversationId(): ?string
    {
        return (string) $this->input('conversation_id');
    }

    public function getLesson(): Lesson
    {
        return Lesson::findOrFail($this->getLessonId());
    }
}
