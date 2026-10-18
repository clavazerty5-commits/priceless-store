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
            ]
        );

        // 2. Ensure Settings
        $defaultSettings = [
            'store_name' => 'PRICELESS',
            'phone' => '+216 23 456 789',
            'email' => 'info@priceless.com',
            'address' => 'تونس، شارع الحبيب بورقيبة',
            'delivery_fee' => '7.00',
            'free_delivery_threshold' => '100.00',
            'facebook_url' => 'https://www.facebook.com/profile.php?id=61570029157830',
            'facebook_pixel_id' => '',
        ];
        foreach ($defaultSettings as $key => $val) {
            Setting::updateOrCreate(['key' => $key], ['value' => $val]);
        }

        // 3. Ensure Banners
        if (Banner::count() === 0) {
            Banner::create([
                'title' => 'SUPER SALE',
                'subtitle' => 'خصومات تصل إلى 50% على الأجهزة الإلكترونية والهواتف الذكية.',
                'badge' => 'عروض حصرية',
                'link' => '#',
                'order' => 1,
                'is_active' => true,
            ]);
            Banner::create([
                'title' => 'NEW ARRIVALS',
                'subtitle' => 'أحدث المنتجات العصرية بأفضل الأسعار مع ضمان كامل وتوصيل سريع.',
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
                'name' => 'سماعات لاسلكية برو عالية الدقة',
                'description' => 'سماعات بلوتوث عالية الجودة مع عزل الضوضاء وبطارية تدوم طويلاً.',
                'price' => 89.00,
                'old_price' => 129.00,
                'category_id' => $cat1->id,
                'stock' => 25,
                'image' => 'https://picsum.photos/seed/headphones/600/400',
            ]);

            Product::create([
                'name' => 'ساعة ذكية مقاومة للماء مع تتبع اللياقة',
                'description' => 'ساعة رياضية ذكية مع شاشة لمس AMOLED ومستشعر دقات القلب.',
                'price' => 145.00,
                'old_price' => 199.00,
                'category_id' => $cat3->id,
                'stock' => 15,
                'image' => 'https://picsum.photos/seed/smartwatch/600/400',
            ]);

            Product::create([
                'name' => 'شاحن سريع 65 واط بتقنية GaN',
                'description' => 'شاحن جداري متعدد المنافذ يدعم الشحن فائق السرعة لجميع الأجهزة.',
                'price' => 45.00,
                'old_price' => 60.00,
                'category_id' => $cat4->id,
                'stock' => 40,
                'image' => 'https://picsum.photos/seed/charger/600/400',
            ]);
        }
    }
}
