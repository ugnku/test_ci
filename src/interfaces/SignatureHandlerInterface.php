<?php

namespace tci\interfaces;

/**
 * SignatureHandler
 *
 * @see https://developers.ecommpay.com/en/en_PP_Authentication.html
 */
interface SignatureHandlerInterface
{
    const ITEMS_DELIMITER = ';';
    const ALGORITHM = 'sha512';
    const IGNORED_KEYS = ['frame_mode'];

    /**
     * Check signature
     *
     * @param array $params
     * @param string $signature
     * @return boolean
     */
    public function check(array $params, $signature);

    /**
     * Return signature
     *
     * @param array $params
     * @return string
     */
    public function sign(array $params);
}
