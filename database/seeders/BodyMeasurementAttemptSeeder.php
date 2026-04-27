<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BodyMeasurementAttemptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $data = [];
        $measurementIds = DB::table('body_measurements')->pluck('id')->all();

        for ($i = 0; $i < 50; $i++) {
            $status = $faker->randomElement(['accepted', 'rejected']);

            // Logika untuk rejection_reasons: hanya diisi jika statusnya 'rejected'
            $rejectionReasons = null;
            if ($status === 'rejected') {
                $reasons = [
                    'Ukuran bust tidak realistis',
                    'Proporsi waist dan hip tidak valid',
                    'Gambar referensi buram',
                    'Terdeteksi anomali pada input',
                ];
                $rejectionReasons = json_encode([$faker->randomElement($reasons)]);
            }

            $createdAt = $faker->dateTimeBetween('-6 months', 'now');

            $data[] = [
                'body_measurement_id' => ! empty($measurementIds)
                    ? $faker->optional(0.8)->randomElement($measurementIds)
                    : null,
                // Ukuran dalam cm yang mendekati realistis
                'bust' => $faker->randomFloat(2, 75, 120),
                'waist' => $faker->randomFloat(2, 60, 100),
                'hip' => $faker->randomFloat(2, 80, 130),
                'status' => $status,
                'rejection_reasons' => $rejectionReasons,
                'is_consistency_issue' => $faker->boolean(15), // 15% kemungkinan true
                'measurement_standard' => 'ISO 8559-1',
                'ip_address' => $faker->ipv4,
                'user_agent' => $faker->userAgent,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];
        }

        // Insert data dalam bentuk chunks agar lebih optimal (meskipun hanya 50 data)
        DB::table('body_measurement_attempts')->insert($data);
    }
}
