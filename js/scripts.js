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


document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('contact-modal');
    const closeBtn = modal ? modal.querySelector('.modal-close') : null;
    const openBtn = document.querySelector('.contact-button'); // un seul bouton

    if (!modal || !openBtn) return;

    function openModal() {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    openBtn.addEventListener('click', function (e) {
        e.preventDefault();

        // Récupérer la référence depuis la photo
        const photoElem = document.querySelector('.photo');
        const refPhotoValue = photoElem ? photoElem.getAttribute('data-ref') : '';

        // Préremplir le champ REF PHOTO
        const refInput = modal.querySelector('input[name="email-2"]');
        if(refInput) refInput.value = refPhotoValue;

        openModal();
    });

    if (closeBtn) closeBtn.addEventListener('click', closeModal);

    modal.addEventListener('click', function (e) {
        if (e.target === modal) closeModal();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.classList.contains('active')) closeModal();
    });
});
