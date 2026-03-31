-- Données d'exemple pour la table articles
-- Attention : statut doit être 'publie' (sans accent) ou 'brouillon' selon la contrainte CHECK

INSERT INTO articles (titre, slug, contenu, contenu_brut, auteur, statut)
VALUES (
    'Bienvenue dans notre blog',
    'bienvenue-blog',
    '<img src="/images/bienvenue.svg" alt="Bienvenue" style="width:100%;max-width:400px;border-radius:8px;margin-bottom:20px;">
    <h1>Bienvenue</h1>
    <p>Ceci est un article <strong>important</strong> avec du <em>texte en italique</em>.</p>
    <p style="color: blue;">Un paragraphe en bleu.</p>
    <p>Un texte <span style="background-color: yellow;">surligné</span> et du <strong style="color: red;">gras rouge</strong>.</p>',
    'Bienvenue dans notre blog. Ceci est un article important avec du texte en italique. Un paragraphe en bleu. Un texte surligné et du gras rouge.',
    'Jean Dupont',
    'publie'
) ON CONFLICT DO NOTHING;

INSERT INTO articles (titre, slug, contenu, contenu_brut, auteur, statut)
VALUES (
    'Iran: Développements politiques majeurs',
    'iran-developpements-politiques',
    '<img src="/images/politique.svg" alt="Politique Iran" style="width:100%;max-width:400px;border-radius:8px;margin-bottom:20px;">
    <h1>Développements politiques en Iran</h1>
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
    '<img src="/images/economie.svg" alt="Économie Iran" style="width:100%;max-width:400px;border-radius:8px;margin-bottom:20px;">
    <h1>État de l''économie iranienne en 2026</h1>
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
    '<img src="/images/culture.svg" alt="Patrimoine Iran" style="width:100%;max-width:400px;border-radius:8px;margin-bottom:20px;">
    <h1>Les trésors culturels de l''Iran</h1>
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

INSERT INTO articles (titre, slug, contenu, contenu_brut, auteur, statut)
VALUES (
    'Technologie et innovation en Iran',
    'technologie-innovation-iran',
    '<img src="/images/technologie.svg" alt="Technologie Iran" style="width:100%;max-width:400px;border-radius:8px;margin-bottom:20px;">
    <h1>L''essor technologique iranien</h1>
    <p>L''Iran se positionne comme un acteur majeur de l''innovation technologique en Asie.</p>
    <p>Des startups aux géants industriels, découvrez les projets révolutionnaires en cours.</p>
    <ul><li>Intelligence artificielle</li><li>Énergies renouvelables</li><li>Biotechnologie</li></ul>',
    'Technologie et innovation en Iran. L''Iran se positionne comme un acteur majeur de l''innovation technologique.',
    'Reza Ahmadi',
    'publie'
) ON CONFLICT DO NOTHING;

INSERT INTO articles (titre, slug, contenu, contenu_brut, auteur, statut)
VALUES (
    'Sports: Les champions iraniens brillent sur la scène internationale',
    'sports-champions-iran',
    '<img src="/images/sports.svg" alt="Sports Champions" style="width:100%;max-width:400px;border-radius:8px;margin-bottom:20px;">
    <h1>Victoires des athlètes iraniens</h1>
    <p>Les sportifs iraniens continuent de remporter des succès remarquables aux compétitions internationales.</p>
    <p><strong>Récents triomphes:</strong></p>
    <ul><li>Lutte libre - Médailles d''or</li><li>Volley-ball féminin</li><li>Haltérophilie mondiale</li></ul>',
    'Sports: Les champions iraniens brillent sur la scène internationale. Les sportifs iraniens continuent de remporter des succès',
    'Fatima Tehrani',
    'publie'
) ON CONFLICT DO NOTHING;

INSERT INTO articles (titre, slug, contenu, contenu_brut, auteur, statut)
VALUES (
    'Prix du pétrole: Impacts sur l''économie iranienne',
    'prix-petrole-iran',
    '<img src="/images/petrole.svg" alt="Prix Pétrole" style="width:100%;max-width:400px;border-radius:8px;margin-bottom:20px;">
    <h1>Fluctuation des prix du pétrole</h1>
    <p>Les variations du marché pétrolier mondial affectent directement l''économie iranienne.</p>
    <p>Analysons les tendances actuelles et les prévisions pour les mois à venir.</p>
    <p>Les revenus d''exportation, les investissements étrangers et la stabilité économique en dépendent largement.</p>',
    'Prix du pétrole: Impacts sur l''économie iranienne. Les variations du marché pétrolier mondial affectent directement l''économie.',
    'Dr. Karim Nasri',
    'publie'
) ON CONFLICT DO NOTHING;

INSERT INTO articles (titre, slug, contenu, contenu_brut, auteur, statut)
VALUES (
    'Tourisme en Iran: Une destination émergente',
    'tourisme-iran-destination',
    '<img src="/images/tourisme.svg" alt="Tourisme Iran" style="width:100%;max-width:400px;border-radius:8px;margin-bottom:20px;">
    <h1>L''Iran, destination touristique de choix</h1>
    <p>L''augmentation du nombre de visiteurs internationaux confirm l''attrait touristique croissant de l''Iran.</p>
    <p>Des mosquées historiques aux déserts spectaculaires, découvrez les attractions majeures.</p>
    <p><strong>Sites incontournables:</strong></p>
    <ul><li>Ispahan - La Perle de l''Orient</li><li>Shiraz - La ville des poètes</li><li>Persépolis - Site archéologique majeur</li></ul>',
    'Tourisme en Iran: Une destination émergente. L''augmentation du nombre de visiteurs internationaux confirme l''attrait touristique.',
    'Leila Khatami',
    'publie'
) ON CONFLICT DO NOTHING;

INSERT INTO articles (titre, slug, contenu, contenu_brut, auteur, statut)
VALUES (
    'Éducation: Réformes dans les universités iraniennes',
    'education-reformes-universites',
    '<img src="/images/education.svg" alt="Éducation Iran" style="width:100%;max-width:400px;border-radius:8px;margin-bottom:20px;">
    <h1>Nouvelles orientations académiques</h1>
    <p>Le système éducatif iranien connaît des transformations majeures visant l''excellence académique.</p>
    <p>L''intégration de nouvelles technologies et la collaboration internationale ouvrent des horizons novateurs.</p>
    <p>Les universités iraniennes renforcent leurs classements mondiaux et leurs partenariats stratégiques.</p>',
    'Éducation: Réformes dans les universités iraniennes. Le système éducatif iranien connaît des transformations majeures.',
    'Professor Hasan Zarif',
    'publie'
) ON CONFLICT DO NOTHING;

INSERT INTO articles (titre, slug, contenu, contenu_brut, auteur, statut)
VALUES (
    'Environnement: initiatives vertes en Iran',
    'environnement-initiatives-vertes',
    '<img src="/images/environnement.svg" alt="Environnement Iran" style="width:100%;max-width:400px;border-radius:8px;margin-bottom:20px;">
    <h1>Vers une Iran plus verte</h1>
    <p>Le gouvernement lance des initiatives ambitieuses pour protéger l''environnement et lutter contre le changement climatique.</p>
    <p>Plantations massives d''arbres, énergies renouvelables et réduction des émissions de carbone sont au programme.</p>
    <p>Les réserves naturelles et les espèces menacées bénéficient également d''efforts de conservation renforcés.</p>',
    'Environnement: initiatives vertes en Iran. Le gouvernement lance des initiatives ambitieuses pour protéger l''environnement.',
    'Dr. Zainab Qajar',
    'publie'
) ON CONFLICT DO NOTHING;
