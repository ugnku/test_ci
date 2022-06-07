<?php

namespace tci;

use tci\exception\argument\InvalidStringException;
use tci\interfaces\SignatureHandlerInterface;
use function in_array;

/**
 * SignatureHandler
 *
 * @see https://developers.ecommpay.com/en/en_PP_Authentication.html
 */
class SignatureHandler implements SignatureHandlerInterface
{
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
     * @throws InvalidStringException
     */
    public function __construct($secretKey)
    {
        if (!is_string($secretKey)) {
            throw new InvalidStringException('secretKey', gettype($secretKey));
        }

        $this->secretKey = $secretKey;
    }

    /**
     * Check signature
     *
     * @param array $params
     * @param string $signature
     * @return boolean
     * @throws InvalidStringException
     */
    public function check(array $params, $signature)
    {
        $arg = 'signature';
        if (!is_string($$arg)) {
            throw new InvalidStringException($arg, gettype($$arg));
        }

        return $this->sign($params) === $$arg;
    }

    /**
     * Return signature
     *
     * @param array $params
     * @return string
     */
    public function sign(array $params)
    {
        $stringToSign = implode(self::ITEMS_DELIMITER, $this->getParamsToSign($params, self::IGNORED_KEYS));
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

            switch (true) {
                case is_array($value):
                    $subArray = $this->getParamsToSign($value, $ignoreParamKeys, $paramKey, false);
                    $paramsToSign = array_merge($paramsToSign, $subArray);
                    break;

                case is_bool($value):
                    $paramsToSign[$paramKey] = $paramKey . ':' . ($value ? '1' : '0');
                    break;

                default:
                    $paramsToSign[$paramKey] = $paramKey . ':' . $value;
            }
        }

        if ($sort) {
            ksort($paramsToSign, SORT_NATURAL);
        }

        return $paramsToSign;
    }
}
