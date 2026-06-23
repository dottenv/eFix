// Alpine.js – global loading indicator
document.addEventListener('htmx:beforeRequest', () => {
    if (window.Alpine) Alpine.store('loading', { active: true });
});
document.addEventListener('htmx:afterRequest', () => {
    if (window.Alpine) Alpine.store('loading', { active: false });
});
document.addEventListener('alpine:init', () => {
    Alpine.store('loading', { active: false });
});

// Alpine.js – price filters
document.addEventListener('alpine:init', () => {
    Alpine.data('priceFilters', () => ({
        brandOptions: '<option value="">Сначала выберите тип</option>',
        modelOptions: '<option value="">Сначала выберите тип</option>',
        init() {
            this.loadBrands('');
        },
        onTypeChange(type) {
            this.loadBrands(type);
            document.getElementById('filter-model').innerHTML = '<option value="">Сначала выберите бренд</option>';
        },
        onBrandChange(brand) {
            const type = document.getElementById('filter-type').value;
            this.loadModels(type, brand);
        },
        loadBrands(type) {
            fetch('/api/brands?type=' + encodeURIComponent(type))
                .then(r => r.text())
                .then(html => { this.brandOptions = html; });
        },
        loadModels(type, brand) {
            const params = new URLSearchParams({ type, brand });
            fetch('/api/models?' + params)
                .then(r => r.text())
                .then(html => { this.modelOptions = html; });
        }
    }));
});

// Alpine.js – services modal
document.addEventListener('alpine:init', () => {
    Alpine.data('servicesModal', () => ({
        openModal: false,
        current: {},
        categories: [],
        init() {
            fetch('/api/categories')
                .then(r => r.json())
                .then(data => { this.categories = data; });
        },
        open(id) {
            this.current = this.categories.find(c => c.id === id) || this.categories[0];
            this.openModal = true;
            document.body.style.overflow = 'hidden';
        },
        close() {
            this.openModal = false;
            document.body.style.overflow = '';
        }
    }));
});

document.addEventListener('DOMContentLoaded', () => {
    // Header scroll effect
    const header = document.getElementById('header');
    let lastScroll = 0;

    window.addEventListener('scroll', () => {
        const currentScroll = window.scrollY;
        if (currentScroll > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
        lastScroll = currentScroll;
    });

    // Burger menu
    const burger = document.getElementById('burger');
    const nav = document.getElementById('nav');

    burger.addEventListener('click', () => {
        burger.classList.toggle('active');
        nav.classList.toggle('open');
    });

    // Close menu on link click
    nav.querySelectorAll('.nav__link').forEach(link => {
        link.addEventListener('click', () => {
            burger.classList.remove('active');
            nav.classList.remove('open');
        });
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', (e) => {
            const href = anchor.getAttribute('href');
            if (href === '#') return;
            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    // Fade-in on scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.service-card, .advantage-card, .process-step, .review-card').forEach(el => {
        el.classList.add('fade-in');
        observer.observe(el);
    });

    // Phone mask (all inputs with .phone-mask or #phone)
    function maskPhone(input) {
        input.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 0) {
                if (value.startsWith('7')) value = '+' + value;
                else if (value.startsWith('8')) value = '+7' + value.substring(1);
                else value = '+7' + value;
            }
            let formatted = '';
            if (value.length > 1) formatted = value.substring(0, 2);
            if (value.length > 2) formatted += ' (' + value.substring(2, 5);
            if (value.length > 5) formatted += ') ' + value.substring(5, 8);
            if (value.length > 8) formatted += '-' + value.substring(8, 10);
            if (value.length > 10) formatted += '-' + value.substring(10, 12);
            e.target.value = formatted;
        });
    }

    document.querySelectorAll('.phone-mask, #phone').forEach(maskPhone);

    // HTMX: re-apply mask after swap
    document.addEventListener('htmx:afterSwap', () => {
        document.querySelectorAll('.phone-mask, #phone').forEach(maskPhone);
    });

    // Modal: re-apply mask when Alpine opens the modal
    document.addEventListener('openModalCallback', () => {
        setTimeout(() => {
            document.querySelectorAll('.phone-mask, #phone').forEach(maskPhone);
        }, 50);
    });
});
