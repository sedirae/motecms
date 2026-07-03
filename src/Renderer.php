<?php
declare(strict_types=1);

namespace MoteCMS;

final class Renderer
{
    /** @param array<string,mixed> $vars */
    public function render(string $template, array $vars): string
    {
        extract($vars, EXTR_SKIP);
        ob_start();
        require dirname(__DIR__) . '/templates/' . $template . '.php';
        return (string)ob_get_clean();
    }
}
