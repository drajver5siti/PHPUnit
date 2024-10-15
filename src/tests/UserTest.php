<?php

use App\Mailer;
use App\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testFullName()
    {
        $user = new User();
        $user->name = "Ivan";
        $user->surname = "Pavlovski";

        $this->assertEquals("Ivan Pavlovski", $user->getFullName());
    }

    public function testMailer()
    {
        $user = new User();
        $user->email = "ivan.pavlovski@peopleweek.com";

        $message = "hello";

        $mailer = $this->createMock(Mailer::class);
        $mailer
            ->expects($this->once())
            ->method('sendMessage')
                ->with($this->equalTo($user->email), $this->equalTo($message))
                ->willReturn(true);

        $user->setMailer($mailer);
        $res = $user->notify($message);

        $this->assertTrue($res);
    }

    // Instead of mocking the exception, we use the original method which already throws the same exception
    public function testCannotNotifyWithoutEmail()
    {
        $user = new User();
        // $mailer = $this->createMock(Mailer::class);
        // $mailer->method('sendMessage')
        //     ->willThrowException(new Exception("EMPTY_EMAIL"));

        $mailer = $this->getMockBuilder(Mailer::class)
            ->onlyMethods([])
            ->getMock();

        $user->setMailer($mailer);

        $this->expectExceptionObject(new Exception("EMPTY_EMAIL"));
        $user->notify("Hello");
    }
}