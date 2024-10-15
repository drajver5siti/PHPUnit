<?php

namespace App;

class User
{
    public string $name;
    public string $surname;
    public string $email = "";

    protected Mailer $mailer;

    public function __construct(
    ) {}

    public function getFullName(): string
    {
        return $this->name . " " . $this->surname;
    }
    
    public function setMailer(Mailer $m)
    {
        $this->mailer = $m;
    }

    public function notify(string $message): bool
    {
        return $this->mailer->sendMessage($this->email, $message);
    }
}