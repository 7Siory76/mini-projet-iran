<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/Database/Connection.php';

use App\Database\Connection;

function slugify(string $text): string
{
    $text = trim($text);
    $text = preg_replace('/[^\pL\pN]+/u', '-', $text) ?? '';
    $text = trim($text, '-');
    $text = mb_strtolower($text);

    return $text !== '' ? $text : 'article';
}

function uniqueSlug(PDO $pdo, string $baseSlug, ?int $excludeId = null): string
{
    $slug = $baseSlug;
    $suffix = 1;

    while (true) {
        $sql = 'SELECT id FROM articles WHERE slug = :slug';
        if ($excludeId !== null) {
            $sql .= ' AND id <> :id';
        }

        $statement = $pdo->prepare($sql);
        $statement->bindValue(':slug', $slug);
        if ($excludeId !== null) {
            $statement->bindValue(':id', $excludeId, PDO::PARAM_INT);
        }
        $statement->execute();

        if (!$statement->fetch()) {
            return $slug;
        }

        $slug = $baseSlug . '-' . $suffix;
        $suffix++;
    }
}

$config = require __DIR__ . '/../config/database.php';
$pdo = Connection::getPdo($config);

$message = '';
$error = '';
$action = $_GET['action'] ?? 'list';
$editId = isset($_GET['id']) ? (int) $_GET['id'] : null;

$formData = [
    'id' => null,
    'titre' => '',
    'slug' => '',
    'auteur' => '',
    'statut' => 'brouillon',
    'contenu' => '',
    'contenu_brut' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mode = $_POST['mode'] ?? 'create';
    $id = isset($_POST['id']) && $_POST['id'] !== '' ? (int) $_POST['id'] : null;

    $titre = trim($_POST['titre'] ?? '');
    $auteur = trim($_POST['auteur'] ?? '');
    $statut = $_POST['statut'] ?? 'brouillon';
    $contenu = trim($_POST['contenu'] ?? '');
    $contenuBrut = trim(strip_tags($contenu));

    if ($titre === '' || $contenu === '') {
        $error = 'Le titre et le contenu sont obligatoires.';
        $action = $mode === 'edit' ? 'edit' : 'new';
        $formData = [
            'id' => $id,
            'titre' => $titre,
            'slug' => '',
            'auteur' => $auteur,
            'statut' => in_array($statut, ['brouillon', 'publie'], true) ? $statut : 'brouillon',
            'contenu' => $contenu,
            'contenu_brut' => $contenuBrut,
        ];
    } else {
        $baseSlug = slugify($titre);

        if ($mode === 'edit' && $id !== null) {
            $slug = uniqueSlug($pdo, $baseSlug, $id);
            $statement = $pdo->prepare(
                'UPDATE articles
                 SET titre = :titre,
                     slug = :slug,
                     contenu = :contenu,
                     contenu_brut = :contenu_brut,
                     auteur = :auteur,
                     statut = :statut
                 WHERE id = :id'
            );
            $statement->execute([
                ':titre' => $titre,
                ':slug' => $slug,
                ':contenu' => $contenu,
                ':contenu_brut' => $contenuBrut,
                ':auteur' => $auteur !== '' ? $auteur : null,
                ':statut' => in_array($statut, ['brouillon', 'publie'], true) ? $statut : 'brouillon',
                ':id' => $id,
            ]);
            header('Location: /?message=updated');
            exit;
        }

        $slug = uniqueSlug($pdo, $baseSlug);
        $statement = $pdo->prepare(
            'INSERT INTO articles (titre, slug, contenu, contenu_brut, auteur, statut)
             VALUES (:titre, :slug, :contenu, :contenu_brut, :auteur, :statut)'
        );
        $statement->execute([
            ':titre' => $titre,
            ':slug' => $slug,
            ':contenu' => $contenu,
            ':contenu_brut' => $contenuBrut,
            ':auteur' => $auteur !== '' ? $auteur : null,
            ':statut' => in_array($statut, ['brouillon', 'publie'], true) ? $statut : 'brouillon',
        ]);

        header('Location: /?message=created');
        exit;
    }
}

if (isset($_GET['message'])) {
    if ($_GET['message'] === 'created') {
        $message = 'Article créé avec succès.';
    }
    if ($_GET['message'] === 'updated') {
        $message = 'Article modifié avec succès.';
    }
}

if ($action === 'edit' && $editId !== null && $error === '') {
    $statement = $pdo->prepare('SELECT * FROM articles WHERE id = :id');
    $statement->execute([':id' => $editId]);
    $article = $statement->fetch();

    if ($article) {
        $formData = $article;
    } else {
        $error = 'Article introuvable.';
        $action = 'list';
    }
}

$statement = $pdo->query(
    'SELECT id, titre, slug, auteur, statut, date_creation, date_modification
     FROM articles
     ORDER BY date_modification DESC, id DESC'
);
$articles = $statement->fetchAll();

?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Back Office Articles</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 10px; }
        th { background: #f2f2f2; text-align: left; }
        .ok { color: #0a7a28; margin: 0.5rem 0; }
        .error { color: #b00020; margin: 0.5rem 0; }
        .topbar { display: flex; gap: 10px; align-items: center; margin-bottom: 1rem; }
        .btn { background: #1f6feb; color: #fff; border: 0; padding: 8px 12px; cursor: pointer; text-decoration: none; border-radius: 4px; }
        .btn.secondary { background: #555; }
        .form-wrap { max-width: 1000px; margin-bottom: 2rem; }
        .grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; margin-bottom: 10px; }
        input, select { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        .toolbar { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 8px; }
        .tool { border: 1px solid #ccc; background: #fff; padding: 6px 8px; cursor: pointer; border-radius: 4px; }
        #editor { min-height: 260px; border: 1px solid #ccc; border-radius: 4px; padding: 10px; background: #fff; }
        .badge { padding: 2px 8px; border-radius: 999px; font-size: 12px; }
        .badge.brouillon { background: #fff3cd; color: #7a5a00; }
        .badge.publie { background: #d4edda; color: #1e6e34; }
    </style>
</head>
<body>
    <h1>Back Office Articles</h1>
    <p class="ok">Connexion base de données: OK</p>

    <?php if ($message !== ''): ?>
        <p class="ok"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <?php if ($error !== ''): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <div class="topbar">
        <a class="btn" href="/?action=new">+ Nouvel article</a>
        <a class="btn secondary" href="/">Liste des articles</a>
    </div>

    <?php if ($action === 'new' || $action === 'edit'): ?>
    <div class="form-wrap">
        <h2><?= $action === 'edit' ? 'Modifier un article' : 'Créer un article' ?></h2>
        <form method="post" id="article-form">
            <input type="hidden" name="mode" value="<?= $action === 'edit' ? 'edit' : 'create' ?>">
            <input type="hidden" name="id" value="<?= htmlspecialchars((string) ($formData['id'] ?? '')) ?>">
            <input type="hidden" name="contenu" id="contenu" value="">

            <div class="grid">
                <div>
                    <label>Titre</label>
                    <input type="text" name="titre" required value="<?= htmlspecialchars((string) ($formData['titre'] ?? '')) ?>">
                </div>
                <div>
                    <label>Auteur</label>
                    <input type="text" name="auteur" value="<?= htmlspecialchars((string) ($formData['auteur'] ?? '')) ?>">
                </div>
                <div>
                    <label>Statut</label>
                    <select name="statut">
                        <option value="brouillon" <?= (($formData['statut'] ?? '') === 'brouillon') ? 'selected' : '' ?>>Brouillon</option>
                        <option value="publie" <?= (($formData['statut'] ?? '') === 'publie') ? 'selected' : '' ?>>Publié</option>
                    </select>
                </div>
            </div>

            <label>Contenu</label>
            <div class="toolbar">
                <button class="tool" type="button" data-cmd="bold"><b>B</b></button>
                <button class="tool" type="button" data-cmd="italic"><i>I</i></button>
                <button class="tool" type="button" data-cmd="underline"><u>U</u></button>
                <button class="tool" type="button" data-cmd="insertUnorderedList">• Liste</button>
                <button class="tool" type="button" data-cmd="insertOrderedList">1. Liste</button>
                <button class="tool" type="button" data-cmd="formatBlock" data-value="h2">H2</button>
                <button class="tool" type="button" data-cmd="formatBlock" data-value="h3">H3</button>
                <button class="tool" type="button" id="btn-link">Lien</button>
                <button class="tool" type="button" data-cmd="removeFormat">Nettoyer</button>
            </div>
            <div id="editor" contenteditable="true"><?= $formData['contenu'] ?? '' ?></div>

            <div style="margin-top: 12px;">
                <button class="btn" type="submit">Enregistrer</button>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <h2>Articles</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Slug</th>
                <th>Auteur</th>
                <th>Statut</th>
                <th>Création</th>
                <th>Modification</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php if (count($articles) === 0): ?>
            <tr>
                <td colspan="8">Aucun article pour le moment.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($articles as $article): ?>
                <tr>
                    <td><?= htmlspecialchars((string) $article['id']) ?></td>
                    <td><?= htmlspecialchars($article['titre']) ?></td>
                    <td><?= htmlspecialchars($article['slug']) ?></td>
                    <td><?= htmlspecialchars((string) ($article['auteur'] ?? '-')) ?></td>
                    <td>
                        <span class="badge <?= htmlspecialchars($article['statut']) ?>">
                            <?= htmlspecialchars($article['statut']) ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars((string) $article['date_creation']) ?></td>
                    <td><?= htmlspecialchars((string) $article['date_modification']) ?></td>
                    <td><a href="/?action=edit&id=<?= (int) $article['id'] ?>">Modifier</a></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>

    <script>
        const editor = document.getElementById('editor');
        const contenuInput = document.getElementById('contenu');
        const form = document.getElementById('article-form');

        document.querySelectorAll('[data-cmd]').forEach((button) => {
            button.addEventListener('click', () => {
                const cmd = button.getAttribute('data-cmd');
                const value = button.getAttribute('data-value');
                document.execCommand(cmd, false, value);
                editor.focus();
            });
        });

        const linkButton = document.getElementById('btn-link');
        if (linkButton) {
            linkButton.addEventListener('click', () => {
                const url = window.prompt('URL du lien:');
                if (url) {
                    document.execCommand('createLink', false, url);
                    editor.focus();
                }
            });
        }

        if (form && editor && contenuInput) {
            form.addEventListener('submit', () => {
                contenuInput.value = editor.innerHTML.trim();
            });
        }
    </script>
</body>
</html>
