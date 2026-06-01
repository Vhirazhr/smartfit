<?php

namespace Database\Seeders;

use App\Models\FashionCategory;
use App\Models\FashionItem;
use Illuminate\Database\Seeder;

class FashionRecommendationPhotoSeeder extends Seeder
{
    private array $categoryCache = [];

    private array $purchaseLinks = [
        'y_shape' => [
            'bohemian' => [
                'https://www.tokopedia.com/find/maxi-skirt-boho-floral-wanita',
                'https://shopee.co.id/Kulot-Batik-Palazzo-Highwaist-Katun-Premium-All-Size-Fit-Bb-75kg-Lezahrasignature-Kulot-Batik-Wide-Leg-Ashley-i.15243915.50004371391',
                'https://shopee.co.id/search?keyword=off+shoulder+boho+dress+aline+wanita',
                'https://shopee.co.id/search?keyword=boho+wrap+maxi+dress+wanita',
                'https://www.zalora.co.id/women/skirts/?q=boho+aline+skirt',
            ],
            'casual' => [
                'https://www.tokopedia.com/find/mom-jeans-barrel-wanita',
                'https://shopee.co.id/search?keyword=rok+aline+cotton+high+waist+wanita',
                'https://www.tokopedia.com/find/high-waist-skirt-pleated-casual',
                'https://shopee.co.id/search?keyword=set+blouse+high+waist+skirt+wanita',
                'https://www.zalora.co.id/women/pants-shorts/?q=kulot+wide+leg+casual',
            ],
            'classic' => [
                'https://www.tokopedia.com/find/high-waist-kulot-klasik-wanita',
                'https://shopee.co.id/search?keyword=rok+midi+aline+elegant+wanita',
                'https://www.tokopedia.com/find/wrap-dress-klasik-wanita',
                'https://www.zalora.co.id/women/skirts/?q=fit+flare+skirt+classic',
                'https://shopee.co.id/search?keyword=midi+skirt+klasik+formal+wanita',
            ],
            'formal' => [
                'https://www.tokopedia.com/find/palazzo-formal-wanita-high-waist',
                'https://shopee.co.id/search?keyword=setelan+blazer+rok+aline+formal+wanita',
                'https://www.zalora.co.id/women/dresses/?q=fit+flare+formal+office',
                'https://www.tokopedia.com/find/kulot-formal-premium-wanita',
                'https://shopee.co.id/search?keyword=rok+panjang+aline+formal+wanita',
            ],
            'sporty' => [
                'https://www.tokopedia.com/reytorrm-199/reytorrm-lycra-legging-celana-olahraga-wanita-high-waist-running-yoga-gym-sport-ck04-1729714506245114099',
                'https://shopee.co.id/Sweatpants-Loose-Baggy-Highwaist-Celana-Panjang-Daily-Wanita-Training-Unisex-Korean-Style-Tebal-Nyaman-i.1791097344.44808605520',
                'https://www.zalora.co.id/p/hellobare-xyna-high-waist-pleated-sports-skort-built-in-shorts-tennis-golf-padel-skirt-for-women-blue-5170998',
                'https://shopee.co.id/(Yogalates)-AL-POCKET-CULOTTES-celana-culottes-olahraga-celana-kulot-wide-leg-pants-celana-olahraga-wanita-i.3920101.57609258643',
                'https://www.tokopedia.com/venus-ishtar-por/venus-ishtar-hitam-celana-panjang-dengan-saku-melebar-flare-pants-panjang-dengan-bagian-bawah-lonceng-tambah-ukuran-yoga-legging-celana-olahraga-high-waist-polos-gym-fitness-running-sport-legging-wanita-pakaian-cepat-kering-nyaman-outdoor-legging-1731799508234045305',
            ],
        ],
        'u' => [
            'bohemian' => [
                'https://www.tokopedia.com/le-joise-apparel/joise-kylie-wrap-3in1-shirt-dress-tali-maxi-dress-panjang-lilit-dress-wanita-polos-casual-rayon-twill-1729856751101380851',
                'https://shopee.co.id/...-MIRABELA-red-set-crop-top-sabrina-long-sleeve-maxi-skirt-woman-premium-korean-summer-set-beachwear-bali-setelan-wanita-atasan-crop-top-sabrina-lengan-panjang-rok-panjang-bohemian-set-casual-set-party-set-boho-set-bali-i.85269115.14404869258',
                'https://www.tokopedia.com/broadwaystyle/outer-kimono-boho-tassel-baju-luaran-wanita-lengan-panjang-bohemian-cardigan-kardigan-1731005986911258292',
                'https://www.zalora.co.id/p/trendyol-multi-colored-belted-halter-neck-maxi-woven-dress-twoss24el00872-multi-5077129',
                'https://shopee.co.id/91977-Journee-Wrap-Maxi-Dress-Boho-Etnic-Gaun-Vintage-Retro-i.3784955.29266947593',
            ],
            'casual' => [
                'https://www.tokopedia.com/amid-569/am-indesign-hyolyn-wrap-shirt-top-cotton-madonna-mewah-elegan-lembut-nyaman-simple-atasan-kemeja-wanita-casual-1734475319645079078',
                'https://shopee.co.id/Maxi-Dress-Marilyn-Casual-Flare-i.180159345.19714230112',
                'https://www.tokopedia.com/csjnzk/celana-jeans-wanita-high-waist-wide-leg-mom-fit-09-vintage-boyfriend-loose-efek-slimming-denim-premium-size-xs-2xl-nyaman-stylish-untuk-daily-look-1732722607776434022',
                'https://www.zalora.co.id/p/tommy-hilfiger-tommy-hilfiger-belted-short-sleeve-mini-dress-navy-navy-4972307',
                'https://shopee.co.id/Evertops-1.0-Mina-Ruched-Top-(Boat-Neck)-i.272095843.29759155052',
            ],
            'classic' => [
                'https://www.tokopedia.com/shoptania-649/d11698-sleeveless-trench-dress-with-belt-1730007733135575331',
                'https://shopee.co.id/...-PREMIUM-LOKAL-...-VRISCA-PLEATS-PLEATED-BUTTON-POCKET-MIDI-SEMIWOOL-SKIRT-i.11220076.43455088232',
                'https://www.tokopedia.com/le-joise-apparel/joise-clara-wrap-top-atasan-blouse-wanita-baju-basic-polos-stretch-panjang-1735491647643550963-1735491804841149683',
                'https://www.zalora.co.id/p/trendyol-fuchsia-body-smoothing-sleeveless-draped-detail-belted-midi-flexible-knitted-pencil-dress-twoss22el0756-pink-5076949',
                'https://shopee.co.id/Sukma-Keisya-Midi-Dress-Bludru-Sabrina-Bodycon-Tali-Ikat-Lepas-766-i.370612052.4875074994',
            ],
            'formal' => [
                'https://www.tokopedia.com/fashion-care-stage/siena-korean-vintage-dress-belt-pesta-formal-blazer-hijab-busui-friend-maroon',
                'https://shopee.co.id/Rok-Kerja-Kuliah-Wanita-Kantor-Formal-Korean-Style-midi-Pleated-skrit-Rok-Panjang-Wanita-Polos-i.1118884314.28239419267',
                'https://www.zalora.co.id/p/trendyol-light-blue-belted-skirt-opening-at-waist-dress-twoss25el00804-blue-5075622',
                'https://www.tokopedia.com/heldas-collection/helda-s-collection-setelan-rok-marina-bahan-marina-silk-wanita-1730638934175221300-1735808414448191028',
                'https://shopee.co.id/MAVERA-Katherine-Multiways-Dress-Cotton-Silk-Kimono-Wrap-Long-Dress-Mewah-Elegan-Lembut-Nyaman-Simple-Dress-Wanita-Casual-i.961231257.48001046037',
            ],
            'sporty' => [
                'https://shopee.co.id/Lassie-Alpha-YC01-YP01-Setelan-Yoga-Wanita-Terbaru-Legging-Pinggang-Tinggi-Crop-Top-Sport-Bahan-Adem-Elastis-Anti-Tembus-Nyaman-Dipakai-Stylish-Trendy-i.376286276.29285873259',
                'https://www.tokopedia.com/mootaid/moota-id-qorravite-celana-olahraga-wanita-legging-double-skirt-premium-rok-celana-skirt-pants-hijab-sporty-high-waist-memberikan-tampilan-stylish-untuk-fitness-senam-yoga-dan-kegiatan-sehari-hari-086-1734273002164094013',
                'https://www.zalora.co.id/p/trendyol-lila-double-breasted-waistband-detailed-diving-scuba-sports-leggings-thmss26ty5-purple-5328720',
                'https://shopee.co.id/Rose-Set-Sport-Bra-Short-Booty-Crunch-Pilates-Setelan-Sport-Bra-Celana-Legging-Pendek-Yoga-Gym-Fitness-Wanita-Premium-i.1355719551.29463586014',
                'https://www.tokopedia.com/beknarostore/3-pcs-celana-legging-pendek-wanita-sporty-biker-shorts-high-waist-anti-melar-nyaman-dipakai-harian-olahraga-1732662130474059368',
            ],
        ],
        'inverted_u' => [
            'bohemian' => [
                'https://shopee.co.id/Aura-Exotic-Maxi-Dress-Import-Gaun-Bohemian-Off-Shoulder-Premium-Motif-Etnik-Maxi-Dress-Pantai-Premium-Motif-Chevron-Dress-Panjang-Wanita-Sabrina-Motif-Etnik-Toska-i.158986903.55104794044',
                'https://www.tokopedia.com/ab-fhasionstlye/ab-liya-blouse-rayon-tuwil-terbaru-legan-batwing-atau-legan-balon-blouse-terbaru-pakaian-wanita-terbaru-1734170533774198672',
                'https://shopee.co.id/Outer-Batik-Asoka-Princes-Motif-Daun-i.15026388.48559351082',
                'https://www.zalora.co.id/p/marks-spencer-cotton-blend-printed-v-neck-blouse-navy-4576038',
                'https://shopee.co.id/TM-92270-Dress-Fabriza-lace-boho-maxi-dress-import-i.278385436.24168171722',
            ],
            'casual' => [
                'https://www.tokopedia.com/halofashion/halofashion-reinata-square-neck-long-sleeve-casual-basic-crop-top-tali-pita-atasan-wanita-lengan-panjang-fashion-korea-1730457829049468437',
                'https://shopee.co.id/Hana-Fashion-Megan-Top-Basic-Top-Fitted-Wanita-Atasan-V-neck-Lengan-3-4-Korean-Top-TS802-i.171615412.45259751985',
                'https://www.tokopedia.com/mybamus-official/mybamus-kinan-puff-sleeve-top-baju-casual-lengan-balon-rekomendasi-blouse-wanita-1729620395685808161',
                'https://www.zalora.co.id/p/executive-boat-neck-stripes-t-shirt-yellow-5290883',
                'https://shopee.co.id/(Last-Piece)-Azure-Cold-Shoulder-Top-Atasan-Crop-Pendek-Wanita-Off-Shoulder-Lengan-Panjang-Kasual-i.746447550.21958739166',
            ],
            'classic' => [
                'https://shopee.co.id/VALINO-LADIES-Blazer-Wanita-(Hitam-Cream)-i.391352205.23234110138',
                'https://www.tokopedia.com/arusthebrand/boat-neck-long-dress-arus-the-brand-1729729732779803865',
                'https://shopee.co.id/Cape-Ceruty-Luaran-Dress-Wanita-Korean-Retro-i.1342149392.47008622595',
                'https://www.zalora.co.id/p/minimal-minimal-joseline-dress-kerah-payet-red-warna-red-4779266',
                'https://www.tokopedia.com/anneira-1/anneira-ella-off-shoulder-blouse-1729434777375509189',
            ],
            'formal' => [
                'https://www.tokopedia.com/izharg/gila-blazer-wanita-premium-casual-formal-ladies-macquile-blezer-padding-bahu-1731006103977166125',
                'https://shopee.co.id/OLIVIA-Dress-...-A-Line-Midi-Korean-Dress-Scuba-Premium-Pressbody-Tampil-Mewah-Elegan-i.1544784370.57100198100',
                'https://www.zalora.co.id/p/hamlin-qemsya-jas-blazer-wanita-korean-style-formal-longsleeve-suit-material-polyester-original-navy-navy-5144268',
                'https://www.tokopedia.com/chloebeautyshop/blazer-blanik-wanita-firsthand-kerah-lurus-outer-slimfit-baju-formal-nyaman-panjang-kantong-casual-kantor-premium-carlo-anti-kusut-size-s-m-l-xl-1731584846534379002',
                'https://shopee.co.id/Clothseup-Blazer-Dress-Wanita-Outer-Elegan-Modis-Kekinian-Hitam-dan-Cokelat-i.1307290244.44055274652',
            ],
            'sporty' => [
                'https://www.tokopedia.com/oniskoid/jaket-olahraga-wanita-lengan-panjang-untuk-olah-raga-sports-model-raglan-terbaru-zipper-crop-two-tone-strip-bahan-fleece-tebal-premium-gramasi-280-gr-jacket-tracktop-training-sporty-crop-2-warna-size-s-m-l-xl-2xl-3xl-4xl-5xl-jumbo-bisa-custom-1729628271403435030',
                'https://shopee.co.id/Thesilversky-Kayra-Women-Jacket-Only-Sporty-Premium-Parka-Waterproof-Jaket-Wanita-Anti-Air-UV-Olahraga-i.7966233.23135554714',
                'https://www.tokopedia.com/nr-fashion2907/jaket-wanita-sport-parasut-casual-cantik-nyaman-tahan-sinar-uv-tahan-air-dan-angin-bahan-premium-1732282592480626596',
                'https://shopee.co.id/3in1-Sporty!-Outfit-OIahraga-Simple-Korean-Set-Sport-(-Hoodie-Crop-Celana-Cargo-Topi-)-KHS195-i.186491375.46510219644',
            ],
        ],
        'diamond' => [
            'bohemian' => [
                'https://shopee.co.id/TM-1803-Dress-Roxena-ethnic-boho-mini-dress-import-i.278385436.25792788446',
                'https://www.tokopedia.com/elankenken/women-s-loose-long-vintage-printed-robe-set-spring-summer-casual-button-shirt-blouses-wide-legs-pants-oversized-two-piece-outfit-1733953287700973334',
                'https://shopee.co.id/Kaftan-Silk-India-Bollywood-Etnik-Boho-Bohemian-i.214849265.47109460124',
                'https://www.zalora.co.id/p/trendyol-geometric-patterned-woven-ruffle-detailed-100-cotton-beach-dress-tbess25el00140-multi-5075952',
                'https://www.tokopedia.com/tyna-fashion/80110-vineas-loose-dress-kimono-v-neck-shirt-midi-dress-floral-import',
            ],
            'casual' => [
                'https://www.tokopedia.com/unistore7/blouse-linen-premium-korea-v-neck-oversize-atasan-wanita-lengan-panjang-aesthetic-casual-loose-top-dengan-desain-elegan-1733822387655509932',
                'https://shopee.co.id/Zalfa-Daily-tunik-AFIKA-bahan-polo-linen-premium-Nyaman-kerja-kuliah-full-kancing-depan-tali-samping-i.881997650.43980364410',
                'https://www.zalora.co.id/p/pomelo-fashion-pomelo-top-wanita-peplum-button-knit-top-taupe-brown-5100242',
                'https://shopee.co.id/D0075-PUFFY-FLARE-MINI-DRESS-SCUBA-NATAL-CHRISTMAS-DINNER-SIMPLE-POLOS-VNECK-i.90196301.1736110104',
                'https://www.tokopedia.com/gerai-outfit-989/razta-tunik-bordir-linen-rami-atasan-wanita-casual-1733143357863396880',
            ],
            'classic' => [
                'https://www.tokopedia.com/blzrid/blzr-id-blazer-wanita-peplum-black-1731758661642126675',
                'https://shopee.co.id/DRES-KATUN-SCUBA-79411-480-GR-KUALITAS-TANPA-TANDING-i.229635932.44080478531',
                'https://www.zalora.co.id/p/love-bonito-dress-wanita-camisole-fit-flare-maxi-dress-black-5228825',
                'https://www.tokopedia.com/kamau-indonesia/kemeja-blouse-vneck-set-rok-wanita-printing-fs-1733185140107084958',
                'https://shopee.co.id/Eiji-Gin-Top-Fitted-Peplum-Atasan-Basic-Wanita-i.521261605.50709429995',
            ],
            'formal' => [
                'https://shopee.co.id/BLZR.ID-BLAZER-WANITA-PEPLUM-WHITE-i.3817615.28726898682',
                'https://www.tokopedia.com/boutique-emma/emma-butik-e00856-gaun-formal-wanita-midi-dress-waffle-brown-hitam-lengan-panjang-elegan-1735777651840157178-1735778033367287290',
                'https://www.zalora.co.id/p/trendyol-black-plain-woven-chiffon-maxi-evening-dress-graduation-dress-black-5330595',
                'https://shopee.co.id/Frill-blazer-lengan-panjang-blazer-kantor-wanita-i.5029672.17394277622',
                'https://www.tokopedia.com/firmandashopp/promo-guncang-folady-p580-dress-maxi-wanita-korean-stayle-aesthetic-import-lengan-balon-panjang-full-blink-blink-warna-hitam-mewah-elegant-fit-dan-flare-bahan-poliester-adem-lembut-halus-premium-formal-gaun-pesta-kondangan-cewek-kekinian-simple-midi-1733613653115765877',
            ],
            'sporty' => [
                'https://shopee.co.id/MOOTA-Akira-Kaos-Olahraga-Wanita-Model-Jaring-Outer-Tile-Running-Oversize-Tank-Top-Tile-047-i.278125153.22586236338',
                'https://www.zalora.co.id/p/hellobare-gemini-tank-top-sports-bra-medium-support-yoga-poundfit-bra-polyester-spandex-sport-vest-blue-5262404',
                'https://shopee.co.id/KEEP-GOAL-celana-olahraga-pendek-wanita-gym-yoga-high-waist-i.1344730451.28963352255',
                'https://www.tokopedia.com/ever-tops/evertops-1-0-jolie-2in1-skort-flare-pants-1734964073150449034-1734964072896628106',
            ],
        ],
    ];

    public function run(): void
    {
        $this->ensureCategories();
        $this->categoryCache = FashionCategory::pluck('id', 'slug')->toArray();
        $this->seedFashionItems();
    }

    private function ensureCategories(): void
    {
        $categories = [
            ['name' => 'Bohemian', 'slug' => 'bohemian', 'description' => 'Bohemian-style fashion with flowy and textured pieces.', 'sort_order' => 6],
            ['name' => 'Classic', 'slug' => 'classic', 'description' => 'Timeless classic outfits with clean cuts.', 'sort_order' => 7],
        ];

        foreach ($categories as $cat) {
            FashionCategory::updateOrCreate(
                ['slug' => $cat['slug']],
                [
                    'name' => $cat['name'],
                    'description' => $cat['description'],
                    'is_active' => true,
                    'sort_order' => $cat['sort_order'],
                ]
            );
        }

        $existingSortOrders = [
            'bohemian' => 1,
            'casual' => 2,
            'classic' => 3,
            'formal' => 4,
            'sporty' => 5,
        ];

        foreach ($existingSortOrders as $slug => $order) {
            FashionCategory::where('slug', $slug)->update(['sort_order' => $order, 'is_active' => true]);
        }
    }

    private function seedFashionItems(): void
    {
        $basePath = storage_path('app/public/fashion-items');
        $bodyTypes = scandir($basePath);
        $sortOrder = 0;

        foreach ($bodyTypes as $bodyType) {
            if ($bodyType === '.' || $bodyType === '..') {
                continue;
            }

            $bodyPath = $basePath . '/' . $bodyType;
            if (! is_dir($bodyPath)) {
                continue;
            }

            $styles = scandir($bodyPath);

            foreach ($styles as $style) {
                if ($style === '.' || $style === '..') {
                    continue;
                }

                $stylePath = $bodyPath . '/' . $style;
                if (! is_dir($stylePath)) {
                    continue;
                }

                $files = glob($stylePath . '/*.{jpg,jpeg,png}', GLOB_BRACE);
                sort($files);
                $linkIndex = 0;

                foreach ($files as $filePath) {
                    $filename = basename($filePath);
                    $title = pathinfo($filename, PATHINFO_FILENAME);

                    $categoryId = $this->categoryCache[$style] ?? null;
                    if ($categoryId === null) {
                        continue;
                    }

                    $data = [
                        'fashion_category_id' => $categoryId,
                        'title' => $title,
                        'description' => "{$title} — recommended for {$bodyType} body type with {$style} style.",
                        'body_type' => $bodyType,
                        'style_preference' => $style,
                        'image_source' => 'upload',
                        'image_path' => "fashion-items/{$bodyType}/{$style}/{$filename}",
                        'purchase_link' => $this->getPurchaseLink($bodyType, $style, $linkIndex),
                        'is_active' => true,
                        'sort_order' => $sortOrder++,
                    ];

                    FashionItem::create($data);
                    $linkIndex++;
                }
            }
        }
    }

    private function getPurchaseLink(string $bodyType, string $style, int $index): ?string
    {
        return $this->purchaseLinks[$bodyType][$style][$index] ?? null;
    }
}
