<?php

namespace CawaKharkov\YandexKassa\ViewComposers;

use Illuminate\View\View;


class SettingsComposer
{

    protected $shopSettings;

    public function __construct()
    {
        $this->shopSettings = config('yandex_kassa.shop');

    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {

        $view->with([
            'shopSettings'=> $this->shopSettings,
        ]);
    }
}