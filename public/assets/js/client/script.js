document.addEventListener('change', function (e) {
    const checkbox = e.target;

    if (!checkbox.matches('input[type="checkbox"][name="seat_ids[]"]')) {
        return;
    }

    const wrapper = checkbox.closest('.buying-scheme__chair-wrapper');
    if (!wrapper) return;

    const chair = wrapper.querySelector('.buying-scheme__chair');
    if (!chair) return;

    chair.classList.toggle('buying-scheme__chair_selected', checkbox.checked);
});
