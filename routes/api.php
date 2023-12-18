<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('v1/listing/get_doctor', 'RestAPIController@getDoctor');
Route::get('v1/listing/get_user_gallery', 'RestAPIController@getGallery');
Route::get('v1/listing/get_hospital', 'RestAPIController@getHospital');
Route::get('v1/listing/get_hospital_team', 'RestAPIController@getHospitalTeam');
Route::get('v1/listing/get_consultation', 'RestAPIController@getDoctorConsulation');
Route::get('v1/listing/get_feedback', 'RestAPIController@getDoctorFeedback');
Route::post('v1/article/store', 'RestAPIController@postArticle');
Route::get('v1/listing/get_articles', 'RestAPIController@getDoctorArticles');
Route::get('v1/listing/get_sinle_article', 'RestAPIController@getArticleDetail');
Route::get('v1/listing/get_doctors', 'RestAPIController@getUsers');
Route::post('v1/user/do-login', 'RestAPIController@userLogin');
Route::post('v1/user/do-logout', 'RestAPIController@userLogout');
Route::post('v1/user/signup', 'RestAPIController@createUser');
Route::post('v1/user/verifyUserCode', 'RestAPIController@verifyUserCode');
Route::post('v1/user/add_wishlist', 'RestAPIController@addWishlist');
Route::get('v1/user/get_wishlist', 'RestAPIController@getSavedItems');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');
Route::get('v1/profile/setting', 'RestAPIController@getUserProfileSettings');
Route::post('v1/profile/store_profile_setting', 'RestAPIController@storeProfileSettings');
Route::get('v1/taxonomies/get_list', 'RestAPIController@getTaxonomies');
Route::get('v1/profile/get_remove_reasons', 'RestAPIController@getRemoveReasons');
Route::get('v1/taxonomies/get-specilities', 'RestAPIController@getSpecilities');
Route::get('v1/forums/basic', 'RestAPIController@getForumSettings');
Route::get('v1/forums/get_listing', 'RestAPIController@getForumListing');
Route::get('v1/forums/get_answers', 'RestAPIController@getForumAnswers');
Route::post('v1/profile/remove_account', 'RestAPIController@removeAccount');
Route::post('v1/forums/add_question', 'RestAPIController@submitQuestion');
Route::post('v1/forums/post_answer', 'RestAPIController@submitAnswer');
Route::get('v1/team/get_listing', 'RestAPIController@getTeam');
Route::get('v1/appointments/get_listing', 'RestAPIController@getDoctorAppointments');
Route::get('v1/appointments/get_patient_listing', 'RestAPIController@getPatientAppointments');
Route::get('v1/appointments/get_single', 'RestAPIController@getDoctorAppointmentSingle');
Route::get('v1/appointments/get_patient_single', 'RestAPIController@getPatientAppointmentSingle');
Route::post('v1/appointments/update_status', 'RestAPIController@updateAppointmentStatus');
Route::post('v1/appointmentBooking', 'RestAPIController@bookAppointment');
Route::get('v1/appointment/get_hospital', 'RestAPIController@getAppointmentHospitals');
Route::get('v1/appointment/get_hospital_services', 'RestAPIController@getHospitalServices');
Route::get('v1/appointment/get_appointment_slots', 'RestAPIController@getAppointmentSlots');
Route::get('v1/appointment/get_locations', 'RestAPIController@getAppointmentLocation');
Route::get('v1/appointment/get_location_services', 'RestAPIController@getLocationServices');
Route::post('v1/appointment/delete_all_slots', 'RestAPIController@deleteAllSlots');
Route::post('v1/appointment/delete_slot', 'RestAPIController@deleteSlot');
Route::post('v1/submit_feedback', 'RestAPIController@submitFeedack');
Route::post('v1/submit_payout_settings', 'RestAPIController@submitPayoutSettings');
Route::get('v1/doctor/get_payouts', 'RestAPIController@getDoctorPayouts');
Route::get('v1/message_center/get_users', 'RestAPIController@getChatUsers');
Route::get('v1/message_center/get_messages', 'RestAPIController@getUserMessages');
Route::post('v1/store_message', 'RestAPIController@storeMessage');
Route::get('v1/get_chat_settings', 'RestAPIController@getChatSettings');
Route::get('v1/paypal/get_settings', 'RestAPIController@getPayPalSetting');
Route::get('v1/stripe/get_settings', 'RestAPIController@getStripeSetting');
Route::post('v1/paypal/create_order', 'RestAPIController@createOrder');
Route::post('v1/paypal/create_invoice', 'RestAPIController@createInvoice');
Route::get('v1/user/invoice', 'RestAPIController@getUserInvoices');
Route::get('v1/show/invoice', 'RestAPIController@showInvoice');
Route::get('v1/user/get_packages', 'RestAPIController@getPackages');
Route::post('v1/hospital/approve-user', 'RestAPIController@approveUser');
Route::get('v1/hospital/manage-team', 'RestAPIController@doctorListing');
Route::get('v1/listing/get-speciality-services', 'RestAPIController@getServices');
Route::post('v1/verify-appointment-password', 'RestAPIController@verifyAppointmentPassword');
Route::post('v1/verify-appointment-code', 'RestAPIController@verifyAppointmentCode');
Route::get('v1/settings/get-booking-settings', 'RestAPIController@getBookingSettings');
/** New  */
/** Doctor Profile Setting Start*/
Route::post('v1/doctor/store/experiences', 'RestAPIController@storeExperiences');
Route::post('v1/doctor/store/educations', 'RestAPIController@storeEducations');
Route::post('v1/doctor/store-award-downloads', 'RestAPIController@storeAwardDownloadSettings');
Route::post('v1/user/store-registration-detail', 'RestAPIController@storeRegistrationSettings');
Route::post('v1/{role_type}/store-gallery', 'RestAPIController@storeUserGallery');
Route::get('v1/get-specialities', 'RestAPIController@getSpecialities');
Route::post('v1/get-speciality-service', 'RestAPIController@getSpecialityService');
Route::post('v1/user/store/services', 'RestAPIController@storeDoctorServices');
/** Doctor Profile Setting End*/

/** Doctor appointment setting start */
Route::get('v1/listing/get_location', 'RestAPIController@getDoctorLocation');
Route::get('v1/appointment/get_location_detail', 'RestAPIController@getLocationDetail');
Route::get('v1/search/get-hospitals', 'RestAPIController@getHospitals');
Route::get('v1/appointment/get_doctor_services', 'RestAPIController@getDoctorServices');
Route::post('v1/appointment/store_location', 'RestAPIController@storeAppointmentLocation');
Route::post('v1/appointment/update_slots', 'RestAPIController@updateSlots');
Route::post('v1/appointment/update_selected_day_slots', 'RestAPIController@storeSelectedDaySlots');
Route::post('v1/doctor/update-location-services/{id}', 'RestAPIController@updateLocationServices');
/** Doctor appointment setting end */