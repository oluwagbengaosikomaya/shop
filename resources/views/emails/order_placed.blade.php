<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Order Confirmation</title>
</head>
<body style="margin:0;padding:0;background:#f4f6f9;font-family:Arial,sans-serif;color:#333;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f9;padding:30px 0;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.08);">

                {{-- Header --}}
                <tr>
                    <td style="background:#dc3545;padding:24px 32px;text-align:center;">
                        <h1 style="margin:0;color:#fff;font-size:22px;">🎁 The Gift Shop</h1>
                        <p style="margin:6px 0 0;color:rgba(255,255,255,.85);font-size:14px;">Order Confirmation</p>
                    </td>
                </tr>

                {{-- Body --}}
                <tr>
                    <td style="padding:32px;">
                        <p style="font-size:16px;margin:0 0 8px;">Hi <strong>{{ $order->customer_name }}</strong>,</p>
                        <p style="color:#555;margin:0 0 24px;">Thank you for your order! We've received it and will begin processing shortly.</p>

                        {{-- Order Meta --}}
                        <table width="100%" cellpadding="8" cellspacing="0" style="background:#f8f9fa;border-radius:6px;margin-bottom:24px;">
                            <tr>
                                <td style="font-size:13px;color:#666;">Order Number</td>
                                <td style="font-size:13px;font-weight:bold;text-align:right;">#{{ $order->id }}</td>
                            </tr>
                            <tr>
                                <td style="font-size:13px;color:#666;">Date</td>
                                <td style="font-size:13px;text-align:right;">{{ $order->created_at->format('d M Y, h:i A') }}</td>
                            </tr>
                            <tr>
                                <td style="font-size:13px;color:#666;">Status</td>
                                <td style="font-size:13px;text-align:right;text-transform:capitalize;">{{ $order->status }}</td>
                            </tr>
                        </table>

                        {{-- Items Table --}}
                        <h3 style="font-size:15px;margin:0 0 12px;border-bottom:2px solid #f0f0f0;padding-bottom:8px;">Order Items</h3>
                        <table width="100%" cellpadding="10" cellspacing="0" style="border-collapse:collapse;margin-bottom:24px;">
                            <thead>
                                <tr style="background:#f8f9fa;">
                                    <th style="text-align:left;font-size:13px;color:#666;border-bottom:1px solid #dee2e6;">Product</th>
                                    <th style="text-align:center;font-size:13px;color:#666;border-bottom:1px solid #dee2e6;">Qty</th>
                                    <th style="text-align:right;font-size:13px;color:#666;border-bottom:1px solid #dee2e6;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td style="font-size:14px;border-bottom:1px solid #f0f0f0;">{{ $item->product_name }}</td>
                                    <td style="font-size:14px;text-align:center;border-bottom:1px solid #f0f0f0;">{{ $item->quantity }}</td>
                                    <td style="font-size:14px;text-align:right;border-bottom:1px solid #f0f0f0;">₦{{ number_format($item->price * $item->quantity) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" style="text-align:right;font-weight:bold;padding-top:12px;">Total:</td>
                                    <td style="text-align:right;font-weight:bold;font-size:16px;color:#dc3545;padding-top:12px;">₦{{ number_format($order->total) }}</td>
                                </tr>
                            </tfoot>
                        </table>

                        {{-- Delivery Address --}}
                        @if($order->delivery_address)
                        <h3 style="font-size:15px;margin:0 0 12px;border-bottom:2px solid #f0f0f0;padding-bottom:8px;">Delivery Address</h3>
                        <p style="margin:0 0 4px;font-size:14px;">{{ $order->delivery_address }}</p>
                        @if($order->delivery_city || $order->delivery_state)
                        <p style="margin:0 0 24px;font-size:14px;color:#666;">{{ implode(', ', array_filter([$order->delivery_city, $order->delivery_state])) }}</p>
                        @endif
                        @endif

                        <p style="font-size:14px;color:#555;margin:0;">If you have any questions, reply to this email or contact us through the shop.</p>
                    </td>
                </tr>

                {{-- Footer --}}
                <tr>
                    <td style="background:#f8f9fa;padding:16px 32px;text-align:center;border-top:1px solid #dee2e6;">
                        <p style="margin:0;font-size:12px;color:#999;">© {{ date('Y') }} The Gift Shop · Nigeria</p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>
</html>
