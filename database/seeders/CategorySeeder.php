<?php

/**
 * Class CategorySeeder.
 *
 * @category Doctry
 *
 * @package Doctry
 * @author  Amentotech <theamentotech@gmail.com>
 * @license http://www.amentotech.com Amentotech
 * @link    http://www.amentotech.com
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Class CategorySeeder
 */
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert(
            [
                [
                    'title' => 'Property',
                    'slug'  => 'anesthesiology',
                    'image'  => null,
                    'abstract'  => 'Consectetur adipisicing elitaed eiusmod tempor incididuatna labore et dolore magna.',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'title' => 'Wills/Trust',
                    'slug'  => 'dermatology',
                    'image'  => null,
                    'abstract'  => 'Consectetur adipisicing elitaed eiusmod tempor incididuatna labore et dolore magna.',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'title' => 'Startup',
                    'slug'  => 'diagnostic-radiology',
                    'image'  => null,
                    'abstract'  => 'Consectetur adipisicing elitaed eiusmod tempor incididuatna labore et dolore magna.',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'title' => 'RERA',
                    'slug'  => 'emergency-medicine',
                    'image'  => null,
                    'abstract'  => 'Consectetur adipisicing elitaed eiusmod tempor incididuatna labore et dolore magna.',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'title' => 'R.T.I',
                    'slug'  => 'family-medicine',
                    'image'  => null,
                    'abstract'  => 'Consectetur adipisicing elitaed eiusmod tempor incididuatna labore et dolore magna.',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
            ]
        );
    }
}
