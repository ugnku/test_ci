<?php

namespace tci\tests;

use PHPUnit\Framework\TestCase;
use tci\Gate;

class CallbackTest extends TestCase
{
    /**
     * @var Gate
     */
    private Gate $gate;

    /**
     * @var Callback
     */
    private $callback;

    protected function setUp(): void
    {
        $this->gate = new Gate('secret');
        $this->callback =
            $this->gate
                ->handleCallback(require __DIR__ . '/data/callback.php');
    }

    public function testGetPaymentId(): void
    {
        self::assertEquals('000049', $this->callback->getPaymentId());
    }

    public function testGetPayment(): void
    {
        self::assertArrayHasKey('id', $this->callback->getPayment());
        self::assertArrayHasKey('status', $this->callback->getPayment());
    }

    public function testGetSignature(): void
    {
        self::assertNotEmpty($this->callback->getSignature());
    }

    public function testGetPaymentStatus(): void
    {
        self::assertEquals('success', $this->callback->getPaymentStatus());
    }
}
