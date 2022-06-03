<?php

namespace ecommpay;

use ecommpay\interfaces\PaymentInterface;
use ecommpay\interfaces\PaymentPageInterface;
use ecommpay\interfaces\SignatureHandlerInterface;

/**
 * Payment page URL Builder
 */
class PaymentPage implements PaymentPageInterface
{
    /**
     * Base URL for payment
     *
     * @var string
     */
    private $baseUrl = 'https://paymentpage.ecommpay.com';

    /**
     * Base URL for payment
     *
     * @var string
     */
    private $apiUrl = 'https://sdk.ecommpay.com';

    /**
     * Signature Handler
     *
     * @var SignatureHandlerInterface $signatureHandler
     */
    private $signatureHandler;

    /**
     * @param SignatureHandlerInterface $signatureHandler
     * @param string $baseUrl
     */
    public function __construct(SignatureHandlerInterface $signatureHandler, $baseUrl = '')
    {
        $this->signatureHandler = $signatureHandler;

        $this->setBaseUrl($baseUrl);
    }

    /**
     * @inheritDoc
     */
    public function setBaseUrl($baseUrl)
    {
        if ($baseUrl) {
            $this->baseUrl = $baseUrl;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getUrl(PaymentInterface $payment)
    {
        return sprintf(
            self::PAYMENT_URL_PATTERN,
            $this->baseUrl,
            http_build_query($payment->getParams()),
            urlencode($this->signatureHandler->sign($payment->getParams()))
        );
    }

    /**
     * @inheritDoc
     */
    public function getValidationUrl(PaymentInterface $payment)
    {
        return sprintf(
            self::VALIDATOR_URL_PATTERN,
            $this->apiUrl,
            http_build_query($payment->getParams())
        );
    }
}
