<?php

namespace tci\exception\argument;

use Exception;
use tci\exception\InvalidArgumentException;

/**
 * Invalid string argument exception.
 */
class InvalidStringException extends InvalidArgumentException
{

    /**
     * Invalid string argument exception constructor.
     *
     * @param string $arg Argument name
     * @param string $received Received argument type
     * @param ?Exception $previous [optional] Previous exception
     */
    public function __construct($arg, $received, Exception $previous = null)
    {
        parent::__construct($arg, self::TYPE_STRING, $received, $previous);
    }
}
