<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * DistrictsSeeder
 * Seeds the districts table with all 64 Bangladesh districts grouped by division.
 * Data ported from the bayanno-news-cms project.
 */
class DistrictsSeeder extends Seeder
{
    public function run(): void
    {
        // Skip if already seeded
        if (DB::table('districts')->count() > 0) {
            $this->command->info('Districts table already has data. Skipping.');
            return;
        }

        $groupedDistricts = [
            'Chattogram' => [
                'Cumilla'       => 'কুমিল্লা',
                'Feni'          => 'ফেনী',
                'Brahmanbaria'  => 'ব্রাহ্মণবাড়িয়া',
                'Rangamati'     => 'রাঙ্গামাটি',
                'Noakhali'      => 'নোয়াখালী',
                'Chandpur'      => 'চাঁদপুর',
                'Lakshmipur'    => 'লক্ষ্মীপুর',
                'Chattogram'    => 'চট্টগ্রাম',
                'Coxsbazar'     => 'কক্সবাজার',
                'Khagrachhari'  => 'খাগড়াছড়ি',
                'Bandarban'     => 'বান্দরবান',
            ],
            'Rajshahi' => [
                'Sirajganj'      => 'সিরাজগঞ্জ',
                'Pabna'          => 'পাবনা',
                'Bogura'         => 'বগুড়া',
                'Rajshahi'       => 'রাজশাহী',
                'Natore'         => 'নাটোর',
                'Joypurhat'      => 'জয়পুরহাট',
                'Chapainawabganj'=> 'চাঁপাইনবাবগঞ্জ',
                'Naogaon'        => 'নওগাঁ',
            ],
            'Khulna' => [
                'Jashore'    => 'যশোর',
                'Satkhira'   => 'সাতক্ষীরা',
                'Meherpur'   => 'মেহেরপুর',
                'Narail'     => 'নড়াইল',
                'Chuadanga'  => 'চুয়াডাঙ্গা',
                'Kushtia'    => 'কুষ্টিয়া',
                'Magura'     => 'মাগুরা',
                'Khulna'     => 'খুলনা',
                'Bagerhat'   => 'বাগেরহাট',
                'Jhenaidah'  => 'ঝিনাইদহ',
            ],
            'Barishal' => [
                'Jhalakathi' => 'ঝালকাঠি',
                'Patuakhali' => 'পটুয়াখালী',
                'Pirojpur'   => 'পিরোজপুর',
                'Barishal'   => 'বরিশাল',
                'Bhola'      => 'ভোলা',
                'Barguna'    => 'বরগুনা',
            ],
            'Sylhet' => [
                'Sylhet'      => 'সিলেট',
                'Moulvibazar' => 'মৌলভীবাজার',
                'Habiganj'    => 'হবিগঞ্জ',
                'Sunamganj'   => 'সুনামগঞ্জ',
            ],
            'Dhaka' => [
                'Narsingdi'   => 'নরসিংদী',
                'Gazipur'     => 'গাজীপুর',
                'Shariatpur'  => 'শরীয়তপুর',
                'Narayanganj' => 'নারায়ণগঞ্জ',
                'Tangail'     => 'টাঙ্গাইল',
                'Kishoreganj' => 'কিশোরগঞ্জ',
                'Manikganj'   => 'মানিকগঞ্জ',
                'Dhaka'       => 'ঢাকা',
                'Munshiganj'  => 'মুন্সীগঞ্জ',
                'Rajbari'     => 'রাজবাড়ী',
                'Madaripur'   => 'মাদারীপুর',
                'Gopalganj'   => 'গোপালগঞ্জ',
                'Faridpur'    => 'ফরিদপুর',
            ],
            'Rangpur' => [
                'Panchagarh'  => 'পঞ্চগড়',
                'Dinajpur'    => 'দিনাজপুর',
                'Lalmonirhat' => 'লালমনিরহাট',
                'Nilphamari'  => 'নীলফামারী',
                'Gaibandha'   => 'গাইবান্ধা',
                'Thakurgaon'  => 'ঠাকুরগাঁও',
                'Rangpur'     => 'রংপুর',
                'Kurigram'    => 'কুড়িগ্রাম',
            ],
            'Mymensingh' => [
                'Sherpur'    => 'শেরপুর',
                'Mymensingh' => 'ময়মনসিংহ',
                'Jamalpur'   => 'জামালপুর',
                'Netrokona'  => 'নেত্রকোনা',
            ],
        ];

        $divisionBangla = [
            'Chattogram'  => 'চট্টগ্রাম',
            'Rajshahi'    => 'রাজশাহী',
            'Khulna'      => 'খুলনা',
            'Barishal'    => 'বরিশাল',
            'Sylhet'      => 'সিলেট',
            'Dhaka'       => 'ঢাকা',
            'Rangpur'     => 'রংপুর',
            'Mymensingh'  => 'ময়মনসিংহ',
        ];

        $records = [];
        foreach ($groupedDistricts as $division => $districtList) {
            foreach ($districtList as $district => $districtBn) {
                $records[] = [
                    'name'             => $district,
                    'division'         => $division,
                    'name_bangla'      => $districtBn,
                    'division_bangla'  => $divisionBangla[$division] ?? $division,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ];
            }
        }

        DB::table('districts')->insert($records);
        $this->command->info('Seeded ' . count($records) . ' districts.');
    }
}
