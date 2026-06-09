document.addEventListener('DOMContentLoaded', () => {
    // Galerie : changer la photo principale au clic sur une miniature
    const mainPhoto = document.getElementById('main-photo');
    if (mainPhoto) {
        document.querySelectorAll('.gallery-thumb').forEach(t => {
            t.addEventListener('click', () => {
                const url = t.dataset.url;
                if (url) {
                    mainPhoto.style.backgroundImage = `url('${url}')`;
                    document.querySelectorAll('.gallery-thumb').forEach(x => x.classList.remove('active'));
                    t.classList.add('active');
                }
            });
        });
    }

    // Auto-hide des messages flash après 5 s
    document.querySelectorAll('.flash').forEach(f => {
        setTimeout(() => { f.style.opacity = '0'; f.style.transition = 'opacity .5s'; }, 5000);
        setTimeout(() => { f.remove(); }, 5600);
    });

    // Confirmation pour les formulaires sensibles
    document.querySelectorAll('form[data-confirm]').forEach(form => {
        form.addEventListener('submit', e => {
            if (!confirm(form.dataset.confirm)) e.preventDefault();
        });
    });

    // Auto-scroll bas de la conversation
    const chat = document.querySelector('.chat-messages');
    if (chat) chat.scrollTop = chat.scrollHeight;
});