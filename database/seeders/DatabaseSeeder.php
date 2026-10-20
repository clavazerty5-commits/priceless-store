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
                'image' => 'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=800&q=80',
                'link' => '#products-section',
                'order' => 1,
                'is_active' => true,
            ]);
            Banner::create([
                'title' => 'NEW ARRIVALS',
                'subtitle' => 'اكتشف أحدث صيحات الموضة والإلكترونيات بأفضل الأسعار.',
                'badge' => 'وصل حديثاً',
                'image' => 'https://images.unsplash.com/photo-1483985988355-763728e1935b?w=800&q=80',
                'link' => '#products-section',
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
            $cat1 = Category::create([
                'name' => 'إلكترونيات', 
                'slug' => 'electronics',
                'image' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?w=400&q=80',
            ]);
            $cat2 = Category::create([
                'name' => 'هواتف ذكية', 
                'slug' => 'smartphones',
                'image' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=400&q=80',
            ]);
            $cat3 = Category::create([
                'name' => 'ساعات ذكية', 
                'slug' => 'smartwatches',
                'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&q=80',
            ]);
            $cat4 = Category::create([
                'name' => 'إكسسوارات', 
                'slug' => 'accessories',
                'image' => 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=400&q=80',
            ]);

            Product::create([
                'name' => 'سماعات لاسلكية عالية الدقة',
                'description' => 'سماعات بلوتوث مريحة وعازلة للضوضاء تعمل حتى 30 ساعة متواصلة مع جودة صوت نقية وعميقة.',
                'price' => 89.00,
                'old_price' => 129.00,
                'category_id' => $cat1->id,
                'stock' => 25,
                'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&q=80',
            ]);

            Product::create([
                'name' => 'ساعة ذكية رياضية مقاومة للماء',
                'description' => 'ساعة ذكية متطورة مع شاشة AMOLED لمتابعة الصحة والتمارين الرياضية بدقة وبطارية تدوم طويلاً.',
                'price' => 145.00,
                'old_price' => 199.00,
                'category_id' => $cat3->id,
                'stock' => 15,
                'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&q=80',
            ]);

            Product::create([
                'name' => 'شاحن سريع 65 واط بتقنية GaN',
                'description' => 'شاحن فائق السرعة متوافق مع كافة الهواتف والحواسيب المحمولة بحجم مدمج وأمان عالي للشحن السريع.',
                'price' => 45.00,
                'old_price' => 60.00,
                'category_id' => $cat4->id,
                'stock' => 40,
                'image' => 'https://images.unsplash.com/photo-1583863788434-e58a36330cf0?w=600&q=80',
            ]);
        } else {
            // Fill default permanent images for existing seeded categories if empty
            Category::where('slug', 'electronics')->whereNull('image')->update(['image' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?w=400&q=80']);
            Category::where('slug', 'smartphones')->whereNull('image')->update(['image' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=400&q=80']);
            Category::where('slug', 'smartwatches')->whereNull('image')->update(['image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&q=80']);
            Category::where('slug', 'accessories')->whereNull('image')->update(['image' => 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=400&q=80']);
        }
    }
}
