<?php
// CORS Headers FIRST - before any output
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=utf-8');

// Handle CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    $host = getenv('POSTGRES_HOST') ?: 'db';
    $db = getenv('POSTGRES_DB') ?: 'backoffice_db';
    $user = getenv('POSTGRES_USER') ?: 'backoffice_user';
    $password = getenv('POSTGRES_PASSWORD') ?: 'backoffice_pass';

    $dsn = "pgsql:host=$host;dbname=$db";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = $pdo->prepare('
        SELECT id, titre, slug, contenu, contenu_brut, auteur, date_creation, statut 
        FROM articles 
        WHERE statut = :statut 
        ORDER BY date_creation DESC
    ');
    $query->execute([':statut' => 'publie']);
    $articles = $query->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $articles,
        'count' => count($articles)
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database Error',
        'message' => $e->getMessage()
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Server Error',
        'message' => $e->getMessage()
    ]);
}
?>
