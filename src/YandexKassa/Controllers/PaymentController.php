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

    /**
     * Display payment from
     * @return mixed
     */
    public function form()
    {
      return view(config('yandex_kassa.form_view'));
    }

    /**
      Receive Http check payment request from Yandex
     * @param PaymentRequest $request
     * @return mixed
     */
    public function check(PaymentRequest $request, YandexPaymentRepositoryInterface $paymentRepo)
    {

        $userId = $request->get('customerNumber');
        $md5 = $request->get('md5');
        $requestDatetime = $request->get('requestDatetime');

        $data = [
            'hash' => uniqid('yandex_payment_',true),
            'invoiceId' => $request->get('invoiceId'),
            'action' => $request->get('action'),
            'orderSumAmount' => $request->get('orderSumAmount'),
            'shopSumAmount' => $request->get('shopSumAmount'),
            'orderSumCurrencyPaycash' => $request->get('orderSumCurrencyPaycash'),
            'orderSumBankPaycash' => $request->get('orderSumBankPaycash'),
            'user_id' => $userId,

        ];

        $code = $this->checkCode($data, $md5);

        if ($code === 0) {

            $transaction = BalanceTransaction::create([
                'value' => $data['orderSumAmount'],
                'hash' => uniqid('transaction_', true),
                'type' => BalanceTransaction::CONST_TYPE_REFILL,
                'user_id' => $data['user_id']
            ]);

            $data['transaction_id'] = $transaction->id;

            $payment = $paymentRepo->create($data);

        }
        

        $xml = $this->generateXml($code, $data['invoiceId'], $requestDatetime);

        return Response::make($xml->asXML())->header('content', 'application/xml');
    }

    /**
     * Receive Http payment request from Yandex
     * @param PaymentRequest $request
     * @param YandexPaymentRepositoryInterface $paymentRepo
     * @return mixed
     */
    public function aviso(PaymentRequest $request,YandexPaymentRepositoryInterface $paymentRepo)
    {
     
        $userId = $request->get('customerNumber');
        $md5 = $request->get('md5');
        $requestDatetime = $request->get('requestDatetime');

        $data = [
            'hash' => uniqid('yandex_payment_',true),
            'invoiceId' => $request->get('invoiceId'),
            'action' => $request->get('action'),
            'orderSumAmount' => $request->get('orderSumAmount'),
            'shopSumAmount' => $request->get('shopSumAmount'),
            'orderSumCurrencyPaycash' => $request->get('orderSumCurrencyPaycash'),
            'orderSumBankPaycash' => $request->get('orderSumBankPaycash'),
            'user_id' => $userId,

        ];

        $code = $this->checkCode($data, $md5);

        if ($code === 0) {

            $payment = $paymentRepo->findByInvoiceId($data['invoiceId']);

            if($payment){

                \DB::transaction(function() use ($payment) {

                    $payment->transaction()->accepted = 1;
                    $payment->transaction()->save();

                    $payment->accepted = 1;
                    $payment->save();

                });

            }

        }

        $xml = $this->generateXml($code, $data['invoiceId'], $requestDatetime);

        return Response::make($xml->asXML())->header('content', 'application/xml');
    }

    /**
     * Generate XML
     * @param $code
     * @param $transactionId
     * @param $requestDatetime
     */
    protected function generateXml($code, $transactionId, $requestDatetime)
    {
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><checkOrderResponse/>');

        $xml->addAttribute('code', $code);
        $xml->addAttribute('invoiceId', $transactionId);
        $xml->addAttribute('shopId', config('yandex_kassa.shop.shopId'));
        $xml->addAttribute('performedDatetime', $requestDatetime);

        return $xml;
    }

    /**
     * @param PaymentRequest $request
     */
    protected function collectData(PaymentRequest $request)
    {
     //@todo move all params to FormRequest
    }

    /**
     * Check md5 of the payment
     * @param array $data
     * @param $md5
     * @return int
     */
    private function checkCode(array $data, $md5)
    {
        $code = 1;

        $hash = md5($data['action']. ';'. $data['orderSumAmount']. ';'
            . $data['orderSumCurrencyPaycash'] . ';'. $data['orderSumBankPaycash'] . ';'
            . config('yandex_kassa.shop.shopId'). ';'. $data['invoiceId']
            . ';'. $data['user_id']. ';'. config('yandex_kassa.shop.password'));

        if (strtolower($hash) == strtolower($md5)) {
            $code = 0;
        }
        return $code;
    }
}