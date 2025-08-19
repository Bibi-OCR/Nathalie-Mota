/**
 * Lightbox 
 * Gestion de l'affichage des photos en plein écran avec navigation
 */

class NathalieLightbox {
    constructor() {
        this.currentIndex = 0;
        this.photos = [];
        this.isOpen = false;
        this.init();
    }

    init() {
        // Attacher les événements
        this.attachEvents();
        
        // Collecter toutes les photos disponibles
        this.collectPhotos();
        
        console.log("✅ Lightbox initialisée avec", this.photos.length, "photos");
    }

    attachEvents() {
        // Événements pour ouvrir la lightbox
        document.addEventListener('click', (e) => {
            const fullscreenIcon = e.target.closest('.icon-fullscreen');
            if (fullscreenIcon) {
                e.preventDefault();
                this.openFromIcon(fullscreenIcon);
            }
        });

        // Événements de la lightbox (délégation d'événements)
        document.addEventListener('click', (e) => {
            const lightbox = document.getElementById('nathalie-lightbox');
            if (!lightbox || !this.isOpen) return;

            // Fermeture
            if (e.target.classList.contains('lightbox-close') || 
                e.target.closest('.lightbox-close') ||
                e.target.classList.contains('lightbox-overlay')) {
                this.close();
            }

            // Navigation
            if (e.target.classList.contains('lightbox-prev') || 
                e.target.closest('.lightbox-prev')) {
                this.prev();
            }

            if (e.target.classList.contains('lightbox-next') || 
                e.target.closest('.lightbox-next')) {
                this.next();
            }
        });

        // Événements clavier
        document.addEventListener('keydown', (e) => {
            if (!this.isOpen) return;

            switch(e.key) {
                case 'Escape':
                    this.close();
                    break;
                case 'ArrowLeft':
                    e.preventDefault();
                    this.prev();
                    break;
                case 'ArrowRight':
                    e.preventDefault();
                    this.next();
                    break;
            }
        });

        // Empêcher le scroll du body quand la lightbox est ouverte
        document.addEventListener('wheel', (e) => {
            if (this.isOpen) {
                e.preventDefault();
            }
        }, { passive: false });

        // Empêcher le scroll tactile sur mobile
        document.addEventListener('touchmove', (e) => {
            if (this.isOpen) {
                e.preventDefault();
            }
        }, { passive: false });
    }

    collectPhotos() {
        // Collecter toutes les photos avec icône fullscreen
        const photoBlocks = document.querySelectorAll('.icon-fullscreen');
        this.photos = [];

        photoBlocks.forEach((icon, index) => {
            const photoData = {
                src: icon.getAttribute('data-full-src') || '',
                reference: icon.getAttribute('data-reference') || '',
                category: icon.getAttribute('data-category') || '',
                title: icon.getAttribute('data-title') || this.getPhotoTitle(icon),
                permalink: icon.getAttribute('data-permalink') || '#',
                element: icon
            };

            // Ajouter un attribut data-lightbox-index pour référence
            icon.setAttribute('data-lightbox-index', index);
            
            this.photos.push(photoData);
        });
    }

    getPhotoTitle(iconElement) {
        // Essayer de trouver le titre de la photo
        const photoBlock = iconElement.closest('.photo-block, .photo-item, article');
        if (photoBlock) {
            // Chercher dans différents endroits possibles
            const titleElement = photoBlock.querySelector('h1, h2, h3, .photo-title, .post-title');
            if (titleElement) {
                return titleElement.textContent.trim();
            }

            // Essayer avec l'attribut alt de l'image
            const img = photoBlock.querySelector('img');
            if (img && img.alt) {
                return img.alt;
            }
        }

        return 'Photo sans titre';
    }

    openFromIcon(iconElement) {
        const index = parseInt(iconElement.getAttribute('data-lightbox-index')) || 0;
        this.open(index);
    }

    open(index = 0) {
        if (this.photos.length === 0) {
            console.warn('Aucune photo disponible pour la lightbox');
            return;
        }

        this.currentIndex = Math.max(0, Math.min(index, this.photos.length - 1));
        this.isOpen = true;

        const lightbox = document.getElementById('nathalie-lightbox');
        if (!lightbox) {
            console.error('Lightbox HTML non trouvée dans le DOM');
            return;
        }

        // Bloquer le scroll du body
        document.body.style.overflow = 'hidden';

        // Afficher la lightbox
        lightbox.classList.add('active');

        // Charger la photo
        this.loadPhoto(this.currentIndex);

        // Focus sur la lightbox pour l'accessibilité
        lightbox.focus();
    }

    close() {
        this.isOpen = false;
        const lightbox = document.getElementById('nathalie-lightbox');
        
        if (lightbox) {
            lightbox.classList.remove('active');
        }

        // Rétablir le scroll du body
        document.body.style.overflow = '';
        
        // Nettoyer l'image pour économiser la mémoire
        const img = lightbox.querySelector('.lightbox-image');
        if (img) {
            img.src = '';
        }
    }

    loadPhoto(index) {
        if (!this.photos[index]) return;

        const photo = this.photos[index];
        const lightbox = document.getElementById('nathalie-lightbox');
        const img = lightbox.querySelector('.lightbox-image');
        const loading = lightbox.querySelector('.lightbox-loading');

        // Afficher le spinner de chargement
        loading.style.display = 'flex';
        img.style.opacity = '0';

        // Charger l'image
        const newImg = new Image();
        newImg.onload = () => {
            img.src = newImg.src;
            img.alt = photo.title || 'Photo';
            loading.style.display = 'none';
            img.style.opacity = '1';
        };

        newImg.onerror = () => {
            console.error('Erreur lors du chargement de l\'image:', photo.src);
            loading.style.display = 'none';
            img.style.opacity = '1';
            
            // Image de fallback en SVG
            img.src = 'data:image/svg+xml;base64,' + btoa(`
                <svg width="400" height="300" xmlns="http://www.w3.org/2000/svg">
                    <rect width="400" height="300" fill="#f3f4f6"/>
                    <text x="200" y="150" font-family="Arial" font-size="16" text-anchor="middle" fill="#9ca3af">
                        Image non disponible
                    </text>
                </svg>
            `);
        };

        // Vérifier si l'URL de l'image est valide
        if (photo.src && photo.src !== '') {
            newImg.src = photo.src;
        } else {
            console.warn('URL d\'image vide pour la photo:', photo.title);
            newImg.onerror();
        }
    }

    next() {
        if (this.photos.length <= 1) return;

        this.currentIndex = (this.currentIndex + 1) % this.photos.length;
        this.loadPhoto(this.currentIndex);
    }

    prev() {
        if (this.photos.length <= 1) return;

        this.currentIndex = (this.currentIndex - 1 + this.photos.length) % this.photos.length;
        this.loadPhoto(this.currentIndex);
    }

    // Méthode publique pour rafraîchir la collection de photos
    refresh() {
        this.collectPhotos();
        console.log("✅ Photos rafraîchies:", this.photos.length, "photos disponibles");
    }

    // Méthode publique pour obtenir des infos de debug
    getDebugInfo() {
        return {
            photosCount: this.photos.length,
            currentIndex: this.currentIndex,
            isOpen: this.isOpen,
            photos: this.photos.map(p => ({
                title: p.title,
                src: p.src,
                reference: p.reference,
                category: p.category
            }))
        };
    }
}

// Initialiser la lightbox au chargement du DOM
document.addEventListener('DOMContentLoaded', function() {
    // Attendre un peu que toutes les images soient chargées
    setTimeout(() => {
        window.nathalieLightbox = new NathalieLightbox();
        
        // Debug en mode développement
        if (window.location.hostname === 'localhost' || window.location.hostname.includes('dev')) {
            console.log('Debug Lightbox:', window.nathalieLightbox.getDebugInfo());
        }
    }, 200);
});

// Rafraîchir après les requêtes AJAX (pour les photos chargées dynamiquement)
document.addEventListener('photosLoaded', function() {
    if (window.nathalieLightbox) {
        window.nathalieLightbox.refresh();
    }
});

// Export pour utilisation externe si nécessaire
if (typeof module !== 'undefined' && module.exports) {
    module.exports = NathalieLightbox;
}