document.addEventListener("DOMContentLoaded", function() {
    const membershipDropdown = document.querySelector('.membership');
    const financeDropdown = document.querySelector('.finance');

    membershipDropdown.addEventListener('click', function(event) {
        event.preventDefault();
        membershipDropdown.nextElementSibling.classList.toggle('show');
    });

    financeDropdown.addEventListener('click', function(event) {
        event.preventDefault();
        financeDropdown.nextElementSibling.classList.toggle('show');
    });
});
