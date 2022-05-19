<?php

namespace tci\tests;

use tci\exception\ProcessException;
use tci\Gate;

class CallbackTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var string
     */
    private $dataRaw;

    /**
     * @var Gate
     */
    private $gate;

    /**
     * @var Callback
     */
    private $callback;

    protected function setUp(): void
    {
        $this->dataRaw = require __DIR__ . '/data/callback.php';
        $this->gate = new Gate('secret');
        $this->callback =
            $this->gate
                ->handleCallback($this->dataRaw);
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

    public function testGetCallbackException(): void
    {
        self::expectException(ProcessException::class);
        $this->gate->handleCallback('}');
    }

    public function testGetData(): void
    {
        $data = json_decode($this->dataRaw);
        self::assertEqualsCanonicalizing($data, $this->callback->getData());
        self::assertEqualsCanonicalizing($data, $this->callback->toArray($this->dataRaw));
    }

    public function testToArrayException(): void
    {
        self::expectException(ProcessException::class);
        $this->callback->toArray('}');
    }

    public function testGetSignatureException(): void
    {
        self::expectException(ProcessException::class);
        $callback = $this->gate->handleCallback('{}');
        $callback->getSignature();
    }

    public function testGetNullValue(): void
    {
        self::assertNull($this->callback->getValue('test.test.test'));
    }
}
