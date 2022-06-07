<?php

namespace tci\exception;

use Exception;
use tci\interfaces\SdkException;

/**
 * Process exception in library
 */
class ProcessException extends Exception implements SdkException
{

    public function getFormattedMessage()
    {
        return $this->getMessage();
    }
}
