<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/Database/Connection.php';

use App\Database\Connection;

$config = require __DIR__ . '/../config/database.php';
$pdo = Connection::getPdo($config);

header('Content-Type: application/xml; charset=utf-8');
header('Cache-Control: public, max-age=86400');

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

$baseUrl = 'https://' . ($_SERVER['HTTP_HOST'] ?? 'localhost:8080');

$statement = $pdo->query(
    'SELECT slug, date_modification, statut
     FROM articles
     WHERE statut = \'publie\'
     ORDER BY date_modification DESC'
);
$articles = $statement->fetchAll();

foreach ($articles as $article) {
    $url = $baseUrl . '/article/' . htmlspecialchars($article['slug'], ENT_XML1, 'UTF-8');
    $lastmod = substr($article['date_modification'], 0, 10);
    $priority = '0.8';
    $changefreq = 'weekly';

    echo '  <url>' . "\n";
    echo '    <loc>' . $url . '</loc>' . "\n";
    echo '    <lastmod>' . htmlspecialchars($lastmod) . '</lastmod>' . "\n";
    echo '    <changefreq>' . $changefreq . '</changefreq>' . "\n";
    echo '    <priority>' . $priority . '</priority>' . "\n";
    echo '  </url>' . "\n";
}

echo '</urlset>';
