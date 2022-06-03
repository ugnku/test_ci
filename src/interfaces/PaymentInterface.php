<?php
/** @noinspection PhpUnused */

namespace ecommpay\interfaces;

use DateTime;

/**
 * Class Payment
 *
 * @link https://developers.ecommpay.com/en/en_PP_Parameters.html
 */
interface PaymentInterface
{
    /**
     * Payment from customer account
     */
    const PURCHASE_TYPE = 'purchase';

    /**
     * Payment to customer account
     */
    const PAYOUT_TYPE = 'payout';

    /**
     * Recurring payment
     */
    const RECURRING_TYPE = 'recurring';

    const INTERFACE_TYPE = 23;

    /**
     * Get payment parameters
     *
     * @return array
     */
    public function getParams();

    /**
     * Date and time when the payment period expires.
     *
     * @param DateTime $time
     *
     * @return PaymentInterface
     */
    public function setBestBefore(DateTime $time);
}
