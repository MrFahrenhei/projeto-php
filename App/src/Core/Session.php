<?php

namespace App\Core;

class Session
{
    public function __construct()
    {
        session_start();
        session_regenerate_id();
    }
    public static function set(string $index, mixed $value): void
    {
        $_SESSION[$index] = $value;
    }
    public static function has(string $index): bool
    {
        return isset($_SESSION[$index]);
    }

    public static function get(string $index): null|string
    {
        if(self::has($index)){
            return $_SESSION[$index];
        }
        return null;
    }
    public static function remove(string $index): void
    {
        if(self::has($index)){
            unset($_SESSION[$index]);
        }
    }

    public static function remove_all(): void
    {
        session_destroy();
    }
    public static function flash(string $index, mixed $value): void
    {
        $_SESSION['__flash'][$index] = $value;
    }
    public static function remove_flash(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && self::has('__flash')) {
            unset($_SESSION['__flash']);
        }
    }
}