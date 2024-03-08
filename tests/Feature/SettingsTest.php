<?php

namespace EscolaLms\YeppChat\Tests\Feature;

use EscolaLms\Auth\Database\Seeders\AuthPermissionSeeder;
use EscolaLms\Core\Tests\CreatesUsers;
use EscolaLms\Settings\Database\Seeders\PermissionTableSeeder;
use EscolaLms\Settings\EscolaLmsSettingsServiceProvider;
use EscolaLms\YeppChat\EscolaLmsYeppChatServiceProvider;
use EscolaLms\YeppChat\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;

class SettingsTest extends TestCase
{
    use CreatesUsers, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        if (!class_exists(EscolaLmsSettingsServiceProvider::class)) {
            $this->markTestSkipped('Settings package not installed');
        }

        $this->seed(PermissionTableSeeder::class);
        $this->seed(AuthPermissionSeeder::class);
        Config::set('escola_settings.use_database', true);
    }

    public function testAdministrableConfigApi(): void
    {
        $user = $this->makeAdmin();

        $configKey = EscolaLmsYeppChatServiceProvider::CONFIG_KEY;

        $yeppEnabled = $this->faker->boolean;
        $yeppAuthEnabled = $this->faker->boolean;
        $yeppAuthKey = $this->faker->uuid;
        $yeppUrl = $this->faker->url;

        $this->actingAs($user, 'api')
            ->postJson('/api/admin/config',
                [
                    'config' => [
                        [
                            'key' => "{$configKey}.enabled",
                            'value' => $yeppEnabled,
                        ],
                        [
                            'key' => "{$configKey}.auth.enabled",
                            'value' => $yeppAuthEnabled,
                        ],
                        [
                            'key' => "{$configKey}.auth.key",
                            'value' => $yeppAuthKey,
                        ],
                        [
                            'key' => "{$configKey}.url",
                            'value' => $yeppUrl,
                        ],
                    ]
                ]
            )
            ->assertOk();

        $this->actingAs($user, 'api')->getJson('/api/admin/config')
            ->assertOk()
            ->assertJsonFragment([
                $configKey => [
                    'enabled' => [
                        'full_key' => "$configKey.enabled",
                        'key' => 'enabled',
                        'public' => false,
                        'rules' => [
                            'boolean'
                        ],
                        'value' => $yeppEnabled,
                        'readonly' => false,
                    ],
                    'auth' => [
                        'enabled' => [
                            'full_key' => "$configKey.auth.enabled",
                            'key' => 'auth.enabled',
                            'public' => false,
                            'rules' => [
                                'boolean'
                            ],
                            'value' => $yeppAuthEnabled,
                            'readonly' => false,
                        ],
                        'key' => [
                            'full_key' => "$configKey.auth.key",
                            'key' => 'auth.key',
                            'public' => false,
                            'rules' => [
                                'string'
                            ],
                            'value' => $yeppAuthKey,
                            'readonly' => false,
                        ],
                    ],
                    'url' => [
                        'full_key' => "$configKey.url",
                        'key' => 'url',
                        'public' => false,
                        'rules' => [
                            'string'
                        ],
                        'value' => $yeppUrl,
                        'readonly' => false,
                    ],
                ],
            ]);

        $this->getJson('/api/config')
            ->assertOk()
            ->assertJsonMissing([
                'enable' => $yeppEnabled,
                'auth' => [
                    'enabled' => $yeppAuthEnabled,
                    'key' => $yeppAuthKey,
                ],
                'url' => $yeppUrl,
            ]);
    }
}
