<?php

namespace ecommpay;

use ecommpay\exception\ProcessException;
use ecommpay\exception\ValidationException;
use ecommpay\interfaces\GateInterface;
use ecommpay\interfaces\PaymentInterface;
use ecommpay\interfaces\PaymentPageInterface;
use ecommpay\interfaces\SignatureHandlerInterface;

/**
 * Gate
 */
class Gate implements GateInterface
{
    /**
     * Builder for Payment page
     *
     * @var PaymentPageInterface $urlBuilder
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
     */
    public function __construct($secret, $baseUrl = '')
    {
        $this->signatureHandler = new SignatureHandler($secret);
        $this->urlBuilder = new PaymentPage($this->signatureHandler, $baseUrl);
    }

    /**
     * Enable or disable validation payment params before generate PaymentPage URL.
     * @param bool $flag
     * @return void
     */
    public function setValidationParams($flag)
    {
        $this->validateParams = $flag;
    }

    /**
     * @inheritDoc
     */
    public function setPaymentBaseUrl($paymentBaseUrl = '')
    {
        $this->urlBuilder->setBaseUrl($paymentBaseUrl);

        return $this;
    }

    /**
     * @inheritDoc
     *
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
     * @inheritDoc
     *
     * @throws ProcessException
     */
    public function handleCallback($data)
    {
        return new Callback($data, $this->signatureHandler);
    }

    /**
     * @param Payment $payment
     * @return void
     * @throws ValidationException
     */
    private function validateParams(Payment $payment)
    {
        $requestUri = $this->urlBuilder->getValidationUrl($payment);
        $stream = fopen($requestUri, 'r');
        $errors = [];
        $status = 0;

        // Reverse required!!!
        $headers = array_reverse(stream_get_meta_data($stream)['wrapper_data']);

        foreach ($headers as $header) {
            if (preg_match('/^HTTP\/\d.\d (\d+) /', $header, $match)) {
                $status = (int) $match[1];
                break;
            }
        }

        if ($status !== 200) {
            $data = json_decode(stream_get_contents($stream));
            $errors = $data['errors'];
        }

        fclose($stream);

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }
    }
}
