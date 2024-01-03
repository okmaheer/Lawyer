<?php
/**
 * Class ServiceSeeder.
 *
 * @category Doctry
 *
 * @package Doctry
 * @author  Amentotech <theamentotech@gmail.com>
 * @license http://www.amentotech.com Amentotech
 * @link    http://www.amentotech.com
 */

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

/**
 * Class ServiceSeeder
 */

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('services')->insert(
            [
                [
                    'title' => 'Arbitration',
                    'slug' => 'administrative-psychiatry',
                    'speciality_id' => 1,
                    'description' => 'Excepteur sint occaecat cupidatat non proident, saeunt in culpa qui officia cupidatat.',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'title' => 'Armed Forces Tribunal',
                    'slug' => 'adolescent-medicine',
                    'speciality_id' => 6,
                    'description' => 'Excepteur sint occaecat cupidatat non proident, saeunt in culpa qui officia cupidatat.',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'title' => 'Banking / Finance',
                    'slug' => 'aerospace-medicine',
                    'speciality_id' => 5,
                    'description' => 'Excepteur sint occaecat cupidatat non proident, saeunt in culpa qui officia cupidatat.',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'title' => 'Bankruptcy / Insolvency',
                    'slug' => 'anatomical-pathology',
                    'speciality_id' => 2,
                    'description' => 'Excepteur sint occaecat cupidatat non proident, saeunt in culpa qui officia cupidatat.',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'title' => 'Breach of Contract',
                    'slug' => 'anesthesiology',
                    'speciality_id' => 3,
                    'description' => 'Excepteur sint occaecat cupidatat non proident, saeunt in culpa qui officia cupidatat.',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'title' => 'Cheque Bounce',
                    'slug' => 'biochemical-genetics',
                    'speciality_id' => 8,
                    'description' => 'Excepteur sint occaecat cupidatat non proident, saeunt in culpa qui officia cupidatat.',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'title' => 'Child Custody',
                    'slug' => 'brain-medicine',
                    'speciality_id' => 9,
                    'description' => 'Excepteur sint occaecat cupidatat non proident, saeunt in culpa qui officia cupidatat.',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'title' => 'Civil',
                    'slug' => 'breast-imaging',
                    'speciality_id' => 4,
                    'description' => 'Excepteur sint occaecat cupidatat non proident, saeunt in culpa qui officia cupidatat.',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'title' => 'Consumer Court',
                    'slug' => 'calculi',
                    'speciality_id' => 14,
                    'description' => 'Excepteur sint occaecat cupidatat non proident, saeunt in culpa qui officia cupidatat.',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'title' => 'Corporate',
                    'slug' => 'cardiothoracic-radiology',
                    'speciality_id' => 4,
                    'description' => 'Excepteur sint occaecat cupidatat non proident, saeunt in culpa qui officia cupidatat.',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'title' => 'Court Marriage',
                    'slug' => 'cardiovascular',
                    'speciality_id' => 5,
                    'description' => 'Excepteur sint occaecat cupidatat non proident, saeunt in culpa qui officia cupidatat.',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'title' => 'Criminal',
                    'slug' => 'cardiovascular-radiology',
                    'speciality_id' => 4,
                    'description' => 'Excepteur sint occaecat cupidatat non proident, saeunt in culpa qui officia cupidatat.',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'title' => 'Customs & Central Excise',
                    'slug' => 'chemical-pathology',
                    'speciality_id' => 1,
                    'description' => 'Excepteur sint occaecat cupidatat non proident, saeunt in culpa qui officia cupidatat.',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'title' => 'Cyber Crime',
                    'slug' => 'chest-radiology',
                    'speciality_id' => 4,
                    'description' => 'Excepteur sint occaecat cupidatat non proident, saeunt in culpa qui officia cupidatat.',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'title' => 'Divorce',
                    'slug' => 'child-neurology',
                    'speciality_id' => 9,
                    'description' => 'Excepteur sint occaecat cupidatat non proident, saeunt in culpa qui officia cupidatat.',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'title' => 'Documentation',
                    'slug' => 'child-psychiatry',
                    'speciality_id' => 14,
                    'description' => 'Excepteur sint occaecat cupidatat non proident, saeunt in culpa qui officia cupidatat.',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],     
            ]
        );
    }
}
