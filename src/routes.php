<?php

Route::group(['prefix' => config('yandex_kassa.prefix')], function () {
    Route::get('form', [
        'as' => 'yandex_kassa.form',
        'middleware' => 'auth',
        'uses' => '\CawaKharkov\YandexKassa\Controllers\PaymentController@form']);

    Route::post('check', [
        'as' => 'yandex_kassa.check',
        'uses' => '\CawaKharkov\YandexKassa\Controllers\PaymentController@check']);
    
    Route::post('payment', [
        'as' => 'yandex_kassa.aviso',
        'uses' => '\CawaKharkov\YandexKassa\Controllers\PaymentController@aviso']);
    
       
    Route::get('success', [
        'as' => 'yandex_kassa.page.success',
        'uses' => '\CawaKharkov\YandexKassa\Controllers\PaymentPageController@success']);

    Route::get('fail', [
        'as' => 'yandex_kassa.page.fail',
        'uses' => '\CawaKharkov\YandexKassa\Controllers\PaymentPageController@fail']);

    
});
