<?php

namespace CawaKharkov\YandexKassa\Models;

use CawaKharkov\YandexKassa\Interfaces\YandexPaymentInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Class YandexPayment
 * @package CawaKharkov\YandexKassa\Models
 */
class YandexPayment extends Model implements YandexPaymentInterface
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'yandex_payments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hash' ,
        'invoiceId',
        'action',
        'type',
        'orderSumAmount' ,
        'shopSumAmount' ,
        'user_id',
        'transaction_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'    =>  'integer',
        'value' => 'float'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    const CONST_TYPE_REFILL = 100;
    const CONST_TYPE_PAYMENT = 200;
    const CONST_TYPE_CHECKOUT = 500;


    /**
     * Transactions labels
     * @var array
     */
    private $typeLabels = [
        self::CONST_TYPE_REFILL => 'Пополнение',
        self::CONST_TYPE_PAYMENT => 'Оплата',
        self::CONST_TYPE_CHECKOUT => 'Вывод',
    ];

    /**
     * Bootstrap labels
     * @var array
     */
    protected $labels = [
        self::CONST_TYPE_REFILL  => 'warning',
        self::CONST_TYPE_PAYMENT => 'success',
        self::CONST_TYPE_CHECKOUT => 'danger',

    ];

    public function user()
    {
        return $this->belongsTo(config('laravel_balance.user'));
    }


}