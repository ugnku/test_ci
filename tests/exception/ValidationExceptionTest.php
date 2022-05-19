<?php

namespace tci\tests\exception;

use PHPUnit\Framework\TestCase;
use tci\Callback;
use tci\exception\ValidationException;
use tci\Gate;
use tci\Payment;

class ValidationExceptionTest extends TestCase
{
    public function testGetErrors(): void
    {
        $data = [
            'payment.id' => 'Payment identifier required.',
            'custom_error' => 'Some other error.'
        ];

        $exception = new ValidationException($data);

        self::assertEquals(ValidationException::MESSAGE, $exception->getMessage());
        self::assertEqualsCanonicalizing($data, $exception->getErrors());
    }
}
