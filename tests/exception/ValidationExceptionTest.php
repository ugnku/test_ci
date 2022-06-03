<?php

namespace ecommpay\tests\exception;

use PHPUnit\Framework\TestCase;
use ecommpay\exception\ValidationException;

class ValidationExceptionTest extends TestCase
{

    public function testGetErrors()
    {
        $data = [
            'payment.id' => 'Payment identifier required.',
            'custom_error' => 'Some other error.'
        ];

        $exception = new ValidationException($data);

        self::assertEquals(ValidationException::MESSAGE, $exception->getMessage());
        self::assertEquals($data, $exception->getErrors());
    }
}
