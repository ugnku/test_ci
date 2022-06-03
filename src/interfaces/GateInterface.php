<?php
/** @noinspection PhpUnused */

namespace ecommpay\interfaces;

/**
 * URL Builder interface for purchase payment page.
 */
interface GateInterface
{
    /**
     * Currency Russian Ruble.
     */
    const CURRENCY_RUB = 'RUB';

    /**
     * Currency US dollar.
     */
    const CURRENCY_USD = 'USD';

    /**
     * Currency Euro.
     */
    const CURRENCY_EUR = 'EUR';

    /**
     * Enable or disable validation payment params before generate PaymentPage URL.
     *
     * @param bool $flag
     *
     * @return void
     */
    public function setValidationParams($flag);

    /**
     * Set URL for purchase payment page.
     *
     * @param string $paymentBaseUrl
     *
     * @return GateInterface
     */
    public function setPaymentBaseUrl($paymentBaseUrl = '');

    /**
     * Returns URL for purchase payment page.
     *
     * @param PaymentInterface $payment Payment object
     *
     * @return string
     */
    public function getPurchasePaymentPageUrl(PaymentInterface $payment);

    /**
     * Returns callback wrapper.
     *
     * @param string $data RAW string data from Gate
     *
     * @return CallbackInterface
     */
    public function handleCallback($data);
}
