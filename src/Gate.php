<?php

namespace tci;

use tci\exception\argument\InvalidBoolException;
use tci\exception\argument\InvalidStringException;
use tci\exception\ProcessException;
use tci\exception\ValidationException;
use tci\interfaces\CallbackInterface;
use tci\interfaces\GateInterface;
use tci\interfaces\PaymentInterface;
use tci\interfaces\SignatureHandlerInterface;
use tci\interfaces\UrlBuilderInterface;

/**
 * Gate
 */
class Gate implements GateInterface
{
    /**
     * Builder for Payment page
     *
     * @var UrlBuilderInterface $urlBuilder
     */
    private $urlBuilder;

    /**
     * Signature Handler (check, sign)
     *
     * @var SignatureHandlerInterface $signatureHandler
     */
    private $signatureHandler;

    /**
     * Flag validate payment params before generate PaymentPage URL.
     *
     * @var bool
     */
    private $validateParams = false;

    /**
     * Gate constructor.
     *
     * @param string $secret Secret key
     * @param string $baseUrl Base URL for concatenate with payment params
     * @throws InvalidStringException
     */
    public function __construct($secret, $baseUrl = '')
    {
        if (!is_string($secret)) {
            throw new InvalidStringException('secret', gettype($secret));
        }

        if (!is_string($baseUrl)) {
            throw new InvalidStringException('baseUrl', gettype($baseUrl));
        }

        $this->signatureHandler = new SignatureHandler($secret);
        $this->urlBuilder = new PaymentPage($this->signatureHandler, $baseUrl);
    }

    /**
     * Enable or disable validation payment params before generate PaymentPage URL.
     * @param bool $flag
     * @return void
     * @throws InvalidBoolException
     */
    public function setValidationParams($flag)
    {
        if (!is_bool($flag)) {
            throw new InvalidBoolException('flag', gettype($flag));
        }

        $this->validateParams = $flag;
    }

    public function getValidationParams()
    {
        return $this->validateParams;
    }

    /**
     * @param string $paymentBaseUrl
     * @return static
     * @throws InvalidStringException
     */
    public function setPaymentBaseUrl($paymentBaseUrl = '')
    {
        if (!is_string($paymentBaseUrl)) {
            throw new InvalidStringException('paymentBaseUrl', gettype($paymentBaseUrl));
        }

        $this->urlBuilder->setBaseUrl($paymentBaseUrl);

        return $this;
    }

    /**
     * Get URL for purchase payment page
     *
     * @param PaymentInterface $payment Payment object
     *
     * @return string
     * @throws ValidationException
     */
    public function getPurchasePaymentPageUrl(PaymentInterface $payment)
    {
        if ($this->validateParams) {
            $this->validateParams($payment);
        }

        return $this->urlBuilder->getUrl($payment);
    }

    /**
     * Callback handler
     *
     * @param string $data RAW string data from Gate
     *
     * @return CallbackInterface
     *
     * @throws ProcessException
     * @throws InvalidStringException
     */
    public function handleCallback($data)
    {
        if (!is_string($data)) {
            throw new InvalidStringException('data', gettype($data));
        }

        return Callback::fromRaw($data, $this->signatureHandler);
    }

    /**
     * @param PaymentInterface $payment
     * @return void
     * @throws ValidationException
     */
    private function validateParams(PaymentInterface $payment)
    {
        $url = $this->urlBuilder->getValidationUrl();
        $content = http_build_query($payment->getParams());
        $options = [
            'http' => [
                'header' => [
                    'Content-type: application/x-www-form-urlencoded',
                    'Content-Length: ' . strlen($content)
                ],
                'method'  => 'POST',
                'content' => $content,
                'ignore_errors' => true,
            ]
        ];

        $context  = stream_context_create($options);
        $result = file_get_contents($url, true, $context);

        if ($result === false) {
        }

        $data = json_decode($result, true);

        if (count($data['errors']) > 0) {
            throw new ValidationException($data['errors']);
        }
    }
}
