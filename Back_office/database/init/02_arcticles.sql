CREATE TABLE articles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    contenu LONGTEXT NOT NULL,          -- HTML généré par l'éditeur
    contenu_brut TEXT,                  -- version sans balises (optionnel)
    auteur VARCHAR(100),
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_modification DATETIME ON UPDATE CURRENT_TIMESTAMP,
    statut ENUM('brouillon', 'publié') DEFAULT 'brouillon'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;