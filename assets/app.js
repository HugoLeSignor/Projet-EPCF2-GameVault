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

    // ── Rating sliders ──
    document.querySelectorAll('.rating-slider').forEach(slider => {
        const isOptional = slider.dataset.optional === 'true';

        const wrapper = document.createElement('div');
        wrapper.className = 'rating-slider-wrapper';
        slider.parentNode.insertBefore(wrapper, slider);
        wrapper.appendChild(slider);

        const display = document.createElement('span');
        display.className = 'rating-slider__value';
        wrapper.appendChild(display);

        const update = () => {
            const val = parseInt(slider.value, 10);
            if (isOptional && val === 0) {
                display.textContent = '—';
                display.classList.add('rating-slider__value--none');
                // update track fill to grey
                slider.style.background = 'rgba(255,255,255,0.1)';
            } else {
                display.textContent = val + '/10';
                display.classList.remove('rating-slider__value--none');
                const pct = ((val - parseInt(slider.min, 10)) / (parseInt(slider.max, 10) - parseInt(slider.min, 10))) * 100;
                slider.style.background = `linear-gradient(to right, #e94560 ${pct}%, rgba(255,255,255,0.1) ${pct}%)`;
            }
        };

        slider.addEventListener('input', update);
        update();
    });

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
