<?php

namespace tci\tests;

use PHPUnit\Framework\TestCase;
use tci\Callback;
use tci\SignatureHandler;

class CallbackFormatTest extends TestCase
{
    /**
     * @var array
     */
    private $cases;

    protected function setUp(): void
    {
        $this->cases = require __DIR__ . '/data/callbackFormats.php';
    }

    public function testFormats(): void
    {
        foreach ($this->cases as $callbackData) {
            $callback = (new Callback($callbackData, new SignatureHandler('123')));

            $callback->getPayment();
            $callback->getPaymentId();
            $callback->getPaymentStatus();
        }
    }
}
