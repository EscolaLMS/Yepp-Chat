<?php

namespace EscolaLms\YeppChat;

use EscolaLms\Auth\EscolaLmsAuthServiceProvider;
use EscolaLms\ModelFields\ModelFieldsServiceProvider;
use EscolaLms\Settings\EscolaLmsSettingsServiceProvider;
use EscolaLms\YeppChat\Providers\SettingsServiceProvider;
use EscolaLms\YeppChat\Services\YeppChatService;
use EscolaLms\YeppChat\Services\Contracts\YeppChatServiceContract;
use Illuminate\Support\ServiceProvider;

/**
 * SWAGGER_VERSION
 */
class EscolaLmsYeppChatServiceProvider extends ServiceProvider
{
    const CONFIG_KEY = 'escolalms_yepp_chat';

    public const REPOSITORIES = [];

    public const SERVICES = [
        YeppChatServiceContract::class => YeppChatService::class,
    ];

    public $singletons = self::SERVICES + self::REPOSITORIES;

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config.php', self::CONFIG_KEY);

        $this->app->register(EscolaLmsSettingsServiceProvider::class);
        $this->app->register(EscolaLmsAuthServiceProvider::class);
        $this->app->register(SettingsServiceProvider::class);
        $this->app->register(ModelFieldsServiceProvider::class);
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    public function bootForConsole()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishes([
            __DIR__ . '/config.php' => config_path(self::CONFIG_KEY . '.php'),
        ], self::CONFIG_KEY . '.config');
    }
}
