<?php

namespace CawaKharkov\YandexKassa;

use CawaKharkov\YandexKassa\ViewComposers\SettingsComposer;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;


/**
 * Class YandexKassaServiceProvider
 * @package CawaKharkov\YandexKassa
 */
class YandexKassaServiceProvider extends ServiceProvider
{

    /**
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../../views', 'yandex-kassa');

        $this->publishes([
            __DIR__ . '/../../config/yandex_kassa.php' => config_path('yandex_kassa.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../../database/migrations/' => database_path('/migrations/yandex_kassa')
        ], 'migrations');

        include __DIR__ . '/../routes.php';

        View::composer(config('yandex_kassa.form_view'), SettingsComposer::class);

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //   $this->app->bind(TransactionRepositoryInterface::class, BalanceTransactionRepository::class);
        //  $this->app->bind(BalanceTransactionInterface::class, BalanceTransaction::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
        ];
    }

}