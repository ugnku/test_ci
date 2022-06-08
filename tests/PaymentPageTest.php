<?php

namespace tci\tests;

use PHPUnit\Framework\TestCase;
use tci\exception\argument\InvalidStringException;
use tci\PaymentPage;
use tci\SignatureHandler;
use tci\Payment;

class PaymentPageTest extends TestCase
{
    private $handler;

    private $builder;

    public function setUp()
    {
        $this->handler = new SignatureHandler('secret');
        $this->builder = new PaymentPage($this->handler);
    }

    public function testSetBaseUrl()
    {
        $baseUrl = 'https://www.example.com';
        $payment = new Payment(100);

        $payment
            ->setPaymentId('test payment id')
            ->setPaymentDescription('B&W');

        $this->builder->setBaseUrl($baseUrl);

        $url = $this->builder->getUrl($payment);

        self::assertEquals(
            $baseUrl . '/payment/?project_id=100&interface_type=%7B%22id%22%3A23%7D'
            . '&payment_id=test+payment+id&payment_description=B%26W&signature=97JFQpAyJ4HPfGVedJh0M1MqQDOFt%2FM'
            . 'Cbdh8VrsT7DdRyTBDAF2mvUOsDANx1ZPfbvZg0%2BVUbF43xJnq0jEeLA%3D%3D',
            $url
        );
    }

    public function testGetUrl()
    {
        $payment = new Payment(100);

        $payment
            ->setPaymentId('test payment id')
            ->setPaymentDescription('B&W');

        $url = $this->builder->getUrl($payment);

        self::assertEquals(
            'https://paymentpage.ecommpay.com/payment/?project_id=100&interface_type=%7B%22id%22%3A23%7D'
            . '&payment_id=test+payment+id&payment_description=B%26W&signature=97JFQpAyJ4HPfGVedJh0M1MqQDOFt%2FM'
            . 'Cbdh8VrsT7DdRyTBDAF2mvUOsDANx1ZPfbvZg0%2BVUbF43xJnq0jEeLA%3D%3D',
            $url
        );
    }

    public function testConstructorException()
    {
        self::expectException(InvalidStringException::class);
        new PaymentPage($this->handler, 123);
    }

    public function testSetBaseUrlException()
    {
        self::expectException(InvalidStringException::class);
        $this->builder->setBaseUrl(123);
    }
}
