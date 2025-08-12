document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('contact-modal');
    const footer = document.querySelector('footer');

    if (!modal) {
        console.warn("🚫 Modale non trouvée dans le DOM.");
        return;
    }

    const closeBtn = modal.querySelector('.modal-close');
    const openBtns = document.querySelectorAll('[data-modal="contact"]');

    function openModal() {
        if (!modal.classList.contains('active')) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeModal() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    openBtns.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();

            // Récupérer la référence photo depuis un data attribute
            const refPhotoValue = btn.getAttribute('data-refphoto');

            // Trouver le formulaire dans la modale
            const form = modal.querySelector('form');

            if (form && refPhotoValue) {
                let action = form.getAttribute('action') || '';

                // Nettoyer l'action pour ne pas accumuler plusieurs fois le paramètre
                action = action.replace(/(\?|&)refphoto=[^&]*/g, '');

                if (action.indexOf('?') === -1) {
                    action += '?refphoto=' + encodeURIComponent(refPhotoValue);
                } else {
                    action += '&refphoto=' + encodeURIComponent(refPhotoValue);
                }
                form.setAttribute('action', action);
            }

            openModal();
        });
    });

    if (closeBtn) {
        closeBtn.addEventListener('click', closeModal);
    }

    modal.addEventListener('click', function (e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            closeModal();
        }
    });

    function checkFooterVisible() {
        const rect = footer.getBoundingClientRect();
        const windowHeight = window.innerHeight || document.documentElement.clientHeight;
        if (rect.top <= windowHeight && rect.bottom >= 0) {
            openModal();
            window.removeEventListener('scroll', checkFooterVisible);
        }
    }

    window.addEventListener('scroll', checkFooterVisible);

    console.log("✅ JS chargé et modale connectée");
});

