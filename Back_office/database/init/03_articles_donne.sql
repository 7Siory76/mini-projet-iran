-- Données informatives sur la sécurité et géopolitique en Iran
-- Contenu éducatif et factuel

INSERT INTO articles (titre, slug, contenu, contenu_brut, auteur, statut)
VALUES (
    'Structure géopolitique du Moyen-Orient et rôle stratégique de l''Iran',
    'geopolitique-iran-moyen-orient',
    '<img src="/images/conflict.svg" alt="Géopolitique" style="width:100%;max-width:400px;border-radius:8px;margin-bottom:20px;">
    <h1>Position géopolitique de l''Iran</h1>
    <p>L''Iran occupe une position stratégique cruciale au Moyen-Orient, à la croisée de routes commerciales majeures et de zones d''influence régionales.</p>
    <p><strong>Éléments clés:</strong></p>
    <ul>
    <li>Localisation entre le Golfe Persique et la Mer Caspienne</li>
    <li>Population de plus de 88 millions d''habitants</li>
    <li>Ressources énergétiques parmi les plus importantes au monde</li>
    <li>Influence diplomatique régionale significative</li>
    </ul>
    <p>Cette position explique l''intérêt géopolitique constant des puissances mondiales pour la région.</p>',
    'Structure géopolitique du Moyen-Orient et rôle stratégique de l''Iran. Iran occupe une position stratégique cruciale.',
    'Dr. Amir Rezaei',
    'publie'
) ON CONFLICT DO NOTHING;

INSERT INTO articles (titre, slug, contenu, contenu_brut, auteur, statut)
VALUES (
    'Conflits régionaux et tensions diplomatiques en Iran',
    'conflits-tensions-diplomatiques',
    '<img src="/images/diplomacy.svg" alt="Diplomatie" style="width:100%;max-width:400px;border-radius:8px;margin-bottom:20px;">
    <h1>Tensions régionales et enjeux diplomatiques</h1>
    <p>L''Iran fait face à plusieurs défis géopolitiques complexes impliquant divers acteurs régionaux et internationaux.</p>
    <p><strong>Enjeux principaux:</strong></p>
    <ul>
    <li>Tensions avec les pays du Golfe Persique</li>
    <li>Différends avec les puissances occidentales</li>
    <li>Influence dans les conflits régionaux</li>
    <li>Négociations nucléaires et sanctions internationales</li>
    <li>Alliances stratégiques régionales</li>
    </ul>
    <p>Ces tensions reflètent des intérêts géopolitiques divergents et des questions de sécurité régionale complexes.</p>',
    'Conflits régionaux et tensions diplomatiques en Iran. Iran fait face à plusieurs défis géopolitiques complexes.',
    'Prof. Hassan Motamedi',
    'publie'
) ON CONFLICT DO NOTHING;

INSERT INTO articles (titre, slug, contenu, contenu_brut, auteur, statut)
VALUES (
    'Impact humanitaire des crises: Déplacements de populations et réfugiés',
    'impact-humanitaire-refugies',
    '<img src="/images/humanitarian.svg" alt="Humanitaire" style="width:100%;max-width:400px;border-radius:8px;margin-bottom:20px;">
    <h1>Crise humanitaire et refugiés</h1>
    <p>Les conflits régionaux ont entraîné des déplacements massifs de populations et créé une crise humanitaire significative.</p>
    <p><strong>Données:</strong></p>
    <ul>
    <li>Millions de réfugiés régionaux en Iran (Irak, Afghanistan, Syrie)</li>
    <li>Accès limité à l''eau potable dans certaines régions</li>
    <li>Défis d''accès aux services de santé</li>
    <li>Besoins alimentaires urgents</li>
    <li>Éducation des enfants déracinés</li>
    </ul>
    <p>Les organisations humanitaires internationales travaillent pour fournir une aide d''urgence aux populations vulnérables.</p>',
    'Impact humanitaire des crises: Déplacements de populations et réfugiés. Conflits régionaux ont entraîné des déplacements.',
    'Dr. Farida Tehrani',
    'publie'
) ON CONFLICT DO NOTHING;

INSERT INTO articles (titre, slug, contenu, contenu_brut, auteur, statut)
VALUES (
    'Sanctions économiques: Impacts sur le développement',
    'sanctions-economiques-iran',
    '<img src="/images/economy.svg" alt="Sanctions" style="width:100%;max-width:400px;border-radius:8px;margin-bottom:20px;">
    <h1>Sanctions internationales et économie iranienne</h1>
    <p>Les sanctions économiques imposées à l''Iran ont des répercussions profondes sur l''économie et la vie quotidienne.</p>
    <p><strong>Effets des sanctions:</strong></p>
    <ul>
    <li>Restrictions sur les exportations pétrolières</li>
    <li>Limitations des échanges commerciaux</li>
    <li>Défis d''accès aux technologies modernes</li>
    <li>Inflation et instabilité monétaire</li>
    <li>Impact sur les secteurs: santé, éducation, industrie</li>
    </ul>
    <p>Ces mesures affectent l''économie mais aussi les populations vulnérables et la qualité de vie.</p>',
    'Sanctions économiques: Impacts sur le développement. Sanctions économiques imposées à l''Iran.',
    'Dr. Karim Sharifi',
    'publie'
) ON CONFLICT DO NOTHING;

INSERT INTO articles (titre, slug, contenu, contenu_brut, auteur, statut)
VALUES (
    'Forces militaires régionales et équilibre des pouvoirs',
    'forces-militaires-equilibre',
    '<img src="/images/military.svg" alt="Équilibre régional" style="width:100%;max-width:400px;border-radius:8px;margin-bottom:20px;">
    <h1>Capacités militaires et équilibre régional</h1>
    <p>L''équilibre militaire au Moyen-Orient joue un rôle crucial dans la dynamique régionale et la stabilité.</p>
    <p><strong>Acteurs clés:</strong></p>
    <ul>
    <li>Forces conventionnelles régionales</li>
    <li>Programmes de modernisation militaire</li>
    <li>Participation à des coalitions régionales</li>
    <li>Équilibre des forces et dissuasion</li>
    <li>Implications pour la sécurité locale et internationale</li>
    </ul>
    <p>Cet équilibre délicat affecte les relations entre nations et les perspectives de stabilité régionale.</p>',
    'Forces militaires régionales et équilibre des pouvoirs. Équilibre militaire au Moyen-Orient.',
    'General Reza Ahmadi (Ret.)',
    'publie'
) ON CONFLICT DO NOTHING;

INSERT INTO articles (titre, slug, contenu, contenu_brut, auteur, statut)
VALUES (
    'Technologie et cybersécurité dans les conflits modernes',
    'technologie-cybersecurite',
    '<img src="/images/cyber.svg" alt="Cybersécurité" style="width:100%;max-width:400px;border-radius:8px;margin-bottom:20px;">
    <h1>La dimension technologique des conflits modernes</h1>
    <p>Les conflits contemporains ne se limitent plus au domaine physique: la guerre technologique et la cybersécurité jouent un rôle croissant.</p>
    <p><strong>Aspects clés:</strong></p>
    <ul>
    <li>Cyber-attaques et défense informatique</li>
    <li>Désinformation et guerre informationnelle</li>
    <li>Surveillance et renseignement numériques</li>
    <li>Vulnérabilités des infrastructures critiques</li>
    <li>Enjeux de souveraineté numérique</li>
    </ul>
    <p>L''Iran fait face à des menaces cyber significatives et investit également dans ses capacités de défense numérique.</p>',
    'Technologie et cybersécurité dans les conflits modernes. Conflits contemporains impliquent la dimension technologique.',
    'Dr. Farhad Tehrani',
    'publie'
) ON CONFLICT DO NOTHING;

INSERT INTO articles (titre, slug, contenu, contenu_brut, auteur, statut)
VALUES (
    'Droits humains et libertés civiles: Enjeux en Iran',
    'droits-humains-libertés',
    '<img src="/images/rights.svg" alt="Droits Humains" style="width:100%;max-width:400px;border-radius:8px;margin-bottom:20px;">
    <h1>Situation des droits humains en Iran</h1>
    <p>Les libertés civiles et les droits fondamentaux demeurent un sujet de préoccupation majeure pour les organisations internationales.</p>
    <p><strong>Points de focus:</strong></p>
    <ul>
    <li>Liberté d''expression et de presse</li>
    <li>Droits des minorités</li>
    <li>Justice pénale et système judiciaire</li>
    <li>Droits des femmes</li>
    <li>Liberté d''association et de rassemblement</li>
    </ul>
    <p>Les organisations des Nations Unies et internationales supervisent l''évolution de ces questions critiques.</p>',
    'Droits humains et libertés civiles: Enjeux en Iran. Libertés civiles et droits fondamentaux.',
    'Amnesty Iran',
    'publie'
) ON CONFLICT DO NOTHING;

INSERT INTO articles (titre, slug, contenu, contenu_brut, auteur, statut)
VALUES (
    'Reconstruction post-conflit: Défis et perspectives',
    'reconstruction-post-conflit',
    '<img src="/images/reconstruction.svg" alt="Reconstruction" style="width:100%;max-width:400px;border-radius:8px;margin-bottom:20px;">
    <h1>Reconstruction et stabilité régionale</h1>
    <p>Après les décennies de tensions, la reconstruction et la stabilité deviennent des priorités stratégiques pour la région.</p>
    <p><strong>Priorités de reconstruction:</strong></p>
    <ul>
    <li>Restauration des infrastructures</li>
    <li>Retour et intégration des réfugiés</li>
    <li>Relance économique et emploi</li>
    <li>Réconciliation et coexistence</li>
    <li>Investissements internationaux et partenariats</li>
    </ul>
    <p>Ces efforts nécessitent une coopération régionale et internationale soutenue pour assurer une paix durable.</p>',
    'Reconstruction post-conflit: Défis et perspectives. Reconstruction et stabilité régionale.',
    'Dr. Saeed Khalili',
    'publie'
) ON CONFLICT DO NOTHING;

INSERT INTO articles (titre, slug, contenu, contenu_brut, auteur, statut)
VALUES (
    'Diplomatie et négociations: Vers la résolution des conflits',
    'diplomatie-negotiations',
    '<img src="/images/peace.svg" alt="Paix" style="width:100%;max-width:400px;border-radius:8px;margin-bottom:20px;">
    <h1>Efforts diplomatiques pour la paix</h1>
    <p>La diplomatie multilatérale et les négociations jouent un rôle central dans la résolution des tensions régionales et la promotion de la stabilité.</p>
    <p><strong>Canaux diplomatiques clés:</strong></p>
    <ul>
    <li>Organismes internationaux (ONU, Ligue arabe)</li>
    <li>Accords bilatéraux et multilatéraux</li>
    <li>Initiatives de médiation régionale</li>
    <li>Conférences de paix et pourparlers</li>
    <li>Organisations régionales et alliances</li>
    </ul>
    <p>Les efforts pour trouver des solutions pacifiques aux litiges régionaux restent essentiels pour la stabilité et la prospérité future.</p>',
    'Diplomatie et négociations: Vers la résolution des conflits. Diplomatie multilatérale pour la paix.',
    'Ambassador Hassan Rouhani',
    'publie'
) ON CONFLICT DO NOTHING;

INSERT INTO articles (titre, slug, contenu, contenu_brut, auteur, statut)
VALUES (
    'Ressources naturelles et enjeux environnementaux en temps de conflit',
    'ressources-environnement-conflit',
    '<img src="/images/environment.svg" alt="Environnement" style="width:100%;max-width:400px;border-radius:8px;margin-bottom:20px;">
    <h1>Environnement et ressources naturelles</h1>
    <p>Les conflits et les tensions géopolitiques ont des impacts significatifs sur l''environnement et la gestion des ressources naturelles.</p>
    <p><strong>Enjeux environnementaux:</strong></p>
    <ul>
    <li>Pollution et dommages environnementaux des conflits</li>
    <li>Gestion des ressources eau et pétrole</li>
    <li>Émissions de carbone et changement climatique</li>
    <li>Conservation de la biodiversité</li>
    <li>Énergies renouvelables comme alternative</li>
    </ul>
    <p>La stabilité environnementale reste cruciale pour la durabilité économique et la qualité de vie de la population.</p>',
    'Ressources naturelles et enjeux environnementaux en temps de conflit. Conflits ont des impacts sur l''environnement.',
    'Dr. Nasrin Azadi',
    'publie'
) ON CONFLICT DO NOTHING;
