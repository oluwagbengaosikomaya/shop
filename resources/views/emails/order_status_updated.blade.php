<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Order Status Update</title>
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
                        <p style="margin:6px 0 0;color:rgba(255,255,255,.85);font-size:14px;">Order Update</p>
                    </td>
                </tr>

                {{-- Status Badge --}}
                @php
                    $colors = [
                        'processing' => '#0d6efd',
                        'shipped'    => '#0dcaf0',
                        'completed'  => '#198754',
                        'cancelled'  => '#dc3545',
                        'pending'    => '#ffc107',
                    ];
                    $color = $colors[$order->status] ?? '#6c757d';
                @endphp
                <tr>
                    <td style="padding:24px 32px 0;text-align:center;">
                        <span style="display:inline-block;background:{{ $color }};color:#fff;padding:6px 20px;border-radius:20px;font-size:15px;font-weight:bold;text-transform:capitalize;">
                            {{ $order->status }}
                        </span>
                    </td>
                </tr>

                {{-- Body --}}
                <tr>
                    <td style="padding:24px 32px 32px;">
                        <p style="font-size:16px;margin:0 0 8px;">Hi <strong>{{ $order->customer_name }}</strong>,</p>

                        @if($order->status === 'processing')
                            <p style="color:#555;margin:0 0 24px;">Great news! Your order <strong>#{{ $order->id }}</strong> is now being processed. We'll notify you once it ships.</p>
                        @elseif($order->status === 'shipped')
                            <p style="color:#555;margin:0 0 24px;">Your order <strong>#{{ $order->id }}</strong> is on its way! Expect delivery soon.</p>
                        @elseif($order->status === 'completed')
                            <p style="color:#555;margin:0 0 24px;">Your order <strong>#{{ $order->id }}</strong> has been delivered. We hope you love your gift! 🎉</p>
                        @elseif($order->status === 'cancelled')
                            <p style="color:#555;margin:0 0 24px;">Your order <strong>#{{ $order->id }}</strong> has been cancelled. If you have questions, please contact us.</p>
                        @else
                            <p style="color:#555;margin:0 0 24px;">Your order <strong>#{{ $order->id }}</strong> status has been updated to <strong>{{ ucfirst($order->status) }}</strong>.</p>
                        @endif

                        {{-- Order Summary --}}
                        <table width="100%" cellpadding="8" cellspacing="0" style="background:#f8f9fa;border-radius:6px;margin-bottom:24px;">
                            <tr>
                                <td style="font-size:13px;color:#666;">Order Number</td>
                                <td style="font-size:13px;font-weight:bold;text-align:right;">#{{ $order->id }}</td>
                            </tr>
                            <tr>
                                <td style="font-size:13px;color:#666;">Total</td>
                                <td style="font-size:13px;font-weight:bold;text-align:right;color:#dc3545;">₦{{ number_format($order->total) }}</td>
                            </tr>
                            <tr>
                                <td style="font-size:13px;color:#666;">Status</td>
                                <td style="font-size:13px;text-align:right;text-transform:capitalize;font-weight:bold;">{{ $order->status }}</td>
                            </tr>
                        </table>

                        <p style="font-size:14px;color:#555;margin:0;">Questions? Reply to this email or visit our shop.</p>
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
