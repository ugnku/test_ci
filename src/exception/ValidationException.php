<?php

namespace tci\exception;

use Exception;
use tci\interfaces\SdkException;

/**
 * Process exception in library
 */
class ValidationException extends Exception implements SdkException
{
    const MESSAGE = 'One or more payment params is not valid';

    /**
     * @var array
     */
    private $errors;

    /**
     * Validation exception constructor.
     *
     * @param array $errors
     * @param ?Exception $previous
     */
    public function __construct(array $errors, Exception $previous = null)
    {
        $this->errors = $errors;
        parent::__construct($this->getFormattedMessage(), self::VALIDATION_ERROR, $previous);
    }

    public function getFormattedMessage()
    {
        return self::MESSAGE;
    }

    /**
     * Return validation errors.
     *
     * @return array
     */
    final public function getErrors()
    {
        return $this->errors;
    }
}
