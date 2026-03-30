CREATE TABLE IF NOT EXISTS articles (
    id SERIAL PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    contenu TEXT NOT NULL,
    contenu_brut TEXT,
    auteur VARCHAR(100),
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    statut VARCHAR(20) NOT NULL DEFAULT 'brouillon' CHECK (statut IN ('brouillon', 'publie'))
);

CREATE OR REPLACE FUNCTION set_date_modification_articles()
RETURNS TRIGGER AS $$
BEGIN
    NEW.date_modification = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS trg_set_date_modification_articles ON articles;

CREATE TRIGGER trg_set_date_modification_articles
BEFORE UPDATE ON articles
FOR EACH ROW
EXECUTE FUNCTION set_date_modification_articles();