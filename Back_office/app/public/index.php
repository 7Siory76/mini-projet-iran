<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/Database/Connection.php';

use App\Database\Connection;

$config = require __DIR__ . '/../config/database.php';
$pdo = Connection::getPdo($config);

$statement = $pdo->query('SELECT id, full_name, email, created_at FROM users ORDER BY id');
$users = $statement->fetchAll();

?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Back Office PHP</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        table { border-collapse: collapse; width: 100%; max-width: 800px; }
        th, td { border: 1px solid #ddd; padding: 10px; }
        th { background: #f2f2f2; text-align: left; }
        .ok { color: #0a7a28; }
    </style>
</head>
<body>
    <h1>Back Office (PHP + Apache + PostgreSQL)</h1>
    <p class="ok">Connexion base de données: OK</p>

    <h2>Utilisateurs</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Créé le</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars((string) $user['id']) ?></td>
                <td><?= htmlspecialchars($user['full_name']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= htmlspecialchars((string) $user['created_at']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
