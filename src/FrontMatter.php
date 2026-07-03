<?php
declare(strict_types=1);

namespace MoteCMS;

final class FrontMatter
{
    /** @return array{meta:array<string,string>,content:string} */
    public static function parse(string $text): array
    {
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        if (!str_starts_with($text, "---\n")) {
            return ['meta' => [], 'content' => $text];
        }
        $end = strpos($text, "\n---\n", 4);
        if ($end === false) {
            return ['meta' => [], 'content' => $text];
        }
        $block = substr($text, 4, $end - 4);
        $content = substr($text, $end + 5);
        $meta = [];
        foreach (explode("\n", $block) as $line) {
            if (trim($line) === '' || !str_contains($line, ':')) {
                continue;
            }
            [$key, $value] = explode(':', $line, 2);
            $meta[trim($key)] = trim($value);
        }
        return ['meta' => $meta, 'content' => $content];
    }

    /** @param array<string,string|int> $meta */
    public static function serialize(array $meta, string $content): string
    {
        $order = ['title', 'slug', 'status', 'menu', 'order'];
        $out = "---\n";
        foreach ($order as $key) {
            $value = (string)($meta[$key] ?? '');
            $out .= $key . ': ' . str_replace(["\r", "\n"], ' ', $value) . "\n";
        }
        return $out . "---\n\n" . ltrim(str_replace(["\r\n", "\r"], "\n", $content));
    }
}
