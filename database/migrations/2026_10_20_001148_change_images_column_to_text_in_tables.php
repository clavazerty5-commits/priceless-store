<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('image')->nullable()->change();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->text('image')->nullable()->change();
        });

        Schema::table('banners', function (Blueprint $table) {
            $table->text('image')->nullable()->change();
        });

        // Convert existing local storage images to permanent Base64 Data URIs
        foreach (['products', 'categories', 'banners'] as $tableName) {
            $rows = DB::table($tableName)->whereNotNull('image')->get();
            foreach ($rows as $row) {
                if (!empty($row->image) && !Str::startsWith($row->image, ['http://', 'https://', 'data:image'])) {
                    if (Storage::disk('public')->exists($row->image)) {
                        $fullPath = Storage::disk('public')->path($row->image);
                        $mime = mime_content_type($fullPath) ?: 'image/jpeg';
                        $data = file_get_contents($fullPath);
                        if ($data) {
                            $base64 = 'data:' . $mime . ';base64,' . base64_encode($data);
                            DB::table($tableName)->where('id', $row->id)->update(['image' => $base64]);
                        }
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('image')->nullable()->change();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->string('image')->nullable()->change();
        });

        Schema::table('banners', function (Blueprint $table) {
            $table->string('image')->nullable()->change();
        });
    }
};
