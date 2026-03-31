/**
 * Script pour afficher les détails d'un article
 */

const API_BASE = 'http://localhost:8080/api';

document.addEventListener('DOMContentLoaded', function () {
    loadArticleDetail();
});

function getArticleIdentifier() {
    // Support quatre formats:
    // 1. /YYYY/MM/DD/slug-article (format Euronews - prioritaire)
    // 2. /actualite/slug-article (ancien format SEO)
    // 3. article-XX.html (ancien format SEO)
    // 4. detail.html?slug=slug ou detail.html?id=XX
    
    // Format 1: /YYYY/MM/DD/slug-article (ex: /2026/03/30/iran-developpements-politiques)
    const dateSlugMatch = window.location.pathname.match(/\/(\d{4})\/(\d{2})\/(\d{2})\/([a-z0-9-]+)\/?$/);
    if (dateSlugMatch) {
        return { type: 'slug', value: dateSlugMatch[4] };
    }
    
    // Format 2: /actualite/slug-article
    const slugMatch = window.location.pathname.match(/\/actualite\/([a-z0-9-]+)\/?$/);
    if (slugMatch) {
        return { type: 'slug', value: slugMatch[1] };
    }
    
    // Format 3: article-XX.html
    const idMatch = window.location.pathname.match(/article-(\d+)\.html/);
    if (idMatch) {
        return { type: 'id', value: idMatch[1] };
    }
    
    // Format 4: Query params (slug prioritaire)
    const params = new URLSearchParams(window.location.search);
    const slug = params.get('slug');
    if (slug) {
        return { type: 'slug', value: slug };
    }
    
    const id = params.get('id');
    if (id) {
        return { type: 'id', value: id };
    }
    
    return null;
}

async function loadArticleDetail() {
    const identifier = getArticleIdentifier();
    
    if (!identifier) {
        showError();
        return;
    }

    try {
        const apiParam = identifier.type === 'slug' ? `slug=${identifier.value}` : `id=${identifier.value}`;
        console.log(`Chargement article (${identifier.type}):`, identifier.value);
        
        const response = await fetch(`${API_BASE}/article-detail.php?${apiParam}`);
        
        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('Données article:', data);
        
        if (data.success && data.data) {
            displayArticleDetail(data.data);
        } else {
            showError();
        }
    } catch (error) {
        console.error('Erreur lors du chargement:', error);
        showError();
    }
}

function displayArticleDetail(article) {
    // Masquer le loading et afficher l'article
    document.getElementById('article-loading').style.display = 'none';
    document.getElementById('article-content').style.display = 'block';
    
    // Remplir les informations de l'article
    document.getElementById('article-title').textContent = article.titre;
    document.getElementById('article-body').innerHTML = article.contenu;
    document.getElementById('article-author').textContent = `Par ${escapeHtml(article.auteur)}`;
    document.getElementById('article-date').textContent = `Publié le ${formatDate(article.date_creation)}`;
    
    // Mettre à jour le titre de la page
    document.title = `${article.titre} - Iran Actualités`;
    
    // Mettre à jour les meta tags pour le SEO
    updateMetaTags(article);
}

function updateMetaTags(article) {
    // Description meta
    const excerpt = stripTags(article.contenu).substring(0, 160);
    updateOrCreateMetaTag('description', excerpt);
    
    // Open Graph tags
    updateOrCreateMetaTag('og:title', article.titre, 'property');
    updateOrCreateMetaTag('og:description', excerpt, 'property');
    updateOrCreateMetaTag('og:url', window.location.href, 'property');
    
    // Twitter Card tags
    updateOrCreateMetaTag('twitter:title', article.titre);
    updateOrCreateMetaTag('twitter:description', excerpt);
}

function updateOrCreateMetaTag(name, content, type = 'name') {
    let tag = document.querySelector(`meta[${type}="${name}"]`);
    if (!tag) {
        tag = document.createElement('meta');
        tag.setAttribute(type, name);
        document.head.appendChild(tag);
    }
    tag.setAttribute('content', content);
}

function showError() {
    document.getElementById('article-loading').style.display = 'none';
    document.getElementById('article-error').style.display = 'block';
}

function formatDate(dateString) {
    const date = new Date(dateString);
    const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
    return date.toLocaleDateString('fr-FR', options);
}

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

function stripTags(html) {
    const tmp = document.createElement('DIV');
    tmp.innerHTML = html;
    return tmp.textContent || tmp.innerText || '';
}
