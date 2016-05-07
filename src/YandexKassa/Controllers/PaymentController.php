<?php

namespace CawaKharkov\YandexKassa\Controllers;


use App\Http\Controllers\Controller;
use CawaKharkov\LaravelBalance\Models\BalanceTransaction;

/**
 * Class PaymentController
 * @package CawaKharkov\YandexKassa\Controllers
 */
class PaymentController extends Controller
{
    public function form()
    {
        return view(config('yandex_kassa.form_view'));
    }
}