@extends('layouts.app')

@section('title', 'Histori')
@section('page-title', 'Histori Pesanan')

@section('content')

<div class="glass-card p-6 rounded-2xl">

    <table class="w-full text-sm">
        <thead>
            <tr class="text-left border-b">
                <th>No Order</th>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @foreach($orders as $order)
            <tr class="border-b">
                <td>{{ $order->order_number }}</td>
                <td>{{ $order->created_at }}</td>
                <td>Rp {{ number_format($order->total_price) }}</td>
                <td>{{ $order->status }}</td>
                <td>
                    <a href="/invoice/{{ $order->id }}" class="text-brand-primary">Lihat</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>

@endsection