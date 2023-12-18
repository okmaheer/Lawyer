<?php

/**
 * Class DatabaseSeeder.
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

/**
 * Class DatabaseSeeder
 *
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(UserProfileSeeder::class);
        $this->call(SpecialitySeeder::class);
        $this->call(ServiceSeeder::class);
        $this->call(RoleTableSeeder::class);
        $this->call(ModelRoleSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(EmailTemplateSeeder::class);
        $this->call(EmailTypeSeeder::class);
        $this->call(PackageSeeder::class);
        $this->call(OrderSeeder::class);
        $this->call(OrderMetaSeeder::class);
        $this->call(SiteManagementSeeder::class);
        $this->call(LocationSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(ArticleSeeder::class);
        $this->call(ArticleCategorySeeder::class);
        $this->call(ForumQuestionSeeder::class);
        $this->call(ForumAnswerSeeder::class);
        $this->call(ImprovementOptionsSeeder::class);
        $this->call(UserServiceSeeder::class);
        $this->call(TeamSeeder::class);
        $this->call(FeedbackSeeder::class);
        $this->call(AppointmentSeeder::class);
        $this->call(MessagesSeeder::class);
        $this->call(PayoutSeeder::class);
        $this->call(SliderSeeder::class);
        $this->call(PageSeeder::class);
        $this->call(MetaSeeder::class);
        $this->call(MedicineDurationSeeder::class);
        $this->call(MedicineUsageSeeder::class);
        $this->call(MedicineTypeSeeder::class);
        $this->call(VitalSignSeeder::class);
        $this->call(LaboratoryTestSeeder::class);
        $this->call(MartialStatusSeeder::class);
        $this->call(ChildhoodIllnessSeeder::class);
        $this->call(DiseaseSeeder::class);
    }
}
