<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MetaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('metas')->insert(
            [
                [
                    'meta_key' => 'slider',
                    'meta_value' => 'a:2:{s:5:"slide";a:3:{i:0;a:8:{s:15:"slide_title_one";s:10:"Emergency?";s:15:"slide_title_two";s:12:"Find Nearest";s:17:"slide_title_three";s:16:"Medical Facility";s:19:"slide_btn_title_one";s:14:"View Hospitals";s:17:"slide_btn_url_one";s:1:"#";s:19:"slide_btn_title_two";s:12:"View Doctors";s:17:"slide_btn_url_two";s:1:"#";s:24:"hidden_slide_inner_image";s:28:"1585668659-inner-image01.png";}i:1;a:8:{s:15:"slide_title_one";s:10:"Emergency?";s:15:"slide_title_two";s:12:"Find Nearest";s:17:"slide_title_three";s:16:"Medical Facility";s:19:"slide_btn_title_one";s:14:"View Hospitals";s:17:"slide_btn_url_one";s:1:"#";s:19:"slide_btn_title_two";s:12:"View Doctors";s:17:"slide_btn_url_two";s:1:"#";s:24:"hidden_slide_inner_image";s:28:"1585668659-inner-image02.png";}i:2;a:8:{s:15:"slide_title_one";s:10:"Emergency?";s:15:"slide_title_two";s:12:"Find Nearest";s:17:"slide_title_three";s:16:"Medical Facility";s:19:"slide_btn_title_one";s:14:"View Hospitals";s:17:"slide_btn_url_one";s:1:"#";s:19:"slide_btn_title_two";s:12:"View Doctors";s:17:"slide_btn_url_two";s:1:"#";s:24:"hidden_slide_inner_image";s:28:"1585668659-inner-image03.png";}}s:13:"slider_bg_img";s:25:"1585668659-banner-img.png";}',
                    'metable_type' => 'App\Slider',
                    'metable_id' => 1,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'meta_key' => 'service_tabs2',
                    'meta_value' => 'a:8:{s:7:"padding";a:5:{s:3:"top";i:0;s:5:"right";i:0;s:6:"bottom";i:0;s:4:"left";i:0;s:4:"unit";s:2:"px";}s:6:"margin";a:5:{s:3:"top";i:0;s:5:"right";i:0;s:6:"bottom";i:0;s:4:"left";i:0;s:4:"unit";s:2:"px";}s:12:"elementClass";N;s:9:"elementId";N;s:9:"sectionId";N;s:12:"sectionClass";N;s:2:"id";i:3;s:11:"parentIndex";i:2;}',
                    'metable_type' => 'App\Page',
                    'metable_id' => 1,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'meta_key' => 'about_sections3',
                    'meta_value' => 'a:18:{s:5:"title";s:19:"Home With One Click";s:10:"titleColor";s:7:"#3d4461";s:8:"subtitle";s:18:"Bring Care To Your";s:13:"subtitleColor";s:7:"#ff5851";s:11:"description";s:133:"<p>Lorem ipsum dolor amet consectetur adipisicing eliteiuim sete eiusmod tempor incididunt ut labore etnalom dolore magna aliqua.</p>";s:9:"btntitle1";s:8:"About us";s:7:"btnurl1";s:1:"#";s:9:"btntitle2";s:7:"contact";s:7:"btnurl2";s:1:"#";s:12:"afterSection";s:25:"1585825094-section-bg.png";s:5:"image";a:12:{s:5:"title";s:19:"Greetings & Welcome";s:10:"titleColor";s:7:"#ffffff";s:8:"subtitle";s:18:"Dr. Tyrone Grindle";s:13:"subtitleColor";s:7:"#ffffff";s:17:"captionBackground";s:7:"#3d4461";s:3:"url";s:32:"1585825094-1569054117-img-01.png";s:5:"width";s:3:"100";s:6:"height";N;s:9:"widthUnit";s:1:"%";s:10:"heightUnit";s:2:"px";s:7:"opacity";N;s:5:"after";s:7:"#ff5851";}s:7:"padding";a:5:{s:3:"top";s:2:"80";s:5:"right";i:0;s:6:"bottom";s:1:"0";s:4:"left";i:0;s:4:"unit";s:2:"px";}s:6:"margin";a:5:{s:3:"top";i:0;s:5:"right";i:0;s:6:"bottom";i:0;s:4:"left";i:0;s:4:"unit";s:2:"px";}s:10:"background";s:11:"transparent";s:9:"sectionId";N;s:12:"sectionClass";N;s:2:"id";i:4;s:11:"parentIndex";i:3;}',
                    'metable_type' => 'App\Page',
                    'metable_id' => 1,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'meta_key' => 'how_work_sections4',
                    'meta_value' => 'a:11:{s:10:"titleColor";s:7:"#3d4461";s:13:"subtitleColor";s:7:"#3d4461";s:17:"contentBackground";s:7:"#e8f6ff";s:12:"contentColor";s:7:"#3d4461";s:13:"tabBackground";s:11:"transparent";s:7:"padding";a:5:{s:3:"top";i:0;s:5:"right";i:0;s:6:"bottom";i:0;s:4:"left";i:0;s:4:"unit";s:2:"px";}s:6:"margin";a:5:{s:3:"top";i:0;s:5:"right";i:0;s:6:"bottom";i:0;s:4:"left";i:0;s:4:"unit";s:2:"px";}s:9:"sectionId";N;s:12:"sectionClass";N;s:2:"id";i:5;s:11:"parentIndex";i:4;}',
                    'metable_type' => 'App\Page',
                    'metable_id' => 1,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'meta_key' => 'speciality_sections5',
                    'meta_value' => 'a:13:{s:5:"title";s:13:"Our Top Rated";s:10:"titleColor";s:7:"#3d4461";s:13:"subtitleColor";s:7:"#3d4461";s:17:"contentBackground";s:7:"#e8f6ff";s:12:"contentColor";s:7:"#3d4461";s:12:"specialityID";i:9;s:6:"detail";a:5:{s:7:"doctors";a:8:{i:0;a:15:{s:2:"id";i:2;s:5:"image";s:62:"http://localhost:8000/uploads/users/2/medium-1569829809-01.jpg";s:5:"saved";s:5:"false";s:11:"profile_url";s:40:"http://localhost:8000/profile/ava-nguyen";s:12:"gender_title";N;s:4:"name";s:10:"Ava Nguyen";s:13:"verifyMedical";i:1;s:12:"medical_text";s:29:"Medical Registration Verified";s:10:"verifyUser";i:1;s:16:"verify_user_text";s:13:"Verified User";s:7:"tagline";s:28:"MBBS, MCPS, MSc (Immunology)";s:5:"stars";i:95;s:14:"total_feedback";i:2;s:8:"location";s:9:"Australia";s:4:"days";a:7:{i:0;a:2:{s:5:"dayon";s:5:"false";s:5:"title";s:3:"Mon";}i:1;a:2:{s:5:"dayon";s:5:"false";s:5:"title";s:3:"Tue";}i:2;a:2:{s:5:"dayon";s:5:"false";s:5:"title";s:3:"Wed";}i:3;a:2:{s:5:"dayon";s:5:"false";s:5:"title";s:3:"Thu";}i:4;a:2:{s:5:"dayon";s:4:"true";s:5:"title";s:3:"Fri";}i:5;a:2:{s:5:"dayon";s:4:"true";s:5:"title";s:3:"Sat";}i:6;a:2:{s:5:"dayon";s:4:"true";s:5:"title";s:3:"Sun";}}}i:1;a:15:{s:2:"id";i:5;s:5:"image";s:62:"http://localhost:8000/uploads/users/5/medium-1569829887-09.jpg";s:5:"saved";s:5:"false";s:11:"profile_url";s:44:"http://localhost:8000/profile/elijah-johnson";s:12:"gender_title";N;s:4:"name";s:14:"Elijah Johnson";s:13:"verifyMedical";i:1;s:12:"medical_text";s:29:"Medical Registration Verified";s:10:"verifyUser";i:1;s:16:"verify_user_text";s:13:"Verified User";s:7:"tagline";s:22:"MBBS, FCPS, MCPS, FESC";s:5:"stars";i:95;s:14:"total_feedback";i:2;s:8:"location";s:6:"Canada";s:4:"days";a:7:{i:0;a:2:{s:5:"dayon";s:5:"false";s:5:"title";s:3:"Mon";}i:1;a:2:{s:5:"dayon";s:5:"false";s:5:"title";s:3:"Tue";}i:2;a:2:{s:5:"dayon";s:5:"false";s:5:"title";s:3:"Wed";}i:3;a:2:{s:5:"dayon";s:5:"false";s:5:"title";s:3:"Thu";}i:4;a:2:{s:5:"dayon";s:4:"true";s:5:"title";s:3:"Fri";}i:5;a:2:{s:5:"dayon";s:4:"true";s:5:"title";s:3:"Sat";}i:6;a:2:{s:5:"dayon";s:4:"true";s:5:"title";s:3:"Sun";}}}i:2;a:15:{s:2:"id";i:6;s:5:"image";s:62:"http://localhost:8000/uploads/users/6/medium-1569829909-23.jpg";s:5:"saved";s:5:"false";s:11:"profile_url";s:43:"http://localhost:8000/profile/brooklyn-chan";s:12:"gender_title";N;s:4:"name";s:13:"Brooklyn Chan";s:13:"verifyMedical";i:1;s:12:"medical_text";s:29:"Medical Registration Verified";s:10:"verifyUser";i:1;s:16:"verify_user_text";s:13:"Verified User";s:7:"tagline";s:23:"MBBS, FCPS (Psychiatry)";s:5:"stars";i:20;s:14:"total_feedback";i:2;s:8:"location";s:6:"Canada";s:4:"days";a:7:{i:0;a:2:{s:5:"dayon";s:5:"false";s:5:"title";s:3:"Mon";}i:1;a:2:{s:5:"dayon";s:5:"false";s:5:"title";s:3:"Tue";}i:2;a:2:{s:5:"dayon";s:5:"false";s:5:"title";s:3:"Wed";}i:3;a:2:{s:5:"dayon";s:5:"false";s:5:"title";s:3:"Thu";}i:4;a:2:{s:5:"dayon";s:4:"true";s:5:"title";s:3:"Fri";}i:5;a:2:{s:5:"dayon";s:4:"true";s:5:"title";s:3:"Sat";}i:6;a:2:{s:5:"dayon";s:4:"true";s:5:"title";s:3:"Sun";}}}i:3;a:15:{s:2:"id";i:7;s:5:"image";s:62:"http://localhost:8000/uploads/users/7/medium-1569829939-12.jpg";s:5:"saved";s:5:"false";s:11:"profile_url";s:41:"http://localhost:8000/profile/beau-simard";s:12:"gender_title";N;s:4:"name";s:11:"Beau Simard";s:13:"verifyMedical";i:1;s:12:"medical_text";s:29:"Medical Registration Verified";s:10:"verifyUser";i:1;s:16:"verify_user_text";s:13:"Verified User";s:7:"tagline";s:19:"MBBS, D-Dermatology";s:5:"stars";i:95;s:14:"total_feedback";i:2;s:8:"location";s:6:"Canada";s:4:"days";a:7:{i:0;a:2:{s:5:"dayon";s:5:"false";s:5:"title";s:3:"Mon";}i:1;a:2:{s:5:"dayon";s:5:"false";s:5:"title";s:3:"Tue";}i:2;a:2:{s:5:"dayon";s:5:"false";s:5:"title";s:3:"Wed";}i:3;a:2:{s:5:"dayon";s:5:"false";s:5:"title";s:3:"Thu";}i:4;a:2:{s:5:"dayon";s:4:"true";s:5:"title";s:3:"Fri";}i:5;a:2:{s:5:"dayon";s:4:"true";s:5:"title";s:3:"Sat";}i:6;a:2:{s:5:"dayon";s:4:"true";s:5:"title";s:3:"Sun";}}}i:4;a:15:{s:2:"id";i:8;s:5:"image";s:62:"http://localhost:8000/uploads/users/8/medium-1569829957-10.jpg";s:5:"saved";s:5:"false";s:11:"profile_url";s:42:"http://localhost:8000/profile/isobel-jones";s:12:"gender_title";N;s:4:"name";s:12:"Isobel Jones";s:13:"verifyMedical";i:1;s:12:"medical_text";s:29:"Medical Registration Verified";s:10:"verifyUser";i:1;s:16:"verify_user_text";s:13:"Verified User";s:7:"tagline";s:18:"MBBS,FCPS(rhumato)";s:5:"stars";i:95;s:14:"total_feedback";i:2;s:8:"location";s:7:"England";s:4:"days";a:7:{i:0;a:2:{s:5:"dayon";s:5:"false";s:5:"title";s:3:"Mon";}i:1;a:2:{s:5:"dayon";s:5:"false";s:5:"title";s:3:"Tue";}i:2;a:2:{s:5:"dayon";s:5:"false";s:5:"title";s:3:"Wed";}i:3;a:2:{s:5:"dayon";s:5:"false";s:5:"title";s:3:"Thu";}i:4;a:2:{s:5:"dayon";s:4:"true";s:5:"title";s:3:"Fri";}i:5;a:2:{s:5:"dayon";s:4:"true";s:5:"title";s:3:"Sat";}i:6;a:2:{s:5:"dayon";s:4:"true";s:5:"title";s:3:"Sun";}}}i:5;a:15:{s:2:"id";i:9;s:5:"image";s:62:"http://localhost:8000/uploads/users/9/medium-1569829981-18.jpg";s:5:"saved";s:5:"false";s:11:"profile_url";s:42:"http://localhost:8000/profile/kian-johnson";s:12:"gender_title";N;s:4:"name";s:12:"Kian Johnson";s:13:"verifyMedical";i:1;s:12:"medical_text";s:29:"Medical Registration Verified";s:10:"verifyUser";i:1;s:16:"verify_user_text";s:13:"Verified User";s:7:"tagline";s:19:"My Health. My Life.";s:5:"stars";i:95;s:14:"total_feedback";i:2;s:8:"location";s:7:"England";s:4:"days";a:7:{i:0;a:2:{s:5:"dayon";s:5:"false";s:5:"title";s:3:"Mon";}i:1;a:2:{s:5:"dayon";s:5:"false";s:5:"title";s:3:"Tue";}i:2;a:2:{s:5:"dayon";s:5:"false";s:5:"title";s:3:"Wed";}i:3;a:2:{s:5:"dayon";s:5:"false";s:5:"title";s:3:"Thu";}i:4;a:2:{s:5:"dayon";s:4:"true";s:5:"title";s:3:"Fri";}i:5;a:2:{s:5:"dayon";s:4:"true";s:5:"title";s:3:"Sat";}i:6;a:2:{s:5:"dayon";s:4:"true";s:5:"title";s:3:"Sun";}}}i:6;a:15:{s:2:"id";i:10;s:5:"image";s:63:"http://localhost:8000/uploads/users/10/medium-1569830001-08.jpg";s:5:"saved";s:5:"false";s:11:"profile_url";s:43:"http://localhost:8000/profile/sarah-chapman";s:12:"gender_title";N;s:4:"name";s:13:"Sarah Chapman";s:13:"verifyMedical";i:1;s:12:"medical_text";s:29:"Medical Registration Verified";s:10:"verifyUser";i:1;s:16:"verify_user_text";s:13:"Verified User";s:7:"tagline";s:21:"MBBS, MRCS (Optha Uk)";s:5:"stars";i:40;s:14:"total_feedback";i:1;s:8:"location";s:7:"England";s:4:"days";a:7:{i:0;a:2:{s:5:"dayon";s:5:"false";s:5:"title";s:3:"Mon";}i:1;a:2:{s:5:"dayon";s:5:"false";s:5:"title";s:3:"Tue";}i:2;a:2:{s:5:"dayon";s:5:"false";s:5:"title";s:3:"Wed";}i:3;a:2:{s:5:"dayon";s:5:"false";s:5:"title";s:3:"Thu";}i:4;a:2:{s:5:"dayon";s:4:"true";s:5:"title";s:3:"Fri";}i:5;a:2:{s:5:"dayon";s:4:"true";s:5:"title";s:3:"Sat";}i:6;a:2:{s:5:"dayon";s:4:"true";s:5:"title";s:3:"Sun";}}}i:7;a:15:{s:2:"id";i:11;s:5:"image";s:63:"http://localhost:8000/uploads/users/11/medium-1569830028-11.jpg";s:5:"saved";s:5:"false";s:11:"profile_url";s:53:"http://localhost:8000/profile/abhinav-balasubramanium";s:12:"gender_title";N;s:4:"name";s:23:"Abhinav Balasubramanium";s:13:"verifyMedical";i:1;s:12:"medical_text";s:29:"Medical Registration Verified";s:10:"verifyUser";i:1;s:16:"verify_user_text";s:13:"Verified User";s:7:"tagline";s:16:"Health Is Wealth";s:5:"stars";i:0;s:14:"total_feedback";i:0;s:8:"location";s:5:"India";s:4:"days";a:7:{i:0;a:2:{s:5:"dayon";s:5:"false";s:5:"title";s:3:"Mon";}i:1;a:2:{s:5:"dayon";s:5:"false";s:5:"title";s:3:"Tue";}i:2;a:2:{s:5:"dayon";s:5:"false";s:5:"title";s:3:"Wed";}i:3;a:2:{s:5:"dayon";s:5:"false";s:5:"title";s:3:"Thu";}i:4;a:2:{s:5:"dayon";s:4:"true";s:5:"title";s:3:"Fri";}i:5;a:2:{s:5:"dayon";s:4:"true";s:5:"title";s:3:"Sat";}i:6;a:2:{s:5:"dayon";s:4:"true";s:5:"title";s:3:"Sun";}}}}s:5:"title";s:9:"Neurology";s:3:"url";s:77:"http://localhost:8000/search-results?search=&type=doctor&speciality=neurology";s:5:"image";s:70:"http://localhost:8000/uploads/specialities/small-1570522041-img-08.png";s:11:"description";s:86:"Excepteur sint occaecat cupidatat non proident, saeunt in culpa qui officia cupidatat.";}s:7:"padding";a:5:{s:3:"top";s:2:"80";s:5:"right";i:0;s:6:"bottom";s:2:"80";s:4:"left";i:0;s:4:"unit";s:2:"px";}s:6:"margin";a:5:{s:3:"top";i:0;s:5:"right";i:0;s:6:"bottom";i:0;s:4:"left";i:0;s:4:"unit";s:2:"px";}s:9:"sectionId";N;s:12:"sectionClass";N;s:2:"id";i:6;s:11:"parentIndex";i:5;}',
                    'metable_type' => 'App\Page',
                    'metable_id' => 1,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'meta_key' => 'app_sections6',
                    'meta_value' => 'a:16:{s:5:"title";s:14:"Care On The GO";s:8:"subtitle";s:19:"Download Mobile App";s:11:"description";s:133:"<p>Lorem ipsum dolor amet consectetur adipisicing eliteiuim sete eiusmod tempor incididunt ut labore etnalom dolore magna aliqua.</p>";s:10:"titleColor";s:7:"#3d4461";s:13:"subtitleColor";s:7:"#3d4461";s:10:"background";s:7:"#e8f6ff";s:10:"googlePlay";a:1:{s:5:"image";s:32:"1585825095-1569221891-img-02.png";}s:8:"appStore";a:1:{s:5:"image";s:32:"1585825095-1569221891-img-03.png";}s:7:"content";a:5:{s:5:"color";s:7:"#000000";s:10:"background";N;s:15:"backgroundColor";N;s:7:"padding";a:5:{s:3:"top";i:0;s:5:"right";i:0;s:6:"bottom";i:0;s:4:"left";i:0;s:4:"unit";s:2:"px";}s:6:"margin";a:5:{s:3:"top";i:0;s:5:"right";i:0;s:6:"bottom";i:0;s:4:"left";i:0;s:4:"unit";s:2:"px";}}s:5:"image";a:4:{s:3:"url";s:21:"1569221891-img-01.png";s:5:"width";s:3:"100";s:9:"widthUnit";s:1:"%";s:7:"opacity";N;}s:7:"padding";a:5:{s:3:"top";i:0;s:5:"right";i:0;s:6:"bottom";i:0;s:4:"left";i:0;s:4:"unit";s:2:"px";}s:6:"margin";a:5:{s:3:"top";i:0;s:5:"right";i:0;s:6:"bottom";i:0;s:4:"left";i:0;s:4:"unit";s:2:"px";}s:9:"sectionId";N;s:12:"sectionClass";N;s:2:"id";i:7;s:11:"parentIndex";i:6;}',
                    'metable_type' => 'App\Page',
                    'metable_id' => 1,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'meta_key' => 'article_sections7',
                    'meta_value' => 'a:15:{s:5:"title";s:15:"Latest Articles";s:8:"subtitle";s:26:"Read Professional Articles";s:11:"description";s:163:"<p>Lorem ipsum dolor amet consectetur adipisicing eliteiuim sete eiusmod tempor incididunt ut labore etnalom dolore magna aliqua udiminimate veniam quis norud.</p>";s:10:"titleColor";s:7:"#3d4461";s:13:"subtitleColor";s:7:"#3d4461";s:12:"contentColor";s:7:"#3d4461";s:10:"background";s:11:"transparent";s:7:"padding";a:5:{s:3:"top";s:2:"80";s:5:"right";i:0;s:6:"bottom";s:2:"80";s:4:"left";i:0;s:4:"unit";s:2:"px";}s:6:"margin";a:5:{s:3:"top";i:0;s:5:"right";i:0;s:6:"bottom";i:0;s:4:"left";i:0;s:4:"unit";s:2:"px";}s:12:"elementClass";N;s:9:"elementId";N;s:9:"sectionId";N;s:12:"sectionClass";N;s:2:"id";i:8;s:11:"parentIndex";i:7;}',
                    'metable_type' => 'App\Page',
                    'metable_id' => 1,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'meta_key' => 'slidersFirstVersion0',
                    'meta_value' => 'a:3:{s:9:"slider_id";i:1;s:2:"id";i:1;s:11:"parentIndex";i:0;}',
                    'metable_type' => 'App\Page',
                    'metable_id' => 1,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'meta_key' => 'search_forms1',
                    'meta_value' => 'a:17:{s:5:"title";s:17:"Start Your Search";s:5:"image";s:38:"1585837972-small-1569052927-img-04.png";s:16:"bannerSubheading";s:17:"Are You A Doctor?";s:13:"bannerHeading";s:13:"Join Our Team";s:12:"bannerButton";s:14:"Join As Doctor";s:9:"bannerUrl";s:1:"#";s:21:"bannerSubheadingColor";s:7:"#ffffff";s:18:"bannerHeadingColor";s:7:"#ffffff";s:16:"bannerBackground";s:7:"#3d4461";s:7:"padding";a:5:{s:3:"top";i:0;s:5:"right";i:0;s:6:"bottom";i:0;s:4:"left";i:0;s:4:"unit";s:2:"px";}s:6:"margin";a:5:{s:3:"top";s:3:"-95";s:5:"right";i:0;s:6:"bottom";s:1:"0";s:4:"left";i:0;s:4:"unit";s:2:"px";}s:12:"elementClass";N;s:9:"elementId";N;s:9:"sectionId";N;s:12:"sectionClass";N;s:2:"id";i:2;s:11:"parentIndex";i:1;}',
                    'metable_type' => 'App\Page',
                    'metable_id' => 1,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'meta_key' => 'slider',
                    'meta_value' => 'a:1:{s:5:"slide";a:5:{s:6:"slides";a:3:{i:0;a:1:{s:5:"image";s:21:"1588142548-img-01.jpg";}i:1;a:1:{s:5:"image";s:21:"1588142548-img-02.jpg";}i:2;a:1:{s:5:"image";s:21:"1588142548-img-03.jpg";}}s:12:"search_title";s:16:"Medical Facility";s:16:"search_subtitle1";s:10:"Emergency?";s:16:"search_subtitle2";s:12:"Find Nearest";s:18:"slider_inner_image";s:21:"1588142548-img-05.png";}}',
                    'metable_type' => 'App\Slider',
                    'metable_id' => 2,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'meta_key' => 'two_columns0',
                    'meta_value' => 'a:25:{s:5:"title";s:20:"To Get Your Solution";s:8:"subtitle";s:30:"Ask Query To Qualifed Doctors.";s:11:"description";s:149:"<p>Lorem ipsum dolor amet consectetur adipisicing elit eiuim sete eiu tempor incididunt ut labore etnaloms dolore magna aliqua udiminimate veniam</p>";s:3:"url";s:1:"#";s:8:"btn_text";s:12:"Start Search";s:5:"image";s:21:"1588835798-img-01.jpg";s:10:"imageOrder";s:5:"right";s:10:"titleColor";s:7:"#3d4461";s:13:"subtitleColor";s:7:"#3d4461";s:12:"contentColor";s:7:"#3d4461";s:10:"imageWidth";i:350;s:14:"imageWidthUnit";s:2:"px";s:12:"imageOpacity";N;s:19:"contentSectionClass";N;s:16:"contentSectionID";N;s:17:"imageSectionClass";N;s:14:"imageSectionID";N;s:3:"row";a:5:{s:7:"padding";a:5:{s:3:"top";i:0;s:5:"right";i:0;s:6:"bottom";s:2:"70";s:4:"left";i:0;s:4:"unit";s:2:"px";}s:6:"margin";a:5:{s:3:"top";i:0;s:5:"right";i:0;s:6:"bottom";i:0;s:4:"left";i:0;s:4:"unit";s:2:"px";}s:6:"border";s:1:"1";s:11:"borderWidth";s:1:"1";s:11:"borderColor";s:7:"#eeeeee";}s:7:"padding";a:5:{s:3:"top";i:0;s:5:"right";i:0;s:6:"bottom";i:0;s:4:"left";i:0;s:4:"unit";s:2:"px";}s:6:"margin";a:5:{s:3:"top";i:0;s:5:"right";i:0;s:6:"bottom";i:0;s:4:"left";i:0;s:4:"unit";s:2:"px";}s:17:"sectionBackground";s:11:"transparent";s:9:"sectionId";N;s:12:"sectionClass";N;s:2:"id";i:1;s:11:"parentIndex";i:0;}',
                    'metable_type' => 'App\Page',
                    'metable_id' => 2,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'meta_key' => 'two_columns1',
                    'meta_value' => 'a:25:{s:5:"title";s:16:"Secured In Vault";s:8:"subtitle";s:20:"Your Personal Detail";s:11:"description";s:149:"<p>Lorem ipsum dolor amet consectetur adipisicing elit eiuim sete eiu tempor incididunt ut labore etnaloms dolore magna aliqua udiminimate veniam</p>";s:3:"url";s:1:"#";s:8:"btn_text";s:10:"Signup Now";s:5:"image";s:21:"1588835798-img-02.jpg";s:10:"imageOrder";s:4:"left";s:10:"titleColor";s:7:"#3d4461";s:13:"subtitleColor";s:7:"#3d4461";s:12:"contentColor";s:7:"#3d4461";s:10:"imageWidth";i:350;s:14:"imageWidthUnit";s:2:"px";s:12:"imageOpacity";N;s:19:"contentSectionClass";N;s:16:"contentSectionID";N;s:17:"imageSectionClass";N;s:14:"imageSectionID";N;s:3:"row";a:5:{s:7:"padding";a:5:{s:3:"top";s:2:"80";s:5:"right";i:0;s:6:"bottom";s:2:"80";s:4:"left";i:0;s:4:"unit";s:2:"px";}s:6:"margin";a:5:{s:3:"top";i:0;s:5:"right";i:0;s:6:"bottom";i:0;s:4:"left";i:0;s:4:"unit";s:2:"px";}s:6:"border";s:1:"1";s:11:"borderWidth";s:1:"1";s:11:"borderColor";s:7:"#eeeeee";}s:7:"padding";a:5:{s:3:"top";i:0;s:5:"right";i:0;s:6:"bottom";i:0;s:4:"left";i:0;s:4:"unit";s:2:"px";}s:6:"margin";a:5:{s:3:"top";i:0;s:5:"right";i:0;s:6:"bottom";i:0;s:4:"left";i:0;s:4:"unit";s:2:"px";}s:17:"sectionBackground";s:11:"transparent";s:9:"sectionId";N;s:12:"sectionClass";N;s:2:"id";i:2;s:11:"parentIndex";i:1;}',
                    'metable_type' => 'App\Page',
                    'metable_id' => 2,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'meta_key' => 'two_columns2',
                    'meta_value' => 'a:25:{s:5:"title";s:21:"To Get Desire Service";s:8:"subtitle";s:29:"Search Tons Of Proffesionals.";s:11:"description";s:149:"<p>Lorem ipsum dolor amet consectetur adipisicing elit eiuim sete eiu tempor incididunt ut labore etnaloms dolore magna aliqua udiminimate veniam</p>";s:3:"url";s:1:"#";s:8:"btn_text";s:11:"Find Doctor";s:5:"image";s:21:"1588835798-img-03.jpg";s:10:"imageOrder";s:5:"right";s:10:"titleColor";s:7:"#3d4461";s:13:"subtitleColor";s:7:"#3d4461";s:12:"contentColor";s:7:"#3d4461";s:10:"imageWidth";i:350;s:14:"imageWidthUnit";s:2:"px";s:12:"imageOpacity";N;s:19:"contentSectionClass";N;s:16:"contentSectionID";N;s:17:"imageSectionClass";N;s:14:"imageSectionID";N;s:3:"row";a:5:{s:7:"padding";a:5:{s:3:"top";s:2:"80";s:5:"right";i:0;s:6:"bottom";s:2:"80";s:4:"left";i:0;s:4:"unit";s:2:"px";}s:6:"margin";a:5:{s:3:"top";i:0;s:5:"right";i:0;s:6:"bottom";i:0;s:4:"left";i:0;s:4:"unit";s:2:"px";}s:6:"border";s:1:"1";s:11:"borderWidth";s:1:"1";s:11:"borderColor";s:7:"#eeeeee";}s:7:"padding";a:5:{s:3:"top";i:0;s:5:"right";i:0;s:6:"bottom";i:0;s:4:"left";i:0;s:4:"unit";s:2:"px";}s:6:"margin";a:5:{s:3:"top";i:0;s:5:"right";i:0;s:6:"bottom";i:0;s:4:"left";i:0;s:4:"unit";s:2:"px";}s:17:"sectionBackground";s:11:"transparent";s:9:"sectionId";N;s:12:"sectionClass";N;s:2:"id";i:3;s:11:"parentIndex";i:2;}',
                    'metable_type' => 'App\Page',
                    'metable_id' => 2,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
                [
                    'meta_key' => 'two_columns3',
                    'meta_value' => 'a:25:{s:5:"title";s:20:"Work All Day & Night";s:8:"subtitle";s:24:"Our Technical Crazy Team";s:11:"description";s:149:"<p>Lorem ipsum dolor amet consectetur adipisicing elit eiuim sete eiu tempor incididunt ut labore etnaloms dolore magna aliqua udiminimate veniam</p>";s:3:"url";s:1:"#";s:8:"btn_text";s:11:"Get Support";s:5:"image";s:21:"1588835798-img-04.jpg";s:10:"imageOrder";s:4:"left";s:10:"titleColor";s:7:"#3d4461";s:13:"subtitleColor";s:7:"#3d4461";s:12:"contentColor";s:7:"#3d4461";s:10:"imageWidth";i:350;s:14:"imageWidthUnit";s:2:"px";s:12:"imageOpacity";N;s:19:"contentSectionClass";N;s:16:"contentSectionID";N;s:17:"imageSectionClass";N;s:14:"imageSectionID";N;s:3:"row";a:5:{s:7:"padding";a:5:{s:3:"top";s:2:"80";s:5:"right";i:0;s:6:"bottom";i:0;s:4:"left";i:0;s:4:"unit";s:2:"px";}s:6:"margin";a:5:{s:3:"top";i:0;s:5:"right";i:0;s:6:"bottom";s:2:"50";s:4:"left";i:0;s:4:"unit";s:2:"px";}s:6:"border";b:0;s:11:"borderWidth";s:1:"1";s:11:"borderColor";s:7:"#eeeeee";}s:7:"padding";a:5:{s:3:"top";i:0;s:5:"right";i:0;s:6:"bottom";i:0;s:4:"left";i:0;s:4:"unit";s:2:"px";}s:6:"margin";a:5:{s:3:"top";i:0;s:5:"right";i:0;s:6:"bottom";i:0;s:4:"left";i:0;s:4:"unit";s:2:"px";}s:17:"sectionBackground";s:7:"#ffffff";s:9:"sectionId";N;s:12:"sectionClass";N;s:2:"id";i:4;s:11:"parentIndex";i:3;}',
                    'metable_type' => 'App\Page',
                    'metable_id' => 2,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ],
            ]
        );
    }
}
