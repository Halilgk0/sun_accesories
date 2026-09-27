/**
 * Güneşin Aksesuarı — interaction layer.
 *
 * Everything here is progressive: the store works without JavaScript, and each
 * behaviour bails out early when its markup is not on the page.
 */

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/* -------------------------------------------------------------------------
 * Falling petals — one shared layer, seeded once per page load.
 * ---------------------------------------------------------------------- */

const PETAL_COLORS = ['#ffd27a', '#f2b5ab', '#ffe3a3', '#fff2d4', '#f6c9c2'];

function seedPetals(count = window.innerWidth < 640 ? 7 : 14) {
    if (prefersReducedMotion) return;

    const layer = document.createElement('div');
    layer.setAttribute('aria-hidden', 'true');
    layer.className = 'pointer-events-none fixed inset-0 z-[1] overflow-hidden';

    for (let i = 0; i < count; i += 1) {
        const petal = document.createElement('span');
        const size = 9 + Math.random() * 15;

        petal.className = 'petal block';
        petal.style.left = `${Math.random() * 100}vw`;
        petal.style.width = `${size}px`;
        petal.style.height = `${size * 0.7}px`;
        petal.style.background = PETAL_COLORS[i % PETAL_COLORS.length];
        petal.style.borderRadius = '80% 10% 80% 10%';
        petal.style.setProperty('--petal-duration', `${13 + Math.random() * 16}s`);
        petal.style.setProperty('--petal-delay', `${Math.random() * -22}s`);
        petal.style.setProperty('--petal-drift', `${-140 + Math.random() * 280}px`);
        petal.style.setProperty('--petal-spin', `${240 + Math.random() * 720}deg`);
        petal.style.setProperty('--petal-opacity', `${0.35 + Math.random() * 0.45}`);

        layer.appendChild(petal);
    }

    document.body.appendChild(layer);
}

/* -------------------------------------------------------------------------
 * Scroll reveal
 * ---------------------------------------------------------------------- */

function watchReveals() {
    const targets = document.querySelectorAll('.reveal');
    if (!targets.length) return;

    if (prefersReducedMotion || !('IntersectionObserver' in window)) {
        targets.forEach((el) => el.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            });
        },
        { rootMargin: '0px 0px -12% 0px', threshold: 0.12 },
    );

    targets.forEach((el) => observer.observe(el));
}

/* -------------------------------------------------------------------------
 * Header: shrink on scroll, and a progress bar that fills as you read.
 * ---------------------------------------------------------------------- */

function watchHeader() {
    const header = document.querySelector('[data-header]');
    const progress = document.querySelector('[data-scroll-progress]');
    if (!header && !progress) return;

    const update = () => {
        const y = window.scrollY;

        if (header) {
            header.classList.toggle('is-stuck', y > 24);
        }

        if (progress) {
            const max = document.documentElement.scrollHeight - window.innerHeight;
            progress.style.transform = `scaleX(${max > 0 ? Math.min(y / max, 1) : 0})`;
        }
    };

    update();
    window.addEventListener('scroll', update, { passive: true });
    window.addEventListener('resize', update);
}

/* -------------------------------------------------------------------------
 * Parallax for elements tagged with data-parallax="0.15"
 * ---------------------------------------------------------------------- */

function watchParallax() {
    const layers = [...document.querySelectorAll('[data-parallax]')];
    if (!layers.length || prefersReducedMotion) return;

    let ticking = false;

    const apply = () => {
        const y = window.scrollY;
        layers.forEach((layer) => {
            const depth = parseFloat(layer.dataset.parallax) || 0.1;
            layer.style.transform = `translate3d(0, ${y * depth}px, 0)`;
        });
        ticking = false;
    };

    window.addEventListener(
        'scroll',
        () => {
            if (ticking) return;
            ticking = true;
            window.requestAnimationFrame(apply);
        },
        { passive: true },
    );
}

/* -------------------------------------------------------------------------
 * Card tilt — the jewellery catches light as the pointer moves over it.
 * ---------------------------------------------------------------------- */

function watchTilt() {
    if (prefersReducedMotion || window.matchMedia('(pointer: coarse)').matches) return;

    document.querySelectorAll('[data-tilt]').forEach((el) => {
        const strength = parseFloat(el.dataset.tilt) || 7;

        el.addEventListener('pointermove', (event) => {
            const rect = el.getBoundingClientRect();
            const px = (event.clientX - rect.left) / rect.width - 0.5;
            const py = (event.clientY - rect.top) / rect.height - 0.5;

            el.style.transform = `perspective(900px) rotateX(${-py * strength}deg) rotateY(${px * strength}deg) translateY(-8px)`;
            el.style.setProperty('--shine-x', `${(px + 0.5) * 100}%`);
            el.style.setProperty('--shine-y', `${(py + 0.5) * 100}%`);
        });

        el.addEventListener('pointerleave', () => {
            el.style.transform = '';
        });
    });
}

/* -------------------------------------------------------------------------
 * Flash toasts
 * ---------------------------------------------------------------------- */

function watchToasts() {
    document.querySelectorAll('[data-toast]').forEach((toast) => {
        const dismiss = () => {
            toast.style.transition = 'opacity .4s ease, transform .4s ease';
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-14px) scale(.96)';
            setTimeout(() => toast.remove(), 420);
        };

        toast.querySelector('[data-toast-close]')?.addEventListener('click', dismiss);
        setTimeout(dismiss, 5200);
    });
}

/* -------------------------------------------------------------------------
 * Mobile navigation
 * ---------------------------------------------------------------------- */

function watchMobileNav() {
    const toggle = document.querySelector('[data-nav-toggle]');
    const panel = document.querySelector('[data-nav-panel]');
    if (!toggle || !panel) return;

    const setOpen = (open) => {
        toggle.setAttribute('aria-expanded', String(open));
        panel.hidden = !open;
        document.body.style.overflow = open ? 'hidden' : '';
    };

    toggle.addEventListener('click', () => setOpen(panel.hidden));
    panel.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => setOpen(false)));
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !panel.hidden) setOpen(false);
    });
}

/* -------------------------------------------------------------------------
 * Product gallery zoom on the detail page
 * ---------------------------------------------------------------------- */

function watchZoom() {
    const frame = document.querySelector('[data-zoom]');
    const image = frame?.querySelector('img');
    if (!frame || !image || window.matchMedia('(pointer: coarse)').matches) return;

    frame.addEventListener('pointermove', (event) => {
        const rect = frame.getBoundingClientRect();
        image.style.transformOrigin = `${((event.clientX - rect.left) / rect.width) * 100}% ${((event.clientY - rect.top) / rect.height) * 100}%`;
        image.style.transform = 'scale(1.75)';
    });

    frame.addEventListener('pointerleave', () => {
        image.style.transform = '';
    });
}

/* ---------------------------------------------------------------------- */

document.addEventListener('DOMContentLoaded', () => {
    seedPetals();
    watchReveals();
    watchHeader();
    watchParallax();
    watchTilt();
    watchToasts();
    watchMobileNav();
    watchZoom();
});
