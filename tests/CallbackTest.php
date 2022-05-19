<?php

namespace ecommpay\tests;

use ecommpay\exception\ProcessException;
use ecommpay\Gate;

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

    protected function setUp()
    {
        $this->dataRaw = require __DIR__ . '/data/callback.php';
        $this->gate = new Gate('secret');
        $this->callback =
            $this->gate
                ->handleCallback($this->dataRaw);
    }

    public function testGetPaymentId()
    {
        self::assertEquals('000049', $this->callback->getPaymentId());
    }

    public function testGetPayment()
    {
        self::assertArrayHasKey('id', $this->callback->getPayment());
        self::assertArrayHasKey('status', $this->callback->getPayment());
    }

    public function testGetSignature()
    {
        self::assertNotEmpty($this->callback->getSignature());
    }

    public function testGetPaymentStatus()
    {
        self::assertEquals('success', $this->callback->getPaymentStatus());
    }

    public function testGetCallbackException()
    {
        self::expectException(ProcessException::class);
        $this->gate->handleCallback('}');
    }

    public function testGetData()
    {
        $data = json_decode($this->dataRaw);
        self::assertEqualsCanonicalizing($data, $this->callback->getData());
        self::assertEqualsCanonicalizing($data, $this->callback->toArray($this->dataRaw));
    }

    public function testToArrayException()
    {
        self::expectException(ProcessException::class);
        $this->callback->toArray('}');
    }

    public function testGetSignatureException()
    {
        self::expectException(ProcessException::class);
        $callback = $this->gate->handleCallback('{}');
        $callback->getSignature();
    }

    public function testGetNullValue()
    {
        self::assertNull($this->callback->getValue('test.test.test'));
    }
}
