<?php

namespace ecommpay\tests;

use ecommpay\Callback;
use ecommpay\exception\ProcessException;
use ecommpay\Gate;
use ecommpay\SignatureHandler;
use PHPUnit\Framework\TestCase;

class CallbackTest extends TestCase
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

    /**
     * @throws ProcessException
     */
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
        try {
            $signature = $this->callback->getSignature();
        } catch (ProcessException $e) {
            self::fail($e->getMessage());
        }

        self::assertNotEmpty($signature);
    }

    public function testGetPaymentStatus()
    {
        self::assertEquals('success', $this->callback->getPaymentStatus());
    }

    public function testGetCallbackException()
    {
        self::expectException('ecommpay\exception\ProcessException');
        new Callback('}', new SignatureHandler('secret'));
    }

    public function testGetData()
    {
        $data = json_decode($this->dataRaw);
        self::assertEquals($data, $this->callback->getData());

        try {
            $fromRaw = $this->callback->toArray($this->dataRaw);
        } catch (ProcessException $e) {
            self::fail($e->getMessage());
        }

        self::assertEquals($data, $fromRaw);
    }

    public function testToArrayException()
    {
        self::expectException('ecommpay\exception\ProcessException');
        $this->callback->toArray('}');
    }

    public function testGetSignatureException()
    {
        self::expectException('ecommpay\exception\ProcessException');
        $callback = $this->gate->handleCallback('{}');
        $callback->getSignature();
    }

    public function testGetNullValue()
    {
        self::assertNull($this->callback->getValue('test.test.test'));
    }
}
