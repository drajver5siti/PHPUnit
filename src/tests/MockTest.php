<?php

use App\Mailer;
use PHPUnit\Framework\TestCase;

class MockTest extends TestCase
{

    public function testMock()
    {
        // $mailer = new Mailer();

        // $result = $mailer->sendMessage("test", "hello_world");

        $mock = $this->createMock(Mailer::class);
        $mock->method('sendMessage')->willReturn(true);

        $result = $mock->sendMessage("test", "hello_world");
        $this->assertTrue($result);
    }
}