import './bootstrap';

// ── Fonts & vendor CSS (bundled from npm: no CDN, no files to host) ──────────
import '@fontsource-variable/bricolage-grotesque';
import '@fontsource-variable/plus-jakarta-sans';
import '@fontsource-variable/readex-pro';
import 'swiper/css';
import 'swiper/css/pagination';
import 'swiper/css/thumbs';
import 'swiper/css/free-mode';
import 'swiper/css/zoom';

import Alpine from 'alpinejs';
import { animate, stagger, inView, scroll } from 'motion';
import Lenis from 'lenis';
import Swiper from 'swiper';
import { Navigation, Pagination, Thumbs, FreeMode, Keyboard, A11y, Zoom, Autoplay } from 'swiper/modules';
import confetti from 'canvas-confetti';

window.Alpine = Alpine;

const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const ease = [0.22, 1, 0.36, 1];
const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content ?? '';
const isRtl = () => document.documentElement.dir === 'rtl';

// The head script arms a fallback that reveals content if this bundle never runs.
clearTimeout(window.__revealFallback);

/* ───────────────────────── Smooth scroll ───────────────────────── */
let lenis = null;
if (!reduceMotion) {
    lenis = new Lenis({ duration: 1.05, easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)) });
    const raf = (time) => { lenis.raf(time); requestAnimationFrame(raf); };
    requestAnimationFrame(raf);

    document.addEventListener('click', (e) => {
        const a = e.target.closest('a[href^="#"]');
        const id = a?.getAttribute('href');
        if (id && id.length > 1 && document.querySelector(id)) {
            e.preventDefault();
            lenis.scrollTo(id, { offset: -110, duration: 1.2 });
        }
    });
}
window.lenis = lenis;

/* ───────────────────────── Toasts ───────────────────────── */
const toastIcons = {
    success: 'icon-[ph--check-circle-fill]',
    error: 'icon-[ph--warning-circle-fill]',
    info: 'icon-[ph--info-fill]',
};

window.toast = (message, type = 'success', duration = 3600) => {
    const root = document.getElementById('toast-root');
    if (!root || !message) return;

    const el = document.createElement('div');
    el.className = 'toast';
    el.dataset.type = type;
    el.setAttribute('role', type === 'error' ? 'alert' : 'status');

    const icon = document.createElement('i');
    icon.className = toastIcons[type] ?? toastIcons.info;
    const text = document.createElement('span');
    text.textContent = message; // textContent: never interpret server text as HTML

    el.append(icon, text);
    root.append(el);

    animate(el, { opacity: [0, 1], y: [-14, 0], scale: [0.96, 1] }, { duration: 0.4, ease });
    setTimeout(async () => {
        await animate(el, { opacity: 0, y: -10 }, { duration: 0.25 });
        el.remove();
    }, duration);
};

function readFlash(doc = document) {
    try {
        return JSON.parse(doc.getElementById('flash-data')?.textContent || '{}');
    } catch {
        return {};
    }
}

/* ───────────────────────── Cart ───────────────────────── */
function setCartCount(count) {
    const n = Number(count);
    if (Number.isNaN(n)) return;
    document.querySelectorAll('[data-cart-badge]').forEach((badge) => {
        badge.textContent = n;
        badge.hidden = n <= 0;
        badge.classList.remove('bump');
        void badge.offsetWidth; // restart the animation
        badge.classList.add('bump');
    });
}

function flyToCart(img) {
    const target = [...document.querySelectorAll('[data-cart-target]')].find((el) => el.offsetParent !== null);
    if (!img || !target || reduceMotion) return;

    const from = img.getBoundingClientRect();
    const to = target.getBoundingClientRect();
    const ghost = document.createElement('img');
    ghost.src = img.currentSrc || img.src;
    Object.assign(ghost.style, {
        position: 'fixed', left: `${from.left}px`, top: `${from.top}px`,
        width: `${from.width}px`, height: `${from.height}px`, objectFit: 'cover',
        borderRadius: '1.25rem', zIndex: 150, pointerEvents: 'none', boxShadow: '0 20px 40px -10px rgba(14,18,48,.4)',
    });
    document.body.append(ghost);

    animate(
        ghost,
        {
            x: to.left + to.width / 2 - (from.left + from.width / 2),
            y: to.top + to.height / 2 - (from.top + from.height / 2),
            scale: 0.1,
            opacity: [1, 0.7],
        },
        { duration: 0.8, ease: [0.55, 0, 0.2, 1] },
    ).then(() => ghost.remove());
}

// Progressive enhancement: forms still work as normal POSTs if this fails.
document.addEventListener('submit', async (e) => {
    const form = e.target.closest('form[data-cart-form]');
    if (!form) return;
    e.preventDefault();

    const btn = e.submitter ?? form.querySelector('[type="submit"]');
    btn?.setAttribute('disabled', '');
    btn?.classList.add('opacity-70');

    try {
        const res = await fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'text/html' },
            credentials: 'same-origin',
        });

        // Products with options bounce to their own page so the shopper can choose.
        if (res.redirected && /\/products\//.test(new URL(res.url).pathname) && !location.pathname.startsWith('/products/')) {
            location.href = res.url;
            return;
        }
        if (!res.ok) throw new Error('cart');

        const doc = new DOMParser().parseFromString(await res.text(), 'text/html');
        const flash = readFlash(doc);
        const count = doc.querySelector('[data-cart-count]')?.dataset.cartCount;

        if (flash.error) {
            window.toast(flash.error, 'error');
        } else if (e.submitter?.hasAttribute('data-buy-now')) {
            location.href = '/cart'; // "Buy now": add, then go straight to the cart
            return;
        } else {
            if (count !== undefined) setCartCount(count);
            flyToCart(form.closest('[data-product-card]')?.querySelector('[data-product-img]') ?? document.querySelector('[data-product-main-img]'));
            window.toast(flash.success || form.dataset.successText || 'Added to cart', 'success');
        }
    } catch {
        form.submit(); // fall back to a normal page load
    } finally {
        btn?.removeAttribute('disabled');
        btn?.classList.remove('opacity-70');
    }
});

/* ───────────────────────── Wishlist ───────────────────────── */
document.addEventListener('click', async (e) => {
    const btn = e.target.closest('[data-wishlist]');
    if (!btn) return;
    e.preventDefault();
    e.stopPropagation();

    try {
        const res = await fetch(`/wishlist/${btn.dataset.wishlist}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf(), Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin',
        });
        if (res.status === 401 || res.status === 419) { location.href = '/login'; return; }
        const data = await res.json();
        const on = data.status === 'added';

        btn.setAttribute('aria-pressed', String(on));
        if (!reduceMotion) animate(btn.querySelector(on ? '.w-on' : '.w-off') ?? btn, { scale: [0.6, 1.3, 1] }, { duration: 0.45, ease });

        document.querySelectorAll('[data-wishlist-badge]').forEach((badge) => {
            const next = Math.max(0, Number(badge.textContent || 0) + (on ? 1 : -1));
            badge.textContent = next;
            badge.hidden = next <= 0;
        });
        window.toast(on ? btn.dataset.addedText : btn.dataset.removedText, on ? 'success' : 'info', 2200);

        // On the wishlist page an un-saved product leaves the grid.
        const card = btn.closest('[data-wishlist-page] [data-product-card]');
        if (card && !on) {
            const grid = card.parentElement;
            await animate(card, { opacity: 0, scale: 0.94 }, { duration: 0.3 });
            card.remove();
            if (!grid.querySelector('[data-product-card]')) location.reload();
        }
    } catch {
        window.toast(btn.dataset.errorText, 'error');
    }
});

/* ───────────────────────── Catalog (live search) ───────────────────────── */
Alpine.data('catalog', (init = {}) => ({
    search: init.search ?? '',
    category: init.category ?? '',
    sort: init.sort ?? '',
    loading: false,
    live: false,
    ctrl: null,

    async load() {
        this.ctrl?.abort();
        this.ctrl = new AbortController();
        this.loading = true;
        this.live = true;

        const params = new URLSearchParams();
        if (this.search) params.set('search', this.search);
        if (this.category) params.set('category', this.category);
        if (this.sort) params.set('sort', this.sort);

        try {
            const res = await fetch(`/products/search?${params}`, {
                signal: this.ctrl.signal,
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            });
            if (!res.ok) throw new Error('search');
            this.$refs.grid.innerHTML = await res.text();
            history.replaceState(null, '', params.toString() ? `?${params}` : location.pathname);
            observeReveal(this.$refs.grid);
        } catch (err) {
            if (err.name !== 'AbortError') window.toast(init.errorText, 'error');
        } finally {
            this.loading = false;
        }
    },

    pick(id) {
        this.category = String(id);
        this.load();
    },
}));

/* ───────────────────────── Scroll reveal (used sparingly) ───────────────────────── */
function observeReveal(root = document) {
    root.querySelectorAll('[data-reveal]:not([data-revealed])').forEach((el) => {
        el.dataset.revealed = '1';
        if (reduceMotion) { el.style.opacity = 1; el.style.transform = 'none'; return; }
        const delay = Number(el.dataset.revealDelay || 0);
        inView(el, () => {
            animate(el, { opacity: [0, 1], y: [28, 0] }, { duration: 0.8, delay, ease });
        }, { margin: '0px 0px -8% 0px' });
    });
}

/* ───────────────────────── Hero (the one orchestrated moment) ───────────────────────── */
function initHero() {
    const hero = document.querySelector('[data-hero]');
    if (!hero) return;

    if (!reduceMotion) {
        const items = hero.querySelectorAll('[data-hero-item]');
        animate([...items], { opacity: [0, 1], y: [34, 0] }, { duration: 0.9, delay: stagger(0.09, { startDelay: 0.1 }), ease });

        hero.querySelectorAll('[data-hero-card]').forEach((card, i) => {
            animate(card, { opacity: [0, 1], y: [80, 0], scale: [0.9, 1] }, { duration: 1.1, delay: 0.35 + i * 0.14, ease });
            // gentle idle float, each card on its own rhythm
            animate(card.firstElementChild ?? card, { y: [0, i % 2 ? 10 : -12, 0] }, {
                duration: 5 + i * 1.3, repeat: Infinity, ease: 'easeInOut', delay: 1.4 + i * 0.2,
            });
        });

        const bg = hero.querySelector('.aurora');
        if (bg) scroll(animate(bg, { y: [0, 140] }), { target: hero, offset: ['start start', 'end start'] });

        if (window.matchMedia('(pointer: fine)').matches) {
            let frame = 0;
            hero.addEventListener('pointermove', (e) => {
                if (frame) return;
                frame = requestAnimationFrame(() => {
                    const r = hero.getBoundingClientRect();
                    hero.style.setProperty('--mx', ((e.clientX - r.left) / r.width - 0.5).toFixed(3));
                    hero.style.setProperty('--my', ((e.clientY - r.top) / r.height - 0.5).toFixed(3));
                    frame = 0;
                });
            });
        }
    }
}

/* ───────────────────────── Swipers ───────────────────────── */
function initSwipers() {
    // Horizontal product rails
    document.querySelectorAll('[data-rail]').forEach((el) => {
        const scope = el.closest('[data-rail-scope]') ?? el.parentElement;
        new Swiper(el, {
            modules: [Navigation, FreeMode, Keyboard, A11y],
            slidesPerView: 1.35,
            spaceBetween: 16,
            freeMode: { enabled: true, momentumRatio: 0.6 },
            keyboard: true,
            navigation: { nextEl: scope.querySelector('[data-next]'), prevEl: scope.querySelector('[data-prev]') },
            breakpoints: {
                560: { slidesPerView: 2.2 },
                900: { slidesPerView: 3.1, spaceBetween: 20 },
                1200: { slidesPerView: 3.6, spaceBetween: 24 },
            },
        });
    });

    // Product gallery with thumbnails + zoom
    document.querySelectorAll('[data-gallery]').forEach((root) => {
        const thumbsEl = root.querySelector('[data-gallery-thumbs]');
        const thumbs = thumbsEl
            ? new Swiper(thumbsEl, { modules: [FreeMode, Thumbs, A11y], slidesPerView: 'auto', spaceBetween: 12, freeMode: true, watchSlidesProgress: true })
            : null;
        new Swiper(root.querySelector('[data-gallery-main]'), {
            modules: [Navigation, Pagination, Thumbs, Zoom, Keyboard, A11y],
            spaceBetween: 12,
            zoom: { maxRatio: 2.5 },
            keyboard: true,
            pagination: { el: root.querySelector('.swiper-pagination'), clickable: true },
            navigation: { nextEl: root.querySelector('[data-next]'), prevEl: root.querySelector('[data-prev]') },
            thumbs: thumbs ? { swiper: thumbs } : undefined,
        });
    });

    // Auto-playing fade/slide quotes, banners
    document.querySelectorAll('[data-banner]').forEach((el) => {
        new Swiper(el, {
            modules: [Pagination, Autoplay, A11y],
            loop: el.querySelectorAll('.swiper-slide').length > 1,
            autoplay: { delay: 5200, disableOnInteraction: false },
            pagination: { el: el.querySelector('.swiper-pagination'), clickable: true },
        });
    });
}

/* ───────────────────────── Small touches ───────────────────────── */
document.addEventListener('keydown', (e) => {
    if (e.key === '/' && !/^(INPUT|TEXTAREA|SELECT)$/.test(document.activeElement?.tagName ?? '') && !e.metaKey && !e.ctrlKey) {
        const input = [...document.querySelectorAll('[data-search-input]')].find((el) => el.offsetParent !== null);
        if (input) { e.preventDefault(); input.focus(); }
    }
});

window.celebrate = () => {
    if (reduceMotion) return;
    const colors = ['#3e4fff', '#ffb224', '#12b886', '#ff4d6a', '#8b5cf6'];
    confetti({ particleCount: 110, spread: 75, origin: { y: 0.35 }, colors, scalar: 1.05 });
    setTimeout(() => confetti({ particleCount: 60, angle: 60, spread: 60, origin: { x: 0, y: 0.6 }, colors }), 220);
    setTimeout(() => confetti({ particleCount: 60, angle: 120, spread: 60, origin: { x: 1, y: 0.6 }, colors }), 320);
};

window.copyText = async (text, el) => {
    try {
        await navigator.clipboard.writeText(text);
        window.toast(el?.dataset.copiedText || 'Copied', 'success', 1800);
    } catch {
        window.toast(text, 'info', 4000);
    }
};

// A page may expose a count-up for plain numbers: <span data-count="1200">
function initCounters() {
    document.querySelectorAll('[data-count]').forEach((el) => {
        const end = Number(el.dataset.count);
        if (!end || reduceMotion) return;
        inView(el, () => {
            animate(0, end, { duration: 1.6, ease, onUpdate: (v) => { el.textContent = Math.round(v).toLocaleString(); } });
        });
    });
}

/* ───────────────────────── Boot ───────────────────────── */
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    initHero();
    initSwipers();
    observeReveal();
    initCounters();

    const flash = readFlash();
    if (flash.success) window.toast(flash.success, 'success');
    if (flash.error) window.toast(flash.error, 'error');
    if (flash.status) window.toast(flash.status, 'info');

    if (document.querySelector('[data-confetti]')) setTimeout(window.celebrate, 450);
});
