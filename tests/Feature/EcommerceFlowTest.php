<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EcommerceFlowTest extends TestCase
{
    public function test_homepage_and_shop_are_accessible(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Swad Desi');

        $shopResponse = $this->get('/shop');
        $shopResponse->assertStatus(200);
        $shopResponse->assertSee('Dadi\'s Special Mango Pickle');
    }

    public function test_product_detail_page_loads_with_variants(): void
    {
        $product = Product::where('slug', 'dadis-special-mango-pickle')->first();
        $this->assertNotNull($product);

        $response = $this->get('/product/' . $product->slug);
        $response->assertStatus(200);
        $response->assertSee('Ingredients');
        $response->assertSee('Choose Weight Size:');
    }

    public function test_cart_and_checkout_flow(): void
    {
        $variant = ProductVariant::where('sku', 'PKL-MNG-500')->first();
        $this->assertNotNull($variant);
        $initialStock = $variant->stock_quantity;

        // 1. Add to Cart
        $cartAdd = $this->post('/cart/add', [
            'variant_id' => $variant->id,
            'quantity' => 2,
        ]);
        $cartAdd->assertSessionHas('cart');

        // 2. View Cart
        $cartPage = $this->get('/cart');
        $cartPage->assertStatus(200);
        $cartPage->assertSee($variant->product->name);

        // 3. Process Checkout
        $checkoutResponse = $this->post('/checkout', [
            'customer_name' => 'Priya Patel',
            'customer_email' => 'priya@example.com',
            'customer_phone' => '+91 9988776655',
            'shipping_address' => '12 Lotus Court, MG Road',
            'city' => 'Ahmedabad',
            'state' => 'Gujarat',
            'postal_code' => '380001',
            'payment_method' => 'online_upi',
            'notes' => 'Ring doorbell twice',
        ]);

        $checkoutResponse->assertStatus(302);
        
        $order = Order::where('customer_email', 'priya@example.com')->latest()->first();
        $this->assertNotNull($order);
        $this->assertEquals(2, $order->items->first()->quantity);

        // Check stock was decremented
        $variant->refresh();
        $this->assertEquals($initialStock - 2, $variant->stock_quantity);

        // 4. View Order Confirmation
        $confirmPage = $this->get('/order-confirmed/' . $order->order_number);
        $confirmPage->assertStatus(200);
        $confirmPage->assertSee($order->order_number);

        // 5. Track Order
        $trackResponse = $this->post('/track-order', [
            'order_number' => $order->order_number,
            'contact' => 'priya@example.com',
        ]);
        $trackResponse->assertStatus(200);
        $trackResponse->assertSee($order->order_number);
    }

    public function test_admin_portal_access_and_fulfillment(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->assertNotNull($admin);

        // Guest cannot access admin without login
        $guestAdmin = $this->get('/admin');
        $guestAdmin->assertRedirect(route('admin.login'));

        // Login as admin
        $loginResponse = $this->post('/admin/login', [
            'email' => $admin->email,
            'password' => 'password',
        ]);
        $loginResponse->assertRedirect(route('admin.dashboard'));

        // Access Admin Dashboard
        $dashboardResponse = $this->actingAs($admin)->get('/admin');
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Store Overview');

        // Access Products Management
        $productsResponse = $this->actingAs($admin)->get('/admin/products');
        $productsResponse->assertStatus(200);

        // Access Inventory Management
        $inventoryResponse = $this->actingAs($admin)->get('/admin/inventory');
        $inventoryResponse->assertStatus(200);

        // Access Orders Management
        $ordersResponse = $this->actingAs($admin)->get('/admin/orders');
        $ordersResponse->assertStatus(200);

        // Update Order fulfillment
        $order = Order::first();
        $updateOrder = $this->actingAs($admin)->put('/admin/orders/' . $order->id, [
            'order_status' => 'shipped',
            'payment_status' => 'paid',
            'courier_name' => 'Delhivery Express',
            'tracking_number' => 'DEL12345678IN',
        ]);
        $updateOrder->assertSessionHas('success');

        $order->refresh();
        $this->assertEquals('shipped', $order->order_status);
        $this->assertEquals('DEL12345678IN', $order->tracking_number);
    }
}
