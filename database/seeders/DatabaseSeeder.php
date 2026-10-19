<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Banner;
use App\Models\Coupon;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Ensure Admin User
        User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]
        );

        // 2. Ensure Settings (firstOrCreate preserves user modifications)
        $defaultSettings = [
            'store_name' => 'PRICELESS',
            'phone' => '+216 23 456 789',
            'email' => 'info@priceless.com',
            'address' => 'تونس العاصمة، تونس',
            'delivery_fee' => '7.00',
            'free_delivery_threshold' => '100.00',
            'facebook_url' => 'https://www.facebook.com/profile.php?id=61570029157830',
            'facebook_pixel_id' => '',
        ];
        foreach ($defaultSettings as $key => $val) {
            Setting::firstOrCreate(['key' => $key], ['value' => $val]);
        }

        // 3. Ensure Banners
        if (Banner::count() === 0) {
            Banner::create([
                'title' => 'SUPER SALE',
                'subtitle' => 'تخفيضات تصل إلى 50% على تشكيلة واسعة من المنتجات المختارة.',
                'badge' => 'عرض خاص',
                'link' => '#',
                'order' => 1,
                'is_active' => true,
            ]);
            Banner::create([
                'title' => 'NEW ARRIVALS',
                'subtitle' => 'اكتشف أحدث صيحات الموضة والإلكترونيات بأفضل الأسعار.',
                'badge' => 'وصل حديثاً',
                'link' => '#',
                'order' => 2,
                'is_active' => true,
            ]);
        }

        // 4. Ensure Coupons
        if (Coupon::count() === 0) {
            Coupon::create([
                'code' => 'PRICELESS10',
                'type' => 'percent',
                'value' => 10,
                'min_order_amount' => 50,
                'expires_at' => now()->addMonths(6),
                'is_active' => true,
            ]);
            Coupon::create([
                'code' => 'WELCOME5',
                'type' => 'fixed',
                'value' => 5,
                'min_order_amount' => 30,
                'expires_at' => now()->addMonths(3),
                'is_active' => true,
            ]);
        }

        // 5. Ensure Categories & Products
        if (Category::count() === 0) {
            $cat1 = Category::create(['name' => 'إلكترونيات', 'slug' => 'electronics']);
            $cat2 = Category::create(['name' => 'هواتف ذكية', 'slug' => 'smartphones']);
            $cat3 = Category::create(['name' => 'ساعات ذكية', 'slug' => 'smartwatches']);
            $cat4 = Category::create(['name' => 'إكسسوارات', 'slug' => 'accessories']);

            Product::create([
                'name' => 'سماعات لاسلكية عالية الدقة',
                'description' => 'سماعات بلوتوث مريحة وعازلة للضوضاء تعمل حتى 30 ساعة متواصلة.',
                'price' => 89.00,
                'old_price' => 129.00,
                'category_id' => $cat1->id,
                'stock' => 25,
                'image' => 'https://picsum.photos/seed/headphones/600/400',
            ]);

            Product::create([
                'name' => 'ساعة ذكية رياضية مقاومة للماء',
                'description' => 'ساعة ذكية متطورة مع شاشة AMOLED لمتابعة الصحة والتمارين الرياضية بدقة.',
                'price' => 145.00,
                'old_price' => 199.00,
                'category_id' => $cat3->id,
                'stock' => 15,
                'image' => 'https://picsum.photos/seed/smartwatch/600/400',
            ]);

            Product::create([
                'name' => 'شاحن سريع 65 واط بتقنية GaN',
                'description' => 'شاحن فائق السرعة متوافق مع كافة الهواتف والحواسيب المحمولة بحجم مدمج وأمان عالي.',
                'price' => 45.00,
                'old_price' => 60.00,
                'category_id' => $cat4->id,
                'stock' => 40,
                'image' => 'https://picsum.photos/seed/charger/600/400',
            ]);
        }
    }
}
