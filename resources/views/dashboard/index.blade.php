@extends('components.layouts.dashboard')

@section('content')

    <!-- Motion & ChartJS Libraries via CDN inside view for safety if build fails -->
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script type="module">
            import { animate, stagger, scroll } from "https://esm.sh/motion";

            // Page load animations using Framer Motion (Motion One vanilla)
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

            // Chart.js implementation
            const ctx = document.getElementById('financeChart').getContext('2d');

            // Smooth curves imitating the reference image
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [
                        {
                            label: 'Income',
                            data: [500000, 750000, 600000, 1200000, 1000000, {{ $income }}],
                            borderColor: '#7B9B6F', // Sage green for income
                            backgroundColor: 'rgba(123, 155, 111, 0.1)',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Expense',
                            data: [300000, 200000, 450000, 300000, 500000, {{ $expense }}],
                            borderColor: '#B09282', // Warm earthy tone for expense
                            backgroundColor: 'rgba(176, 146, 130, 0.1)',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: false
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top', align: 'end', labels: { usePointStyle: true, boxWidth: 8 } }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { borderDash: [5, 5], color: '#e2e8f0' } },
                        x: { grid: { display: false } }
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                }
            });
        </script>
    @endpush

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800 tracking-tight motion-card">Overview</h1>
        <p class="text-sm text-gray-500 motion-card">Pantau semua metrik penjualan dan pengeluaran</p>
    </div>

    <!-- Top Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Income Card -->
        <div
            class="motion-card bg-gradient-to-br from-[#7B9B6F] to-[#5A6852] rounded-2xl p-6 text-white shadow-[0_10px_20px_rgba(90,104,82,0.25)] relative overflow-hidden group">
            <div
                class="absolute right-0 top-0 w-32 h-32 bg-white/10 rounded-full translate-x-12 -translate-y-12 transition-transform group-hover:scale-110">
            </div>
            <div
                class="absolute right-0 bottom-0 w-24 h-24 bg-white/10 rounded-full translate-x-8 translate-y-8 transition-transform group-hover:scale-110">
            </div>

            <div class="flex justify-between items-start mb-4 relative z-10">
                <span class="bg-white/20 px-2 py-1 rounded text-xs font-semibold tracking-wider">/TOTAL</span>
                <a href="#"
                    class="text-xs text-white/80 hover:text-white underline decoration-dashed underline-offset-2">View
                    Detail</a>
            </div>
            <h3 class="text-white/80 font-medium mb-1 relative z-10">Pemasukan Keseluruhan</h3>
            <div class="text-3xl font-bold tracking-tight relative z-10">Rp {{ number_format($income ?? 0, 0, ',', '.') }}
            </div>
        </div>

        <!-- Expense Card -->
        <div
            class="motion-card bg-gradient-to-br from-[#B09282] to-[#94786A] rounded-2xl p-6 text-white shadow-[0_10px_20px_rgba(148,120,106,0.25)] relative overflow-hidden group">
            <div
                class="absolute right-0 top-0 w-32 h-32 bg-white/10 rounded-full translate-x-12 -translate-y-12 transition-transform group-hover:scale-110">
            </div>
            <div
                class="absolute right-0 bottom-0 w-24 h-24 bg-white/10 rounded-full translate-x-8 translate-y-8 transition-transform group-hover:scale-110">
            </div>

            <div class="flex justify-between items-start mb-4 relative z-10">
                <span class="bg-white/20 px-2 py-1 rounded text-xs font-semibold tracking-wider">/TOTAL</span>
                <a href="#"
                    class="text-xs text-white/80 hover:text-white underline decoration-dashed underline-offset-2">View
                    Detail</a>
            </div>
            <h3 class="text-white/80 font-medium mb-1 relative z-10">Pengeluaran Operasional</h3>
            <div class="text-3xl font-bold tracking-tight relative z-10">Rp {{ number_format($expense ?? 0, 0, ',', '.') }}
            </div>
        </div>

        <!-- Profit Card -->
        <div
            class="motion-card bg-gradient-to-br from-[#96A78D] to-[#7B9B6F] rounded-2xl p-6 text-white shadow-[0_10px_20px_rgba(123,155,111,0.25)] relative overflow-hidden group">
            <div
                class="absolute right-0 top-0 w-32 h-32 bg-black/5 rounded-full translate-x-12 -translate-y-12 transition-transform group-hover:scale-110">
            </div>
            <div
                class="absolute right-0 bottom-0 w-24 h-24 bg-black/5 rounded-full translate-x-8 translate-y-8 transition-transform group-hover:scale-110">
            </div>

            <div class="flex justify-between items-start mb-4 relative z-10">
                <span class="bg-black/10 px-2 py-1 rounded text-xs font-semibold tracking-wider">/NET PROFIT</span>
                <a href="#"
                    class="text-xs text-white/80 hover:text-white underline decoration-dashed underline-offset-2">View
                    Detail</a>
            </div>
            <h3 class="text-white/80 font-medium mb-1 relative z-10">Keuntungan Bersih</h3>
            <div class="text-3xl font-bold tracking-tight relative z-10">Rp {{ number_format($profit ?? 0, 0, ',', '.') }}
            </div>
        </div>
    </div>

    <div class="mb-6 flex justify-between items-end">
        <h2 class="text-xl font-bold text-gray-800 tracking-tight motion-card">Performance & Activity</h2>
    </div>

    <!-- Bottom Section: Chart & Queue list -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Chart Section -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-gray-100 motion-chart h-[400px]">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-gray-800">Sales vs Expense</h3>
                <!-- Chart Filter mockup -->
                <select class="text-xs border-gray-200 rounded text-gray-500 focus:ring-brand-primary">
                    <option>Line Chart</option>
                    <option>Bar Chart</option>
                </select>
            </div>
            <div class="relative w-full h-[300px]">
                <canvas id="financeChart"></canvas>
            </div>
        </div>

        <!-- Active Queue Section -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 motion-queue flex flex-col h-[400px]">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    User Activity / Queue
                    <span
                        class="bg-red-500 text-white text-[10px] px-1.5 py-0.5 rounded-full">{{ count($queues ?? []) }}</span>
                </h3>
                <button class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                            d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                    </svg>
                </button>
            </div>

            <!-- Stylish User Queue Table List -->
            <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
                <style>
                    .custom-scrollbar::-webkit-scrollbar {
                        width: 4px;
                    }

                    .custom-scrollbar::-webkit-scrollbar-thumb {
                        background: #e2e8f0;
                        border-radius: 4px;
                    }
                </style>

                @forelse($queues ?? [] as $queue)
                    <div
                        class="flex items-center gap-4 py-3 border-b border-gray-50 last:border-0 hover:bg-gray-50 transition rounded-lg px-2 -mx-2">
                        <!-- Avatar initals -->
                        <div
                            class="w-10 h-10 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center font-bold text-gray-500 text-sm shrink-0">
                            {{ strtoupper(substr($queue->user->name ?? '?', 0, 2)) }}
                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ $queue->user->name ?? 'Guest' }} <span
                                    class="text-xs text-brand-primary ml-1">• New order</span></p>
                            <p class="text-[11px] text-gray-500 truncate">Q-NO: {{ $queue->queue_number }} — Rp
                                {{ number_format($queue->total_price ?? 0, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="text-right shrink-0">
                            <span
                                class="inline-flex py-1 px-2 rounded-full text-[10px] font-medium 
                                        {{ $queue->status === 'processing' ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ ucfirst($queue->status) }}
                            </span>
                            <p class="text-[10px] text-gray-400 mt-1">
                                {{ optional($queue->created_at)->diffForHumans() ?? 'Just now' }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="h-full flex flex-col items-center justify-center text-gray-400 pb-8">
                        <svg class="w-12 h-12 mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <p class="text-sm">Tidak ada antrean</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

@endsection