<?php

namespace ecommpay;

use ecommpay\exception\ProcessException;
use ecommpay\interfaces\CallbackInterface;
use ecommpay\interfaces\SdkException;
use ecommpay\interfaces\SignatureHandlerInterface;

/**
 * Callback
 */
class Callback implements CallbackInterface
{
    /**
     * Callback data as array
     *
     * @var array
     */
    private $data;

    /**
     * Signature Handler
     *
     * @var SignatureHandlerInterface
     */
    private $signatureHandler;

    /**
     * @param string|array $data RAW or already processed data from gate
     * @param SignatureHandlerInterface $signatureHandler
     * @throws ProcessException
     */
    public function __construct($data, SignatureHandlerInterface $signatureHandler)
    {
        $this->data = is_array($data) ? $data : $this->toArray($data);
        $this->signatureHandler = $signatureHandler;

        if (!$this->checkSignature()) {
            throw new ProcessException(
                sprintf('Signature %s is invalid', $this->getSignature()),
                SdkException::INVALID_SIGNATURE
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function getPayment()
    {
        return $this->getValue('payment');
    }

    /**
     * @inheritDoc
     */
    public function getPaymentStatus()
    {
        return $this->getValue('payment.status');
    }

    /**
     * @inheritDoc
     */
    public function getPaymentId()
    {
        return $this->getValue('payment.id');
    }

    /**
     * @inheritDoc
     *
     * @throws ProcessException
     */
    public function getSignature()
    {
        $signature = $this->getValue('signature')
            ? $this->getValue('signature')
            : $this->getValue('general.signature');

        if (!$signature) {
            throw new ProcessException('Undefined signature', SdkException::UNDEFINED_SIGNATURE);
        }

        return $signature;
    }

    /**
     * @inheritDoc
     *
     * @throws ProcessException
     */
    public function toArray($rawData)
    {
        $data = json_decode($rawData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ProcessException('Error on response decoding', SdkException::DECODING_ERROR);
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function getValue($namePath)
    {
        $keys = explode('.', $namePath);
        $callbackData = $this->data;

        foreach ($keys as $key) {
            $value = isset($callbackData[$key]) ? $callbackData[$key] : null;

            if ($value === null) {
                return null;
            }

            $callbackData = $value;
        }

        return $callbackData;
    }

    /**
     * @inheritDoc
     *
     * @throws ProcessException
     */
    public function checkSignature()
    {
        $data = $this->data;
        $signature = $this->getSignature();
        $this->removeParam('signature', $data);
        return $this->signatureHandler->check($data, $signature);
    }

    /**
     * Unset param at callback data
     *
     * @param string $name param name
     * @param array $data tmp data
     */
    private function removeParam($name, array &$data)
    {
        if (isset($data[$name])) {
            unset($data[$name]);
        }

        foreach ($data as &$val) {
            if (is_array($val)) {
                $this->removeParam($name, $val);
            }
        }
    }

    /**
     * Reads input data from gate
     * @return string
     */
    public static function readData()
    {
        return file_get_contents('php://input') ?: '{}';
    }
}
