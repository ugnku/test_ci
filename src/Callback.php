<?php

namespace tci;

use tci\exception\argument\InvalidStringException;
use tci\exception\ProcessException;
use tci\interfaces\SdkException;

use tci\interfaces\CallbackInterface;
use tci\interfaces\SignatureHandlerInterface;
use function is_array;

/**
 * Callback wrapper
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
     * @param array $data Raw data from gate
     * @param SignatureHandlerInterface $signatureHandler
     * @throws ProcessException
     */
    public function __construct($data, SignatureHandlerInterface $signatureHandler)
    {
        $this->data = $data;
        $this->signatureHandler = $signatureHandler;

        if (!$this->checkSignature()) {
            throw new ProcessException(
                sprintf('Signature %s is invalid', $this->getSignature()),
                SdkException::INVALID_SIGNATURE
            );
        }
    }

    /**
     * @param $rawData
     * @param SignatureHandlerInterface $signatureHandler
     * @return CallbackInterface
     * @throws InvalidStringException
     * @throws ProcessException
     */
    public static function fromRaw($rawData, SignatureHandlerInterface $signatureHandler)
    {
        if (!is_string($rawData)) {
            throw new InvalidStringException('rawData', gettype($rawData));
        }

        $data = json_decode($rawData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ProcessException(
                'Error on response decoding: ' . json_last_error_msg(),
                SdkException::DECODING_ERROR
            );
        }

        return new static($data, $signatureHandler);
    }

    /**
     * Returns already parsed gate data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Get payment info
     *
     * @return ?array
     */
    public function getPayment()
    {
        try {
            return $this->getValue('payment');
        } catch (InvalidStringException $e) {
            return null;
        }
    }

    /**
     * Get payment status
     *
     * @return ?string
     */
    public function getPaymentStatus()
    {
        try {
            return $this->getValue('payment.status');
        } catch (InvalidStringException $e) {
            return null;
        }
    }

    /**
     * Get payment ID
     *
     * @return ?string
     */
    public function getPaymentId()
    {
        try {
            return $this->getValue('payment.id');
        } catch (InvalidStringException $e) {
            return null;
        }
    }

    /**
     * Get signature
     *
     * @return string
     * @throws ProcessException
     */
    public function getSignature()
    {
        try {
            $signature = $this->getValue('signature')
                ? $this->getValue('signature')
                : $this->getValue('general.signature');
        } catch (InvalidStringException $e) {
            throw new ProcessException('Undefined signature', SdkException::UNDEFINED_SIGNATURE, $e);
        }

        if (!$signature) {
            throw new ProcessException('Undefined signature', SdkException::UNDEFINED_SIGNATURE);
        }

        return $signature;
    }

    /**
     * Returns the converted raw data as an array
     *
     * @param string $rawData
     *
     * @return array
     *
     * @throws ProcessException
     * @throws InvalidStringException
     */
    public function toArray($rawData)
    {
        if (!is_string($rawData)) {
            throw new InvalidStringException('rawData', gettype($rawData));
        }

        $data = json_decode($rawData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ProcessException(
                'Error on response decoding: ' . json_last_error_msg(),
                SdkException::DECODING_ERROR
            );
        }

        return $data;
    }

    /**
     * Get value by name path
     *
     * @param string $namePath
     *
     * @return mixed
     * @throws InvalidStringException
     */
    public function getValue($namePath)
    {
        if (!is_string($namePath)) {
            throw new InvalidStringException('namePath', gettype($namePath));
        }

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
     * Returns the result of request signature verification.
     *
     * @return bool True if valid or false otherwise.
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
     * Unset parameter at callback data.
     *
     * @param string $name Parameter name
     * @param array $data Callback temporary data
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
     * Reads input data from gate.
     *
     * @return string
     */
    public static function readData()
    {
        return file_get_contents('php://input') ?: '{}';
    }
}
