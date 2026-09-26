<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->products() as $product) {
            Product::updateOrCreate(['slug' => $product['slug']], $product);
        }
    }

    /** @return array<int, array<string, mixed>> */
    private function products(): array
    {
        return [
            [
                'name' => 'Gün Doğumu Kolye',
                'name_en' => 'Sunrise Necklace',
                'slug' => 'gun-dogumu-kolye',
                'category' => 'necklace',
                'tagline' => 'Boynunda taşıdığın ilk ışık',
                'tagline_en' => 'The first light of the day, worn close',
                'description' => 'Işınları tek tek elde şekillendirilmiş güneş formu, merkezinde bal rengi sitrin taşıyla buluşuyor. 45 cm ince zincir üzerinde, gün boyu yakanda duran minik bir ışık.',
                'description_en' => 'A sun whose rays are shaped one by one by hand, meeting a honey-coloured citrine at its centre. On a fine 45 cm chain, it sits at your collarbone all day.',
                'price' => 1290.00,
                'compare_at_price' => 1690.00,
                'image_path' => 'images/products/gun-dogumu-kolye.jpg',
                'material' => '18 ayar altın kaplama',
                'material_en' => '18k gold plated',
                'stone' => 'Sitrin',
                'stone_en' => 'Citrine',
                'color_hex' => '#F2A007',
                'badge' => 'bestseller',
                'stock' => 24,
                'rating' => 4.9,
                'review_count' => 148,
                'is_featured' => true,
            ],
            [
                'name' => 'Papatya Küpe',
                'name_en' => 'Daisy Studs',
                'slug' => 'papatya-kupe',
                'category' => 'earrings',
                'tagline' => 'Sabah ışığı kadar hafif',
                'tagline_en' => 'As light as morning sunlight',
                'description' => 'Beyaz mineli yaprakları ve altın sarısı göbeğiyle her biri elde boyanmış papatyalar. Hafifliği sayesinde sabahtan akşama kulağında olduğunu unutuyorsun.',
                'description_en' => 'Daisies with white enamel petals and a golden centre, each one painted by hand. They are so light that you forget you are wearing them.',
                'price' => 649.00,
                'compare_at_price' => null,
                'image_path' => 'images/products/papatya-kupe.jpg',
                'material' => 'Gümüş üzeri altın kaplama',
                'material_en' => 'Gold plated silver',
                'stone' => 'Beyaz mine',
                'stone_en' => 'White enamel',
                'color_hex' => '#F7E7B4',
                'badge' => 'new_in',
                'stock' => 41,
                'rating' => 4.8,
                'review_count' => 92,
                'is_featured' => true,
            ],
            [
                'name' => 'Amber Işıltı Bileklik',
                'name_en' => 'Amber Glow Bracelet',
                'slug' => 'amber-isilti-bileklik',
                'category' => 'bracelet',
                'tagline' => 'Işığı bileğinde topla',
                'tagline_en' => 'Gather the light at your wrist',
                'description' => 'Tek tek tellenmiş sitrin ve amber boncuklar, ışık vurduğunda bal gibi akan bir parıltı bırakıyor. Uzatma zinciriyle 16 ile 19 cm arası ayarlanabilir.',
                'description_en' => 'Citrine and amber beads, wired one by one, leave a honey-like glow wherever the light hits. Adjustable from 16 to 19 cm with the extension chain.',
                'price' => 899.00,
                'compare_at_price' => 1150.00,
                'image_path' => 'images/products/amber-isilti-bileklik.jpg',
                'material' => '14 ayar altın dolgu',
                'material_en' => '14k gold filled',
                'stone' => 'Amber & Sitrin',
                'stone_en' => 'Amber & citrine',
                'color_hex' => '#E89B2B',
                'badge' => 'deal',
                'stock' => 17,
                'rating' => 5.0,
                'review_count' => 61,
                'is_featured' => true,
            ],
            [
                'name' => 'Lale Yüzük',
                'name_en' => 'Tulip Ring',
                'slug' => 'lale-yuzuk',
                'category' => 'ring',
                'tagline' => 'Açmak üzere olan bir tomurcuk',
                'tagline_en' => 'A bud about to open',
                'description' => 'Açmak üzere olan bir lale: pudra pembesi mine yapraklar, altın gövde ve yaprağın kıvrımını izleyen bant. Parmağında sakin duran, elini gizlemeyen bir form.',
                'description_en' => 'A tulip caught mid-bloom: powder pink enamel petals, a gold stem and a band that follows the curve of the leaf. A quiet shape that never overwhelms the hand.',
                'price' => 1150.00,
                'compare_at_price' => null,
                'image_path' => 'images/products/lale-yuzuk.jpg',
                'material' => '18 ayar altın kaplama',
                'material_en' => '18k gold plated',
                'stone' => 'Pembe kuvars',
                'stone_en' => 'Rose quartz',
                'color_hex' => '#F0A9A0',
                'badge' => 'handmade',
                'stock' => 9,
                'rating' => 4.9,
                'review_count' => 37,
                'is_featured' => true,
            ],
            [
                'name' => 'Kelebek Halhal',
                'name_en' => 'Butterfly Anklet',
                'slug' => 'kelebek-halhal',
                'category' => 'anklet',
                'tagline' => 'Her adımda kanat çırpan',
                'tagline_en' => 'Wings with every step',
                'description' => 'Mercan ve turkuaz mineli üç kelebek, top zincirin üzerinde yürüdükçe salınıyor. Su ve tuza dayanıklı kaplamasıyla çıkarmadan takabileceğin bir parça.',
                'description_en' => 'Three butterflies in coral and turquoise enamel sway along a beaded chain as you walk. The water and salt resistant plating means you never have to take it off.',
                'price' => 549.00,
                'compare_at_price' => 720.00,
                'image_path' => 'images/products/kelebek-halhal.jpg',
                'material' => 'Çelik üzeri altın kaplama',
                'material_en' => 'Gold plated steel',
                'stone' => 'Renkli mine',
                'stone_en' => 'Coloured enamel',
                'color_hex' => '#4FC3C9',
                'badge' => 'everyday',
                'stock' => 33,
                'rating' => 4.7,
                'review_count' => 118,
                'is_featured' => true,
            ],
        ];
    }
}
