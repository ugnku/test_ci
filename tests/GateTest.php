<?php

namespace ecommpay\tests;

use ecommpay\Callback;
use ecommpay\exception\ProcessException;
use ecommpay\exception\ValidationException;
use ecommpay\Gate;
use ecommpay\Payment;
use PHPUnit\Framework\TestCase;

class GateTest extends TestCase
{
    /**
     * @var string
     */
    private $testUrl = 'http://test-url.test/test';

    /**
     * @var Gate
     */
    private $gate;

    protected function setUp()
    {
        $this->gate = new Gate('secret', $this->testUrl);
    }

    public function testGetPurchasePaymentPageUrl()
    {
        $payment = (new Payment(100))->setPaymentId('test payment id');

        try {
            $paymentUrl = $this->gate->getPurchasePaymentPageUrl($payment);
        } catch (ValidationException $e) {
            self::fail($e->getMessage());
        }

        self::assertNotEmpty($paymentUrl);
        self::assertStringStartsWith($this->testUrl, $paymentUrl);
    }

    public function testSetPaymentBaseUrl()
    {
        $someTestUrl = 'http://some-test-url.test/test';

        self::assertEquals(Gate::class, get_class($this->gate->setPaymentBaseUrl($someTestUrl)));

        try {
            $paymentUrl = $this->gate->getPurchasePaymentPageUrl(new Payment(100));
        } catch (ValidationException $e) {
            self::fail($e->getMessage());
        }

        self::assertStringStartsWith($someTestUrl, $paymentUrl);
    }

    public function testHandleCallback()
    {
        try {
            $callback = $this->gate->handleCallback(require __DIR__ . '/data/callback.php');
        } catch (ProcessException $e) {
            self::fail($e->getMessage());
        }

        self::assertInstanceOf(Callback::class, $callback);
    }
}
