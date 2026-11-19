<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Show Website Settings form.
     */
    public function index()
    {
        $settings = SettingService::all();
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update Website Settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'store_name'            => 'required|string|max:100',
            'store_tagline'         => 'nullable|string|max:200',
            'support_email'         => 'required|email|max:100',
            'support_phone'         => 'required|string|max:30',
            'store_address'         => 'required|string|max:255',
            'gstin_number'          => 'nullable|string|max:50',
            'fssai_number'          => 'nullable|string|max:50',
            'free_shipping_min'     => 'required|numeric|min:0',
            'standard_shipping_fee' => 'required|numeric|min:0',
            'delivery_estimate'     => 'nullable|string|max:100',
            'announcement_active'   => 'nullable|boolean',
            'announcement_badge'    => 'nullable|string|max:50',
            'announcement_text'     => 'nullable|string|max:255',
            'enable_cod'            => 'nullable|boolean',
            'enable_online_upi'     => 'nullable|boolean',
            'new_coupon_code'       => 'nullable|string|max:20',
            'new_coupon_type'       => 'nullable|in:percentage,flat',
            'new_coupon_val'        => 'nullable|numeric|min:1',
            'new_coupon_min'        => 'nullable|numeric|min:0',
        ]);

        $settingsData = [
            'store_name'            => $validated['store_name'],
            'store_tagline'         => $validated['store_tagline'] ?? '',
            'support_email'         => $validated['support_email'],
            'support_phone'         => $validated['support_phone'],
            'store_address'         => $validated['store_address'],
            'gstin_number'          => $validated['gstin_number'] ?? '',
            'fssai_number'          => $validated['fssai_number'] ?? '',
            'free_shipping_min'     => (float) $validated['free_shipping_min'],
            'standard_shipping_fee' => (float) $validated['standard_shipping_fee'],
            'delivery_estimate'     => $validated['delivery_estimate'] ?? '3 - 5 business days',
            'announcement_active'   => $request->boolean('announcement_active'),
            'announcement_badge'    => $validated['announcement_badge'] ?? "Dadi's Secret",
            'announcement_text'     => $validated['announcement_text'] ?? '',
            'enable_cod'            => $request->boolean('enable_cod'),
            'enable_online_upi'     => $request->boolean('enable_online_upi'),
        ];

        // Handle adding a new coupon if provided
        $currentCoupons = SettingService::get('coupons', []);
        if (!empty($validated['new_coupon_code']) && !empty($validated['new_coupon_val'])) {
            $currentCoupons[] = [
                'code'          => strtoupper(trim($validated['new_coupon_code'])),
                'discount_type' => $validated['new_coupon_type'] ?? 'percentage',
                'value'         => (float) $validated['new_coupon_val'],
                'min_order'     => (float) ($validated['new_coupon_min'] ?? 0),
                'is_active'     => true,
                'description'   => ($validated['new_coupon_type'] === 'percentage' ? $validated['new_coupon_val'] . '% off' : 'Flat ₹' . $validated['new_coupon_val'] . ' off'),
            ];
            $settingsData['coupons'] = $currentCoupons;
        }

        SettingService::update($settingsData);

        return back()->with('success', 'Website settings and storefront policies updated successfully.');
    }

    /**
     * Delete or toggle an active coupon.
     */
    public function removeCoupon(Request $request, string $code)
    {
        $currentCoupons = SettingService::get('coupons', []);
        $updated = array_values(array_filter($currentCoupons, fn ($c) => strtoupper($c['code']) !== strtoupper($code)));

        SettingService::update(['coupons' => $updated]);

        return back()->with('success', "Coupon {$code} removed successfully.");
    }
}

