<?php

namespace tci\tests;

use PHPUnit\Framework\TestCase;
use tci\Callback;
use tci\Gate;
use tci\Payment;

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

    protected function setUp(): void
    {
        $this->gate = new Gate('secret', $this->testUrl);
    }

    public function testGetPurchasePaymentPageUrl(): void
    {
        $payment = (new Payment(100))->setPaymentId('test payment id');
        $paymentUrl = $this->gate->getPurchasePaymentPageUrl($payment);

        self::assertNotEmpty($paymentUrl);
        self::assertStringStartsWith($this->testUrl, $paymentUrl);
    }

    public function testSetPaymentBaseUrl(): void
    {
        $someTestUrl = 'http://some-test-url.test/test';

        self::assertEquals(Gate::class, get_class($this->gate->setPaymentBaseUrl($someTestUrl)));

        $paymentUrl = $this->gate->getPurchasePaymentPageUrl(new Payment(100));

        self::assertStringStartsWith($someTestUrl, $paymentUrl);
    }

    public function testHandleCallback(): void
    {
        $callback = $this->gate->handleCallback(require __DIR__ . '/data/callback.php');

        self::assertInstanceOf(Callback::class, $callback);
    }
}
