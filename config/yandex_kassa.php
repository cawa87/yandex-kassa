<?php
/**
 * Yandex.Kassa package config
 */
return [
    'prefix' => 'payment',
    'user' => App\User::class,
    'layout' => 'layouts.app',
    'form_view' => 'yandex-kass::payment.form',
    
    /** 
     * Views to inject transaction repository 
     */
    'compose' => [
        'laravel-balance::transactions.list',
        'laravel-balance::transactions.list',
    ]
];