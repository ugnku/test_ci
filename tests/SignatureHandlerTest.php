<?php

namespace tci\tests;

use PHPUnit\Framework\TestCase;
use tci\SignatureHandler;

class SignatureHandlerTest extends TestCase
{
    /**
     * @var array
     */
    private array $data = [
        'customer' => [
            'project_id' => 0,
            'id' => 'sutm_id',
        ],
        'card' => [
            'pan' => '4242424242424242',
            'year' => 2020,
            'month' => 8,
            'card_holder' => 'John Smith',
            'cvv' => '123',
            'save' => true,
        ],
        'frame_mode' => 'popup',
    ];

    /**
     * @var string
     */
    private string $signature = 'lY0LTSAzpR7zGce5qfYGacOuYlHGWqkMcQlqmjlsDDZI2gVcE1qVeWANnkIR7mdOqRXJnL1kO0lUmkQ0YYLWRg==';

    /**
     * @var SignatureHandler
     */
    private SignatureHandler $handler;

    protected function setUp(): void
    {
        $this->handler = new SignatureHandler('secret');
    }

    public function testSign(): void
    {
        self::assertEquals(
            $this->signature,
            $this->handler->sign($this->data)
        );
    }

    public function testCheck(): void
    {
        self::assertTrue($this->handler->check($this->data, $this->signature));
    }
}
