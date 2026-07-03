<?php
declare(strict_types=1);

namespace MoteCMS;

spl_autoload_register(function (string $class): void {
    $prefix = 'MoteCMS\\';
    if (str_starts_with($class, $prefix)) {
        require __DIR__ . '/' . substr($class, strlen($prefix)) . '.php';
    }
});

function e(string $text): string
{
    return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8');
}

function security_headers(bool $admin = false): void
{
    header("Content-Security-Policy: default-src 'none'; style-src 'self'; form-action 'self'; base-uri 'none'; frame-ancestors 'none'" . ($admin ? '' : '; img-src \'none\''));
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: no-referrer');
    header('X-Frame-Options: DENY');
}
