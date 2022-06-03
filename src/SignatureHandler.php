<?php

namespace ecommpay;

use ecommpay\interfaces\SignatureHandlerInterface;

/**
 * SignatureHandler
 *
 * @see https://developers.ecommpay.com/en/en_PP_Authentication.html
 */
class SignatureHandler implements SignatureHandlerInterface
{
    private $ignoreKeys = array(self::FIELD_FRAME_MODE);

    /**
     * Secret key
     *
     * @var string
     */
    private $secretKey;

    /**
     * __construct
     *
     * @param string $secretKey
     */
    public function __construct($secretKey)
    {
        $this->secretKey = $secretKey;
    }

    /**
     * @inheritDoc
     */
    public function check(array $params, $signature)
    {
        return $this->sign($params) === $signature;
    }

    /**
     * @inheritDoc
     */
    public function sign(array $params)
    {
        $stringToSign = implode(
            self::ITEMS_DELIMITER,
            $this->getParamsToSign($params, $this->ignoreKeys)
        );

        return base64_encode(hash_hmac(self::ALGORITHM, $stringToSign, $this->secretKey, true));
    }

    /**
     * Get parameters to sign
     *
     * @param array $params
     * @param array $ignoreParamKeys
     * @param string $prefix
     * @param bool $sort
     * @return array
     */
    private function getParamsToSign(array $params, array $ignoreParamKeys = [], $prefix = '', $sort = true)
    {
        $paramsToSign = [];

        foreach ($params as $key => $value) {
            if (in_array($key, $ignoreParamKeys, true)) {
                continue;
            }

            $paramKey = ($prefix ? $prefix . ':' : '') . $key;

            if (is_array($value)) {
                $subArray = $this->getParamsToSign($value, $ignoreParamKeys, $paramKey, false);
                $paramsToSign = array_merge($paramsToSign, $subArray);
                continue;
            }

            if (is_bool($value)) {
                $value = $value ? '1' : '0';
            } else {
                $value = (string)$value;
            }

            $paramsToSign[$paramKey] = $paramKey . ':' . $value;
        }

        if ($sort) {
            ksort($paramsToSign, SORT_NATURAL);
        }

        return $paramsToSign;
    }
}
