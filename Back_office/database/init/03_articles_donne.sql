INSERT INTO articles (titre, slug, contenu, auteur, statut)
VALUES (
    'Bienvenue dans notre blog',
    'bienvenue-blog',
    '<h1>Bienvenue</h1>
    <p>Ceci est un article <strong>important</strong> avec du <em>texte en italique</em>.</p>
    <p style="color: blue;">Un paragraphe en bleu.</p>
    <p><img src="/images/exemple.svg" alt="Exemple d''image" style="width:300px;"></p>
    <p>Un texte <span style="background-color: yellow;">surligné</span> et du <strong style="color: red;">gras rouge</strong>.</p>',
    'Jean Dupont',
    'publie'
)
ON CONFLICT (slug) DO NOTHING;