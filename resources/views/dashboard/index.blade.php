@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<style>
    .dashboard-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 1.25rem;
    }

    .chart-card { grid-column: 1 / 3; }
    .queue-card { grid-column: 3 / 4; }

    @media (max-width: 1024px) {
        .dashboard-grid { grid-template-columns: 1fr 1fr; }
        .chart-card { grid-column: 1 / -1; }
        .queue-card { grid-column: 1 / -1; }
    }

    @media (max-width: 640px) {
        .dashboard-grid { grid-template-columns: 1fr; }
    }

    /* Period toggle */
    .period-toggle {
        display: flex;
        background: #F4F6F2;
        border-radius: 9px;
        padding: 3px;
        gap: 2px;
    }

    .period-btn {
        padding: 0.35rem 0.9rem;
        border: none;
        background: transparent;
        border-radius: 7px;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.78rem;
        font-weight: 500;
        color: #7A8A78;
        cursor: pointer;
        transition: all 0.2s;
    }

    .period-btn.active {
        background: white;
        color: var(--sage-deeper);
        box-shadow: 0 1px 4px rgba(0,0,0,0.08);
    }

    /* Queue list */
    .queue-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.7rem 0;
        border-bottom: 1px solid #F4F6F2;
    }

    .queue-item:last-child { border-bottom: none; }

    .queue-num {
        width: 32px; height: 32px;
        background: var(--sage-pale);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--sage-deeper);
        flex-shrink: 0;
    }

    .queue-num.active-queue {
        background: var(--sage);
        color: white;
    }

    /* Recent orders table shortcut */
    .quick-stat {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.65rem 0;
        border-bottom: 1px solid #F4F6F2;
        font-size: 0.85rem;
    }

    .quick-stat:last-child { border-bottom: none; }
</style>
@endpush

@section('content')

<div class="page-header">
    <div>
        <h2>Selamat Datang, {{ explode(' ', Auth::user()->name)[0] }} 👋</h2>
        <p>{{ now()->translatedFormat('l, d F Y') }} — Ringkasan bisnis hari ini</p>
    </div>
    <a href="{{ route('cashier.index') }}" class="btn btn-sage">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
        Buka Kasir
    </a>
</div>

{{-- Stat Cards --}}
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-label">Pendapatan Hari Ini</div>
        <div class="stat-value">Rp {{ number_format($stats['revenue_today'] ?? 0, 0, ',', '.') }}</div>
        <div class="stat-sub">
            @if(($stats['revenue_pct'] ?? 0) >= 0)
                <span style="color:#2E7D32">↑ {{ $stats['revenue_pct'] ?? 0 }}%</span>
            @else
                <span style="color:#C62828">↓ {{ abs($stats['revenue_pct'] ?? 0) }}%</span>
            @endif
            vs kemarin
        </div>
        <div class="stat-icon" style="background:#E8F5E9;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2E7D32" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Total Order</div>
        <div class="stat-value">{{ $stats['orders_today'] ?? 0 }}</div>
        <div class="stat-sub">{{ $stats['orders_pending'] ?? 0 }} masih diproses</div>
        <div class="stat-icon" style="background:#E3F2FD;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1565C0" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Antrian Aktif</div>
        <div class="stat-value">{{ $stats['queue_active'] ?? 0 }}</div>
        <div class="stat-sub">No. antrian saat ini: <strong>{{ $stats['queue_current'] ?? '-' }}</strong></div>
        <div class="stat-icon" style="background:#FFF8E1;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#F57F17" stroke-width="2"><polyline points="12 8 12 12 14 14"/><path d="M3.05 11a9 9 0 1 0 .5-4.5"/></svg>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Total Pengeluaran</div>
        <div class="stat-value">Rp {{ number_format($stats['expenses_today'] ?? 0, 0, ',', '.') }}</div>
        <div class="stat-sub">Bulan ini: Rp {{ number_format($stats['expenses_month'] ?? 0, 0, ',', '.') }}</div>
        <div class="stat-icon" style="background:#FFEBEE;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#C62828" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
        </div>
    </div>
</div>

{{-- Main Grid --}}
<div class="dashboard-grid">

    {{-- Revenue Chart --}}
    <div class="card chart-card">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
            <div>
                <div style="font-weight:600;color:var(--sage-deeper);font-size:0.95rem;">Grafik Pendapatan</div>
                <div style="font-size:0.75rem;color:#9CA89A;margin-top:2px;">Pendapatan & pengeluaran harian</div>
            </div>
            <div class="period-toggle">
                <button class="period-btn active" onclick="switchPeriod(this, 7)">7 Hari</button>
                <button class="period-btn" onclick="switchPeriod(this, 30)">30 Hari</button>
            </div>
        </div>
        <canvas id="revenueChart" height="100"></canvas>
    </div>

    {{-- Queue --}}
    <div class="card queue-card">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
            <div style="font-weight:600;color:var(--sage-deeper);font-size:0.95rem;">Antrian</div>
            <span class="badge badge-sage">{{ count($queues ?? []) }} aktif</span>
        </div>

        @forelse($queues ?? [] as $q)
        <div class="queue-item">
            <div class="queue-num {{ $loop->first ? 'active-queue' : '' }}">{{ $q->number ?? $loop->iteration }}</div>
            <div style="flex:1;min-width:0;">
                <div style="font-size:0.85rem;font-weight:500;color:#2C3328;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                    {{ $q->customer_name ?? 'Customer #' . $loop->iteration }}
                </div>
                <div style="font-size:0.72rem;color:#9CA89A;">{{ $q->service ?? 'Layanan umum' }}</div>
            </div>
            <span class="badge {{ $loop->first ? 'badge-green' : 'badge-amber' }}">
                {{ $loop->first ? 'Dilayani' : 'Tunggu' }}
            </span>
        </div>
        @empty
        <div style="text-align:center;padding:2rem 0;color:#9CA89A;font-size:0.85rem;">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#D0DAC8" stroke-width="1.5" style="margin:0 auto 0.5rem;display:block;"><polyline points="12 8 12 12 14 14"/><path d="M3.05 11a9 9 0 1 0 .5-4.5"/></svg>
            Belum ada antrian
        </div>
        @endforelse
    </div>

    {{-- Recent Orders --}}
    <div class="card" style="grid-column:1/-1;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
            <div style="font-weight:600;color:var(--sage-deeper);font-size:0.95rem;">Order Terbaru</div>
            <a href="{{ route('history.index') }}" class="btn btn-outline btn-sm">Lihat semua</a>
        </div>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No. Order</th>
                        <th>Customer</th>
                        <th>Layanan/Produk</th>
                        <th>Total</th>
                        <th>Waktu</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent_orders ?? [] as $order)
                    <tr>
                        <td><span style="font-family:monospace;font-size:0.82rem;color:var(--sage-dark);">#{{ $order->order_number ?? str_pad($loop->iteration, 4, '0', STR_PAD_LEFT) }}</span></td>
                        <td style="font-weight:500;">{{ $order->customer->name ?? 'Customer' }}</td>
                        <td style="color:#7A8A78;">{{ $order->items_summary ?? 'Layanan' }}</td>
                        <td style="font-weight:600;">Rp {{ number_format($order->total ?? 0, 0, ',', '.') }}</td>
                        <td style="color:#9CA89A;font-size:0.8rem;">{{ $order->created_at?->diffForHumans() ?? '-' }}</td>
                        <td>
                            @php $s = $order->status ?? 'done'; @endphp
                            <span class="badge {{ $s === 'done' ? 'badge-green' : ($s === 'pending' ? 'badge-amber' : 'badge-blue') }}">
                                {{ ucfirst($s) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('invoice.show', $order->id ?? 1) }}" class="btn btn-outline btn-sm">Struk</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" style="text-align:center;color:#9CA89A;padding:2rem;">Belum ada order hari ini</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
const labels7  = @json($chart_labels_7 ?? ['Sen','Sel','Rab','Kam','Jum','Sab','Min']);
const revenue7 = @json($chart_revenue_7 ?? [320000,480000,390000,520000,610000,750000,430000]);
const expense7 = @json($chart_expense_7 ?? [120000,180000,90000,200000,150000,210000,110000]);

const labels30  = @json($chart_labels_30 ?? array_map(fn($d) => date('d/m', strtotime("-$d days")), range(29,0)));
const revenue30 = @json($chart_revenue_30 ?? array_fill(0, 30, 0));
const expense30 = @json($chart_expense_30 ?? array_fill(0, 30, 0));

let chart;

function buildChart(labels, revenue, expense) {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    if (chart) chart.destroy();
    chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: 'Pendapatan',
                    data: revenue,
                    borderColor: '#96A78D',
                    backgroundColor: 'rgba(150,167,141,0.08)',
                    borderWidth: 2.5,
                    pointRadius: 3,
                    pointBackgroundColor: '#96A78D',
                    tension: 0.4,
                    fill: true,
                },
                {
                    label: 'Pengeluaran',
                    data: expense,
                    borderColor: '#E07070',
                    backgroundColor: 'rgba(224,112,112,0.05)',
                    borderWidth: 2,
                    pointRadius: 3,
                    pointBackgroundColor: '#E07070',
                    tension: 0.4,
                    fill: true,
                    borderDash: [5,4],
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { font: { family: 'DM Sans', size: 12 }, padding: 16 } },
                tooltip: {
                    callbacks: {
                        label: ctx => ' Rp ' + ctx.raw.toLocaleString('id-ID')
                    }
                }
            },
            scales: {
                y: {
                    ticks: {
                        callback: v => 'Rp ' + (v/1000).toFixed(0) + 'k',
                        font: { family: 'DM Sans', size: 11 }
                    },
                    grid: { color: 'rgba(0,0,0,0.04)' }
                },
                x: {
                    ticks: { font: { family: 'DM Sans', size: 11 } },
                    grid: { display: false }
                }
            }
        }
    });
}

function switchPeriod(btn, days) {
    document.querySelectorAll('.period-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    if (days === 7) buildChart(labels7, revenue7, expense7);
    else buildChart(labels30, revenue30, expense30);
}

buildChart(labels7, revenue7, expense7);
</script>
@endpush