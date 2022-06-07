<?php

namespace tci\exception;

use Exception;
use tci\interfaces\SdkException;

/**
 * Invalid argument exception in library
 */
class InvalidArgumentException extends Exception implements SdkException
{
    /**
     * String argument type.
     */
    const TYPE_INTEGER = 'integer';

    /**
     * String argument type.
     */
    const TYPE_STRING = 'string';

    /**
     * Boolean argument type.
     */
    const TYPE_BOOLEAN = 'boolean';

    /**
     * Exception message template.
     */
    const MESSAGE_FORMAT = 'Invalid type of argument "%s": expected a %s but received a %s';

    /**
     * Argument name.
     *
     * @var string
     */
    private $arg;

    /**
     * Expected argument type.
     *
     * @var string
     */
    private $expected;

    /**
     * Received argument type.
     *
     * @var string
     */
    private $received;

    /**
     * Exception constructor.
     *
     * @param string $arg Wrong argument name
     * @param string $expected Expected argument type
     * @param string $received Received argument type
     * @param ?Exception $previous Previous exception
     */
    public function __construct($arg, $expected, $received, Exception $previous = null)
    {
        $this->arg = $arg;
        $this->expected = $expected;
        $this->received = $received;

        parent::__construct($this->getFormattedMessage(), self::INVALID_ARGUMENT, $previous);
    }

    /**
     * Returns argument name.
     *
     * @return string
     */
    final public function getArg()
    {
        return $this->arg;
    }

    /**
     * Returns expected argument type.
     *
     * @return string
     */
    final public function getExpected()
    {
        return $this->expected;
    }

    /**
     * Returns received argument type.
     *
     * @return string
     */
    final public function getReceived()
    {
        return $this->received;
    }

    /**
     * Returns exception formatted message.
     *
     * @return string
     */
    final public function getFormattedMessage()
    {
        return sprintf(self::MESSAGE_FORMAT, $this->getArg(), $this->getExpected(), $this->getReceived());
    }
}
