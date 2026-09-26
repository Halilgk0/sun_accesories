<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('name_en')->nullable()->after('name');
            $table->string('tagline_en')->nullable()->after('tagline');
            $table->text('description_en')->nullable()->after('description');
            $table->string('material_en')->nullable()->after('material');
            $table->string('stone_en')->nullable()->after('stone');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['name_en', 'tagline_en', 'description_en', 'material_en', 'stone_en']);
        });
    }
};
