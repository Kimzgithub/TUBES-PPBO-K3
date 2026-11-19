<?php

namespace App\Exceptions;

use RuntimeException;

class BusinessRuleException extends RuntimeException
{
    // Exception umum aturan bisnis (otomatis dirender menjadi JSON 422 di bootstrap/app.php)
}
