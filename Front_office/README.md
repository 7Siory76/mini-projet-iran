# Front Office - Iran Actualités

Site public pour la consultation des actualités d'Iran.

## Structure

```
Front_office/
├── index.html          # Page principale
├── css/
│   └── style.css       # Styles globaux
├── js/
│   └── main.js         # Scripts principaux
├── images/             # Dossier pour les images
└── README.md           # Documentation
```

## Caractéristiques

### Architecture Sémantique
- `<header>` : En-tête avec titre unique `<h1>`
- `<nav>` : Navigation principale
- `<main>` : Contenu principal
  - Section "Articles À la une"
  - Section "Dernière minute"
- `<footer>` : Pied de page avec liens

### Optimisation SEO
- Un seul `<h1>` pour le titre du site
- `<h2>` pour les titres d'articles
- Meta tags essentiels (description, viewport, keywords, author)
- Emplacements prévus pour favicon et apple-touch-icon

### Design Responsive
- Grille d'articles responsive (CSS Grid)
- Mobile-first approach
- Breakpoints: 768px, 480px
- Inspiré de CNews

## API Integration

Le frontend est conçu pour consommer une API backend :

```
GET /api/articles           # Récupérer tous les articles
GET /api/articles/:id       # Récupérer un article
GET /api/articles/latest    # Articles récents
```

## Installation

Aucune dépendance externe - HTML/CSS/JS pur.

### Servir localement

```bash
# Avec Python
python -m http.server 8000

# Avec Node.js (http-server)
npx http-server -p 8000

# Avec PHP
php -S localhost:8000
```

Accéder à `http://localhost:8000`

## Images

Créer un dossier `images/` avec les fichiers suivants :
- `favicon.ico` - Icône du site
- `apple-touch-icon.png` - Icône pour appareils Apple
- `article-1.jpg`, `article-2.jpg`, `article-3.jpg`
- `breaking-1.jpg`, `breaking-2.jpg`, `breaking-3.jpg`

## Améliorations Futures

- [ ] Intégration API dynamique
- [ ] Système de recherche
- [ ] Filtraje par catégorie
- [ ] Pagination
- [ ] Système de commentaires
- [ ] Newsletter
- [ ] Dark mode
- [ ] Multilangue

## Lien avec le Backend

Le front office doit être servit en parallèle du back office :

**Back Office** (Admin) : `http://localhost:8080` (port 8080)
**Front Office** (Public) : `http://localhost:3000` (port 3000)

Le front office consommera l'API du back office :
- API URL : `http://localhost:8080/api`
