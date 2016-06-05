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

        /**
         * app.requests {"request":{"orderNumber":"abc1111111",
         * "orderSumAmount":"345.00","cdd_exp_date":"1222",
         * "shopArticleId":"240809","paymentPayerCode":"4100322062290",
         * "cdd_rrn":"","external_id":"deposit","type":"AC","paymentType":"AC",
         * "requestDatetime":"2016-06-05T14:21:33.393+03:00",
         * "depositNumber":"QyGUYW1MTLterOVkVg5rNbXGIiIZ.001f.201606",
         * "cps_user_country_code":"PL","orderCreatedDatetime":"2016-06-05T14:21:33.294+03:00",
         * "sk":"u71590a8bd78f21ade045c8f4107452c3","action":"checkOrder","shopId":"116273",
         * "scid":"535929","shopSumBankPaycash":"1003","shopSumCurrencyPaycash":"10643",
         * "rebillingOn":"false","orderSumBankPaycash":"1003","cps_region_id":"11475",
         * "orderSumCurrencyPaycash":"10643","merchant_order_id":"abc1111111_050616142059_00000_116273",
         * "cdd_pan_mask":"444444|4448","customerNumber":"116273","yandexPaymentId":"2570045470018",
         * "invoiceId":"2000000774818","shopSumAmount":"332.92",
         * "md5":"4383906914F0DC177CBD1F2198642D7D","/payment/check":""}}
         */

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


        /**
         * app.requests {"request":{"orderNumber":"57540d921905f",
         * "orderSumAmount":"100.00","cdd_exp_date":"1222","shopArticleId":"240809",
         * "paymentPayerCode":"4100322062290","paymentDatetime":"2016-06-05T14:32:48.257+03:00"
         * ,"cdd_rrn":"","external_id":"deposit","type":"AC","paymentType":"AC",
         * "requestDatetime":"2016-06-05T14:32:48.932+03:00"
         * ,"depositNumber":"bIypHvuKBhtAzAY1xaBbABqR04kZ.001f.201606"
         * ,"cps_user_country_code":"PL","orderCreatedDatetime":"2016-06-05T14:32:48.354+03:00",
         * "sk":"u71590a8bd78f21ade045c8f4107452c3","action":"paymentAviso","shopId":"116273",
         * "scid":"535929","shopSumBankPaycash":"1003","shopSumCurrencyPaycash":"10643",
         * "rebillingOn":"false","orderSumBankPaycash":"1003","cps_region_id":"11475",
         * "orderSumCurrencyPaycash":"10643","merchant_order_id":"57540d921905f_050616143241_00000_116273",
         * "cdd_pan_mask":"444444|4448","customerNumber":"hodzhajan.d11","yandexPaymentId":"2570045470564"
         * ,"invoiceId":"2000000802631","shopSumAmount":"96.50","md5":"FE97EF63A1CF25F1946A62F3DD45BE0B"
         * ,"/payment/payment":""}}

         */

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