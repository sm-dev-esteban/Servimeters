<?php

namespace Controller;

use Model\LoginModel;

class Login extends LoginModel
{
    public function startSession(string $user, string $pass): array
    {
        return self::newSession(
            user: $user,
            pass: $pass
        );
    }
}
