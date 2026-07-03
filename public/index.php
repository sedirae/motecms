<?php
declare(strict_types=1);

require dirname(__DIR__) . '/src/bootstrap.php';

use MoteCMS\Config;
use MoteCMS\Markdown;
use MoteCMS\PageRepository;
use MoteCMS\Renderer;
use function MoteCMS\e;
use function MoteCMS\security_headers;

$config = Config::load();
$repo = new PageRepository($config->getString('content_dir'));
$slug = (string)($_GET['page'] ?? $config->getString('default_page'));
$page = PageRepository::validSlug($slug) ? $repo->findPublished($slug) : null;
$status = 200;
if ($page === null) {
    $status = 404;
    $page = ['title' => 'Not found', 'content' => '# Page not found' . "\n\nThe requested page is not published.", 'mtime' => time()];
}
$markdown = new Markdown();
$contentHtml = $markdown->render((string)$page['content']);
$renderer = new Renderer();
$html = $renderer->render('layout', ['title' => (string)$page['title'], 'siteName' => $config->getString('site_name'), 'basePath' => $config->getString('base_path'), 'contentHtml' => $contentHtml, 'headerNav' => $repo->navigation('header'), 'sidebarNav' => $repo->navigation('sidebar')]);
$mtime = max((int)$page['mtime'], $repo->latestMtime(), (int)filemtime(__FILE__), (int)filemtime(dirname(__DIR__) . '/public/assets/style.css'));
$etag = '"' . sha1($html . $mtime) . '"';
security_headers(false);
header('Content-Type: text/html; charset=UTF-8');
header('ETag: ' . $etag);
header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $mtime) . ' GMT');
if (($_SERVER['HTTP_IF_NONE_MATCH'] ?? '') === $etag || (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime((string)$_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $mtime)) {
    http_response_code(304);
    exit;
}
http_response_code($status);
echo $html;
