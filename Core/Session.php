<?php

namespace Core;

class Session {
    const FLASH_KEY_NAME = "__flash_value";

    public static function set(string $key, mixed $value): void {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key): mixed {
        return $_SESSION[$key] ?? null;
    }

    /**
     * Delete all session data.
     */
    public static function flush(): void {
        $_SESSION = [];
    }

    public static function flash(string $key, mixed $value): void {
        $_SESSION[self::FLASH_KEY_NAME][$key] = $value;
    }

    public static function flashGet(string $key): mixed {
        if (isset($_SESSION[self::FLASH_KEY_NAME][$key])) {
            $value = $_SESSION[self::FLASH_KEY_NAME][$key];
            unset($_SESSION[self::FLASH_KEY_NAME][$key]); // Remove the flashed message to prevent it from being displayed again
            return $value;
        }

        return null;
    }

    /**
     * Delete all session data and destroy the session.
     */
    public static function destroy(): void {
        session_unset();
        session_destroy();
    }
}