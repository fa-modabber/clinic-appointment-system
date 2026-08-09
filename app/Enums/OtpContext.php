<?php

namespace App\Enums;

enum OtpContext: string
{
    case LOGIN = 'login';
    case PASSWORD_RESET = 'password-reset';
}
