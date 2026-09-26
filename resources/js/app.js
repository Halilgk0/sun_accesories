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
 * Quantity steppers — submit the surrounding form after a change.
 * ---------------------------------------------------------------------- */

function watchQuantity() {
    document.querySelectorAll('[data-quantity]').forEach((widget) => {
        const input = widget.querySelector('input[type="number"]');
        if (!input) return;

        const min = Number(input.min || 1);
        const max = Number(input.max || 99);
        const autoSubmit = widget.hasAttribute('data-quantity-submit');

        widget.querySelectorAll('[data-step]').forEach((button) => {
            button.addEventListener('click', () => {
                const next = Number(input.value) + Number(button.dataset.step);
                input.value = String(Math.min(Math.max(next, min), max));
                input.classList.add('animate-pop');
                setTimeout(() => input.classList.remove('animate-pop'), 460);

                if (autoSubmit) {
                    input.form?.requestSubmit();
                }
            });
        });
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
 * Checkout: swap the payment panel, and format card input as it is typed.
 * ---------------------------------------------------------------------- */

function watchPayment() {
    const radios = document.querySelectorAll('[data-payment-option]');
    if (!radios.length) return;

    const sync = () => {
        const selected = document.querySelector('[data-payment-option]:checked')?.value;

        document.querySelectorAll('[data-payment-panel]').forEach((panel) => {
            panel.hidden = panel.dataset.paymentPanel !== selected;
        });

        document.querySelectorAll('[data-payment-option]').forEach((radio) => {
            radio.closest('label')?.classList.toggle('is-selected', radio.checked);
        });
    };

    radios.forEach((radio) => radio.addEventListener('change', sync));
    sync();

    const cardNumber = document.querySelector('[data-card-number]');
    cardNumber?.addEventListener('input', () => {
        const digits = cardNumber.value.replace(/\D/g, '').slice(0, 16);
        cardNumber.value = digits.replace(/(.{4})/g, '$1 ').trim();
        document.querySelector('[data-card-preview-number]')?.replaceChildren(
            document.createTextNode(cardNumber.value.padEnd(19, '•')),
        );
    });

    const cardExpiry = document.querySelector('[data-card-expiry]');
    cardExpiry?.addEventListener('input', () => {
        const digits = cardExpiry.value.replace(/\D/g, '').slice(0, 4);
        cardExpiry.value = digits.length > 2 ? `${digits.slice(0, 2)}/${digits.slice(2)}` : digits;
        document.querySelector('[data-card-preview-expiry]')?.replaceChildren(
            document.createTextNode(cardExpiry.value || 'AA/YY'),
        );
    });

    const cardName = document.querySelector('[data-card-name]');
    cardName?.addEventListener('input', () => {
        document.querySelector('[data-card-preview-name]')?.replaceChildren(
            document.createTextNode(cardName.value.toLocaleUpperCase('tr-TR') || 'AD SOYAD'),
        );
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

/* -------------------------------------------------------------------------
 * Countdown to the end of the spring campaign
 * ---------------------------------------------------------------------- */

function watchCountdown() {
    const root = document.querySelector('[data-countdown]');
    if (!root) return;

    const deadline = new Date(root.dataset.countdown).getTime();
    const slots = {
        days: root.querySelector('[data-countdown-days]'),
        hours: root.querySelector('[data-countdown-hours]'),
        minutes: root.querySelector('[data-countdown-minutes]'),
        seconds: root.querySelector('[data-countdown-seconds]'),
    };

    const pad = (value) => String(Math.max(value, 0)).padStart(2, '0');

    const tick = () => {
        const remaining = Math.max(deadline - Date.now(), 0);
        const totalSeconds = Math.floor(remaining / 1000);

        if (slots.days) slots.days.textContent = pad(Math.floor(totalSeconds / 86400));
        if (slots.hours) slots.hours.textContent = pad(Math.floor((totalSeconds % 86400) / 3600));
        if (slots.minutes) slots.minutes.textContent = pad(Math.floor((totalSeconds % 3600) / 60));
        if (slots.seconds) slots.seconds.textContent = pad(totalSeconds % 60);
    };

    tick();
    setInterval(tick, 1000);
}

/* -------------------------------------------------------------------------
 * Cart badge pop whenever the page reports a fresh count
 * ---------------------------------------------------------------------- */

function popCartBadge() {
    const badge = document.querySelector('[data-cart-count]');
    if (!badge || !sessionStorage) return;

    const current = badge.dataset.cartCount;
    const previous = sessionStorage.getItem('cart-count');

    if (previous !== null && previous !== current) {
        badge.classList.add('animate-pop');
        setTimeout(() => badge.classList.remove('animate-pop'), 500);
    }

    sessionStorage.setItem('cart-count', current);
}

/* ---------------------------------------------------------------------- */

document.addEventListener('DOMContentLoaded', () => {
    seedPetals();
    watchReveals();
    watchHeader();
    watchParallax();
    watchTilt();
    watchToasts();
    watchQuantity();
    watchMobileNav();
    watchPayment();
    watchZoom();
    watchCountdown();
    popCartBadge();
});
