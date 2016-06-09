<?php

namespace CawaKharkov\YandexKassa\Repositories;


use CawaKharkov\YandexKassa\Interfaces\YandexPaymentInterface;
use CawaKharkov\YandexKassa\Interfaces\YandexPaymentRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use YandexKassa\Events\PaymentCreated;
use Event;

/**
 * Class YandexPaymentRepository
 * @package CawaKharkov\YandexKassa\Repositories
 */
class YandexPaymentRepository implements YandexPaymentRepositoryInterface
{

    protected $model;

    /**
     * YandexPaymentRepositoryInterface constructor.
     * @param YandexPaymentInterface $model
     */
    public function __construct(YandexPaymentInterface $model)
    {
        $this->model = $model;
    }

    /**
     * Create payment
     * @param array $data
     * @return static
     */
    public function create(array $data)
    {
        $payment = $this->model->create($data);

        Event::fire(new PaymentCreated($payment));

        return ;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findByInvoiceId($id)
    {
        return $this->model->where(['invoiceId' => $id])->first();
    }
}