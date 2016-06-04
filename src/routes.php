<?php

Route::group(['prefix' => config('yandex_kassa.prefix')], function () {
    Route::get('form', [
        'as' => 'yandex_kassa.form',
        'uses' => '\CawaKharkov\YandexKassa\Controllers\PaymentController@form']);

    Route::post('check', [
        'as' => 'yandex_kassa.check',
        'uses' => '\CawaKharkov\YandexKassa\Controllers\PaymentController@check']);
    
    Route::post('payment', [
        'as' => 'yandex_kassa.aviso',
        'uses' => '\CawaKharkov\YandexKassa\Controllers\PaymentController@aviso']);
});
