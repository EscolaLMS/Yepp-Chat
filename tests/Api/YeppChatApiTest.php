<?php

namespace EscolaLms\YeppChat\Tests\Api;

use EscolaLms\Core\Tests\CreatesUsers;
use EscolaLms\YeppChat\Database\Seeders\YeppChatPermissionSeeder;
use EscolaLms\YeppChat\EscolaLmsYeppChatServiceProvider;
use EscolaLms\YeppChat\Tests\TestCase;
use EscolaLms\YeppChat\Tests\YeppChatTesting;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class YeppChatApiTest extends TestCase
{
    use YeppChatTesting, CreatesUsers, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(YeppChatPermissionSeeder::class);

        Config::set('escola_settings.use_database', true);
        Config::set(EscolaLmsYeppChatServiceProvider::CONFIG_KEY . '.enabled', true);
        Config::set(EscolaLmsYeppChatServiceProvider::CONFIG_KEY . '.auth.enabled', true);
        Config::set(EscolaLmsYeppChatServiceProvider::CONFIG_KEY . '.auth.key', 'xyz123');
    }

    public function testGetYeppChat(): void
    {
        Http::fakeSequence()->push($this->getConversationMessageResponse());

        $this->actingAs($this->makeStudent(), 'api')
            ->postJson('api/yepp-chat/' . $this->makeLesson()->getKey(), $this->getYeppChatPayload())
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'answer',
                    'conversation_id',
                ]
            ]);
    }

    public function testGetYeppChatErrorService(): void
    {
        Http::fakeSequence()->pushStatus(500);

        $this->actingAs($this->makeStudent(), 'api')
            ->postJson('api/yepp-chat/' . $this->makeLesson()->getKey(), $this->getYeppChatPayload())
            ->assertStatus(500);
    }


    public function testGetYeppChatLessonInvalidAssistantId(): void
    {
        $this->actingAs($this->makeStudent(), 'api')
            ->postJson('api/yepp-chat/' . $this->makeInvalidLesson()->getKey(), $this->getYeppChatPayload())
            ->assertUnprocessable()
            ->assertJsonFragment([
                'message' => 'The lesson has no defined chat.'
            ]);
    }

    public function testGetYeppChatDisabled(): void
    {
        Config::set(EscolaLmsYeppChatServiceProvider::CONFIG_KEY . '.enabled', false);

        $this->actingAs($this->makeStudent(), 'api')
            ->postJson('api/yepp-chat/' . $this->makeLesson()->getKey(), $this->getYeppChatPayload())
            ->assertUnprocessable()
            ->assertJsonFragment([
                'message' => 'Yepp chat is disabled.'
            ]);
    }

    public function testGetYeppChatInvalidData(): void
    {
        $this->actingAs($this->makeStudent(), 'api')
            ->postJson('api/yepp-chat/' . $this->makeLesson()->getKey(), ['question' => null])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['question' => 'is required.']);

        $this->actingAs($this->makeStudent(), 'api')
            ->postJson('api/yepp-chat/' . $this->makeLesson()->getKey(), ['question' => ''])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['question' => 'is required.']);

        $this->actingAs($this->makeStudent(), 'api')
            ->postJson('api/yepp-chat/' . $this->makeLesson()->getKey(), ['question' => '123'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['question' => 'least 5 characters.']);

        $this->actingAs($this->makeStudent(), 'api')
            ->postJson('api/yepp-chat/' . $this->makeLesson()->getKey(), ['question' => $this->faker->words(5000, true)])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['question' => 'must not be greater than 5000 characters.']);
    }

    public function testGetYeppChaForbidden(): void
    {
        $user = config('auth.providers.users.model')::factory()->create();

        $this->actingAs($user, 'api')
            ->postJson('api/yepp-chat/' . $this->makeLesson()->getKey(), $this->getYeppChatPayload())
            ->assertForbidden();
    }

    public function testGetYeppChaUnauthorized(): void
    {
        $this->postJson('api/yepp-chat/' . $this->makeLesson()->getKey(), $this->getYeppChatPayload())
            ->assertUnauthorized();
    }
}
