<?php

namespace CawaKharkov\YandexKassa\Events;

use App\Podcast;
use Event;
use CawaKharkov\YandexKassa\Interfaces\YandexPaymentInterface;
use Illuminate\Queue\SerializesModels;

/**
 * Class PaymentAccepted
 * @package YandexKassa\Events
 */
class PaymentAccepted extends Event
{
    use SerializesModels;

    public $payment;

    /**
     * Create a new event instance.
     * @param YandexPaymentInterface $transaction
     */
    public function __construct(YandexPaymentInterface $payment)
    {
        $this->payment = $payment;
    }
}
