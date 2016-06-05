<?php

namespace CawaKharkov\YandexKassa\Repositories;


use CawaKharkov\LaravelBalance\Interfaces\BalanceTransactionInterface;
use CawaKharkov\LaravelBalance\Interfaces\TransactionRepositoryInterface;
use CawaKharkov\YandexKassa\Interfaces\YandexPaymentInterface;
use CawaKharkov\YandexKassa\Interfaces\YandexPaymentRepositoryInterface;
use CawaKharkov\YandexKassa\Models\YandexPayment;
use Illuminate\Support\Facades\Auth;

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
        return YandexPayment::create($data);
    }
}