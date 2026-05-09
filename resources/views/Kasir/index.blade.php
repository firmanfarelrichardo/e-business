@extends('layouts.app')

@section('title', 'Kasir')
@section('page-title', 'Kasir')
@section('page-subtitle', 'Buat order baru')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- PILIH PRODUK --}}
    <div class="lg:col-span-2 glass-card p-6 rounded-2xl">
        <h3 class="font-bold mb-4">Pilih Produk / Jasa</h3>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            @foreach($products as $product)
            <button onclick="addItem({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})"
                class="p-4 bg-white rounded-xl border hover:bg-brand-tertiary/30 text-left">
                <p class="font-semibold">{{ $product->name }}</p>
                <p class="text-sm text-slate-500">Rp {{ number_format($product->price) }}</p>
            </button>
            @endforeach
        </div>
    </div>

    {{-- CART --}}
    <div class="glass-card p-6 rounded-2xl">
        <h3 class="font-bold mb-4">Order</h3>

        <form method="POST" action="/orders">
            @csrf

            <div id="cart"></div>

            <div class="mt-4">
                <label class="text-sm">Customer</label>
                <select name="user_id" class="w-full mt-1 p-2 border rounded">
                    @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mt-4">
                <p class="font-bold">Total: Rp <span id="total">0</span></p>
            </div>

            <button class="mt-4 w-full bg-brand-primary text-white py-2 rounded-xl">
                Simpan Order
            </button>
        </form>
    </div>

</div>

@endsection

@push('scripts')
<script>
let cart = [];

function addItem(id, name, price) {
    let item = cart.find(i => i.id === id);
    if(item) {
        item.qty++;
    } else {
        cart.push({id, name, price, qty:1});
    }
    renderCart();
}

function renderCart() {
    let html = '';
    let total = 0;

    cart.forEach((item, i) => {
        total += item.price * item.qty;

        html += `
        <div class="flex justify-between mb-2">
            <div>
                <p>${item.name}</p>
                <small>${item.qty} x ${item.price}</small>
            </div>
            <button type="button" onclick="removeItem(${i})">x</button>
        </div>

        <input type="hidden" name="items[${i}][product_id]" value="${item.id}">
        <input type="hidden" name="items[${i}][qty]" value="${item.qty}">
        `;
    });

    document.getElementById('cart').innerHTML = html;
    document.getElementById('total').innerText = total;
}

function removeItem(i){
    cart.splice(i,1);
    renderCart();
}
</script>
@endpush