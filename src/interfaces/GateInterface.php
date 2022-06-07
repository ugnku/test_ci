<?php

namespace tci\interfaces;

/**
 * Gate interface
 */
interface GateInterface
{
    const CURRENCY_RUB = 'RUB';
    const CURRENCY_USD = 'USD';
    const CURRENCY_EUR = 'EUR';

    /**
     * Enable or disable validation payment params before generate PaymentPage URL.
     * @param bool $flag
     * @return void
     */
    public function setValidationParams($flag);

    /**
     * @param string $paymentBaseUrl
     * @return static
     */
    public function setPaymentBaseUrl($paymentBaseUrl = '');

    /**
     * Get URL for purchase payment page
     *
     * @param PaymentInterface $payment Payment object
     *
     * @return string
     */
    public function getPurchasePaymentPageUrl(PaymentInterface $payment);

    /**
     * Callback handler
     *
     * @param string $data RAW string data from Gate
     *
     * @return CallbackInterface
     */
    public function handleCallback($data);
}
