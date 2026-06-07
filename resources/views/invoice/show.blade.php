<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</head>
<body>

<h2>Sinergi ATK</h2>

<p>No: {{ $order->order_number }}</p>
<p>Tanggal: {{ $order->created_at }}</p>

<hr>

@foreach($order->items as $item)
<p>
    {{ $item->product_name }} 
    ({{ $item->qty }} x {{ $item->price }}) 
    = {{ $item->qty * $item->price }}
</p>
@endforeach

<hr>

<h3>Total: Rp {{ number_format($order->total_price) }}</h3>

</body>
</html>