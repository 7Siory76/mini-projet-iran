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
    
    // Ajouter les articles à la première grille (limite 3)
    const articlesLaUne = articles.slice(0, 3);
    articlesLaUne.forEach(article => {
        const articleHTML = createArticleElement(article);
        grid1.innerHTML += articleHTML;
    });
    
    // Ajouter les articles à la deuxième grille (à partir du 4ème, limite 3)
    const dernierMinute = articles.slice(3, 6);
    if (dernierMinute.length > 0) {
        dernierMinute.forEach(article => {
            const articleHTML = createArticleElement(article);
            grid2.innerHTML += articleHTML;
        });
    } else {
        // Si moins de 6 articles, remplir la 2e section avec les mêmes
        articlesLaUne.forEach(article => {
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
    
    return `
        <article>
            <img src="/images/placeholder.svg" alt="${escapeHtml(article.titre)}" class="article-image">
            <div class="article-content">
                <h2><a href="/articles/${article.slug}">${escapeHtml(article.titre)}</a></h2>
                <div class="article-meta">
                    <span>Publié le ${formattedDate}</span> • <span>Par ${escapeHtml(article.auteur)}</span>
                </div>
                <p class="article-excerpt">
                    ${stripTags(article.contenu).substring(0, 150)}...
                </p>
                <a href="/articles/${article.slug}" class="article-link">Lire la suite →</a>
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
