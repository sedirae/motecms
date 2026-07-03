<?php declare(strict_types=1); use function MoteCMS\e; ?>
<aside class="side"><a href="#content" class="skip">Skip to content</a><nav aria-label="Sidebar"><ul><?php foreach ($sidebarNav as $item): ?><li><a href="<?= e($basePath) ?>/index.php?page=<?= e($item['slug']) ?>"><?= e($item['title']) ?></a></li><?php endforeach; ?></ul></nav></aside>
