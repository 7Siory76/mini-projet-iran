-- Données d'exemple pour la table articles
-- Attention : statut doit être 'publie' (sans accent) ou 'brouillon' selon la contrainte CHECK

INSERT INTO articles (titre, slug, contenu, contenu_brut, auteur, statut)
VALUES (
    'Bienvenue dans notre blog',
    'bienvenue-blog',
    '<h1>Bienvenue</h1>
    <p>Ceci est un article <strong>important</strong> avec du <em>texte en italique</em>.</p>
    <p style="color: blue;">Un paragraphe en bleu.</p>
    <p><img src="/images/exemple.jpg" alt="Exemple d''image" style="width:300px;"></p>
    <p>Un texte <span style="background-color: yellow;">surligné</span> et du <strong style="color: red;">gras rouge</strong>.</p>',
    'Bienvenue dans notre blog. Ceci est un article important avec du texte en italique. Un paragraphe en bleu. Un texte surligné et du gras rouge.',
    'Jean Dupont',
    'publie'
) ON CONFLICT DO NOTHING;

INSERT INTO articles (titre, slug, contenu, contenu_brut, auteur, statut)
VALUES (
    'Iran: Développements politiques majeurs',
    'iran-developpements-politiques',
    '<h1>Développements politiques en Iran</h1>
    <p>Les dernières évolutions politiques du pays marquent un tournant important.</p>
    <p><strong>Points clés:</strong></p>
    <ul><li>Réformes institutionnelles</li><li>Nouveau gouvernement</li><li>Mesures économiques</li></ul>',
    'Iran: Développements politiques majeurs. Les dernières évolutions politiques du pays marquent un tournant important.',
    'Marie Dubois',
    'publie'
) ON CONFLICT DO NOTHING;

INSERT INTO articles (titre, slug, contenu, contenu_brut, auteur, statut)
VALUES (
    'Économie iranienne: Analyse et prévisions',
    'economie-iran-2026',
    '<h1>État de l''économie iranienne en 2026</h1>
    <p>Une analyse approfondie des indicateurs économiques actuels.</p>
    <p>Les exports, les investissements et les réformes fiscales sont au cœur des enjeux.</p>',
    'Économie iranienne: Analyse et prévisions. Une analyse approfondie des indicateurs économiques actuels.',
    'Admin',
    'publie'
) ON CONFLICT DO NOTHING;

INSERT INTO articles (titre, slug, contenu, contenu_brut, auteur, statut)
VALUES (
    'Culture et patrimoine: Les trésors de l''Iran',
    'patrimoine-culturel-iran',
    '<h1>Les trésors culturels de l''Iran</h1>
    <p>Découvrez l''histoire riche et le patrimoine culturel du pays.</p>
    <p>Du patrimoine architectural ancien aux traditions modernes, l''Iran offre une diversité culturelle remarquable.</p>',
    'Culture et patrimoine: Les trésors de l''Iran. Découvrez l''histoire riche et le patrimoine culturel du pays.',
    'Mohammad Hassan',
    'publie'
) ON CONFLICT DO NOTHING;

INSERT INTO articles (titre, slug, contenu, contenu_brut, auteur, statut)
VALUES (
    'Dernier article en brouillon',
    'article-brouillon',
    '<h1>Cet article n''est pas encore publié</h1>
    <p>Il s''agit d''un article en brouillon qui ne sera pas affiché sur le site public.</p>',
    'Article en brouillon',
    'Admin',
    'brouillon'
) ON CONFLICT DO NOTHING;
