<?php

namespace tci\interfaces;

/**
 * Interface for all SDK exception.
 */
interface SdkException
{
    const
        INVALID_SIGNATURE = 0xa0000001,
        UNDEFINED_SIGNATURE = 0xa0000002,
        DECODING_ERROR = 0xa0000003,
        INVALID_ARGUMENT = 0xa0000004,
        VALIDATION_ERROR = 0xa0000010;

    /**
     * @return string
     */
    public function getFormattedMessage();
}
