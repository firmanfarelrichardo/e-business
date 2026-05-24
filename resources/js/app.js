import './bootstrap';

// ── Theme initialization ─────────────────────────────────────────
// Reads saved preference or system preference, sets data-theme attribute.
// Note: For instant theme (no flash), an inline <script> in <head> runs first (see layouts).
const saved = localStorage.getItem('sinergi_theme');
const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
const initial = saved || (prefersDark ? 'dark' : 'light');
document.documentElement.setAttribute('data-theme', initial);

// ── Global toggle function (called by theme-toggle button) ───────
window.toggleTheme = function () {
    const current = document.documentElement.getAttribute('data-theme');
    const next = current === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', next);
    localStorage.setItem('sinergi_theme', next);
};

// ── Respect system preference change if user hasn't manually chosen ──
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
    if (!localStorage.getItem('sinergi_theme')) {
        document.documentElement.setAttribute('data-theme', e.matches ? 'dark' : 'light');
    }
});
