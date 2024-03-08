<?php

namespace EscolaLms\YeppChat\Tests;

use EscolaLms\Courses\Models\Course;
use EscolaLms\Courses\Models\Lesson;
use Illuminate\Foundation\Testing\WithFaker;

trait YeppChatTesting
{
    use WithFaker;

    public function makeLesson(): Lesson
    {
        return Lesson::factory()->for(Course::factory())->create(['assistant_id' => $this->faker->uuid]);
    }

    public function makeInvalidLesson(): Lesson
    {
        return Lesson::factory()->for(Course::factory())->create();
    }

    public function getYeppChatPayload(): array
    {
        return [
            'question' => $this->faker->words(5, true),
            'conversation_id' => $this->faker->uuid,
        ];
    }

    public function getConversationMessageResponse(): array
    {
        return [
            'ans' => $this->faker->words(5, true),
            'conversation_id' => $this->faker->uuid,
        ];
    }
}
