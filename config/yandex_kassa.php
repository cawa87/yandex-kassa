<?php
/**
 * Yandex.Kassa package config
 */
return [
    'prefix' => 'payment',
    'user' => App\User::class,
    'layout' => 'layouts.app',
    'form_view' => 'yandex-kassa::payment.form',
    'own_pages' => false,  // use own success/fail payment redirect pages
    
    /** 
     * Views to inject transaction repository 
     */
    'compose' => [
        'laravel-balance::transactions.list',
        'laravel-balance::transactions.list',
    ],

    'shop' =>[
        'form_url' => 'https://demomoney.yandex.ru/eshop.xml',
        'shopId' => '',  //Идентификатор магазина, выдается при подключении к Яндекс.Кассе.
        'scid' => '', //Идентификатор витрины магазина, выдается при подключении к Яндекс.Кассе.
        'password' => '', 
    ]
];