<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Categories were five keys in the model and ten labels in the translation
 * files, so adding one meant a deployment. They become rows the atelier can
 * edit, keeping the slug that products already store and that every category
 * link in the site is built from.
 */
return new class extends Migration
{
    /**
     * The five the site shipped with, so nothing changes the moment this runs.
     *
     * @var array<int, array{slug: string, name: string, name_en: string}>
     */
    private const SEED = [
        ['slug' => 'necklace', 'name' => 'Kolye', 'name_en' => 'Necklaces'],
        ['slug' => 'earrings', 'name' => 'Küpe', 'name_en' => 'Earrings'],
        ['slug' => 'bracelet', 'name' => 'Bileklik', 'name_en' => 'Bracelets'],
        ['slug' => 'ring', 'name' => 'Yüzük', 'name_en' => 'Rings'],
        ['slug' => 'anklet', 'name' => 'Halhal', 'name_en' => 'Anklets'],
    ];

    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });

        $now = now();

        DB::table('categories')->insert(array_map(
            fn (array $category, int $index) => [
                ...$category,
                'position' => $index,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            self::SEED,
            array_keys(self::SEED),
        ));
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
