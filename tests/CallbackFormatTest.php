<?php

namespace ecommpay\tests;

use ecommpay\Callback;
use ecommpay\exception\ProcessException;
use ecommpay\SignatureHandler;
use PHPUnit\Framework\TestCase;

class CallbackFormatTest extends TestCase
{
    /**
     * @var array
     */
    private $cases;

    protected function setUp()
    {
        $this->cases = require __DIR__ . '/data/callbackFormats.php';
    }

    public function testFormats()
    {
        foreach ($this->cases as $callbackData) {
            try {
                $callback = new Callback($callbackData, new SignatureHandler('123'));
            } catch (ProcessException $e) {
                self::fail($e->getMessage());
            }

            $callback->getPayment();
            $callback->getPaymentId();
            $callback->getPaymentStatus();
        }
    }
}
