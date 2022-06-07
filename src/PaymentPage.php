<?php

namespace tci;

use tci\exception\argument\InvalidStringException;
use tci\interfaces\PaymentInterface;
use tci\interfaces\SignatureHandlerInterface;
use tci\interfaces\UrlBuilderInterface;

/**
 * Payment page URL Builder
 */
class PaymentPage implements UrlBuilderInterface
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
     * @throws InvalidStringException
     */
    public function __construct(SignatureHandlerInterface $signatureHandler, $baseUrl = '')
    {
        if (!is_string($baseUrl)) {
            throw new InvalidStringException('baseUrl', gettype($baseUrl));
        }

        $this->signatureHandler = $signatureHandler;

        $this->setBaseUrl($baseUrl);
    }

    /**
     * @inheritDoc
     * @throws InvalidStringException
     */
    public function setBaseUrl($baseUrl)
    {
        if (!is_string($baseUrl)) {
            throw new InvalidStringException('baseUrl', gettype($baseUrl));
        }

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
