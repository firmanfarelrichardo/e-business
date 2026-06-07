<button
    type="button"
    data-testid="theme-toggle-button"
    class="theme-toggle relative w-11 h-11 rounded-full glass-panel flex items-center justify-center transition hover:scale-105 active:scale-95"
    onclick="toggleTheme()"
    aria-label="Toggle dark mode">
    <!-- Sun icon (visible in light mode) -->
    <svg class="theme-icon-sun w-5 h-5 absolute text-[var(--color-text)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <circle cx="12" cy="12" r="4"/>
        <path stroke-linecap="round" d="M12 2v2m0 16v2M4.93 4.93l1.41 1.41m11.32 11.32l1.41 1.41M2 12h2m16 0h2M4.93 19.07l1.41-1.41m11.32-11.32l1.41-1.41"/>
    </svg>
    <!-- Moon icon (visible in dark mode) -->
    <svg class="theme-icon-moon w-5 h-5 absolute text-[var(--color-text)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
    </svg>
</button>
