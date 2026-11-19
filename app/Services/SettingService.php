<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class SettingService
{
    protected static string $settingsFile = 'settings.json';

    /**
     * Default fallback settings for AchaarG.
     */
    public static function defaults(): array
    {
        return [
            // Store Identity & Legal
            'store_name'            => 'AchaarG',
            'store_tagline'         => 'Handcrafted Sun-Dried Pickles & Royal Mukhwas',
            'support_email'         => 'care@swaddesi.com',
            'support_phone'         => '+91 98765 43210',
            'store_address'         => 'Shop No. 12, Heritage Spice Market, Old Heritage Quarter, Jaipur, Rajasthan - 302001, India',
            'gstin_number'          => '08AABCA1234F1Z8',
            'fssai_number'          => '12224026000341',

            // Shipping & Delivery Policy
            'free_shipping_min'     => 499,
            'standard_shipping_fee' => 49,
            'delivery_estimate'     => '3 - 5 business days',
            'packaging_charge'      => 0,

            // Announcement & Top Bar
            'announcement_active'   => true,
            'announcement_badge'    => "Dadi's Secret",
            'announcement_text'     => "Sun-Dried Pickles in Kachi Ghani Mustard Oil & Royal Digestive Mukhwas",

            // Payment Gateways & Methods
            'enable_cod'            => true,
            'enable_online_upi'     => true,

            // Active Discount Coupons
            'coupons' => [
                [
                    'code'          => 'WELCOME10',
                    'discount_type' => 'percentage',
                    'value'         => 10,
                    'min_order'     => 299,
                    'is_active'     => true,
                    'description'   => '10% off for first-time orders',
                ],
                [
                    'code'          => 'DESI50',
                    'discount_type' => 'flat',
                    'value'         => 50,
                    'min_order'     => 599,
                    'is_active'     => true,
                    'description'   => 'Flat ₹50 off on orders above ₹599',
                ],
            ],
        ];
    }

    /**
     * Get a specific setting value with fallback.
     */
    public static function get(string $key, $fallback = null)
    {
        $all = self::all();
        if (array_key_exists($key, $all)) {
            return $all[$key];
        }

        $defaults = self::defaults();
        return $defaults[$key] ?? $fallback;
    }

    /**
     * Retrieve all settings merged with defaults.
     */
    public static function all(): array
    {
        $path = storage_path('app/' . self::$settingsFile);
        if (!File::exists($path)) {
            return self::defaults();
        }

        $stored = json_decode(File::get($path), true) ?: [];
        return array_merge(self::defaults(), $stored);
    }

    /**
     * Save/update settings to storage.
     */
    public static function update(array $newSettings): void
    {
        $current = self::all();
        $updated = array_merge($current, $newSettings);

        $dir = storage_path('app');
        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        File::put($dir . '/' . self::$settingsFile, json_encode($updated, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

