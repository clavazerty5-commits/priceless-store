<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('city')->nullable()->after('phone');
            $table->text('address')->nullable()->after('city');
            $table->boolean('is_admin')->default(false)->after('password');
        });

        // Ensure the initial admin user is marked as admin
        DB::table('users')->where('id', 1)->orWhere('email', 'admin@admin.com')->update(['is_admin' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'city', 'address', 'is_admin']);
        });
    }
};
