
document.addEventListener('click', function (event) {
    document.querySelectorAll('.notification-action').forEach(function (menu) {
        const checkbox = menu.querySelector('.menu-toggle');

        if (checkbox && !menu.contains(event.target)) {
            checkbox.checked = false;
        }
    });
});
