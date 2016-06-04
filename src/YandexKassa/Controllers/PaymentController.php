<?php

namespace CawaKharkov\YandexKassa\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

/**
 * Class PaymentController
 * @package CawaKharkov\YandexKassa\Controllers
 */
class PaymentController extends Controller
{


    /**
     * PaymentController constructor.
     */
    public function __construct()
    {
    }

    public function form()
    {
        return view(config('yandex_kassa.form_view'));
    }

    public function check(Request $request)
    {
        Config::set('laravel-debugbar::config.enabled', false);

        Log::info('app.requests', ['request' => $request->all()]);
        $transactionId = $request->get('invoiceId');
        $action = $request->get('action');
        $value = $request->get('orderSumAmount');
        $orderSumCurrencyPaycash = $request->get('orderSumCurrencyPaycash');
        $orderSumBankPaycash = $request->get('orderSumBankPaycash');
        $customerNumber = $request->get('customerNumber');
        $md5 = $request->get('md5');
        $requestDatetime = $request->get('requestDatetime');

        $hash = md5($action . ';' . $value .
            ';' . $orderSumCurrencyPaycash . ';' .
            $orderSumBankPaycash . ';' .
            '116273' . ';' . $transactionId . ';'
            . $_POST['customerNumber'] . ';' . 'eKa5vVhcoLGf');
        if (strtolower($hash) != strtolower($md5)) {
            $code = 1;
        } else {
            $code = 0;
        }

        Log::info('yandex_check_code', ['code' => $code]);

        print '<?xml version="1.0" encoding="UTF-8"?>';
        print '<checkOrderResponse performedDatetime="' .
            $requestDatetime . '" code="' . $code . '"' .
            ' invoiceId="' . $transactionId .
            '" shopId="' . '116273' . '"/>';
    }

    public function aviso(Request $request)
    {
        Log::info('app.requests', ['request' => $request->all()]);

        $transactionId = $request->get('invoiceId');
        $action = $request->get('action');
        $value = $request->get('orderSumAmount');
        $orderSumCurrencyPaycash = $request->get('orderSumCurrencyPaycash');
        $orderSumBankPaycash = $request->get('orderSumBankPaycash');
        $customerNumber = $request->get('customerNumber');
        $md5 = $request->get('md5');
        $requestDatetime = $request->get('requestDatetime');

        $hash = md5($action . ';' . $value . ';' . $orderSumCurrencyPaycash . ';'
            . $orderSumBankPaycash . ';'
            . '116273' . ';' . $transactionId
            . ';' . $customerNumber . ';' . 'eKa5vVhcoLGf');
        if (strtolower($hash) != strtolower($md5)) {
            $code = 1;
        } else {
            $code = 0;
        }
        print '<?xml version="1.0" encoding="UTF-8"?>';
        print '<paymentAvisoResponse performedDatetime="' .
            $requestDatetime . '" code="' . $code .
            '" invoiceId="' . $transactionId . '" shopId="' .
            '116273' . '"/>';
    }
}