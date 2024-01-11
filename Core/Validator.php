<?php

namespace Core;

class Validator {
    public static function validateString(string $string, int $min = 1, mixed $max = INF): bool {
        $len = strlen($string);
        return $len < $min || $len > $max ? false : true;
    }

    public static function validateEmail(string $email): mixed {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}