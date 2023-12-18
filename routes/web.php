<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
// Cache clear route
Route::get(
    'cache-clear',
    function () {
        \Artisan::call('config:cache');
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        return redirect()->back();
    }
);
// Authentication|Guest Routes
Auth::routes();
// Home
if (empty(Request::segment(1))) {
    if (Schema::hasTable('users') && Schema::hasTable('site_managements')) {
        Route::get('/', 'HomeController@index')->name('home');
    } else {
        //echo "okk";die;
        if (!empty(env('DB_DATABASE'))) {
            Route::get('/',
                function () {
                    return Redirect::to('/install');
                }
            );
        } else {
            return trans('lang.configure_database');
        }
    }
}


// Route::get(
//     '/',
//     function () {
//         if (Schema::hasTable('users')) {
//             if (file_exists(resource_path('views/extend/front-end/index.blade.php'))) {
//                 return view('extend.front-end.index');
//             } else {
//                 return view('front-end.index');
//             }
//         } else {
//             if (!empty(env('DB_DATABASE'))) {
//                 return Redirect::to('/install');
//             } else {
//                 return trans('lang.configure_database');
//             }
//         }
//     }
// )->name('home');
Route::get(
    '/home',
    function () {
        return Redirect::to('/');
    }
)->name('home');
Route::post('user/add-wishlist', 'UserController@addWishlist');
Route::post('user/add-liked-answer', 'UserController@addLikedAnswer');
Route::post('profile/get-liked-answer', 'UserController@getLikedAnswer');
Route::post('profile/get-wishlist', 'UserController@getUserWishlist');
Route::post('submit-report', 'UserController@storeReport');
//Admin Routes
Route::group(
    ['middleware' => ['role:admin']],
    function () {
        // Conversations
        Route::get('admin/conversations/search', 'MessageController@getConversations');
        Route::get('admin/view/conversations', 'MessageController@getConversations')->name('viewConversations');
        Route::post('message-center/get-messages', 'MessageController@getUserMessages');
        Route::post('admin/conversation/delete-message', 'MessageController@deleteMessage');
        Route::post('admin/conversation/delete', 'MessageController@deleteConversation');
        Route::post('admin/update/medical-verify', 'UserController@updateUserMedical');
        Route::post('admin/update/user-verify', 'UserController@updateUserVerification');
        //Specialities
        Route::get('admin/specialities', 'SpecialityController@index')->name('specialities');
        Route::get('admin/specialities/edit/{slug}', 'SpecialityController@edit')->name('editSpeciality');
        Route::post('admin/store-speciality', 'SpecialityController@store');
        Route::get('admin/specialities/search', 'SpecialityController@index')->name('searchSpecialities');
        Route::post('admin/specialities/delete', 'SpecialityController@destroy');
        Route::post('admin/specialities/update/{id}', 'SpecialityController@update');
        Route::post('admin/delete-checked-specialities', 'SpecialityController@deleteSelected');
        // Category Routes
        Route::get('admin/categories', 'CategoryController@index')->name('categories');
        Route::get('admin/categories/edit/{slug}', 'CategoryController@edit')->name('editCategories');
        Route::post('admin/store-category', 'CategoryController@store');
        Route::get('admin/categories/search', 'CategoryController@index')->name('categoriesSearch');
        Route::post('admin/categories/delete', 'CategoryController@destroy');
        Route::post('admin/categories/update/{id}', 'CategoryController@update');
        Route::post('admin/delete-checked-categories', 'CategoryController@deleteSelected');
        // Improvement Options Routes
        Route::get('admin/improvement-options', 'ImprovementOptionController@index')->name('improvement-opts');
        Route::get('admin/improvement-options/edit/{slug}', 'ImprovementOptionController@edit')->name('edit-improvement-opts');
        Route::post('admin/store-improvement-opts', 'ImprovementOptionController@store');
        Route::get('admin/improvement-options/search', 'ImprovementOptionController@index')->name('search-improvement-opts');
        Route::post('admin/improvement-options/delete', 'ImprovementOptionController@destroy');
        Route::post('admin/improvement-options/update/{id}', 'ImprovementOptionController@update');
        Route::post('admin/delete-checked-imprv-opts', 'ImprovementOptionController@deleteSelected');
        // Location Routes
        Route::get('admin/locations', 'LocationController@index')->name('locations');
        Route::get('admin/locations/edit/{slug}', 'LocationController@edit')->name('editLocations');
        Route::post('admin/store-location', 'LocationController@store');
        Route::get('admin/locations/search', 'LocationController@index')->name('searchLocations');
        Route::post('admin/locations/delete', 'LocationController@destroy');
        Route::post('admin/locations/update/{id}', 'LocationController@update');
        Route::post('admin/get-location-flag', 'LocationController@getFlag');
        Route::post('admin/delete-checked-locations', 'LocationController@deleteSelected');
        // Services Routes
        Route::get('admin/services', 'ServiceController@index')->name('services');
        Route::get('admin/services/edit/{slug}', 'ServiceController@edit')->name('editServices');
        Route::post('admin/store-service', 'ServiceController@store');
        Route::get('admin/services/search', 'ServiceController@index')->name('searchServices');
        Route::post('admin/services/delete', 'ServiceController@destroy');
        Route::post('admin/services/update/{id}', 'ServiceController@update');
        Route::post('admin/delete-checked-services', 'ServiceController@deleteSelected');
        //Home Page Settings Route
        Route::get('admin/settings/home-page-settings', 'SiteManagementController@homePageSettings')->name('homePageSettings');
        Route::get('admin/settings/services', 'SiteManagementController@serviceSettings')->name('serviceSettings');
        Route::get('admin/settings/how-it-work', 'SiteManagementController@workSettings')->name('workSettings');
        Route::get('admin/settings/general-settings', 'SiteManagementController@generalSettings')->name('generalSettings');
        Route::post('admin/store/reg-form-settings', 'SiteManagementController@storeRegistrationSettings')->name('storeRegFormSettings');
        Route::post('admin/store/home-slider-settings', 'SiteManagementController@storeHomeSliderSettings')->name('storeHomeSettings');
        Route::post('admin/store/home-search-banner-settings', 'SiteManagementController@storeHomeSearchBannerSettings')->name('storeSearchBannerSettings');
        Route::post('admin/store/home-about-us-settings', 'SiteManagementController@storeHomeAboutUsSettings')->name('storeAboutUsSettings');
        Route::post('admin/store/home-how-works-settings', 'SiteManagementController@storeHowItWorksSettings')->name('storeHowItWorksSettings');
        Route::post('admin/store/home-service-tabs-settings', 'SiteManagementController@storeServiceTabsSettings')->name('storeServiceTabsSettings');
        Route::post('admin/store/home-seo-settings', 'SiteManagementController@storeSeoSettings');
        Route::post('admin/store/home-how-work-tabs-settings', 'SiteManagementController@storeHowWorkTabSettings')->name('storeHowWorkTabSettings');
        Route::post('admin/store/doctor-slider-section-settings', 'SiteManagementController@storeDoctorSliderSettings');
        Route::post('admin/store/home-download-app-settings', 'SiteManagementController@storeDownloadAppSecSettings')->name('storeDownloadAppSecSettings');
        Route::post('admin/store/article-section-settings', 'SiteManagementController@storeArticleSectionSettings')->name('storeArticleSectionSettings');
        Route::get('admin/get-homeslider-slides', 'SiteManagementController@getHomeSliderSlides');
        Route::get('admin/get-home-sections-display-settings', 'SiteManagementController@getHomeSectionsDisplaySettings');
        Route::get('admin/get-home-service-section-color', 'SiteManagementController@getHomeServiceSectionsColorSettings');
        Route::get('admin/settings/home-page-settings/services-section', 'SiteManagementController@homePageSettings')->name('homeServicesSection');
        // General Settings
        Route::post('admin/store/settings', 'SiteManagementController@storeGeneralSettings');
        Route::post('admin/store/sidebar-settings', 'SiteManagementController@storeSidebarSettings');
        Route::post('admin/store/forum-settings', 'SiteManagementController@storeforumSettings');
        Route::post('admin/store/topbar-settings', 'SiteManagementController@storeTopBarSettings');
        Route::post('admin/store/booking-settings', 'SiteManagementController@storeAppointmentBookingSettings');
        Route::get('admin/import-update', 'SiteManagementController@importUpdate');
        Route::post('admin/store/theme-styling-settings', 'SiteManagementController@storeThemeStylingSettings');
        Route::post('admin/store/social-settings', 'SiteManagementController@storeSocialSettings');
        Route::post('admin/store/footer-settings', 'SiteManagementController@storeFooterSettings');
        Route::get('admin/get-theme-color-display-setting', 'SiteManagementController@getThemeColorDisplaySetting');
        Route::get('admin/get-theme-language-setting', 'SiteManagementController@getThemeLanguageSetting');
        Route::get('admin/get-topbar-switch-settings', 'SiteManagementController@getTopbarSwicthSettings');
        Route::get('admin/get-booking-switch-settings', 'SiteManagementController@getBookingSwicthSettings');
        Route::get('admin/get-footer-settings', 'SiteManagementController@getFooterSettings');
        Route::get('admin/get-chat-display-setting', 'SiteManagementController@getchatDisplaySetting');
        Route::get('admin/get-sidebar-display-setting', 'SiteManagementController@getSidebarSetting');
        Route::post('admin/store/upload-icons', 'SiteManagementController@storeDashboardIcons');
        Route::get('admin/get-roles', 'SiteManagementController@getRoles')->name('getRoles');
        Route::post('admin/update-role', 'SiteManagementController@updateRole')->name('updateRole');
        Route::post('admin/clear-cache', 'SiteManagementController@clearCache');
        Route::get('admin/clear-allcache', 'SiteManagementController@clearAllCache');
        Route::get('admin/import-demo', 'SiteManagementController@importDemo');
        Route::get('admin/remove-demo', 'SiteManagementController@removeDemoContent');
        Route::post('admin/store/chat-settings', 'SiteManagementController@storeChatSettings');
        Route::post('admin/store/homepage-settings', 'SiteManagementController@storeHomepageSettings');
        Route::post('admin/store/email-settings', 'SiteManagementController@storeEmailSettings');
        Route::post('admin/store/payment-settings', 'SiteManagementController@storePaymentSettings');
        Route::post('admin/store/paypal-settings', 'SiteManagementController@storePaypalSettings');
        Route::post('admin/store/stripe-settings', 'SiteManagementController@storeStripeSettings');
        //Appointment Interval Routes
        Route::get('admin/appointment-interval', 'AppointmentIntervalController@index')->name('appointment-interval');
        Route::get('admin/appointment-interval/edit/{slug}', 'AppointmentIntervalController@edit')->name('edit-appointment-interval');
        Route::post('admin/store-appointment-interval', 'AppointmentIntervalController@store');
        Route::get('admin/appointment-interval/search', 'AppointmentIntervalController@index')->name('search-appointment-interval');
        Route::post('admin/appointment-interval/delete', 'AppointmentIntervalController@destroy');
        Route::post('admin/appointment-interval/update/{id}', 'AppointmentIntervalController@update');
        Route::post('admin/delete-checked-appnt-intrvl', 'AppointmentIntervalController@deleteSelected');
        // Appointment Duration Routes
        Route::get('admin/appointment-duration', 'AppointmentDurationController@index')->name('appointment-duration');
        Route::get('admin/appointment-duration/edit/{slug}', 'AppointmentDurationController@edit')->name('edit-appointment-duration');
        Route::post('admin/store-appointment-duration', 'AppointmentDurationController@store');
        Route::get('admin/appointment-duration/search', 'AppointmentDurationController@index')->name('search-appointment-duration');
        Route::post('admin/appointment-duration/delete', 'AppointmentDurationController@destroy');
        Route::post('admin/appointment-duration/update/{id}', 'AppointmentDurationController@update');
        Route::post('admin/delete-checked-appnt-dur', 'AppointmentDurationController@deleteSelected');
        // Pages Routes
        Route::get('admin/pages', 'PageController@index')->name('pages');
        Route::get('admin/create/page', 'PageController@create')->name('createPage');
        Route::get('admin/pages/edit-page/{id}', 'PageController@edit')->name('editPage');
        Route::post('admin/store-page', 'PageController@store');
        Route::post('admin/update-page', 'PageController@update');
        Route::get('admin/pages/search', 'PageController@index');
        Route::post('admin/pages/delete-page', 'PageController@destroy');
        // Route::post('admin/pages/update-page', 'PageController@update');
        Route::post('admin/delete-checked-pages', 'PageController@deleteSelected');
        Route::post('admin/get-page-option', 'SiteManagementController@getPageOption');
        Route::post('admin/get/innerpage-settings', 'SiteManagementController@getInnerPageSettings');
        Route::post('admin/store/innerpage-settings', 'SiteManagementController@storeInnerPageSettings');
        // Sliders Routes
        Route::get('admin/sliders', 'SliderController@index')->name('sliders');
        Route::get('admin/sliders/search', 'SliderController@index')->name('searchSliders');
        Route::get('admin/create/slider', 'SliderController@create')->name('createSlider');
        Route::get('admin/sliders/edit/{id}', 'SliderController@edit')->name('editSlider');
        Route::post('admin/slider/store', 'SliderController@store');
        Route::post('admin/sliders/delete', 'SliderController@destroy');
        Route::post('admin/sliders/update/{id}', 'SliderController@update');
        Route::post('admin/slider/delete-checked-sliders', 'SliderController@deleteSelected');
        //All packages
        Route::get('admin/packages', 'PackageController@create')->name('createPackage');
        Route::get('admin/packages/search', 'PackageController@create');
        Route::get('admin/packages/edit/{id}', 'PackageController@edit')->name('editPackage');
        Route::post('admin/packages/update', 'PackageController@update');
        Route::post('admin/store/package', 'PackageController@store');
        Route::post('admin/packages/delete-package', 'PackageController@destroy');
        Route::post('package/get-package-options', 'PackageController@getPackageOptions');
        Route::get('admin/payouts', 'UserController@getPayouts')->name('adminPayouts');
        Route::post('admin/update-payout-status', 'UserController@updatePayoutStatus');
        Route::get('admin/payouts/download/{year}/{month}', 'UserController@generatePDF');
        Route::get('admin/get/site-payment-option', 'SiteManagementController@getSitePaymentOption');
        Route::get('admin/email-templates', 'EmailTemplateController@index')->name('emailTemplates');
        Route::get('admin/email-templates/filter-templates', 'EmailTemplateController@index')->name('emailTemplates');
        Route::get('admin/email-templates/{id}', 'EmailTemplateController@edit')->name('editEmailTemplates');
        Route::post('admin/email-templates/update-content', 'EmailTemplateController@updateTemplateContent');
        Route::post('admin/email-templates/update-templates/{id}', 'EmailTemplateController@update');
        // Get user listing
        Route::get('users', 'UserController@userListing')->name('manageUsers');
        Route::post('admin/delete-user', 'UserController@deleteUser')->name('adminDeleteUser');
        Route::get('admin/edit-user/{id}', 'UserController@editUser')->name('adminEditUser');
        Route::get('admin/add-user', 'UserController@createUser')->name('adminAddUser');
        Route::post('admin/edit-user-detail', 'UserController@updateUserProfileSettings');
        Route::post('admin/create-user', 'UserController@storeUser');
        Route::get('admin/appointments', 'DoctorController@showAppointments')->name('adminAppointments');
        Route::post('admin/get-appointments', 'DoctorController@getAppointments');
        // Get forum listing
        Route::get('admin/forums', 'ForumController@adminForumListing')->name('adminForum');
        Route::get('admin/forums/search', 'ForumController@adminForumListing')->name('searchHealthForums');
        Route::get('admin/edit-forums/{id}', 'ForumController@edit');
        Route::post('admin/forum/update/{id}', 'ForumController@update');
        Route::get('admin/question/answers/{id}', 'ForumController@getAdminForumAnswers');
        Route::post('admin/answer/update', 'ForumController@updateUserAnswer');
        Route::post('admin/forums/delete', 'ForumController@destroy');
        Route::post('admin/answer/delete', 'ForumController@destroyAnswer');
        Route::post('admin/forums/delete-checked-forums', 'ForumController@deleteSelected');
        Route::post('admin/answers/delete-checked-answers', 'ForumController@deleteSelectedAnswers');

        Route::post('admin/store/menu-settings', 'SiteManagementController@storeMenuSettings');

        Route::get('get-pages-list', 'PageController@getPagesList');
        Route::get('get-inner-pages-list', 'PageController@getInnerPagesList');
        Route::get('get-saved-pages-order', 'SiteManagementController@getPagesOrder');
        Route::get('get-saved-inner-pages-order', 'SiteManagementController@getInnerPagesOrder');
        Route::get('admin/get-menu-color-setting', 'SiteManagementController@getMenuColorSetting');

        Route::get('get-parent-menu-list', 'SiteManagementController@getParentMenuList');
        Route::get('get-saved-custom-menus-list', 'SiteManagementController@getSavedMenusList');
        
        // Diseases Routes
        Route::get('admin/prescription/diseases', 'DiseaseController@index')->name('diseases');
        Route::get('admin/disease/edit/{slug}', 'DiseaseController@edit')->name('edit-disease');
        Route::post('admin/store-disease', 'DiseaseController@store');
        Route::get('admin/disease/search', 'DiseaseController@index')->name('search-disease');
        Route::post('admin/disease/delete', 'DiseaseController@destroy');
        Route::post('admin/disease/update/{id}', 'DiseaseController@update');
        Route::post('admin/delete-checked-diseases', 'DiseaseController@deleteSelected');

         // Childhood Illness Routes
        Route::get('admin/prescription/childhood-illness', 'ChildhoodIllnessController@index')->name('childhood_illness');
        Route::get('admin/childhood-illness/edit/{slug}', 'ChildhoodIllnessController@edit')->name('edit-childhood-illness');
        Route::post('admin/store-childhood-illness', 'ChildhoodIllnessController@store');
        Route::get('admin/childhood-illness/search', 'ChildhoodIllnessController@index')->name('search-childhood-illness');
        Route::post('admin/childhood-illness/delete', 'ChildhoodIllnessController@destroy');
        Route::post('admin/childhood-illness/update/{id}', 'ChildhoodIllnessController@update');
        Route::post('admin/delete-checked-childhood-illness', 'ChildhoodIllnessController@deleteSelected');

         // Martial Status Routes
         Route::get('admin/prescription/martial-status', 'MartialStatusController@index')->name('martial_status');
         Route::get('admin/martial-status/edit/{slug}', 'MartialStatusController@edit')->name('edit-martial-status');
         Route::post('admin/store-martial-status', 'MartialStatusController@store');
         Route::get('admin/martial-status/search', 'MartialStatusController@index')->name('search-martial-status');
         Route::post('admin/martial-status/delete', 'MartialStatusController@destroy');
         Route::post('admin/martial-status/update/{id}', 'MartialStatusController@update');
         Route::post('admin/delete-checked-martial-status', 'MartialStatusController@deleteSelected');

         // Laboratory Test Routes
         Route::get('admin/prescription/laboratory-tests', 'LaboratoryTestController@index')->name('laboratory_tests');
         Route::get('admin/laboratory-test/edit/{slug}', 'LaboratoryTestController@edit')->name('edit-laboratory-test');
         Route::post('admin/store-laboratory-test', 'LaboratoryTestController@store');
         Route::get('admin/laboratory-test/search', 'LaboratoryTestController@index')->name('search-laboratory-test');
         Route::post('admin/laboratory-test/delete', 'LaboratoryTestController@destroy');
         Route::post('admin/laboratory-test/update/{id}', 'LaboratoryTestController@update');
         Route::post('admin/delete-checked-laboratory-test', 'LaboratoryTestController@deleteSelected');

         
         // Vital Sign Routes
         Route::get('admin/prescription/vital-signs', 'VitalSignController@index')->name('vital_signs');
         Route::get('admin/vital-sign/edit/{slug}', 'VitalSignController@edit')->name('edit-vital-sign');
         Route::post('admin/store-vital-sign', 'VitalSignController@store');
         Route::get('admin/vital-sign/search', 'VitalSignController@index')->name('search-vital-sign');
         Route::post('admin/vital-sign/delete', 'VitalSignController@destroy');
         Route::post('admin/vital-sign/update/{id}', 'VitalSignController@update');
         Route::post('admin/delete-checked-vital-sign', 'VitalSignController@deleteSelected');

         // Medicine Type Routes
         Route::get('admin/prescription/medicine-types', 'MedicineTypeController@index')->name('medicine_types');
         Route::get('admin/medicine-type/edit/{slug}', 'MedicineTypeController@edit')->name('edit-medicine-type');
         Route::post('admin/store-medicine-type', 'MedicineTypeController@store');
         Route::get('admin/medicine-type/search', 'MedicineTypeController@index')->name('search-medicine-type');
         Route::post('admin/medicine-type/delete', 'MedicineTypeController@destroy');
         Route::post('admin/medicine-type/update/{id}', 'MedicineTypeController@update');
         Route::post('admin/delete-checked-medicine-type', 'MedicineTypeController@deleteSelected');

         // Medicine usage Routes
         Route::get('admin/prescription/medicine-usages', 'MedicineUsageController@index')->name('medicine_usages');
         Route::get('admin/medicine-usage/edit/{slug}', 'MedicineUsageController@edit')->name('edit-medicine-usage');
         Route::post('admin/store-medicine-usage', 'MedicineUsageController@store');
         Route::get('admin/medicine-usage/search', 'MedicineUsageController@index')->name('search-medicine-usage');
         Route::post('admin/medicine-usage/delete', 'MedicineUsageController@destroy');
         Route::post('admin/medicine-usage/update/{id}', 'MedicineUsageController@update');
         Route::post('admin/delete-checked-medicine-usage', 'MedicineUsageController@deleteSelected');

         // Medicine duration Routes
         Route::get('admin/prescription/medicine-durations', 'MedicineDurationController@index')->name('medicine_durations');
         Route::get('admin/medicine-duration/edit/{slug}', 'MedicineDurationController@edit')->name('edit-medicine-duration');
         Route::post('admin/store-medicine-duration', 'MedicineDurationController@store');
         Route::get('admin/medicine-duration/search', 'MedicineDurationController@index')->name('search-medicine-duration');
         Route::post('admin/medicine-duration/delete', 'MedicineDurationController@destroy');
         Route::post('admin/medicine-duration/update/{id}', 'MedicineDurationController@update');
         Route::post('admin/delete-checked-medicine-duration', 'MedicineDurationController@deleteSelected');
    }
);
//Doctor Routes
Route::group(
    ['middleware' => ['role:doctor']],
    function () {
        Route::get('doctor/packages', 'PackageController@index')->name('doctorPackages');
        Route::get('doctor/package-checkout/{id}', 'DoctorController@checkout')->name('doctorCheckout');
        Route::post('doctor/store-award-downloads', 'DoctorController@storeAwardDownloadSettings')->name('storeAwardDownloadSettings');
        Route::get('doctor/get-booking-numbers', 'DoctorController@getBookingNumbers');
        Route::get('doctor/get-awards', 'DoctorController@getDoctorAwards')->name('getDoctorAwards');
        Route::post('doctor/store/experiences', 'DoctorController@storeExperiences')->name('storeExperiences');
        Route::post('doctor/store/educations', 'DoctorController@storeEducations')->name('storeEducations');
        Route::get('doctor/get-experiences', 'UserController@getExperiences');
        Route::get('doctor/get-educations', 'UserController@getEducations');
        Route::get('appointment-settings', 'DoctorController@addLocation')->name('addAppointmentLocation');
        Route::get('appointment-detail/{id}', 'DoctorController@editLocation')->name('editLocation');
        Route::post('doctor/store/appointment-location', 'DoctorController@storeAppointmentLocation');
        Route::post('doctor/update/slots/{id}', 'DoctorController@updateSlots');
        Route::post('doctor/update-day-slots/{id}', 'DoctorController@storeSelectedDaySlots');
        Route::post('doctor/update-location-services/{id}', 'DoctorController@updateLocationServices');
        Route::post('doctor/delete-slots/{slot_id}/{day}/{id}', 'DoctorController@deleteSlots');
        Route::post('doctor/delete-all-slots/{day}/{id}', 'DoctorController@deleteAllSlots');
        Route::get('doctor/appointments', 'DoctorController@showAppointments')->name('doctorAppointments');
        Route::post('doctor/get-appointments', 'DoctorController@getAppointments');
        Route::get('doctor/payout-settings', 'DoctorController@payoutSettings')->name('doctorPayoutsSettings');
        Route::get('doctor/payouts', 'DoctorController@getPayouts')->name('getDoctorPayouts');
        Route::post('doctor/accept-appointment', 'DoctorController@acceptAppointment');
        Route::post('doctor/decline-appointment', 'DoctorController@declineAppointment');
        // Prescription routes
        Route::get('doctor/prescription/{appointment_id?}', 'PrescriptionController@index')->name('add-prescription');
        Route::post('doctor/submit/prescription', 'PrescriptionController@store');
    }
);
Route::group(
    ['middleware' => ['role:admin|doctor|hospital|regular']],
    function () {
        Route::get('user/products/thankyou', 'UserController@thankyou');
        Route::post('user/store-registration-detail', 'UserController@storeRegistrationSettings')->name('storeRegistrationSettings');
        // Account Settings Routes
        Route::get('profile/settings/account-settings', 'UserController@accountSettings')->name('accountSettings');
        Route::get('profile/settings/reset-password', 'UserController@resetPassword')->name('resetPassword');
        Route::post('profile/settings/request-password', 'UserController@requestPassword');
        Route::get('profile/settings/email-notification-settings', 'UserController@emailNotificationSettings')->name('emailNotificationSettings');
        Route::post('profile/settings/save-email-settings', 'UserController@saveEmailNotificationSettings');
        Route::post('profile/settings/save-account-settings', 'UserController@saveAccountSettings');
        Route::get('profile/settings/delete-account', 'UserController@deleteAccount')->name('deleteAccount');
        Route::post('user/settings/delete-account', 'UserController@destroy');
        Route::post('user/resend-verification-code', 'UserController@resendCode');
        Route::post('user/verify-user-code', 'UserController@reVerifyUserCode');
        Route::get('profile/settings/get-user-searchable-settings', 'UserController@getUserSearchableSettings');
        Route::get('checkout/{id}', 'UserController@checkout')->name('checkout');
        Route::post('user/update-payout-detail', 'UserController@updatePayoutDetail');
        Route::get('user/get-payout-detail', 'UserController@getPayoutDetail');
        Route::get('{role_type}/profile-settings', 'UserController@userProfileSettings')->name('profileSettings');
        Route::post('{role_type}/store-personal-detail', 'UserController@storeUserProfileSettings')->name('storeUserProfileSettings');
        Route::post('{role_type}/store-gallery', 'UserController@storeUserGallery');
        Route::post('doctor/store-default-location', 'UserController@storeDefaultLocation')->name('storeDefaultLocation');
    }
);
Route::group(
    ['middleware' => ['role:admin|doctor']],
    function () {
        Route::get('create-article', 'ArticleController@createArticle')->name('createArticle');
        Route::get('edit-article/{slug}', 'ArticleController@editArticle')->name('editArticle');
        Route::post('get-stored-cats', 'ArticleController@getStoredCategories')->name('getAllCategories');
        Route::post('get-article-cats', 'ArticleController@getArticleCats');
        Route::post('post/article', 'ArticleController@postArticle')->name('postArticle');
        Route::post('update/article', 'ArticleController@updateArticle')->name('updateArticle');
        Route::post('delete/article', 'ArticleController@destroy')->name('deleteArticle');
        Route::post('get/featured-article', 'ArticleController@getFeaturedArticleSetting')->name('getFeaturedArticleSetting');
    }
);
Route::group(
    ['middleware' => ['role:doctor|regular']],
    function () {
        Route::get('user/invoice', 'UserController@getUserInvoices')->name('userInvoice');
        Route::get('show/invoice/{id}', 'UserController@showInvoice')->name('showInvoice');
        Route::get('prescription/download/{appointment_id}', 'PrescriptionController@generatePrescriptionPDF');
    }
);
Route::group(
    ['middleware' => ['role:doctor|regular|admin']],
    function () {
        Route::get('prescription/download/{appointment_id}', 'PrescriptionController@generatePrescriptionPDF');
    }
);
Route::group(
    ['middleware' => ['role:regular']],
    function () {
        Route::get('patient/appointments/{id?}', 'PatientController@showAppointments')->name('userAppointments');
        Route::post('patient/get-appointments', 'PatientController@getAppointments');
    }
);
Route::group(
    ['middleware' => ['role:hospital']],
    function () {
        Route::get('hospital/manage-team', 'HospitalController@doctorListing')->name('manageTeams');
        Route::post('hospital/approve-user', 'HospitalController@approveUser')->name('approveUser');
        Route::post('hospital/reject-user', 'HospitalController@rejectUser')->name('rejectUser');
        Route::post('hospital/delete-user', 'HospitalController@deleteUser')->name('deleteUser');
    }
);
Route::fallback(
    function () {
        return View('errors.404 ');
    }
);
Route::post('submit-appointment-by-doc', 'DoctorController@submitAppointmentByDoctor');
Route::post('submit-appointment', 'PublicController@submitAppointment');
Route::post('verify-appointment-password', 'PublicController@verifyAppointmentPassword');
Route::post('verify-appointment-code', 'PublicController@verifyAppointmentCode');
Route::post('doctor/get-hospital-services', 'DoctorController@getHospitalServices');
Route::get('paypal/redirect-url', 'PaypalController@getIndex');
Route::get('paypal/ec-checkout', 'PaypalController@getExpressCheckout');
Route::get('paypal/ec-checkout-success', 'PaypalController@getExpressCheckoutSuccess');
Route::get('addmoney/stripe', array('as' => 'addmoney.paywithstripe', 'uses' => 'StripeController@payWithStripe',));
Route::post('addmoney/stripe', array('as' => 'addmoney.stripe', 'uses' => 'StripeController@postPaymentWithStripe',));
Route::post('stripe/generate-order', 'StripeController@generateOrder');
Route::get('search-results', 'PublicController@getSearchResult')->name('searchResults');
Route::post('submit-feedback', 'PublicController@submitFeedack');
Route::post('message/send-private-message', 'MessageController@store');
Route::get('message-center', 'MessageController@index')->name('message');
Route::get('message-center/get-users', 'MessageController@getUsers');
Route::post('message-center/get-messages', 'MessageController@getUserMessages');
Route::post('message', 'MessageController@store')->name('message.store');
Route::get('get-user-specialities', 'UserController@getSpecialities');
Route::post('user/speciality_delete/{speciality_index}/{service_index?}', 'UserController@destroySpeciality');
Route::post('get-doctor-education', 'PublicController@getDoctorEducations')->name('getDoctorEducations');
Route::post('get-doctor-experience', 'PublicController@getDoctorExperiences')->name('getDoctorExperiences');
Route::post('store-appointment-data', 'PublicController@storeAppointmentInSession');
Route::get('health-forum', 'ForumController@index')->name('forumQuestions');
Route::get('health-forum/search-query', 'ForumController@index')->name('searchQueryFilter');
Route::get('health-forum/filter-questions', 'ForumController@index')->name('getFilteredQuestions');
Route::post('health-forum/post-question', 'ForumController@store')->name('storeForumQuestions');
Route::get('health-forum/{slug?}', 'ForumController@getForumAnswers')->name('getForumAnswers');
Route::post('health-forum/post-answer', 'ForumController@postAnswer')->name('ForumAnswers');
Route::post('user/store/services', 'UserController@storeServices');
Route::post('send/app-link', 'PublicController@sendDownloadAppEmail');
Route::post('register/login-register-user', 'PublicController@loginUser')->name('loginUser');
Route::post('register/verify-user-code', 'PublicController@verifyUserCode');
Route::post('register/form-step1-custom-errors', 'PublicController@RegisterStep1Validation');
Route::post('register/form-step2-custom-errors', 'PublicController@RegisterStep2Validation');
Route::post('register/single-form-custom-errors', 'PublicController@singleFormValidation');
Route::get('profile/{slug}', 'PublicController@showProfile')->name('userProfile');
Route::get('get-specialities', 'SpecialityController@getSpecialities');
Route::post('get-speciality-service', 'SpecialityController@getSpecialityService');
Route::post('get-speciality-services', 'SpecialityController@getServices');
Route::get('articles/{category?}', 'ArticleController@articleListing')->name('articleListing');
Route::get('article/{slug}', 'ArticleController@articleDetail')->name('articleDetail');
Route::get('search/get-hospitals', 'UserController@getHospitals');
Route::get('page/{slug}', 'PageController@show')->name('showPage');
Route::get('{role}/saved-items', 'UserController@getSavedItems')->name('getSavedItems');
Route::get('{role}/dashboard', 'UserController@getDashboard')->name('dashboard');
Route::post('media/upload-temp-image/{type}/{file_name}/{img_type?}', 'MediaController@uploadTempImage');
Route::post('doctor/get-appointment-slots', 'DoctorController@getAppointmentSlots');
Route::post('doctor/get-required-appointment-data', 'DoctorController@getRequiredAppointmentData');
// attachments
Route::get('get/{type}/{id}/{filename}', 'MediaController@getFile')->name('getfile');
Route::get('section/get-services', 'PublicController@getServicesSection');
Route::get('section/get-howwork-content', 'PublicController@getHowWorkSection');
Route::get('section/get-speciality-data/{id}', 'PublicController@getSpecialityData');
Route::get('section/get-articles', 'PublicController@getArticles');
Route::get('section/get-sliders', 'SliderController@getSliders');
Route::get('section/get-sliders/{id}', 'SliderController@getSelectedSlider');
Route::get('slider/get-search-setting/{id}', 'SliderController@getSearchFormSetting');
Route::get('slider/get-slider-type/{id}', 'SliderController@getStoredType');

Route::get('get-edit-page/{id}', 'PageController@getEditPageData');
Route::get('page/get-sections/{id}', 'PageController@getEditPageSections');
Route::get('section/get-locations', 'LocationController@getLocations');
Route::get('section/get-roles', 'PublicController@getSearchableRoles');
Route::get('section/get-parent-pages/{id}', 'PageController@getParentPages');
Route::get('pages/get-parents', 'PageController@getAllParentPages');
Route::get('search/location-list', 'PublicController@getLocationList');

Route::post('doctor/fetch-user-detail', 'PublicController@fetchUserDetail');
Route::get('search/appointment-users', 'PublicController@fetchAppointmentUser');
Route::get('search/get-searchable-data', 'PublicController@getSearchableData');
Route::get('admin/get-registration-type-settings', 'SiteManagementController@getRegistrationTypeSettings');