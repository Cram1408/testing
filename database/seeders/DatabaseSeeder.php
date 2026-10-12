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
            ['email' => 'admin@pickles.com'],
            [
                'name' => 'Store Administrator',
                'password' => Hash::make('password'),
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

        // 3. Seed Products & Variants
        $productsData = [
            [
                'category_id' => $catPickles->id,
                'name' => "Dadi's Special Mango Pickle (Aam Ka Achar)",
                'slug' => 'dadis-special-mango-pickle',
                'tagline' => 'Handcrafted Sun-Cured Raw Mango in Cold-Pressed Mustard Oil',
                'short_description' => 'Rich, pungent, and authentically sour raw mango chunks marinated in yellow mustard seeds, fenugreek, and cold-pressed oil.',
                'description' => "Our signature Dadi's Special Mango Pickle brings back nostalgic memories of traditional earthenware jars soaking in the summer sun. Sourced from organic farm mangoes and steeped in pure kachi ghani mustard oil with roasted fenugreek, fennel, and hing. Zero artificial colors or chemical preservatives.",
                'ingredients' => 'Raw Mango Chunks, Cold-Pressed Mustard Oil, Fenugreek (Methi), Yellow Mustard, Fennel (Saunf), Red Chilli Powder, Turmeric, Rock Salt, Hing (Asafoetida).',
                'spice_level' => 'Spicy',
                'shelf_life' => '18 Months',
                'storage_instructions' => 'Keep in a cool, dry place. Always use a dry spoon and keep the pickle submerged in mustard oil.',
                'image' => 'images/products/mango_pickle.png',
                'is_featured' => true,
                'is_bestseller' => true,
                'is_active' => true,
                'variants' => [
                    ['weight_value' => '250g Jar', 'sku' => 'PKL-MNG-250', 'price' => 149.00, 'sale_price' => 129.00, 'stock_quantity' => 50, 'is_default' => false],
                    ['weight_value' => '500g Jar', 'sku' => 'PKL-MNG-500', 'price' => 269.00, 'sale_price' => 239.00, 'stock_quantity' => 85, 'is_default' => true],
                    ['weight_value' => '1kg Family Pack', 'sku' => 'PKL-MNG-1KG', 'price' => 499.00, 'sale_price' => 449.00, 'stock_quantity' => 40, 'is_default' => false],
                ],
            ],
            [
                'category_id' => $catPickles->id,
                'name' => 'Banarasi Bharwan Lal Mirch (Stuffed Red Chilli)',
                'slug' => 'banarasi-bharwan-lal-mirch-pickle',
                'tagline' => 'Iconic Banarasi Stuffed Red Chilli in Tangy Mustard Masala',
                'short_description' => 'Whole succulent sun-dried red chillies generously stuffed with an authentic tangy dry mango and roasted spice blend.',
                'description' => 'A royal delicacy from the heart of Varanasi. Big plump red peppers are gently slit, sun-cured, and tightly hand-stuffed with roasted amchur, fennel, nigella seeds, and aromatic spices before being matured in cold-pressed mustard oil. Adds fiery soul to any dal-chawal or paratha.',
                'ingredients' => 'Whole Red Peppers, Dry Mango Powder (Amchur), Mustard Seeds, Roasted Cumin, Saunf, Kalonji, Rock Salt, Mustard Oil.',
                'spice_level' => 'Extra Spicy',
                'shelf_life' => '12 Months',
                'storage_instructions' => 'Store in a cool dry area. Ensure chillies stay coated with oil.',
                'image' => 'images/products/red_chilli_pickle.png',
                'is_featured' => true,
                'is_bestseller' => true,
                'is_active' => true,
                'variants' => [
                    ['weight_value' => '250g Jar', 'sku' => 'PKL-RLM-250', 'price' => 179.00, 'sale_price' => 159.00, 'stock_quantity' => 35, 'is_default' => false],
                    ['weight_value' => '500g Jar', 'sku' => 'PKL-RLM-500', 'price' => 329.00, 'sale_price' => 289.00, 'stock_quantity' => 60, 'is_default' => true],
                    ['weight_value' => '1kg Family Pack', 'sku' => 'PKL-RLM-1KG', 'price' => 599.00, 'sale_price' => 549.00, 'stock_quantity' => 25, 'is_default' => false],
                ],
            ],
            [
                'category_id' => $catPickles->id,
                'name' => 'Crispy Lahsun Ka Achar (Garlic Pickle)',
                'slug' => 'crispy-lahsun-ka-achar-garlic-pickle',
                'tagline' => 'Aromatic Whole Garlic Cloves with Roasted Fenugreek & Mustard',
                'short_description' => 'Crunchy peeled whole garlic cloves soaked in rich mustard oil and spiced with methi seeds, Kashmiri chilli, and lemon essence.',
                'description' => 'Known for both bold flavour and cardiovascular wellness benefits, our Lahsun Ka Achar is made using selected desi garlic cloves cured slowly to maintain crisp bite and mellow pungent goodness.',
                'ingredients' => 'Fresh Garlic Cloves, Cold-Pressed Mustard Oil, Fenugreek Seeds, Yellow Mustard, Kashmiri Mirch, Rock Salt, Lemon Juice.',
                'spice_level' => 'Medium',
                'shelf_life' => '12 Months',
                'storage_instructions' => 'Keep jar tightly closed in a cool dry pantry.',
                'image' => 'images/products/garlic_pickle.png',
                'is_featured' => false,
                'is_bestseller' => true,
                'is_active' => true,
                'variants' => [
                    ['weight_value' => '250g Jar', 'sku' => 'PKL-GRL-250', 'price' => 159.00, 'sale_price' => 139.00, 'stock_quantity' => 45, 'is_default' => false],
                    ['weight_value' => '500g Jar', 'sku' => 'PKL-GRL-500', 'price' => 289.00, 'sale_price' => 259.00, 'stock_quantity' => 70, 'is_default' => true],
                    ['weight_value' => '1kg Family Pack', 'sku' => 'PKL-GRL-1KG', 'price' => 529.00, 'sale_price' => 489.00, 'stock_quantity' => 30, 'is_default' => false],
                ],
            ],
            [
                'category_id' => $catPickles->id,
                'name' => 'Khatta Meetha Nimbu Achar (Sweet Lemon Pickle)',
                'slug' => 'khatta-meetha-nimbu-achar',
                'tagline' => 'Aged Sun-Cured Thin-Skinned Lemon with Rock Salt & Jaggery',
                'short_description' => 'Zero-oil traditional lemon pickle slowly fermented with black salt, carom seeds (ajwain), and organic jaggery.',
                'description' => 'Our Sweet & Tangy Lemon pickle is an oil-free heritage recipe that gets softer, darker, and tastier with time. Cured for over 45 days in natural sunlight to eliminate any rind bitterness, releasing soothing digestive oils and deep tang.',
                'ingredients' => 'Kagzi Lemons, Organic Jaggery, Black Salt, Rock Salt, Ajwain (Carom Seeds), Roasted Cumin, Red Chilli Powder, Hing.',
                'spice_level' => 'Sweet & Tangy',
                'shelf_life' => '24 Months',
                'storage_instructions' => 'Stores for years. Requires no refrigeration; avoid moisture.',
                'image' => 'images/products/lemon_pickle.png',
                'is_featured' => true,
                'is_bestseller' => false,
                'is_active' => true,
                'variants' => [
                    ['weight_value' => '250g Jar', 'sku' => 'PKL-LMN-250', 'price' => 139.00, 'sale_price' => 119.00, 'stock_quantity' => 40, 'is_default' => false],
                    ['weight_value' => '500g Jar', 'sku' => 'PKL-LMN-500', 'price' => 249.00, 'sale_price' => 219.00, 'stock_quantity' => 65, 'is_default' => true],
                    ['weight_value' => '1kg Family Pack', 'sku' => 'PKL-LMN-1KG', 'price' => 469.00, 'sale_price' => 419.00, 'stock_quantity' => 35, 'is_default' => false],
                ],
            ],
            [
                'category_id' => $catSweet->id,
                'name' => 'Gujarati Keri No Chhundo (Sweet Mango Chunda)',
                'slug' => 'gujarati-keri-no-chhundo-sweet-mango-chunda',
                'tagline' => 'Sun-Cooked Shredded Raw Mango with Cumin & Kashmiri Chili',
                'short_description' => 'Sun-glazed shredded raw mango infused with natural sugar crystals, aromatic roasted cumin, and mild Kashmiri mirch.',
                'description' => 'Chhundo is the crown jewel of Gujarati cuisine. Freshly grated Rajapuri mangoes are hand-mixed with sugar and placed on open rooftops under the blazing sun for two weeks until thick and glistening with natural caramel sheen. The ultimate accompaniment to theplas and mathris.',
                'ingredients' => 'Shredded Raw Mango, Sugar, Roasted Jeera (Cumin), Kashmiri Red Chilli, Rock Salt.',
                'spice_level' => 'Sweet & Tangy',
                'shelf_life' => '18 Months',
                'storage_instructions' => 'Keep at room temperature in an airtight jar.',
                'image' => 'images/products/sweet_chunda.png',
                'is_featured' => true,
                'is_bestseller' => true,
                'is_active' => true,
                'variants' => [
                    ['weight_value' => '250g Jar', 'sku' => 'SW-CHD-250', 'price' => 149.00, 'sale_price' => 129.00, 'stock_quantity' => 50, 'is_default' => false],
                    ['weight_value' => '500g Jar', 'sku' => 'SW-CHD-500', 'price' => 269.00, 'sale_price' => 239.00, 'stock_quantity' => 75, 'is_default' => true],
                    ['weight_value' => '1kg Family Pack', 'sku' => 'SW-CHD-1KG', 'price' => 499.00, 'sale_price' => 449.00, 'stock_quantity' => 45, 'is_default' => false],
                ],
            ],
            [
                'category_id' => $catMukhwas->id,
                'name' => 'Royal Panchratna Mukhwas',
                'slug' => 'royal-panchratna-mukhwas',
                'tagline' => 'Five-Jewel Blend of Roasted Saunf, Dhana Dal & Silver Pearls',
                'short_description' => 'A refreshing celebratory mix of green Lucknowi fennel, crisp coriander splits, white sesame, dry coconut, and silver pearls.',
                'description' => 'Crafted for royal banquets and luxury dining tables, this 5-gem digestive mukhwas combines slow-roasted Lucknowi saunf, crunchy roasted dhana dal (coriander splits), white sesame seeds, grated copra coconut, and a whisper of natural cooling menthol.',
                'ingredients' => 'Lucknowi Fennel Seeds, Coriander Seeds Split (Dhana Dal), Roasted Sesame, Dried Coconut Flakes, Sugar Coated Pearls, Menthol, Natural Flavours.',
                'spice_level' => 'Aromatic & Refreshing',
                'shelf_life' => '9 Months',
                'storage_instructions' => 'Store in a sealed glass jar away from direct moisture.',
                'image' => 'images/products/royal_mukhwas.png',
                'is_featured' => true,
                'is_bestseller' => true,
                'is_active' => true,
                'variants' => [
                    ['weight_value' => '150g Pouch', 'sku' => 'MKH-PCH-150', 'price' => 119.00, 'sale_price' => 99.00, 'stock_quantity' => 60, 'is_default' => false],
                    ['weight_value' => '300g Jar', 'sku' => 'MKH-PCH-300', 'price' => 219.00, 'sale_price' => 189.00, 'stock_quantity' => 90, 'is_default' => true],
                    ['weight_value' => '600g Jar', 'sku' => 'MKH-PCH-600', 'price' => 399.00, 'sale_price' => 349.00, 'stock_quantity' => 50, 'is_default' => false],
                ],
            ],
            [
                'category_id' => $catMukhwas->id,
                'name' => 'Calcutta Meetha Paan Mukhwas',
                'slug' => 'calcutta-meetha-paan-mukhwas',
                'tagline' => 'Handcrafted Betel Leaves, Gulkand, Cardamom & Menthol Crunch',
                'short_description' => '100% tobacco-free real betel leaf mukhwas packed with rich rose petal gulkand, fennel, and cooling menthol crystals.',
                'description' => 'Experience the royal flavor of a fresh Banarasi & Calcutta paan anytime! We dehydrate pure fresh betel leaves and blend them with organic rose petal preserve (gulkand), green fennel, cardamom pods, and colourful tutti-frutti. Burst of instant freshness after every meal.',
                'ingredients' => 'Dehydrated Betel Leaf (Paan), Rose Petal Gulkand, Cardamom, Candied Fennel, Tutti-Frutti, Kattha, Menthol.',
                'spice_level' => 'Sweet & Cool',
                'shelf_life' => '9 Months',
                'storage_instructions' => 'Keep jar tightly closed in a cool, shaded spot.',
                'image' => 'images/products/calcutta_paan.png',
                'is_featured' => true,
                'is_bestseller' => true,
                'is_active' => true,
                'variants' => [
                    ['weight_value' => '150g Pouch', 'sku' => 'MKH-PAN-150', 'price' => 139.00, 'sale_price' => 119.00, 'stock_quantity' => 55, 'is_default' => false],
                    ['weight_value' => '300g Jar', 'sku' => 'MKH-PAN-300', 'price' => 259.00, 'sale_price' => 229.00, 'stock_quantity' => 80, 'is_default' => true],
                    ['weight_value' => '600g Jar', 'sku' => 'MKH-PAN-600', 'price' => 479.00, 'sale_price' => 429.00, 'stock_quantity' => 35, 'is_default' => false],
                ],
            ],
            [
                'category_id' => $catAyurvedic->id,
                'name' => 'Chatpata Hing Peda & Anardana Goli',
                'slug' => 'chatpata-hing-peda-anardana-goli',
                'tagline' => 'Ayurvedic Digestive Pearls with Pure Hing & Kala Namak',
                'short_description' => 'Sweet, tangy, and tongue-tingling digestive candy pearls dusted with rock salt, cumin, and medicinal asafoetida.',
                'description' => 'A treasured post-dinner companion across Indian homes. Formulated with authentic Ayurvedic digestive spices including pure Bandhani hing, dried pomegranate seeds (anardana), black salt, and ginger. Eases digestion while offering irresistible chatpata taste.',
                'ingredients' => 'Pure Asafoetida (Hing), Anardana (Pomegranate Seeds), Black Pepper, Kala Namak, Cumin Powder, Amchur, Sugar, Lemon Juice.',
                'spice_level' => 'Tangy & Salty',
                'shelf_life' => '12 Months',
                'storage_instructions' => 'Keep jar tightly closed to prevent humidity absorption.',
                'image' => 'images/products/hing_peda.png',
                'is_featured' => false,
                'is_bestseller' => true,
                'is_active' => true,
                'variants' => [
                    ['weight_value' => '200g Pouch', 'sku' => 'AY-HNG-200', 'price' => 119.00, 'sale_price' => 99.00, 'stock_quantity' => 60, 'is_default' => false],
                    ['weight_value' => '400g Jar', 'sku' => 'AY-HNG-400', 'price' => 209.00, 'sale_price' => 179.00, 'stock_quantity' => 85, 'is_default' => true],
                    ['weight_value' => '800g Jar', 'sku' => 'AY-HNG-800', 'price' => 379.00, 'sale_price' => 329.00, 'stock_quantity' => 40, 'is_default' => false],
                ],
            ],
        ];

        foreach ($productsData as $item) {
            $variants = $item['variants'];
            unset($item['variants']);

            $product = Product::firstOrCreate(
                ['slug' => $item['slug']],
                $item
            );

            foreach ($variants as $variantData) {
                ProductVariant::updateOrCreate(
                    ['sku' => $variantData['sku']],
                    array_merge($variantData, ['product_id' => $product->id])
                );
            }
        }

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
