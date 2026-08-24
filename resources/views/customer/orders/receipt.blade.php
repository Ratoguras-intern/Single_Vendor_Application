<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $order->order_number }}</title>
    @include('partials.favicon')
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; color: #1a1a1a; background: #f5f5f5; padding: 2rem; }
        .receipt { max-width: 640px; margin: 0 auto; background: #fff; border: 1px solid #e5e5e5; border-radius: 8px; padding: 2rem; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid #1a1a1a; }
        .shop-name { font-size: 1.5rem; font-weight: 700; }
        .shop-tagline { font-size: 0.75rem; color: #666; margin-top: 0.125rem; }
        .order-meta { text-align: right; }
        .order-meta .order-num { font-size: 1rem; font-weight: 700; font-family: monospace; }
        .order-meta .date { font-size: 0.75rem; color: #666; margin-top: 0.125rem; }
        .section { margin-bottom: 1.25rem; }
        .section-title { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #999; margin-bottom: 0.5rem; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; font-size: 0.8rem; }
        .info-grid dt { color: #666; }
        .info-grid dd { font-weight: 600; }
        table { width: 100%; border-collapse: collapse; font-size: 0.8rem; }
        thead th { text-align: left; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #999; padding: 0.5rem 0; border-bottom: 1px solid #e5e5e5; }
        thead th:last-child { text-align: right; }
        tbody td { padding: 0.5rem 0; border-bottom: 1px solid #f0f0f0; vertical-align: top; }
        tbody td:last-child { text-align: right; font-weight: 600; }
        .item-name { font-weight: 600; }
        .item-meta { font-size: 0.7rem; color: #666; margin-top: 0.125rem; }
        .totals { margin-top: 0.75rem; }
        .totals-row { display: flex; justify-content: space-between; font-size: 0.8rem; padding: 0.25rem 0; }
        .totals-row.discount { color: #16a34a; }
        .totals-row.total { border-top: 2px solid #1a1a1a; margin-top: 0.5rem; padding-top: 0.5rem; font-size: 1rem; font-weight: 700; }
        .footer { margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid #e5e5e5; text-align: center; font-size: 0.7rem; color: #999; }
        @media print {
            body { background: #fff; padding: 0; }
            .receipt { border: none; border-radius: 0; padding: 0; max-width: 100%; box-shadow: none; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="max-width:640px;margin:0 auto 1rem;text-align:right;">
        <button onclick="window.print()" style="padding:0.5rem 1rem;border:1px solid #d1d5db;border-radius:6px;background:#fff;font-size:0.8rem;cursor:pointer;font-weight:600;">Print Receipt</button>
    </div>

    <div class="receipt">
        <div class="header">
            <div>
                <div class="shop-name">{{ site_name() }}</div>
                <div class="shop-tagline">Order Receipt</div>
            </div>
            <div class="order-meta">
                <div class="order-num">#{{ $order->order_number }}</div>
                <div class="date">{{ $order->created_at->format('M d, Y h:i A') }}</div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Customer</div>
            <dl class="info-grid">
                <div>
                    <dt>Name</dt>
                    <dd>{{ $order->user->name ?? 'Guest' }}</dd>
                </div>
                <div>
                    <dt>Email</dt>
                    <dd>{{ $order->user->email ?? '—' }}</dd>
                </div>
                <div>
                    <dt>Phone</dt>
                    <dd>{{ $order->phone ?: '—' }}</dd>
                </div>
                <div>
                    <dt>Payment</dt>
                    <dd>{{ str_replace('_', ' ', ucfirst($order->payment_method)) }} ({{ ucfirst($order->payment_status) }})</dd>
                </div>
            </dl>
        </div>

        <div class="section">
            <div class="section-title">Items Ordered</div>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td>
                                <div class="item-name">{{ $item->product->name ?? 'Product #' . $item->product_id }}</div>
                                @if($item->product && $item->product->sku)
                                    <div class="item-meta">SKU: {{ $item->product->sku }}</div>
                                @endif
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ format_currency($item->price) }}</td>
                            <td>{{ format_currency($item->price * $item->quantity) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="totals">
                <div class="totals-row">
                    <span>Subtotal</span>
                    <span>{{ format_currency($order->subtotal) }}</span>
                </div>
                <div class="totals-row">
                    <span>Shipping</span>
                    <span>{{ $order->shipping > 0 ? format_currency($order->shipping) : 'Free' }}</span>
                </div>
                <div class="totals-row">
                    <span>Tax</span>
                    <span>{{ format_currency($order->tax) }}</span>
                </div>
                @if ($order->discount > 0)
                    <div class="totals-row discount">
                        <span>Discount</span>
                        <span>-{{ format_currency($order->discount) }}</span>
                    </div>
                @endif
                <div class="totals-row total">
                    <span>Total</span>
                    <span>{{ format_currency($order->total_amount) }}</span>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Shipping Address</div>
            <p style="font-size:0.8rem;white-space:pre-line;line-height:1.5;">{{ $order->shipping_address }}</p>
        </div>

        @if($order->billing_address && $order->billing_address !== $order->shipping_address)
            <div class="section">
                <div class="section-title">Billing Address</div>
                <p style="font-size:0.8rem;white-space:pre-line;line-height:1.5;">{{ $order->billing_address }}</p>
            </div>
        @endif

        <div class="footer">
            Thank you for your purchase!<br>
            {{ site_name() }} &mdash; {{ now()->year }}
        </div>
    </div>
</body>
</html>
