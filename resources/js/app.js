const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
        if (entry.isIntersecting) {
            entry.target.classList.add('active');
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll('.reveal').forEach((el) => observer.observe(el));

document.querySelectorAll('[data-faq-toggle]').forEach((btn) => {
    btn.addEventListener('click', () => {
        const content = btn.nextElementSibling;
        const icon = btn.querySelector('.material-symbols-outlined');
        const isHidden = content.classList.contains('hidden');

        content.classList.toggle('hidden');
        icon.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
    });
});
