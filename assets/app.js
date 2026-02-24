import './styles/app.scss';

document.addEventListener('DOMContentLoaded', () => {
    // ── Burger menu toggle ──
    const burger = document.querySelector('.navbar__burger');
    const collapse = document.querySelector('.navbar__collapse');
    if (burger && collapse) {
        burger.addEventListener('click', () => {
            const isOpen = collapse.classList.toggle('navbar__collapse--open');
            burger.classList.toggle('navbar__burger--open', isOpen);
            burger.setAttribute('aria-expanded', isOpen);
        });
    }

    // ── Toggle progression field visibility based on game status ──
    const statutSelect = document.getElementById('user_game_collection_statut');
    if (!statutSelect) return;

    const progressionRow = document.getElementById('user_game_collection_progression')?.closest('.form-group')
        || document.getElementById('user_game_collection_progression')?.parentElement;
    if (!progressionRow) return;

    const toggleProgression = () => {
        const show = ['en_cours', 'en_pause'].includes(statutSelect.value);
        progressionRow.style.display = show ? '' : 'none';
    };

    toggleProgression();
    statutSelect.addEventListener('change', toggleProgression);
});
