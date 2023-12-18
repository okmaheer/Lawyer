<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pages')->insert(
            [
                [
                    'title' => 'Homepage',
                    'slug' => 'homepage',
                    'body' => 'null',
                    'meta' => 'a:6:{s:9:"show_page";b:0;s:15:"show_page_title";b:0;s:16:"show_page_banner";b:0;s:10:"meta_title";N;s:8:"seo_desc";N;s:6:"banner";N;}',
                    'relation_type' => 0,
                    'sections' => 'a:8:{i:0;O:8:"stdClass":5:{s:4:"name";s:6:"Slider";s:7:"section";s:8:"sliderV1";s:5:"value";s:19:"slidersFirstVersion";s:4:"icon";s:10:"img-01.png";s:2:"id";i:1;}i:1;O:8:"stdClass":5:{s:4:"name";s:11:"Search Form";s:7:"section";s:11:"search_form";s:5:"value";s:12:"search_forms";s:4:"icon";s:10:"img-01.png";s:2:"id";i:2;}i:2;O:8:"stdClass":5:{s:4:"name";s:8:"Services";s:7:"section";s:11:"service_tab";s:5:"value";s:12:"service_tabs";s:4:"icon";s:10:"img-04.png";s:2:"id";i:3;}i:3;O:8:"stdClass":5:{s:4:"name";s:13:"About Section";s:7:"section";s:13:"about_section";s:5:"value";s:14:"about_sections";s:4:"icon";s:10:"img-05.png";s:2:"id";i:4;}i:4;O:8:"stdClass":5:{s:4:"name";s:19:"How It Work Section";s:7:"section";s:16:"how_work_section";s:5:"value";s:17:"how_work_sections";s:4:"icon";s:10:"img-06.png";s:2:"id";i:5;}i:5;O:8:"stdClass":5:{s:4:"name";s:18:"Speciality Section";s:7:"section";s:18:"speciality_section";s:5:"value";s:19:"speciality_sections";s:4:"icon";s:10:"img-07.png";s:2:"id";i:6;}i:6;O:8:"stdClass":5:{s:4:"name";s:11:"App Section";s:7:"section";s:11:"app_section";s:5:"value";s:12:"app_sections";s:4:"icon";s:10:"img-08.png";s:2:"id";i:7;}i:7;O:8:"stdClass":5:{s:4:"name";s:15:"Article Section";s:7:"section";s:15:"article_section";s:5:"value";s:16:"article_sections";s:4:"icon";s:10:"img-06.png";s:2:"id";i:8;}}',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'title' => 'How it works',
                    'slug' => 'how-it-works',
                    'body' => 'null',
                    'meta' => 'a:8:{s:9:"show_page";b:1;s:15:"show_page_title";b:0;s:10:"meta_title";N;s:8:"seo_desc";N;s:12:"sidebarOrder";s:5:"right";s:6:"header";s:8:"headerv1";s:9:"meta_desc";N;s:7:"sidebar";b:1;}',
                    'relation_type' => 0,
                    'sections' => 'a:4:{i:0;O:8:"stdClass":5:{s:4:"name";s:5:"Intro";s:7:"section";s:10:"two_column";s:5:"value";s:11:"two_columns";s:4:"icon";s:10:"img-13.png";s:2:"id";i:1;}i:1;O:8:"stdClass":5:{s:4:"name";s:5:"Intro";s:7:"section";s:10:"two_column";s:5:"value";s:11:"two_columns";s:4:"icon";s:10:"img-13.png";s:2:"id";i:2;}i:2;O:8:"stdClass":5:{s:4:"name";s:5:"Intro";s:7:"section";s:10:"two_column";s:5:"value";s:11:"two_columns";s:4:"icon";s:10:"img-13.png";s:2:"id";i:3;}i:3;O:8:"stdClass":5:{s:4:"name";s:5:"Intro";s:7:"section";s:10:"two_column";s:5:"value";s:11:"two_columns";s:4:"icon";s:10:"img-13.png";s:2:"id";i:4;}}',
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
            ]
        );
    }
}
