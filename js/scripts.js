document.addEventListener('DOMContentLoaded', function () {
    // =======================
    // MODALE CONTACT
    // =======================
    const modal = document.getElementById('contact-modal');
    const footer = document.querySelector('footer');
    const closeBtn = modal ? modal.querySelector('.modal-close') : null;
    const openBtns = document.querySelectorAll('[data-modal="contact"]');
    const singleOpenBtn = document.querySelector('.contact-button');

    function openModal() {
        if (modal && !modal.classList.contains('active')) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeModal() {
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    // Ouverture via tous les boutons data-modal
    openBtns.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            openModal();
        });
    });

    // Ouverture via un seul bouton avec préremplissage
    if (singleOpenBtn) {
        singleOpenBtn.addEventListener('click', function (e) {
            e.preventDefault();

            // Récupérer la référence depuis la photo
            const photoElem = document.querySelector('.photo');
            const refPhotoValue = photoElem ? photoElem.getAttribute('data-ref') : '';

            // Préremplir le champ REF PHOTO
            const refInput = modal.querySelector('input[name="email-2"]');
            if (refInput) refInput.value = refPhotoValue;

            openModal();
        });
    }

    // Fermeture
    if (closeBtn) closeBtn.addEventListener('click', closeModal);
    if (modal) {
        modal.addEventListener('click', function (e) {
            if (e.target === modal) closeModal();
        });
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal && modal.classList.contains('active')) {
            closeModal();
        }
    });

    // Ouverture automatique quand le footer est visible
    function checkFooterVisible() {
        if (!footer) return;
        const rect = footer.getBoundingClientRect();
        const windowHeight = window.innerHeight || document.documentElement.clientHeight;
        if (rect.top <= windowHeight && rect.bottom >= 0) {
            openModal();
            window.removeEventListener('scroll', checkFooterVisible);
        }
    }
    window.addEventListener('scroll', checkFooterVisible);

    console.log("✅ Modale JS chargée et connectée");

    // =======================
    // LOAD MORE PHOTOS
    // =======================
    const loadMoreBtn = document.getElementById('load-more-photos');
    const photoGrid = document.getElementById('photo-grid');

    console.log("Bouton Load More :", loadMoreBtn);
    console.log("Grille photo :", photoGrid);

    if (loadMoreBtn && photoGrid) {
        loadMoreBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Désactiver le bouton pendant le chargement
            loadMoreBtn.disabled = true;
            loadMoreBtn.textContent = 'Chargement...';
            
            let currentPage = parseInt(loadMoreBtn.getAttribute('data-page')) || 1;
            let nextPage = currentPage + 1;
            
            console.log("Chargement de la page :", nextPage);

            // Utiliser FormData pour une requête POST plus robuste
            const formData = new FormData();
            formData.append('action', 'load_more_photos');
            formData.append('page', nextPage);

            fetch(wp_ajax_object.ajax_url, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau : ' + response.status);
                }
                return response.text();
            })
            .then(data => {
                console.log("Data reçue AJAX :", data);
                
                // Supprimer les espaces et vérifier si il y a du contenu
                const trimmedData = data.trim();
                
                if (trimmedData === '' || trimmedData === '0') {
                    // Plus de photos à charger
                    loadMoreBtn.style.display = 'none';
                    console.log("Plus de photos à charger");
                } else {
                    // Ajouter les nouvelles photos à la grille
                    photoGrid.insertAdjacentHTML('beforeend', trimmedData);
                    loadMoreBtn.setAttribute('data-page', nextPage);
                    
                    // Réactiver le bouton
                    loadMoreBtn.disabled = false;
                    loadMoreBtn.textContent = 'Charger plus';
                    
                    console.log("Photos ajoutées, page actuelle :", nextPage);
                }
            })
            .catch(error => {
                console.error("Erreur AJAX :", error);
                
                // Réactiver le bouton en cas d'erreur
                loadMoreBtn.disabled = false;
                loadMoreBtn.textContent = 'Charger plus';
                
                // Optionnel : afficher un message d'erreur à l'utilisateur
                alert('Erreur lors du chargement des photos. Veuillez réessayer.');
            });
        });
    } else {
        console.warn("Éléments Load More non trouvés dans le DOM");
    }
});