<?php
declare(strict_types=1);

namespace MoteCMS;

final class Markdown
{
    public function render(string $text): string
    {
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $lines = explode("\n", $text);
        $html = '';
        $paragraph = [];
        $list = false;
        $code = false;
        $codeLines = [];

        $flushParagraph = function () use (&$html, &$paragraph): void {
            if ($paragraph !== []) {
                $html .= '<p>' . $this->inline(implode(' ', $paragraph)) . "</p>\n";
                $paragraph = [];
            }
        };
        $closeList = function () use (&$html, &$list): void {
            if ($list) {
                $html .= "</ul>\n";
                $list = false;
            }
        };

        foreach ($lines as $line) {
            if (trim($line) === '```') {
                if ($code) {
                    $html .= '<pre><code>' . $this->esc(implode("\n", $codeLines)) . "</code></pre>\n";
                    $code = false;
                    $codeLines = [];
                } else {
                    $flushParagraph();
                    $closeList();
                    $code = true;
                }
                continue;
            }
            if ($code) {
                $codeLines[] = $line;
                continue;
            }
            $trim = trim($line);
            if ($trim === '') {
                $flushParagraph();
                $closeList();
                continue;
            }
            if (preg_match('/^(#{1,3})\s+(.+)$/', $trim, $m)) {
                $flushParagraph();
                $closeList();
                $level = strlen($m[1]);
                $html .= '<h' . $level . '>' . $this->inline($m[2]) . '</h' . $level . ">\n";
                continue;
            }
            if (preg_match('/^-\s+(.+)$/', $trim, $m)) {
                $flushParagraph();
                if (!$list) {
                    $html .= "<ul>\n";
                    $list = true;
                }
                $html .= '<li>' . $this->inline($m[1]) . "</li>\n";
                continue;
            }
            $paragraph[] = $trim;
        }
        if ($code) {
            $html .= '<pre><code>' . $this->esc(implode("\n", $codeLines)) . "</code></pre>\n";
        }
        $flushParagraph();
        $closeList();
        return $html;
    }

    private function inline(string $text): string
    {
        $safe = $this->esc($text);
        $safe = preg_replace_callback('/\[([^\]]+)\]\(([^)\s]+)\)/', function (array $m): string {
            $label = $m[1];
            $url = html_entity_decode($m[2], ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $scheme = strtolower((string)parse_url($url, PHP_URL_SCHEME));
            if (!in_array($scheme, ['http', 'https', 'mailto'], true)) {
                return $label;
            }
            return '<a href="' . $this->esc($url) . '">' . $label . '</a>';
        }, $safe) ?? $safe;
        $safe = preg_replace('/`([^`]+)`/', '<code>$1</code>', $safe) ?? $safe;
        $safe = preg_replace('/\*\*([^*]+)\*\*/', '<strong>$1</strong>', $safe) ?? $safe;
        return preg_replace('/(?<!\*)\*([^*]+)\*(?!\*)/', '<em>$1</em>', $safe) ?? $safe;
    }

    private function esc(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8');
    }
}
