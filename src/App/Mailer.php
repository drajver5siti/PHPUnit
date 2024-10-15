<?php

namespace App;

use Exception;

class Mailer
{
    public function sendMessage(string $email, string $message)
    {
        if ($email === "") {
            throw new Exception("EMPTY_EMAIL");
        }

        sleep(3);

        echo sprintf("send %s to %s", $message, $email);

        return true;
    }
}