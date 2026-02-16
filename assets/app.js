import './styles/app.scss';

// Toggle progression field visibility based on game status
document.addEventListener('DOMContentLoaded', () => {
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
