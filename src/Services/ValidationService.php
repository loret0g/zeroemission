<?php
namespace App\Services;

class ValidationService
{
    public static function validateRequired(string $value): bool {
        return trim($value) !== '';
    }

    public static function validateEmail(string $email): bool {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function validatePassword(string $password, int $minLength = 8): bool {
        return strlen($password) >= $minLength;
    }

    public static function validateMatch(string $value1, string $value2): bool {
        return $value1 === $value2;
    }
}