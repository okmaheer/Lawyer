<?php

/**
 * Class ImprovementOptionsSeeder.
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
 * Class ImprovementOptionsSeeder
 */
class ImprovementOptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('improvement_options')->insert(
            [
                [
                    'title' => 'Behavior of Lawyer?',
                    'slug'  => 'behavior-of-doctor',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'title' => 'Lawyer communication skills?',
                    'slug'  => 'doctor-communication-skills',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'title' => 'Was the lawyer humble?',
                    'slug'  => 'was-the-doctor-humble',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'title' => 'Safety measures taken by lawyer?',
                    'slug'  => 'safety-measures-taken-by-doctor?',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
            ]
        );
    }
}
