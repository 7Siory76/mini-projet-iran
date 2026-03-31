<?php

declare(strict_types=1);

session_start();

// Vérification de connexion - rediriger vers login si pas authentifié
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

// Gestion de la déconnexion
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: /login.php');
    exit;
}

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

function jsonResponse(array $payload, int $statusCode = 200): never
{
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

$config = require __DIR__ . '/../config/database.php';
$pdo = Connection::getPdo($config);

$message = '';
$error = '';
$action = $_GET['action'] ?? 'list';
$editId = isset($_GET['id']) ? (int) $_GET['id'] : null;
$viewSlug = isset($_GET['slug']) ? trim((string) $_GET['slug']) : '';

$formData = [
    'id' => null,
    'titre' => '',
    'slug' => '',
    'auteur' => '',
    'statut' => 'brouillon',
    'contenu' => '',
    'contenu_brut' => '',
];

if ($action === 'upload-image' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['image'])) {
        jsonResponse(['ok' => false, 'message' => 'Aucun fichier image reçu.'], 400);
    }

    $file = $_FILES['image'];
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        jsonResponse(['ok' => false, 'message' => 'Erreur pendant l\'upload image.'], 400);
    }

    if (($file['size'] ?? 0) > 5 * 1024 * 1024) {
        jsonResponse(['ok' => false, 'message' => 'Image trop volumineuse (max 5MB).'], 400);
    }

    $tmpPath = (string) ($file['tmp_name'] ?? '');
    if (!is_uploaded_file($tmpPath)) {
        jsonResponse(['ok' => false, 'message' => 'Fichier upload invalide.'], 400);
    }

    $originalName = (string) ($file['name'] ?? 'image');
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];

    if (!in_array($extension, $allowedExtensions, true)) {
        $mimeType = '';
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            if ($finfo !== false) {
                $detected = finfo_file($finfo, $tmpPath);
                $mimeType = is_string($detected) ? $detected : '';
                finfo_close($finfo);
            }
        }

        $mimeToExtension = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'image/svg+xml' => 'svg',
        ];

        if (isset($mimeToExtension[$mimeType])) {
            $extension = $mimeToExtension[$mimeType];
        }
    }

    if (!in_array($extension, $allowedExtensions, true)) {
        jsonResponse(['ok' => false, 'message' => 'Format non supporté.'], 400);
    }

    // Dossier back office (mapé via Docker à ./shared_images)
    $imageDirectory = __DIR__ . '/images';
    if (!is_dir($imageDirectory) && !mkdir($imageDirectory, 0777, true) && !is_dir($imageDirectory)) {
        jsonResponse(['ok' => false, 'message' => 'Impossible de créer le dossier images.'], 500);
    }

    $newFilename = sprintf('%s-%s.%s', date('YmdHis'), bin2hex(random_bytes(4)), $extension);
    $destinationPath = $imageDirectory . '/' . $newFilename;

    if (!move_uploaded_file($tmpPath, $destinationPath)) {
        jsonResponse(['ok' => false, 'message' => 'Impossible de sauvegarder l\'image.'], 500);
    }

    jsonResponse([
        'ok' => true,
        'url' => '/images/' . $newFilename,
        'message' => 'Image uploadée avec succès.',
    ]);
}

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

$viewArticle = null;
if ($action === 'view' && $viewSlug !== '') {
    $statement = $pdo->prepare('SELECT * FROM articles WHERE slug = :slug');
    $statement->execute([':slug' => $viewSlug]);
    $viewArticle = $statement->fetch();

    if (!$viewArticle) {
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
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: #f5f5f5;
        }
        
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }
        
        .navbar h1 {
            font-size: 24px;
            font-weight: 600;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-info {
            text-align: right;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 14px;
        }
        
        .user-email {
            font-size: 12px;
            opacity: 0.9;
        }
        
        .logout-btn {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.5);
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 14px;
            font-weight: 500;
        }
        
        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.5);
            border-color: white;
        }
        
        .container { 
            max-width: 1200px; 
            margin: 0 auto; 
            padding: 0 2rem;
        }
        
        body { margin: 0; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 10px; }
        th { background: #f2f2f2; text-align: left; }
        .ok { color: #0a7a28; margin: 0.5rem 0; }
        .error { color: #b00020; margin: 0.5rem 0; }
        .topbar { display: flex; gap: 10px; align-items: center; margin-bottom: 1rem; }
        .btn { background: #1f6feb; color: #fff; border: 0; padding: 8px 12px; cursor: pointer; text-decoration: none; border-radius: 4px; }
        .btn.secondary { background: #555; }
        .form-wrap { max-width: 1000px; margin-bottom: 2rem; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); }
        .grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; margin-bottom: 10px; }
        input, select { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        .toolbar { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 8px; }
        .tool { border: 1px solid #ccc; background: #fff; padding: 6px 8px; cursor: pointer; border-radius: 4px; }
        #editor { min-height: 260px; border: 1px solid #ccc; border-radius: 4px; padding: 10px; background: #fff; }
        .badge { padding: 2px 8px; border-radius: 999px; font-size: 12px; }
        .badge.brouillon { background: #fff3cd; color: #7a5a00; }
        .badge.publie { background: #d4edda; color: #1e6e34; }
        .article-view { max-width: 900px; border: 1px solid #ddd; border-radius: 8px; padding: 16px; margin-bottom: 2rem; background: white; }
        .article-view img { max-width: 100%; height: auto; }
        .meta { color: #666; margin-bottom: 1rem; }
        .small { color: #666; font-size: 13px; }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>🔐 Back Office Articles</h1>
        <div class="user-menu">
            <div class="user-info">
                <div class="user-name"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Utilisateur') ?></div>
                <div class="user-email"><?= htmlspecialchars($_SESSION['user_email'] ?? '') ?></div>
            </div>
            <a href="/?logout=1" class="logout-btn">Déconnexion</a>
        </div>
    </div>

    <div class="container">
        <h1>Back Office Articles</h1>
        <p class="ok">Connexion base de données: OK</p>

        <?php if ($message !== ''): ?>
            <p class="ok"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <?php if ($error !== ''): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <div class="topbar">
            <a class="btn" href="/admin/new">+ Nouvel article</a>
            <a class="btn secondary" href="/admin/">Liste des articles</a>
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
                <button class="tool" type="button" id="btn-image-upload">Uploader image</button>
                <button class="tool" type="button" data-cmd="removeFormat">Nettoyer</button>
            </div>
            <input type="file" id="image-file-input" accept="image/*" style="display:none;">
            <p class="small">Upload local: JPG, PNG, GIF, WEBP, SVG (max 5MB). Tu peux aussi coller avec Ctrl+V ou glisser-déposer.</p>
            <div id="editor" contenteditable="true"><?= $formData['contenu'] ?? '' ?></div>

            <div style="margin-top: 12px;">
                <button class="btn" type="submit">Enregistrer</button>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <?php if ($action === 'view' && $viewArticle): ?>
    <div class="article-view">
        <h2><?= htmlspecialchars($viewArticle['titre']) ?></h2>
        <div class="meta">
            Slug: <?= htmlspecialchars($viewArticle['slug']) ?> |
            Auteur: <?= htmlspecialchars((string) ($viewArticle['auteur'] ?? '-')) ?> |
            Statut: <?= htmlspecialchars($viewArticle['statut']) ?>
        </div>
        <div><?= $viewArticle['contenu'] ?></div>
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
                    <td>
                        <a href="/article/<?= htmlspecialchars($article['slug']) ?>">Voir</a>
                        |
                        <a href="/admin/edit/<?= (int) $article['id'] ?>">Modifier</a>
                    </td>
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
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const cmd = button.getAttribute('data-cmd');
                const value = button.getAttribute('data-value');
                document.execCommand(cmd, false, value);
                editor.focus();
            });
        });

        const linkButton = document.getElementById('btn-link');
        const imageUploadButton = document.getElementById('btn-image-upload');
        const imageFileInput = document.getElementById('image-file-input');

        const uploadImageFile = async (file) => {
            const uploadData = new FormData();
            uploadData.append('image', file);

            const response = await fetch('/?action=upload-image', {
                method: 'POST',
                body: uploadData,
            });
            const result = await response.json();

            if (!response.ok || !result.ok || !result.url) {
                throw new Error(result.message || 'Échec upload image.');
            }

            const imgHtml = `<img src="${result.url}" alt="Image" style="max-width:100%;height:auto;" />`;
            document.execCommand('insertHTML', false, imgHtml);
            editor.focus();
        };
        if (linkButton) {
            linkButton.addEventListener('click', () => {
                const url = window.prompt('URL du lien:');
                if (url) {
                    document.execCommand('createLink', false, url);
                    editor.focus();
                }
            });
        }

        if (imageUploadButton && imageFileInput && editor) {
            imageUploadButton.addEventListener('click', () => {
                imageFileInput.click();
            });

            imageFileInput.addEventListener('change', async () => {
                const file = imageFileInput.files && imageFileInput.files[0] ? imageFileInput.files[0] : null;
                if (!file) {
                    return;
                }

                try {
                    await uploadImageFile(file);
                } catch (uploadError) {
                    window.alert(uploadError.message || 'Erreur réseau pendant l\'upload image.');
                } finally {
                    imageFileInput.value = '';
                }
            });

            editor.addEventListener('paste', async (event) => {
                const clipboardItems = event.clipboardData && event.clipboardData.items ? event.clipboardData.items : [];
                for (const item of clipboardItems) {
                    if (item.kind === 'file' && item.type.startsWith('image/')) {
                        const file = item.getAsFile();
                        if (!file) {
                            continue;
                        }

                        event.preventDefault();
                        try {
                            await uploadImageFile(file);
                        } catch (uploadError) {
                            window.alert(uploadError.message || 'Impossible d\'uploader l\'image collée.');
                        }
                        return;
                    }
                }
            });

            editor.addEventListener('dragover', (event) => {
                event.preventDefault();
            });

            editor.addEventListener('drop', async (event) => {
                const droppedFiles = event.dataTransfer && event.dataTransfer.files ? event.dataTransfer.files : [];
                if (!droppedFiles.length) {
                    return;
                }

                const file = droppedFiles[0];
                if (!file.type.startsWith('image/')) {
                    return;
                }

                event.preventDefault();
                try {
                    await uploadImageFile(file);
                } catch (uploadError) {
                    window.alert(uploadError.message || 'Impossible d\'uploader l\'image déposée.');
                }
            });
        }

        if (form && editor && contenuInput) {
            form.addEventListener('submit', () => {
                contenuInput.value = editor.innerHTML.trim();
            });
        }
    </script>
    </div>
</body>
</html>
