<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tax Invoice #{{ $order->order_number }} - AchaarG</title>

    {{-- Fonts & Icons --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- html2pdf.js for client-side PDF compilation --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: #1e293b;
            background-color: #f1f5f9;
        }

        .font-serif-heading {
            font-family: 'Playfair Display', Georgia, serif;
        }

        /* Standard A4 Paper Specs */
        .invoice-sheet {
            width: 100%;
            max-width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            background: #ffffff;
            box-sizing: border-box;
        }

        /* Print Media Styles */
        @media print {
            @page {
                size: A4 portrait;
                margin: 10mm;
            }
            body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
                color: #000000 !important;
                font-size: 11pt;
            }
            .no-print {
                display: none !important;
            }
            .invoice-sheet {
                box-shadow: none !important;
                border: none !important;
                border-radius: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
                min-height: auto !important;
            }
            .keep-together {
                page-break-inside: avoid;
            }
        }

        /* Digital Stamp Effect */
        .digital-stamp {
            border: 2px dashed #b45309;
            border-radius: 12px;
            color: #b45309;
            transform: rotate(-3deg);
            display: inline-block;
            padding: 6px 14px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            background: rgba(254, 243, 199, 0.4);
        }
    </style>
</head>
<body class="p-4 sm:p-8 antialiased">

    <!-- Top Action Control Bar (Hidden from Print & PDF) -->
    <div class="no-print max-w-4xl mx-auto mb-6 flex flex-wrap items-center justify-between gap-3 bg-white px-6 py-4 rounded-2xl shadow-sm border border-slate-200">
        <div class="flex items-center gap-2">
            @if(auth()->check() && auth()->user()->isAdmin())
                <a href="{{ route('admin.orders.show', $order->id) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 hover:text-amber-800 transition-colors">
                    <i class="fa-solid fa-arrow-left text-[11px]"></i>
                    <span>Back to Order Management</span>
                </a>
            @elseif(auth()->check())
                <a href="{{ route('customer.orders.show', $order->order_number) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 hover:text-amber-800 transition-colors">
                    <i class="fa-solid fa-arrow-left text-[11px]"></i>
                    <span>Back to Order Details</span>
                </a>
            @else
                <a href="{{ route('checkout.confirmation', $order->order_number) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 hover:text-amber-800 transition-colors">
                    <i class="fa-solid fa-arrow-left text-[11px]"></i>
                    <span>Back to Confirmation</span>
                </a>
            @endif
        </div>

        <div class="flex items-center gap-2.5">
            {{-- Print Button --}}
            <button type="button" onclick="printInvoice()"
                class="px-4 py-2.5 rounded-xl border border-slate-300 hover:border-slate-400 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold shadow-xs transition-all flex items-center gap-2">
                <i class="fa-solid fa-print text-amber-700"></i>
                <span>Print Invoice</span>
            </button>

            {{-- Download PDF Button --}}
            <button type="button" id="downloadPdfBtn" onclick="downloadInvoicePDF()"
                class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-amber-700 to-amber-800 hover:from-amber-800 hover:to-amber-900 text-white text-xs font-bold shadow-md hover:shadow-lg transition-all flex items-center gap-2 active:scale-95">
                <i id="downloadIcon" class="fa-solid fa-file-arrow-down"></i>
                <i id="downloadSpinner" class="fa-solid fa-circle-notch fa-spin hidden"></i>
                <span id="downloadBtnText">Download Invoice (PDF)</span>
            </button>
        </div>
    </div>

    <!-- Printable & Exportable Invoice Sheet -->
    <div id="invoiceDocument" class="invoice-sheet p-8 sm:p-12 rounded-3xl shadow-xl border border-slate-200 text-slate-800">

        <!-- Top Header & Brand Strip -->
        <div class="flex flex-col sm:flex-row justify-between items-start gap-4 pb-6 border-b-2 border-slate-800">
            <!-- Brand Info -->
            <div class="flex items-start gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-amber-700 text-white flex items-center justify-center shadow-sm shrink-0">
                    <svg class="w-7 h-7 text-amber-100 fill-current" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M6 3h12v2H6V3zm13 4v13a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V7h14zm-4 3H9v2h6v-2zm0 4H9v2h6v-2z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="font-serif-heading text-2xl sm:text-3xl font-bold text-amber-950 tracking-tight leading-none">AchaarG</h1>
                    <p class="text-[11px] font-bold text-amber-800 uppercase tracking-widest mt-1">Authentic Handcrafted Pickles & Royal Mukhwas</p>
                    <p class="text-[11px] text-slate-500 mt-1">AchaarG Foods Pvt. Ltd. • Registered FSSAI & GST Compliant</p>
                </div>
            </div>

            <!-- Tax Invoice Badge -->
            <div class="text-left sm:text-right">
                <span class="inline-block px-3 py-1 bg-amber-800 text-white text-[11px] font-extrabold uppercase tracking-wider rounded-md">
                    Tax Invoice / Cash Memo
                </span>
                <p class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider mt-1.5">Original for Recipient</p>
                <p class="font-mono font-extrabold text-sm text-slate-900 mt-1">INV-{{ strtoupper(str_replace('ORD-', '', $order->order_number)) }}</p>
            </div>
        </div>

        <!-- Seller & Invoice Metadata 2-Column Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 py-6 border-b border-slate-200 text-xs">
            <!-- Sold By Details -->
            <div class="space-y-1">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Sold By / Registered Office:</span>
                <p class="font-bold text-slate-900 text-sm">AchaarG Foods Private Limited</p>
                <p class="text-slate-600">Shop No. 12, Heritage Spice Market, Old Heritage Quarter</p>
                <p class="text-slate-600">Jaipur, Rajasthan - 302001, India</p>
                <p class="text-slate-700 font-mono text-[11px] pt-1"><strong>GSTIN:</strong> 08AABCA1234F1Z8 • <strong>State:</strong> Rajasthan (08)</p>
                <p class="text-slate-500 text-[11px]"><strong>FSSAI Lic:</strong> 12224026000341 • <strong>CIN:</strong> U15400RJ2024PTC081234</p>
                <p class="text-slate-500 text-[11px]"><strong>Email:</strong> care@swaddesi.com • <strong>Phone:</strong> +91 98765 43210</p>
            </div>

            <!-- Invoice & Order Metadata -->
            <div class="sm:text-right space-y-1.5">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Invoice & Order Summary:</span>
                <div class="flex justify-between sm:justify-end gap-3 text-xs">
                    <span class="text-slate-500">Invoice Number:</span>
                    <span class="font-mono font-bold text-slate-900">INV-{{ strtoupper(str_replace('ORD-', '', $order->order_number)) }}</span>
                </div>
                <div class="flex justify-between sm:justify-end gap-3 text-xs">
                    <span class="text-slate-500">Invoice Date:</span>
                    <span class="font-semibold text-slate-800">{{ $order->created_at->format('d M, Y') }}</span>
                </div>
                <div class="flex justify-between sm:justify-end gap-3 text-xs">
                    <span class="text-slate-500">Order ID:</span>
                    <span class="font-mono font-bold text-amber-900">{{ $order->order_number }}</span>
                </div>
                <div class="flex justify-between sm:justify-end gap-3 text-xs">
                    <span class="text-slate-500">Order Date & Time:</span>
                    <span class="text-slate-700">{{ $order->created_at->format('d M, Y • h:i A') }}</span>
                </div>
                <div class="flex justify-between sm:justify-end gap-3 text-xs">
                    <span class="text-slate-500">Place of Supply:</span>
                    <span class="font-semibold text-slate-800">{{ $order->state ?? 'Rajasthan' }}</span>
                </div>
                <div class="flex justify-between sm:justify-end gap-3 text-xs">
                    <span class="text-slate-500">Reverse Charge Applicable:</span>
                    <span class="font-semibold text-slate-800">No</span>
                </div>
            </div>
        </div>

        <!-- Bill To / Ship To & Fulfillment Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 py-6 border-b border-slate-200 text-xs">
            <!-- Customer Shipping Address -->
            <div class="space-y-1">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Bill To / Ship To:</span>
                <p class="font-bold text-slate-900 text-sm">{{ $order->customer_name }}</p>
                <p class="text-slate-700">{{ $order->shipping_address }}</p>
                <p class="text-slate-700 font-semibold">{{ $order->city }}, {{ $order->state }} - {{ $order->postal_code }}</p>
                <p class="text-slate-600 pt-1"><i class="fa-solid fa-phone text-[10px] text-amber-700 mr-1"></i> +91 {{ $order->customer_phone }}</p>
                <p class="text-slate-600"><i class="fa-solid fa-envelope text-[10px] text-amber-700 mr-1"></i> {{ $order->customer_email }}</p>
            </div>

            <!-- Payment & Dispatch Status -->
            <div class="sm:text-right space-y-1.5">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-1">Payment & Shipping Mode:</span>
                <div class="flex justify-between sm:justify-end gap-3 text-xs">
                    <span class="text-slate-500">Payment Mode:</span>
                    <span class="font-bold text-slate-900 uppercase">
                        @if($order->payment_method === 'cod')
                            Cash On Delivery (COD)
                        @elseif($order->payment_method === 'online_upi')
                            UPI / Online Payment
                        @else
                            {{ strtoupper($order->payment_method) }}
                        @endif
                    </span>
                </div>
                <div class="flex justify-between sm:justify-end gap-3 text-xs">
                    <span class="text-slate-500">Payment Status:</span>
                    <span class="font-bold uppercase {{ $order->payment_status === 'paid' ? 'text-emerald-700' : 'text-amber-800' }}">
                        {{ $order->payment_status === 'paid' ? 'PAID IN FULL' : strtoupper($order->payment_status) }}
                    </span>
                </div>
                <div class="flex justify-between sm:justify-end gap-3 text-xs">
                    <span class="text-slate-500">Order Status:</span>
                    <span class="font-bold uppercase text-slate-800">{{ $order->order_status }}</span>
                </div>
                @if($order->tracking_number)
                    <div class="flex justify-between sm:justify-end gap-3 text-xs">
                        <span class="text-slate-500">Courier Partner:</span>
                        <span class="font-semibold text-slate-800">{{ $order->courier_name ?? 'Express Delivery' }}</span>
                    </div>
                    <div class="flex justify-between sm:justify-end gap-3 text-xs">
                        <span class="text-slate-500">Tracking / AWB:</span>
                        <span class="font-mono font-bold text-amber-900">{{ $order->tracking_number }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Itemized Products Table -->
        <div class="py-6 border-b-2 border-slate-800">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-100 text-slate-700 uppercase text-[10px] font-extrabold border-y border-slate-300">
                        <th class="py-2.5 px-3 text-center w-10">#</th>
                        <th class="py-2.5 px-3">Item Description</th>
                        <th class="py-2.5 px-3 text-center">HSN Code</th>
                        <th class="py-2.5 px-3 text-center">Pack / Weight</th>
                        <th class="py-2.5 px-3 text-right">Unit Price</th>
                        <th class="py-2.5 px-3 text-center">Qty</th>
                        <th class="py-2.5 px-3 text-center">GST</th>
                        <th class="py-2.5 px-3 text-right">Total Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @php
                        $totalTaxCalculated = round($order->total_amount * 0.05 / 1.05, 2);
                        $cgst = round($totalTaxCalculated / 2, 2);
                        $sgst = round($totalTaxCalculated / 2, 2);
                    @endphp
                    @foreach($order->items as $index => $item)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-3 px-3 text-center text-slate-400 font-bold">{{ $index + 1 }}</td>
                            <td class="py-3 px-3">
                                <p class="font-bold text-slate-900">{{ $item->product_name }}</p>
                                <p class="text-[10px] text-slate-400">Sun-cured traditional authentic recipe</p>
                            </td>
                            <td class="py-3 px-3 text-center font-mono text-slate-500">2001</td>
                            <td class="py-3 px-3 text-center font-semibold text-slate-700">{{ $item->variant_weight }}</td>
                            <td class="py-3 px-3 text-right font-mono text-slate-700">₹{{ number_format($item->unit_price, 2) }}</td>
                            <td class="py-3 px-3 text-center font-bold text-slate-900">{{ $item->quantity }}</td>
                            <td class="py-3 px-3 text-center text-slate-600 font-medium">5%</td>
                            <td class="py-3 px-3 text-right font-mono font-bold text-slate-900">₹{{ number_format($item->total_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Financial Breakdown & Totals -->
        <div class="py-6 border-b border-slate-200 grid grid-cols-1 sm:grid-cols-12 gap-6 items-start text-xs keep-together">
            <!-- Left: Amount in Words & Tax Note -->
            <div class="sm:col-span-7 space-y-3">
                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl space-y-1">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Amount in Words:</span>
                    <p class="font-serif-heading font-bold text-slate-900 text-sm">{{ $order->amount_in_words }}</p>
                </div>

                {{-- Tax Breakdown Table --}}
                <div class="border border-slate-200 rounded-xl overflow-hidden text-[11px]">
                    <div class="bg-slate-100 px-3 py-1.5 font-bold text-slate-700 uppercase text-[9px] tracking-wider border-b border-slate-200 flex justify-between">
                        <span>Tax Classification</span>
                        <span>Rate</span>
                        <span>Tax Amount</span>
                    </div>
                    <div class="px-3 py-1.5 flex justify-between text-slate-600 border-b border-slate-100">
                        <span>Central GST (CGST)</span>
                        <span>2.5%</span>
                        <span class="font-mono">₹{{ number_format($cgst, 2) }}</span>
                    </div>
                    <div class="px-3 py-1.5 flex justify-between text-slate-600 border-b border-slate-100">
                        <span>State GST (SGST / UTGST)</span>
                        <span>2.5%</span>
                        <span class="font-mono">₹{{ number_format($sgst, 2) }}</span>
                    </div>
                    <div class="px-3 py-1.5 flex justify-between font-bold text-slate-800 bg-slate-50">
                        <span>Total Tax Amount (Included)</span>
                        <span>5.0%</span>
                        <span class="font-mono">₹{{ number_format($totalTaxCalculated, 2) }}</span>
                    </div>
                </div>

                <p class="text-[10px] text-slate-400">
                    * Goods once sold are packed in airtight, food-grade jars. For questions about shelf life or storage, please contact support.
                </p>
            </div>

            <!-- Right: Subtotal, Shipping & Grand Total -->
            <div class="sm:col-span-5 space-y-2 text-slate-700">
                <div class="flex justify-between py-1 border-b border-slate-100">
                    <span class="text-slate-500">Items Gross Total:</span>
                    <span class="font-mono font-bold text-slate-900">₹{{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-100">
                    <span class="text-slate-500">Shipping & Handling:</span>
                    <span class="font-mono font-bold {{ $order->shipping_fee == 0 ? 'text-emerald-700' : 'text-slate-900' }}">
                        {{ $order->shipping_fee == 0 ? 'FREE (0.00)' : '₹' . number_format($order->shipping_fee, 2) }}
                    </span>
                </div>
                @if($order->discount_amount > 0)
                    <div class="flex justify-between py-1 border-b border-slate-100 text-emerald-700 font-semibold">
                        <span>Discount Savings:</span>
                        <span class="font-mono">-₹{{ number_format($order->discount_amount, 2) }}</span>
                    </div>
                @endif
                <div class="flex justify-between py-1 border-b border-slate-100">
                    <span class="text-slate-500">Included GST (5%):</span>
                    <span class="font-mono text-slate-700">₹{{ number_format($totalTaxCalculated, 2) }}</span>
                </div>

                {{-- Grand Total --}}
                <div class="flex justify-between items-center pt-3 border-t-2 border-slate-900 text-sm font-extrabold text-slate-900">
                    <span>Grand Total:</span>
                    <span class="text-xl font-extrabold text-amber-950 font-mono">₹{{ number_format($order->total_amount, 2) }}</span>
                </div>
                <p class="text-[10px] text-right text-slate-400 font-medium">All applicable taxes included</p>
            </div>
        </div>

        <!-- Declarations, Signature & Footer Signoff -->
        <div class="pt-6 grid grid-cols-1 sm:grid-cols-2 gap-6 items-end text-xs keep-together">
            <!-- Terms & Conditions -->
            <div class="space-y-1.5 text-[11px] text-slate-500">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Declaration & Terms:</span>
                <p>1. We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct.</p>
                <p>2. Handcrafted natural pickles without artificial colors or chemical preservatives.</p>
                <p>3. This is a computer-generated tax invoice and requires no physical signature.</p>
            </div>

            <!-- Authorized Signatory & Digital Stamp -->
            <div class="text-left sm:text-right space-y-2">
                <div class="digital-stamp">
                    <i class="fa-solid fa-stamp mr-1"></i> AchaarG Verified Invoice
                </div>
                <div class="pt-4">
                    <p class="font-bold text-slate-900 text-xs">For AchaarG Foods Private Limited</p>
                    <div class="h-10 flex items-center justify-start sm:justify-end">
                        <span class="font-serif-heading italic text-lg text-slate-600">Authorized Signatory</span>
                    </div>
                    <p class="text-[10px] text-slate-400">Registered Office • Jaipur, India</p>
                </div>
            </div>
        </div>

        <!-- Bottom Page Number & Support Note -->
        <div class="mt-8 pt-4 border-t border-slate-200 text-center text-[10px] text-slate-400 flex flex-col sm:flex-row justify-between items-center gap-2">
            <span>Customer Support: care@swaddesi.com | +91 98765 43210</span>
            <span>AchaarG Authenticated Document • Page 1 of 1</span>
        </div>

    </div>

    {{-- Interactive Scripts for Print & PDF Download --}}
    <script>
        // ── Direct Browser Printing ───────────────────────────
        function printInvoice() {
            window.print();
        }

        // ── High Quality PDF Download via html2pdf.js ─────────
        function downloadInvoicePDF() {
            const btn = document.getElementById('downloadPdfBtn');
            const icon = document.getElementById('downloadIcon');
            const spinner = document.getElementById('downloadSpinner');
            const btnText = document.getElementById('downloadBtnText');
            const element = document.getElementById('invoiceDocument');

            if (!element) return;

            // UI Loading State
            if (btn) btn.disabled = true;
            if (icon) icon.classList.add('hidden');
            if (spinner) spinner.classList.remove('hidden');
            if (btnText) btnText.innerText = 'Generating PDF...';

            const opt = {
                margin:       [8, 8, 8, 8],
                filename:     'Invoice-{{ $order->order_number }}.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true, logging: false },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };

            // Generate and save the PDF
            if (typeof html2pdf !== 'undefined') {
                html2pdf().set(opt).from(element).save().then(() => {
                    resetButton();
                }).catch((err) => {
                    console.error('PDF Generation Error:', err);
                    resetButton();
                    // Fallback to native print if html2pdf encounters an issue
                    window.print();
                });
            } else {
                resetButton();
                window.print();
            }

            function resetButton() {
                if (btn) btn.disabled = false;
                if (icon) icon.classList.remove('hidden');
                if (spinner) spinner.classList.add('hidden');
                if (btnText) btnText.innerText = 'Download Invoice (PDF)';
            }
        }

        // ── Auto-trigger action if passed via query params ────
        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('action') === 'print' || urlParams.get('print') === '1') {
                setTimeout(printInvoice, 500);
            } else if (urlParams.get('action') === 'download' || urlParams.get('download') === '1') {
                setTimeout(downloadInvoicePDF, 600);
            }
        });
    </script>
</body>
</html>
