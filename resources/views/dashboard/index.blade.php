@extends('components.layouts.dashboard')

@section('header')
    <h1 class="text-lg font-bold text-[var(--color-text)] font-display">Dashboard</h1>
    <p class="text-xs text-[var(--color-text-muted)]">Pantau semua metrik penjualan dan pengeluaran</p>
@endsection

@section('content')

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script type="module">
            import { animate, stagger, scroll } from "https://esm.sh/motion";

            animate(
                ".motion-card",
                { y: [30, 0], opacity: [0, 1] },
                { delay: stagger(0.1), duration: 0.6, easing: [0.22, 1, 0.36, 1] }
            );
            animate(
                ".motion-chart",
                { scale: [0.95, 1], opacity: [0, 1] },
                { delay: 0.4, duration: 0.7, easing: "ease-out" }
            );
            animate(
                ".motion-queue",
                { x: [30, 0], opacity: [0, 1] },
                { delay: 0.5, duration: 0.7, easing: "ease-out" }
            );

            // Determine chart colors based on theme
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            const gridColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)';
            const textColor = isDark ? 'rgba(255,255,255,0.5)' : 'rgba(0,0,0,0.5)';

            const ctx = document.getElementById('financeChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [
                        {
                            label: 'Income',
                            data: [500000, 750000, 600000, 1200000, 1000000, {{ $income }}],
                            borderColor: '#22d3ee',
                            backgroundColor: 'rgba(34, 211, 238, 0.08)',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#22d3ee',
                            pointBorderWidth: 0,
                            pointRadius: 0,
                            pointHoverRadius: 6,
                        },
                        {
                            label: 'Expense',
                            data: [300000, 200000, 450000, 300000, 500000, {{ $expense }}],
                            borderColor: '#0284C7',
                            backgroundColor: 'rgba(2, 132, 199, 0.08)',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: false,
                            pointBackgroundColor: '#0284C7',
                            pointBorderWidth: 0,
                            pointRadius: 0,
                            pointHoverRadius: 6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top', align: 'end',
                            labels: {
                                usePointStyle: true, boxWidth: 8,
                                font: { family: "'Plus Jakarta Sans', sans-serif", size: 12, weight: 600 },
                                color: textColor,
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { borderDash: [5, 5], color: gridColor },
                            ticks: { font: { family: "'JetBrains Mono', monospace", size: 10 }, color: textColor }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: "'Plus Jakarta Sans', sans-serif", size: 11 }, color: textColor }
                        }
                    },
                    interaction: { mode: 'index', intersect: false },
                }
            });
        </script>
    @endpush

    {{-- Top Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- Income Card --}}
        <div class="motion-card rounded-[var(--radius-xl)] p-6 text-white shadow-[0_10px_20px_rgba(34,211,238,0.15)] relative overflow-hidden group"
             style="background: linear-gradient(135deg, var(--accent-teal), var(--accent-cyan));"
             data-testid="dash-income-card">
            <div class="absolute right-0 top-0 w-32 h-32 bg-white/10 rounded-full translate-x-12 -translate-y-12 transition-transform group-hover:scale-110"></div>
            <div class="absolute right-0 bottom-0 w-24 h-24 bg-white/10 rounded-full translate-x-8 translate-y-8 transition-transform group-hover:scale-110"></div>
            <div class="flex justify-between items-start mb-4 relative z-10">
                <span class="bg-white/20 px-2 py-1 rounded text-xs font-semibold tracking-wider font-display">/TOTAL</span>
                <a href="#" class="text-xs text-white/80 hover:text-white underline decoration-dashed underline-offset-2">View Detail</a>
            </div>
            <h3 class="text-white/80 font-medium mb-1 relative z-10 font-display">Pemasukan Keseluruhan</h3>
            <div class="text-3xl font-bold tracking-tight relative z-10 font-mono" style="font-variant-numeric: tabular-nums;">Rp {{ number_format($income ?? 0, 0, ',', '.') }}</div>
        </div>

        {{-- Expense Card --}}
        <div class="motion-card rounded-[var(--radius-xl)] p-6 text-white shadow-[0_10px_20px_rgba(124,92,255,0.15)] relative overflow-hidden group"
             style="background: linear-gradient(135deg, var(--accent-violet), var(--accent-magenta));"
             data-testid="dash-expense-card">
            <div class="absolute right-0 top-0 w-32 h-32 bg-white/10 rounded-full translate-x-12 -translate-y-12 transition-transform group-hover:scale-110"></div>
            <div class="absolute right-0 bottom-0 w-24 h-24 bg-white/10 rounded-full translate-x-8 translate-y-8 transition-transform group-hover:scale-110"></div>
            <div class="flex justify-between items-start mb-4 relative z-10">
                <span class="bg-white/20 px-2 py-1 rounded text-xs font-semibold tracking-wider font-display">/TOTAL</span>
                <a href="#" class="text-xs text-white/80 hover:text-white underline decoration-dashed underline-offset-2">View Detail</a>
            </div>
            <h3 class="text-white/80 font-medium mb-1 relative z-10 font-display">Pengeluaran Operasional</h3>
            <div class="text-3xl font-bold tracking-tight relative z-10 font-mono" style="font-variant-numeric: tabular-nums;">Rp {{ number_format($expense ?? 0, 0, ',', '.') }}</div>
        </div>

        {{-- Profit Card --}}
        <div class="motion-card rounded-[var(--radius-xl)] p-6 text-white shadow-[0_10px_20px_rgba(30,133,251,0.15)] relative overflow-hidden group"
             style="background: linear-gradient(135deg, var(--gold-500), var(--gold-600));"
             data-testid="dash-profit-card">
            <div class="absolute right-0 top-0 w-32 h-32 bg-black/5 rounded-full translate-x-12 -translate-y-12 transition-transform group-hover:scale-110"></div>
            <div class="absolute right-0 bottom-0 w-24 h-24 bg-black/5 rounded-full translate-x-8 translate-y-8 transition-transform group-hover:scale-110"></div>
            <div class="flex justify-between items-start mb-4 relative z-10">
                <span class="bg-black/10 px-2 py-1 rounded text-xs font-semibold tracking-wider font-display">/NET PROFIT</span>
                <a href="#" class="text-xs text-white/80 hover:text-white underline decoration-dashed underline-offset-2">View Detail</a>
            </div>
            <h3 class="text-white/80 font-medium mb-1 relative z-10 font-display">Keuntungan Bersih</h3>
            <div class="text-3xl font-bold tracking-tight relative z-10 font-mono" style="font-variant-numeric: tabular-nums;">Rp {{ number_format($profit ?? 0, 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="mb-6 flex justify-between items-end">
        <h2 class="text-xl font-bold text-[var(--color-text)] tracking-tight font-display motion-card">Performance & Activity</h2>
    </div>

    {{-- Bottom Section: Chart & Queue --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Chart Section --}}
        <x-ui.glass-card variant="default" padding="lg" class="lg:col-span-2 motion-chart h-[400px]" data-testid="dash-chart-card">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-[var(--color-text)] font-display">Sales vs Expense</h3>
                <select class="text-xs border border-[var(--color-border)] rounded-[var(--radius-sm)] text-[var(--color-text-muted)] px-2 py-1 bg-[var(--color-bg-sunken)] focus:outline-none focus:ring-1 focus:ring-[var(--color-primary)]">
                    <option>Line Chart</option>
                    <option>Bar Chart</option>
                </select>
            </div>
            <div class="relative w-full h-[300px]">
                <canvas id="financeChart"></canvas>
            </div>
        </x-ui.glass-card>

        {{-- Active Queue Section --}}
        <x-ui.glass-card variant="default" padding="lg" class="motion-queue flex flex-col h-[400px]" data-testid="dash-queue-card">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-[var(--color-text)] flex items-center gap-2 font-display">
                    Antrian Aktif
                    <span class="text-white text-[10px] px-1.5 py-0.5 rounded-full font-mono" style="background: var(--accent-rose);">{{ count($queues ?? []) }}</span>
                </h3>
            </div>

            <div class="flex-1 overflow-y-auto pr-2" style="-webkit-overflow-scrolling: touch;">
                @forelse($queues ?? [] as $queue)
                    <div class="flex items-center gap-4 py-3 border-b border-[var(--color-border-subtle)] last:border-0 hover:bg-[var(--color-bg-elevated)]/50 transition rounded-[var(--radius-sm)] px-2 -mx-2">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-white text-sm shrink-0"
                             style="background: linear-gradient(135deg, var(--accent-violet), var(--accent-cyan));">
                            {{ strtoupper(substr($queue->user->name ?? '?', 0, 2)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-[var(--color-text)] truncate font-display">{{ $queue->user->name ?? 'Guest' }} <span class="text-xs text-[var(--color-primary)] ml-1 font-sans">• New order</span></p>
                            <p class="text-[11px] text-[var(--color-text-muted)] truncate font-mono" style="font-variant-numeric: tabular-nums;">Q-NO: {{ $queue->queue_number }} — Rp
                                {{ number_format($queue->total_price ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="text-right shrink-0">
                            @if($queue->status === 'processing')
                                <x-ui.badge variant="warning" size="xs">{{ ucfirst($queue->status) }}</x-ui.badge>
                            @else
                                <x-ui.badge variant="info" size="xs">{{ ucfirst($queue->status) }}</x-ui.badge>
                            @endif
                            <p class="text-[10px] text-[var(--color-text-muted)] mt-1">
                                {{ optional($queue->created_at)->diffForHumans() ?? 'Just now' }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="h-full flex flex-col items-center justify-center pb-8">
                        <div class="w-16 h-16 mb-3 rounded-full flex items-center justify-center" style="background: var(--color-bg-sunken);">
                            <svg class="w-8 h-8 text-[var(--color-text-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <p class="text-sm text-[var(--color-text-muted)]">Tidak ada antrean</p>
                    </div>
                @endforelse
            </div>
        </x-ui.glass-card>
    </div>

@endsection