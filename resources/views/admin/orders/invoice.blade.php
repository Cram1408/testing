<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice #{{ $order->order_number }} - Swad Desi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; background: white; }
        }
    </style>
</head>
<body class="bg-slate-100 p-6 sm:p-10 text-slate-800 antialiased">
    <div class="max-w-3xl mx-auto bg-white p-8 sm:p-12 rounded-3xl shadow-lg border border-slate-200">
        
        <!-- Print Action Bar -->
        <div class="no-print mb-6 pb-4 border-b border-slate-200 flex items-center justify-between">
            <a href="{{ route('admin.orders.show', $order->id) }}" class="text-xs font-bold text-slate-500 hover:text-slate-900">← Back to Order</a>
            <button onclick="window.print()" class="px-5 py-2 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-xl shadow-md transition-all">
                Print Packing Slip / Invoice
            </button>
        </div>

        <!-- Invoice Header -->
        <div class="flex items-start justify-between border-b border-slate-200 pb-6">
            <div>
                <h1 class="text-2xl font-extrabold text-amber-950">Swad Desi</h1>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mt-0.5">Handcrafted Pickles & Royal Mukhwas</p>
                <div class="text-[11px] text-slate-500 mt-2 space-y-0.5">
                    <p>Shop 12, Spice Market, Old Heritage Quarter</p>
                    <p>Jaipur, Rajasthan 302001 • India</p>
                    <p>Phone: +91 98765 43210 • care@swaddesi.com</p>
                </div>
            </div>

            <div class="text-right">
                <span class="inline-block px-3 py-1 bg-amber-100 text-amber-900 rounded-lg text-xs font-extrabold uppercase">Tax Invoice</span>
                <p class="font-mono font-bold text-slate-900 text-sm mt-2">#{{ $order->order_number }}</p>
                <p class="text-xs text-slate-500 mt-1">Date: {{ $order->created_at->format('d M, Y') }}</p>
                <p class="text-xs text-slate-500">Status: <strong class="uppercase text-emerald-700">{{ $order->payment_status }}</strong></p>
            </div>
        </div>

        <!-- Bill To / Ship To -->
        <div class="grid grid-cols-2 gap-8 py-6 border-b border-slate-200 text-xs">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Delivered To:</span>
                <p class="font-bold text-slate-900 text-sm">{{ $order->customer_name }}</p>
                <p class="text-slate-600 mt-0.5">{{ $order->shipping_address }}</p>
                <p class="text-slate-600">{{ $order->city }}, {{ $order->state }} - {{ $order->postal_code }}</p>
                <p class="text-slate-500 mt-1">Phone: {{ $order->customer_phone }}</p>
                <p class="text-slate-500">Email: {{ $order->customer_email }}</p>
            </div>

            <div class="text-right">
                <span class="text-[10px] font-bold text-slate-400 uppercase block mb-1">Fulfillment Details:</span>
                <p class="font-semibold text-slate-800">Payment: <span class="uppercase">{{ $order->payment_method }}</span></p>
                <p class="text-slate-600 mt-1">Order Status: <span class="uppercase font-bold text-amber-900">{{ $order->order_status }}</span></p>
                @if($order->tracking_number)
                    <p class="text-slate-600 mt-1">Courier: {{ $order->courier_name ?? 'Express Partner' }}</p>
                    <p class="font-mono font-bold text-slate-800 text-[11px]">AWB: {{ $order->tracking_number }}</p>
                @endif
            </div>
        </div>

        <!-- Items Table -->
        <div class="py-6 border-b border-slate-200">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-400 uppercase text-[10px] font-bold border-b border-slate-200">
                        <th class="pb-2">Item Description</th>
                        <th class="pb-2 text-center">Size / Weight</th>
                        <th class="pb-2 text-center">Unit Price</th>
                        <th class="pb-2 text-center">Qty</th>
                        <th class="pb-2 text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($order->items as $item)
                        <tr>
                            <td class="py-3 font-bold text-slate-900">{{ $item->product_name }}</td>
                            <td class="py-3 text-center text-slate-600 font-semibold">{{ $item->variant_weight }}</td>
                            <td class="py-3 text-center text-slate-600">₹{{ number_format($item->unit_price, 2) }}</td>
                            <td class="py-3 text-center font-bold text-slate-900">{{ $item->quantity }}</td>
                            <td class="py-3 text-right font-extrabold text-slate-900">₹{{ number_format($item->total_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals Breakdown -->
        <div class="pt-4 flex justify-between items-start text-xs">
            <div class="text-slate-400 max-w-xs space-y-1">
                <p class="font-bold text-slate-600">Packing & Handling Note:</p>
                <p>Every pickle jar is sealed with food-grade foil and bubble-cushioned. Best consumed within shelf life period specified on the jar lid.</p>
            </div>

            <div class="w-64 space-y-2 text-slate-600">
                <div class="flex justify-between">
                    <span>Subtotal:</span>
                    <span class="font-bold text-slate-900">₹{{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Shipping Charges:</span>
                    <span class="font-bold {{ $order->shipping_fee == 0 ? 'text-emerald-700' : 'text-slate-900' }}">
                        {{ $order->shipping_fee == 0 ? 'FREE' : '₹' . number_format($order->shipping_fee, 2) }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span>Taxes (GST):</span>
                    <span class="font-bold text-emerald-700">Included</span>
                </div>
                <div class="flex justify-between text-base font-extrabold text-slate-900 pt-2 border-t border-slate-200">
                    <span>Grand Total:</span>
                    <span class="text-amber-950">₹{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Footer Signoff -->
        <div class="mt-12 pt-6 border-t border-slate-200 text-center text-[10px] text-slate-400">
            <p>Thank you for choosing Swad Desi authentic handcrafted food traditions!</p>
            <p class="mt-0.5">This is a computer-generated tax invoice. For queries, contact care@swaddesi.com</p>
        </div>

    </div>
</body>
</html>
