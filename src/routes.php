<?php

Route::group(['middleware' => 'auth','prefix' => config('yandex_kassa.prefix')], function () {
    Route::get('form', [
        'as' => 'yandex_kassa.form',
        'uses' => '\CawaKharkov\YandexKassa\Controllers\PaymentController@form']);
});
