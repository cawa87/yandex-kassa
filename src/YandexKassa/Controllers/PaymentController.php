<?php

namespace CawaKharkov\YandexKassa\Controllers;


use App\Http\Controllers\Controller;
use CawaKharkov\LaravelBalance\Models\BalanceTransaction;
use CawaKharkov\YandexKassa\Interfaces\YandexPaymentRepositoryInterface;
use CawaKharkov\YandexKassa\Requests\PaymentRequest;
use CawaKharkov\YandexKassa\Validators\PaymentValidator;
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

    public function check(PaymentRequest $request)
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

        print '<?xml version="1.0" encoding="UTF-8"?>';
        print '<checkOrderResponse performedDatetime="' .
            $requestDatetime . '" code="' . $code . '"' .
            ' invoiceId="' . $transactionId .
            '" shopId="' . config('yandex_kassa.shop.shopId'). '"/>';
    }

    public function aviso(PaymentRequest $request, YandexPaymentRepositoryInterface $paymentRepo)
    {
        Log::info('app.requests', ['request' => $request->all()]);

        $userId = $request->get('customerNumber');
        $orderSumAmount = $request->get('orderSumAmount');
        $shopSumAmount = $request->get('shopSumAmount');
        $orderSumCurrencyPaycash = $request->get('orderSumCurrencyPaycash');
        $orderSumBankPaycash = $request->get('orderSumBankPaycash');

        $data = [
            'hash' => uniqid('yandex_payment_',true),
            'transactionId' => $request->get('invoiceId'),
            'action' => $request->get('action'),
            'orderSumAmount' => $orderSumAmount,
            'shopSumAmount' =>$shopSumAmount,
            'orderSumCurrencyPaycash' => $orderSumCurrencyPaycash,
            'user_id' => $userId,
        ];

        $md5 = $request->get('md5');
        $requestDatetime = $request->get('requestDatetime');

        $transaction = BalanceTransaction::create([
            'value' => $shopSumAmount,
             'hash' => uniqid('transaction_',true),
            'type' => BalanceTransaction::CONST_TYPE_REFILL,
            'user_id' => $userId
        ]);
        
        $payment =  $paymentRepo->create($data);


        $hash = md5($data['action'] . ';' . $orderSumAmount . ';' . $orderSumCurrencyPaycash . ';'
            . $orderSumBankPaycash . ';'
            . config('yandex_kassa.shop.shopId') . ';' . $data['transactionId']
            . ';' . $userId . ';' . config('yandex_kassa.shop.password'));
        if (strtolower($hash) != strtolower($md5)) {
            $code = 1;
        } else {
            $code = 0;
        }
        print '<?xml version="1.0" encoding="UTF-8"?>';
        print '<checkOrderResponse performedDatetime="' .
            $requestDatetime . '" code="' . $code . '"' .
            ' invoiceId="' . $data['transactionId'] .
            '" shopId="' . config('yandex_kassa.shop.shopId'). '"/>';
    }
}