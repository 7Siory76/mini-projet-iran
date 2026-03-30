/**
 * Script pour afficher les détails d'un article
 */

const API_BASE = 'http://localhost:8080/api';

document.addEventListener('DOMContentLoaded', function () {
    loadArticleDetail();
});

function getArticleIdFromURL() {
    const params = new URLSearchParams(window.location.search);
    return params.get('id');
}

async function loadArticleDetail() {
    const articleId = getArticleIdFromURL();
    
    if (!articleId) {
        showError();
        return;
    }

    try {
        console.log('Chargement article ID:', articleId);
        const response = await fetch(`${API_BASE}/article-detail.php?id=${articleId}`);
        
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
    
    // Remplir les informations
    document.getElementById('article-title').textContent = article.titre;
    document.getElementById('article-body').innerHTML = article.contenu;
    document.getElementById('article-author').textContent = `Par ${escapeHtml(article.auteur)}`;
    document.getElementById('article-date').textContent = `Publié le ${formatDate(article.date_creation)}`;
    
    // Mettre à jour le titre de la page
    document.title = `${article.titre} - Iran Actualités`;
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
