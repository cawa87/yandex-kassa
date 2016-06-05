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
     
        $userId = $request->get('customerNumber');
        $orderSumAmount = $request->get('orderSumAmount');
        $shopSumAmount = $request->get('shopSumAmount');
        $orderSumCurrencyPaycash = $request->get('orderSumCurrencyPaycash');
        $orderSumBankPaycash = $request->get('orderSumBankPaycash');



        $md5 = $request->get('md5');
        $requestDatetime = $request->get('requestDatetime');

        $transaction = BalanceTransaction::create([
            'value' => $shopSumAmount,
             'hash' => uniqid('transaction_',true),
            'type' => BalanceTransaction::CONST_TYPE_REFILL,
            'user_id' => $userId
        ]);

        $data = [
            'hash' => uniqid('yandex_payment_',true),
            'transactionId' => $request->get('invoiceId'),
            'action' => $request->get('action'),
            'orderSumAmount' => $orderSumAmount,
            'shopSumAmount' =>$shopSumAmount,
            'user_id' => $userId,
            'type' => $request->get('action'),
            'transaction_id' => $transaction->id

        ];
        
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