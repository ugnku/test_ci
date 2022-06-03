<?php

namespace ecommpay\exception;

use ecommpay\interfaces\SdkException;
use Exception;

/**
 * Exception on validation process in SDK.
 */
class ValidationException extends Exception implements SdkException
{
    const MESSAGE = 'One or more payment params is not valid';

    /**
     * List of fields with corrupted values.
     *
     * @var array
     */
    private $errors;

    /**
     * Exception constructor.
     *
     * @param array $errors List of fields with corrupted values.
     * @param Exception|null $previous [optional] Previous exception in process if exists.
     */
    public function __construct(array $errors, Exception $previous = null)
    {
        $this->errors = $errors;
        parent::__construct(self::MESSAGE, self::VALIDATION_ERROR, $previous);
    }

    /**
     * Returns list of corrupted fields.
     *
     * @return array
     */
    final public function getErrors()
    {
        return $this->errors;
    }
}
