<h2>Thank you for your order, {{ $order->customer_name }}!</h2>

<p>Order ID: {{ $order->id }}</p>
<p>Total: ₦{{ number_format($order->total) }}</p>

<h4>Items:</h4>
<ul>
@foreach($order->items as $item)
    <li>{{ $item->product_name }} x {{ $item->quantity }} - ₦{{ number_format($item->price * $item->quantity) }}</li>
@endforeach
</ul>

<p>Status: {{ $order->status }}</p>
