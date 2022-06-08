<?php

namespace tci\tests;

use PHPUnit\Framework\TestCase;
use tci\Callback;
use tci\exception\argument\InvalidBoolException;
use tci\exception\argument\InvalidStringException;
use tci\exception\ValidationException;
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

    protected function setUp()
    {
        $this->gate = new Gate('secret', $this->testUrl);
    }

    public function testGetPurchasePaymentPageUrl()
    {
        $payment = (new Payment(100))->setPaymentId('test payment id');
        $paymentUrl = $this->gate->getPurchasePaymentPageUrl($payment);

        self::assertNotEmpty($paymentUrl);
        self::assertStringStartsWith($this->testUrl, $paymentUrl);
    }

    public function testSetPaymentBaseUrl()
    {
        $someTestUrl = 'http://some-test-url.test/test';

        self::assertEquals(Gate::class, get_class($this->gate->setPaymentBaseUrl($someTestUrl)));

        $paymentUrl = $this->gate->getPurchasePaymentPageUrl(new Payment(100));

        self::assertStringStartsWith($someTestUrl, $paymentUrl);
    }

    public function testHandleCallback()
    {
        $callback = $this->gate->handleCallback(require __DIR__ . '/data/callback.php');

        self::assertInstanceOf(Callback::class, $callback);
    }

    public function testSetValidateFlag()
    {
        self::assertEquals($this->gate->getValidationParams(), false);
        $this->gate->setValidationParams(true);
        self::assertEquals($this->gate->getValidationParams(), true);
    }

    public function testValidateParam()
    {
        $gate = new Gate('');
        $gate->setValidationParams(true);
        $payment = new Payment(8311, 'test_payment_sdk_php');
        $payment->setAccountToken(123);
        self::expectException(ValidationException::class);

        $gate->getPurchasePaymentPageUrl($payment);
    }

    public function testConstructorSecretException()
    {
        self::expectException(InvalidStringException::class);
        new Gate(123);
    }

    public function testConstructorBaseUrlException()
    {
        self::expectException(InvalidStringException::class);
        new Gate('secret', 123);
    }

    public function testSetBaseUrlException()
    {
        self::expectException(InvalidStringException::class);
        $this->gate->setPaymentBaseUrl(123);
    }

    public function testHandleCallbackException()
    {
        self::expectException(InvalidStringException::class);
        $this->gate->handleCallback(false);
    }

    public function testSetValidateFlagException()
    {
        self::expectException(InvalidBoolException::class);
        $this->gate->setValidationParams('test');
    }

//    public function testValidate()
//    {
//        $validator = $this->getMockBuilder(Gate::class)
//            ->setConstructorArgs(['secret'])
//            ->setMethods(['validateParams'])
//            ->getMock();
//
//        $validator->expects($this->once())
//            ->method('validateParams')
//            ->will($this->returnValue('{}'));
//    }
}
