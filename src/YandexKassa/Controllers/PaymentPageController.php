<?php

namespace CawaKharkov\YandexKassa\Controllers;


use App\Http\Controllers\Controller;
use CawaKharkov\YandexKassa\Interfaces\YandexPaymentRepositoryInterface;
use CawaKharkov\LaravelBalance\Models\BalanceTransaction;
use CawaKharkov\YandexKassa\Requests\PaymentRequest;
use CawaKharkov\YandexKassa\Validators\PaymentValidator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;


/**
 * Class PaymentPageController
 * @package CawaKharkov\YandexKassa\Controllers
 */
class PaymentPageController extends Controller
{

    /**
     * Success payment page
     * @return mixed
     */
    public function success()
    {
        return view('yandex-kassa::payment_page.success');
    }

    /**
     * Faild payment page
     * @return mixed
     */
    public function fail()
    {
        return view('yandex-kassa::payment_page');
    }
}