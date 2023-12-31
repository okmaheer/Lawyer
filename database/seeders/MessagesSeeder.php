<?php
/**
 * Class MessagesSeeder.
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
 * Class MessagesSeeder
 */
class MessagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('messages')->insert(
            [
                [
                    'user_id' => 2,
                    'receiver_id' => 4,
                    'body' => 'Donec placerat, massa eu tincidunt volutpat.',
                    'status' => '1',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),

                ],
                [
                    'user_id' => 4,
                    'receiver_id' => 2,
                    'body' => 'Donec placerat, massa eu tincidunt volutpat, lectus nibh commodo nisl, quis fermentum neque quam et erat. In vel dictum dui. In hac habitasse platea dictumst. Suspendisse vel libero libero.',
                    'status' => '1',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),

                ],
                [
                    'user_id' => 2,
                    'receiver_id' => 4,
                    'body' => 'Donec placerat, massa eu tincidunt volutpat, lectus nibh commodo nisl, quis fermentum neque quam et erat. In vel dictum dui. In hac habitasse platea dictumst. Suspendisse vel libero libero.Donec placerat, massa eu tincidunt volutpat, lectus nibh commodo nisl, quis fermentum neque quam et erat. In vel dictum dui. In hac habitasse platea dictumst. Suspendisse vel libero libero.',
                    'status' => '1',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),

                ]
            ]
        );
    }
}
