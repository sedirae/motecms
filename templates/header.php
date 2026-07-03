<?php declare(strict_types=1); use function MoteCMS\e; ?>
<header class="top"><a class="brand" href="<?= e($basePath) ?>/index.php"><?= e($siteName) ?></a><nav aria-label="Header"><ul><?php foreach ($headerNav as $item): ?><li><a href="<?= e($basePath) ?>/index.php?page=<?= e($item['slug']) ?>"><?= e($item['title']) ?></a></li><?php endforeach; ?></ul></nav></header>
