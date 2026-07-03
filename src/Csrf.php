<?php
declare(strict_types=1);

namespace MoteCMS;

final class Csrf
{
    public static function token(): string
    {
        if (empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        }
        return (string)$_SESSION['csrf'];
    }
    public static function valid(string $token): bool
    {
        return isset($_SESSION['csrf']) && hash_equals((string)$_SESSION['csrf'], $token);
    }
}
