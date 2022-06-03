<?php

namespace ecommpay\interfaces;

/**
 * Interface for all SDK exception.
 */
interface SdkException
{
    const
        INVALID_SIGNATURE = 0xa0000001,
        UNDEFINED_SIGNATURE = 0xa0000002,
        DECODING_ERROR = 0xa0000003,
        VALIDATION_ERROR = 0xa0000010;
}
