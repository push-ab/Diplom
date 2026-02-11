document.addEventListener('click', (e) => {
    const openBtn = e.target.closest('[data-popup-open]');
    if (openBtn) {
        e.preventDefault();

        const popupSel = openBtn.getAttribute('data-popup-open');
        const popup = document.querySelector(popupSel);
        if (!popup) return;

        const title = openBtn.getAttribute('data-remove-title');
        const action = openBtn.getAttribute('data-remove-action');

        const titleEl = popup.querySelector('[data-remove-title]');
        if (titleEl && title) titleEl.textContent = `"${title}"`;

        const form = popup.querySelector('form');
        if (form && action) form.setAttribute('action', action);

        popup.classList.add('active');
        return;
    }

    const closeBtn = e.target.closest('[data-popup-close]');
    if (closeBtn) {
        e.preventDefault();
        const popup = closeBtn.closest('.popup');
        if (popup) popup.classList.remove('active');
        return;
    }

    if (e.target.classList && e.target.classList.contains('popup')) {
        e.target.classList.remove('active');
    }
});



document.addEventListener('click', (e) => {
    const chair = e.target.closest('.conf-step__chair');
    if (!chair) return;
    if (!chair.dataset.seatId) return;

    const current = chair.dataset.seatType || 'standard';
    const next = current === 'disabled' ? 'standard' : (current === 'standard' ? 'vip' : 'disabled');

    chair.dataset.seatType = next;

    chair.classList.remove('conf-step__chair_disabled', 'conf-step__chair_standart', 'conf-step__chair_vip');

    if (next === 'disabled') chair.classList.add('conf-step__chair_disabled');
    if (next === 'standard') chair.classList.add('conf-step__chair_standart');
    if (next === 'vip') chair.classList.add('conf-step__chair_vip');
});

document.addEventListener('submit', (e) => {
    const form = e.target.closest('form');
    if (!form) return;

    const hidden = form.querySelector('#seats_json');
    if (!hidden) return;

    const chairs = form.querySelectorAll('.conf-step__chair[data-seat-id]');
    const payload = [];

    chairs.forEach(ch => {
        payload.push({
            id: Number(ch.dataset.seatId),
            type: ch.dataset.seatType || 'standard'
        });
    });

    hidden.value = JSON.stringify(payload);
});
