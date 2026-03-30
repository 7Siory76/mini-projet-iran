/**
 * Main JavaScript for Iran Actualités Frontend
 * =============================================
 */

// Le JavaScript s'exécute dans le navigateur du CLIENT
// Donc on utilise localhost (pas host.docker.internal)
const API_URL = 'http://localhost:8080/api/articles.php';

// Initialisation
document.addEventListener('DOMContentLoaded', function () {
    console.log('Iran Actualités Frontend loaded');
    initNavigation();
    loadArticles();
});

/**
 * Initialise la navigation active
 */
function initNavigation() {
    const navLinks = document.querySelectorAll('nav a');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            // Retirer la classe active de tous les liens
            navLinks.forEach(a => a.classList.remove('active'));
            
            // Ajouter la classe active au lien cliqué
            this.classList.add('active');
        });
    });
}

/**
 * Charge et affiche les articles depuis l'API
 */
async function loadArticles() {
    try {
        console.log('Chargement des articles depuis:', API_URL);
        const response = await fetch(API_URL);
        
        console.log('Réponse reçue:', response.status);
        
        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('Données reçues:', data);
        
        if (data.success && data.data.length > 0) {
            displayArticles(data.data);
        } else {
            console.warn('Aucun article trouvé');
            displayNoArticlesMessage();
        }
    } catch (error) {
        console.error('Erreur lors du chargement des articles:', error);
        displayErrorMessage();
    }
}

/**
 * Mappe les slugs aux images correspondantes
 */
function getArticleImage(slug) {
    const imageMap = {
        'bienvenue-blog': 'bienvenue.svg',
        'iran-developpements-politiques': 'politique.svg',
        'economie-iran-2026': 'economie.svg',
        'patrimoine-culturel-iran': 'culture.svg',
        'technologie-innovation-iran': 'technologie.svg',
        'sports-champions-iran': 'sports.svg',
        'prix-petrole-iran': 'petrole.svg',
        'tourisme-iran-destination': 'tourisme.svg',
        'education-reformes-universites': 'education.svg',
        'environnement-initiatives-vertes': 'environnement.svg'
    };
    return imageMap[slug] ? `/images/${imageMap[slug]}` : '/images/placeholder.svg';
}

/**
 * Affiche les articles dynamiquement dans les deux sections
 */
function displayArticles(articles) {
    // Remplir la première grille "Articles À la une"
    const grid1 = document.querySelector('#articles-la-une .articles-grid');
    // Remplir la deuxième grille "Dernière minute"
    const grid2 = document.querySelector('#derniere-minute .articles-grid');
    
    if (!grid1 || !grid2) {
        console.error('Grilles articles non trouvées');
        return;
    }
    
    // Vider les grilles
    grid1.innerHTML = '';
    grid2.innerHTML = '';
    
    // Afficher TOUS les articles publiés
    const articlesLaUne = articles.slice(0, 3);
    articlesLaUne.forEach(article => {
        const articleHTML = createArticleElement(article);
        grid1.innerHTML += articleHTML;
    });
    
    // Afficher le reste dans la 2e section
    const dernierMinute = articles.slice(3);
    if (dernierMinute.length > 0) {
        dernierMinute.forEach(article => {
            const articleHTML = createArticleElement(article);
            grid2.innerHTML += articleHTML;
        });
    }
    
    console.log(`${articles.length} articles affichés`);
}

/**
 * Crée l'élément HTML d'un article
 */
function createArticleElement(article) {
    const formattedDate = formatDate(article.date_creation);
    const articleURL = getArticleDateURL(article);
    const articleImage = getArticleImage(article.slug);
    const altText = `${escapeHtml(article.titre)} - Publié par ${escapeHtml(article.auteur)}`;
    
    return `
        <article>
            <img src="${articleImage}" alt="${altText}" class="article-image">
            <div class="article-content">
                <h3><a href="${articleURL}">${escapeHtml(article.titre)}</a></h3>
                <div class="article-meta">
                    <span>Publié le ${formattedDate}</span> • <span>Par ${escapeHtml(article.auteur)}</span>
                </div>
                <p class="article-excerpt">
                    ${stripTags(article.contenu).substring(0, 150)}...
                </p>
                <a href="${articleURL}" class="article-link">Lire la suite →</a>
            </div>
        </article>
    `;
}

/**
 * Affiche un message d'erreur
 */
function displayErrorMessage() {
    const grid = document.querySelector('#articles-la-une .articles-grid');
    if (grid) {
        grid.innerHTML = '<p style="grid-column: 1/-1; text-align: center; color: #999;">Erreur lors du chargement des articles. Vérifiez la connexion à l\'API.</p>';
    }
}

/**
 * Affiche un message quand pas d'articles
 */
function displayNoArticlesMessage() {
    const grid = document.querySelector('#articles-la-une .articles-grid');
    if (grid) {
        grid.innerHTML = '<p style="grid-column: 1/-1; text-align: center; color: #999;">Aucun article publié pour le moment.</p>';
    }
}

/**
 * Format date helper
 */
function formatDate(dateString) {
    const date = new Date(dateString);
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return date.toLocaleDateString('fr-FR', options);
}

/**
 * Génère l'URL complète d'un article au format YYYY/MM/DD/slug
 * Exemple: /2026/03/30/iran-developpements-politiques
 */
function getArticleDateURL(article) {
    const date = new Date(article.date_creation);
    
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0'); // +1 car getMonth() retourne 0-11
    const day = String(date.getDate()).padStart(2, '0');
    
    return `/${year}/${month}/${day}/${article.slug}`;
}

/**
 * Supprime les balises HTML
 */
function stripTags(html) {
    const tmp = document.createElement('DIV');
    tmp.innerHTML = html;
    return tmp.textContent || tmp.innerText || '';
}

/**
 * Échappe les caractères HTML
 */
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}
