<?php
declare(strict_types=1);

namespace MoteCMS;

final class Config
{
    /** @param array<string,mixed> $values */
    public function __construct(private array $values) {}

    public static function load(): self
    {
        $root = dirname(__DIR__);
        $defaults = [
            'site_name' => 'MoteCMS',
            'default_page' => 'home',
            'content_dir' => $root . '/content/pages',
            'session_name' => 'motecms_session',
            'base_path' => '',
        ];
        $file = $root . '/config/config.php';
        if (is_file($file)) {
            $custom = require $file;
            if (is_array($custom)) {
                $defaults = array_merge($defaults, $custom);
            }
        }
        return new self($defaults);
    }

    public function getString(string $key): string
    {
        return (string)($this->values[$key] ?? '');
    }
}
