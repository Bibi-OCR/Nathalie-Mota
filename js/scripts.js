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

// =======================
    // DROPDOWNS PERSONNALISÉS
    // =======================
    
    // Variables pour gérer l'état des filtres
    let currentFilters = {
        category: '',
        format: '',
        sort: 'desc',
        page: 1
    };

    // Initialiser les dropdowns personnalisés
    function initCustomSelects() {
        const customSelects = document.querySelectorAll('.custom-select');
        
        customSelects.forEach(function(customSelect) {
            const selected = customSelect.querySelector('.select-selected');
            const items = customSelect.querySelector('.select-items');
            const options = items.querySelectorAll('div');
            
            // Clic sur le dropdown pour l'ouvrir/fermer
            selected.addEventListener('click', function(e) {
                e.stopPropagation();
                closeAllSelect(customSelect);
                items.classList.toggle('select-hide');
                selected.classList.toggle('select-arrow-active');
            });
            
            // Clic sur une option
            options.forEach(function(option) {
                option.addEventListener('click', function(e) {
                    e.stopPropagation();
                    
                    const value = this.getAttribute('data-value');
                    const text = this.textContent;
                    const filterType = customSelect.getAttribute('data-filter');
                    
                    // Mettre à jour l'affichage
                    selected.textContent = text;
                    
                    // Supprimer la classe "same-as-selected" de toutes les options
                    options.forEach(opt => opt.classList.remove('same-as-selected'));
                    
                    // Ajouter la classe à l'option sélectionnée
                    this.classList.add('same-as-selected');
                    
                    // Mettre à jour le filtre
                    currentFilters[filterType] = value;
                    currentFilters.page = 1;
                    
                    // Charger les photos filtrées
                    loadFilteredPhotos(false);
                    
                    // Fermer le dropdown
                    items.classList.add('select-hide');
                    selected.classList.remove('select-arrow-active');
                });
            });
        });
    }
    
    // Fermer tous les dropdowns sauf celui passé en paramètre
    function closeAllSelect(exceptThis) {
        const items = document.querySelectorAll('.select-items');
        const selected = document.querySelectorAll('.select-selected');
        
        items.forEach(function(item, index) {
            if (exceptThis !== item.parentElement) {
                item.classList.add('select-hide');
                selected[index].classList.remove('select-arrow-active');
            }
        });
    }
    
    // Fermer tous les dropdowns en cliquant ailleurs
    document.addEventListener('click', function() {
        closeAllSelect();
    });
    
    // Fonction pour afficher/masquer le spinner
    function toggleLoading(show) {
        const loadingSpinner = document.getElementById('loading-spinner');
        const loadMoreBtn = document.getElementById('load-more-photos');
        
        if (loadingSpinner) {
            loadingSpinner.style.display = show ? 'flex' : 'none';
        }
        if (loadMoreBtn) {
            loadMoreBtn.disabled = show;
            loadMoreBtn.textContent = show ? 'Chargement...' : 'Charger plus';
        }
    }
    
    // Fonction pour charger les photos avec filtres
    function loadFilteredPhotos(isLoadMore = false) {
        const photoGrid = document.getElementById('photo-grid');
        if (!photoGrid) return;

        toggleLoading(true);

        const formData = new FormData();
        formData.append('action', 'filter_photos');
        formData.append('category', currentFilters.category);
        formData.append('format', currentFilters.format);
        formData.append('sort', currentFilters.sort);
        formData.append('page', currentFilters.page);

        fetch(wp_ajax_object.ajax_url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            const loadMoreBtn = document.getElementById('load-more-photos');
            
            if (data.html) {
                if (isLoadMore) {
                    photoGrid.insertAdjacentHTML('beforeend', data.html);
                } else {
                    photoGrid.innerHTML = data.html;
                }

                if (loadMoreBtn) {
                    if (data.has_more) {
                        loadMoreBtn.style.display = 'block';
                        loadMoreBtn.setAttribute('data-page', data.current_page);
                    } else {
                        loadMoreBtn.style.display = 'none';
                    }
                }
            } else {
                if (!isLoadMore) {
                    photoGrid.innerHTML = '<p>Aucune photo trouvée pour ces critères.</p>';
                }
                if (loadMoreBtn) {
                    loadMoreBtn.style.display = 'none';
                }
            }

            toggleLoading(false);
        })
        .catch(error => {
            console.error('Erreur lors du filtrage:', error);
            toggleLoading(false);
        });
    }
    
    // Modifier le bouton "Charger plus"
    const loadMoreBtn = document.getElementById('load-more-photos');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function(e) {
            e.preventDefault();
            currentFilters.page += 1;
            loadFilteredPhotos(true);
        });
    }
    
    // Initialiser les dropdowns au chargement
    initCustomSelects();
    
    console.log("✅ Dropdowns personnalisés chargés et connectés");