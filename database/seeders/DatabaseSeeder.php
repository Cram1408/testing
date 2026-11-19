<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Users (Idempotent)
        $admin = User::firstOrCreate(
            ['email' => 'adminram14@achaarg.com'],
            [
                'name' => 'Store Administrator',
                'password' => Hash::make('adminR1a2m3p4@'),
                'role' => 'admin',
                'phone' => '+91 98765 43210',
                'address' => 'Shop 12, Spice Market, Old Heritage Quarter',
                'city' => 'Jaipur',
                'state' => 'Rajasthan',
                'postal_code' => '302001',
            ]
        );

        $customer = User::firstOrCreate(
            ['email' => 'customer@pickles.com'],
            [
                'name' => 'Aarav Sharma',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'phone' => '+91 98111 22334',
                'address' => 'Flat 402, Royal Palms Heights, Link Road, Andheri West',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'postal_code' => '400053',
            ]
        );

        // 2. Seed Categories (Idempotent)
        $catPickles = Category::firstOrCreate(
            ['slug' => 'traditional-pickles'],
            [
                'name' => 'Traditional Pickles (Achar)',
                'description' => 'Authentic homemade sun-dried pickles prepared with cold-pressed mustard oil, ground spices, and heirloom recipes.',
                'image' => 'images/products/mango_pickle.png',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        $catSweet = Category::firstOrCreate(
            ['slug' => 'sweet-pickles-murabba'],
            [
                'name' => 'Sweet Pickles & Murabba',
                'description' => 'Glistening Gujarati chunda, honeyed mango murabbas, and sweet-spicy sun-cured delicacies.',
                'image' => 'images/products/sweet_chunda.png',
                'is_active' => true,
                'sort_order' => 2,
            ]
        );

        $catMukhwas = Category::firstOrCreate(
            ['slug' => 'royal-mukhwas'],
            [
                'name' => 'Royal Mukhwas',
                'description' => 'Premium aromatic mouth fresheners, Calcutta paan blends, roasted seeds, and royal after-meal treats.',
                'image' => 'images/products/royal_mukhwas.png',
                'is_active' => true,
                'sort_order' => 3,
            ]
        );

        $catAyurvedic = Category::firstOrCreate(
            ['slug' => 'ayurvedic-digestives'],
            [
                'name' => 'Ayurvedic Digestives & Candies',
                'description' => 'Tangy hing pedas, anardana pachak, and soothing herbal digestive treats crafted for gut wellness.',
                'image' => 'images/products/hing_peda.png',
                'is_active' => true,
                'sort_order' => 4,
            ]
        );

        // 3. Seed Comprehensive Catalog (32 Pickles + 32 Mukhwas)
        $this->call(ProductCatalogSeeder::class);

        // 4. Seed a Sample Order for Testing (Idempotent)
        $order = Order::firstOrCreate(
            ['order_number' => 'ORD-202610-1001'],
            [
                'user_id' => $customer->id,
                'customer_name' => $customer->name,
                'customer_email' => $customer->email,
                'customer_phone' => $customer->phone,
                'shipping_address' => $customer->address,
                'city' => $customer->city,
                'state' => $customer->state,
                'postal_code' => $customer->postal_code,
                'subtotal' => 428.00,
                'shipping_fee' => 0.00, // Free above 400
                'discount_amount' => 0.00,
                'total_amount' => 428.00,
                'payment_method' => 'online_upi',
                'payment_status' => 'paid',
                'order_status' => 'processing',
                'tracking_number' => 'DTDC98234120IN',
                'courier_name' => 'DTDC Express',
                'notes' => 'Please deliver between 10am to 5pm.',
            ]
        );

        $mango500 = ProductVariant::where('sku', 'PKL-MNG-500')->first();
        $mukhwas300 = ProductVariant::where('sku', 'MKH-PCH-300')->first();

        if ($mango500 && $order->items()->count() === 0) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $mango500->product_id,
                'product_variant_id' => $mango500->id,
                'product_name' => $mango500->product->name,
                'variant_weight' => $mango500->weight_value,
                'unit_price' => $mango500->effective_price,
                'quantity' => 1,
                'total_price' => $mango500->effective_price * 1,
                'image' => $mango500->product->image,
            ]);

            if ($mukhwas300) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $mukhwas300->product_id,
                    'product_variant_id' => $mukhwas300->id,
                    'product_name' => $mukhwas300->product->name,
                    'variant_weight' => $mukhwas300->weight_value,
                    'unit_price' => $mukhwas300->effective_price,
                    'quantity' => 1,
                    'total_price' => $mukhwas300->effective_price * 1,
                    'image' => $mukhwas300->product->image,
                ]);
            }
        }
    }
}
