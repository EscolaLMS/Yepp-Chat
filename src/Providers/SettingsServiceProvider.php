<?php

namespace EscolaLms\YeppChat\Providers;

use EscolaLms\Settings\EscolaLmsSettingsServiceProvider;
use EscolaLms\Settings\Facades\AdministrableConfig;
use EscolaLms\YeppChat\EscolaLmsYeppChatServiceProvider;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if (class_exists(EscolaLmsSettingsServiceProvider::class)) {
            if (!$this->app->getProviders(EscolaLmsSettingsServiceProvider::class)) {
                $this->app->register(EscolaLmsSettingsServiceProvider::class);
            }

            AdministrableConfig::registerConfig(EscolaLmsYeppChatServiceProvider::CONFIG_KEY . '.enabled', ['boolean'], false);
            AdministrableConfig::registerConfig(EscolaLmsYeppChatServiceProvider::CONFIG_KEY . '.auth.enabled', ['boolean'], false);
            AdministrableConfig::registerConfig(EscolaLmsYeppChatServiceProvider::CONFIG_KEY . '.auth.key', ['string'], false);
            AdministrableConfig::registerConfig(EscolaLmsYeppChatServiceProvider::CONFIG_KEY . '.url', ['string'], false);
        }
    }
}
