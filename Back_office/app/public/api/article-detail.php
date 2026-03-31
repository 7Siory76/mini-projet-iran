<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    // Accepter slug ou id
    $slug = isset($_GET['slug']) ? $_GET['slug'] : null;
    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    
    if (!$slug && !$id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Paramètre slug ou id requis']);
        exit();
    }

    $host = getenv('POSTGRES_HOST') ?: 'backoffice_db';
    $db = getenv('POSTGRES_DB') ?: 'backoffice_db';
    $user = getenv('POSTGRES_USER') ?: 'backoffice_user';
    $password = getenv('POSTGRES_PASSWORD') ?: 'backoffice_pass';

    $dsn = "pgsql:host=$host;dbname=$db";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête par slug (priorité) ou par id
    if ($slug) {
        $query = $pdo->prepare('
            SELECT id, titre, slug, contenu, contenu_brut, auteur, date_creation, date_modification, statut 
            FROM articles 
            WHERE slug = :slug AND statut = :statut
        ');
        $query->execute([':slug' => $slug, ':statut' => 'publie']);
    } else {
        $query = $pdo->prepare('
            SELECT id, titre, slug, contenu, contenu_brut, auteur, date_creation, date_modification, statut 
            FROM articles 
            WHERE id = :id AND statut = :statut
        ');
        $query->execute([':id' => $id, ':statut' => 'publie']);
    }
    
    $article = $query->fetch(PDO::FETCH_ASSOC);

    if (!$article) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Article not found']);
        exit();
    }

    echo json_encode(['success' => true, 'data' => $article]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
