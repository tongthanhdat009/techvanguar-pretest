/**
 * Public/Landing Page JavaScript Module
 * Handles landing page interactivity
 */

// ───────────────────────────────────────────────────────────────────────────────
// Flip Cards (Demo Section)
// ───────────────────────────────────────────────────────────────────────────────

const FlipCards = {
    init() {
        this.container = document.getElementById('demo-cards');
        if (!this.container) return;

        this.cards = this.container.querySelectorAll('.flip-card');
        this.bindEvents();
    },

    bindEvents() {
        // Click to flip
        this.cards.forEach(card => {
            card.addEventListener('click', () => {
                card.classList.toggle('flipped');
            });

            // Keyboard accessibility
            card.setAttribute('tabindex', '0');
            card.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    card.classList.toggle('flipped');
                }
            });
        });

        // Reset button
        const resetBtn = document.querySelector('[data-reset-cards]');
        resetBtn?.addEventListener('click', () => this.resetAll());
    },

    resetAll() {
        this.cards.forEach(card => {
            card.classList.remove('flipped');
        });
    },

    flipAll() {
        this.cards.forEach(card => {
            card.classList.add('flipped');
        });
    }
};

// ───────────────────────────────────────────────────────────────────────────────
// Smooth Scroll Navigation
// ───────────────────────────────────────────────────────────────────────────────

const SmoothScroll = {
    init() {
        this.links = document.querySelectorAll('a[href^="#"]');
        this.bindEvents();
    },

    bindEvents() {
        this.links.forEach(link => {
            link.addEventListener('click', (e) => {
                const href = link.getAttribute('href');
                if (href === '#' || href === '#!') return;

                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    this.scrollTo(target);
                }
            });
        });
    },

    scrollTo(target) {
        const headerOffset = 80;
        const elementPosition = target.getBoundingClientRect().top;
        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

        window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth'
        });
    }
};

// ───────────────────────────────────────────────────────────────────────────────
// Navbar Scroll Effect
// ───────────────────────────────────────────────────────────────────────────────

const NavbarScroll = {
    init() {
        this.navbar = document.querySelector('.public-navbar');
        if (!this.navbar) return;

        this.lastScrollY = window.pageYOffset;
        this.bindEvents();
    },

    bindEvents() {
        window.addEventListener('scroll', () => this.onScroll());
    },

    onScroll() {
        const currentScrollY = window.pageYOffset;

        if (currentScrollY > 50) {
            this.navbar.classList.add('scrolled');
        } else {
            this.navbar.classList.remove('scrolled');
        }

        this.lastScrollY = currentScrollY;
    }
};

// ───────────────────────────────────────────────────────────────────────────────
// Stats Counter Animation
// ───────────────────────────────────────────────────────────────────────────────

const StatsCounter = {
    init() {
        this.stats = document.querySelectorAll('[data-count]');
        if (this.stats.length === 0) return;

        this.animated = false;
        this.bindEvents();
    },

    bindEvents() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !this.animated) {
                    this.animate();
                    this.animated = true;
                }
            });
        }, { threshold: 0.5 });

        const statsSection = document.querySelector('.stats-bar');
        if (statsSection) {
            observer.observe(statsSection);
        }
    },

    animate() {
        this.stats.forEach(stat => {
            const target = parseInt(stat.dataset.count);
            const duration = 2000;
            const step = target / (duration / 16);
            let current = 0;

            const timer = setInterval(() => {
                current += step;
                if (current >= target) {
                    stat.textContent = this.formatNumber(target);
                    clearInterval(timer);
                } else {
                    stat.textContent = this.formatNumber(Math.floor(current));
                }
            }, 16);
        });
    },

    formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
};

// ───────────────────────────────────────────────────────────────────────────────
// Feature Cards Animation
// ───────────────────────────────────────────────────────────────────────────────

const FeatureCards = {
    init() {
        this.cards = document.querySelectorAll('.feature-card');
        if (this.cards.length === 0) return;

        this.bindEvents();
    },

    bindEvents() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('animate-in');
                    }, index * 100);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.2 });

        this.cards.forEach(card => observer.observe(card));
    }
};

// ───────────────────────────────────────────────────────────────────────────────
// CTA Button Pulse Effect
// ───────────────────────────────────────────────────────────────────────────────

const CtaButton = {
    init() {
        this.buttons = document.querySelectorAll('.btn-cta');
        this.bindEvents();
    },

    bindEvents() {
        this.buttons.forEach(btn => {
            btn.addEventListener('mouseenter', () => {
                // Pause animation on hover
                btn.style.animation = 'none';
            });

            btn.addEventListener('mouseleave', () => {
                // Resume animation
                btn.style.animation = 'pulse-glow 2.4s ease-in-out infinite';
            });
        });
    }
};

// ───────────────────────────────────────────────────────────────────────────────
// Mobile Menu (if needed)
// ───────────────────────────────────────────────────────────────────────────────

const MobileMenu = {
    init() {
        this.toggle = document.querySelector('[data-mobile-menu-toggle]');
        this.menu = document.querySelector('[data-mobile-menu]');

        if (!this.toggle || !this.menu) return;

        this.isOpen = false;
        this.bindEvents();
    },

    bindEvents() {
        this.toggle.addEventListener('click', () => this.toggleMenu());

        // Close when clicking outside
        document.addEventListener('click', (e) => {
            if (this.isOpen &&
                !this.menu.contains(e.target) &&
                !this.toggle.contains(e.target)) {
                this.close();
            }
        });
    },

    toggleMenu() {
        this.isOpen ? this.close() : this.open();
    },

    open() {
        this.isOpen = true;
        this.menu.classList.add('open');
        document.body.style.overflow = 'hidden';
    },

    close() {
        this.isOpen = false;
        this.menu.classList.remove('open');
        document.body.style.overflow = '';
    }
};

// ───────────────────────────────────────────────────────────────────────────────
// Initialize
// ───────────────────────────────────────────────────────────────────────────────

document.addEventListener('DOMContentLoaded', () => {
    FlipCards.init();
    SmoothScroll.init();
    NavbarScroll.init();
    StatsCounter.init();
    FeatureCards.init();
    CtaButton.init();
    MobileMenu.init();
});
