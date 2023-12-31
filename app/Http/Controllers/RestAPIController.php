<?php

namespace App\Http\Controllers;

use App\Appointment;
use Illuminate\Http\Request;
use App\User;
use App\Helper;
use Response;
use App\Feedback;
use App\SiteManagement;
use File;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;
use App\Team;
use DB;
use App\Order;
use App\OrderMeta;
use App\Package;
use App\EmailTemplate;
use Illuminate\Support\Facades\Mail;
use App\Mail\GeneralEmailMailable;
use App\Mail\AdminEmailMailable;
use App\Mail\DoctorEmailMailable;
use App\Mail\HospitalEmailMailable;
use App\Category;
use App\Forum;
use App\Article;
use App\Speciality;
use Auth;
use App\Location;
use App\UserMeta;
use App\ImprovementOption;
use App\Message;
use Session;
use Hash;
use Storage;
use Intervention\Image\Facades\Image;

class RestAPIController extends Controller
{
    /**
     * Get doctor detail data
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function getDoctor()
    {
        $json = array();
        $profile_id = !empty($_GET['profile_id']) ? $_GET['profile_id'] : '';
        $auth_id = !empty($_GET['auth_id']) ? $_GET['auth_id'] : '';
        $user = !empty($profile_id) ? User::find($profile_id) : new User();
        $role_type = !empty($profile_id) ? Helper::getRoleTypeByUserID($profile_id) : '';
        $currency   = SiteManagement::getMetaValue('payment_settings');
        $symbol = !empty($currency) && !empty($currency['currency']) ? Helper::currencyList($currency['currency']) : 'symbol';
        $user_detail = array();
        if (!empty($user) && !empty($profile_id)) {
            $auth_user = !empty($auth_id) ? User::find($auth_id) : '';
            $saved_doctors = !empty($auth_user) && !empty($auth_user->profile->saved_doctors) ? unserialize($auth_user->profile->saved_doctors) : array();
            $avg_rating = Feedback::where('user_id', $user->id)->pluck('avg_rating')->first();
            $specialities = !empty($user->profile->services) ? Helper::getUnserializeData($user->profile->services) : array();
            $experiences = !empty($user->profile->experiences) ? Helper::getUnserializeData($user->profile->experiences) : array();
            $educations = !empty($user->profile->educations) ? Helper::getUnserializeData($user->profile->educations) : array();
            $awards = !empty($user->profile->awards) ? Helper::getUnserializeData($user->profile->awards) : array();
            $memberships = !empty($user->profile->memberships) ? Helper::getUnserializeData($user->profile->memberships) : array();
            $registration_details = !empty($user->profile->verify_medical) ? Helper::getUnserializeData($user->profile->verify_medical) : array();
            $downloads = !empty($user->profile->downloads) ? Helper::getUnserializeData($user->profile->downloads) : array();
            $gender_title = !empty($user->profile->gender_title) ?  $user->profile->gender_title : '';
            if (!empty($specialities)) {
                foreach ($specialities as $speciality_key => $user_speciality) {
                    $speciality = Helper::getSpecialityByID($user_speciality['speciality_id']);
                    if (!empty($speciality)) {
                        $user_detail['specialires_data'][$speciality_key]['name'] = $speciality->title;
                        $user_detail['specialires_data'][$speciality_key]['logo'] = asset(Helper::getImage('uploads/specialities',  $speciality->image, '', 'default-speciality.png'));
                    }
                    if (!empty($user_speciality['services'])) {
                        foreach ($user_speciality['services'] as $spe_ser_key => $speciality_service) {
                            $service = Helper::getServiceByID($speciality_service['service']);
                            if (!empty($service)) {
                                $user_detail['specialires_data'][$speciality_key]['services'][$spe_ser_key]['title'] = $service->title;
                                $user_detail['specialires_data'][$speciality_key]['services'][$spe_ser_key]['price'] = !empty($speciality_service['price']) ? (!empty($symbol['symbol']) ? $symbol['symbol'] : '$') . $speciality_service['price'] : '';
                                $user_detail['specialires_data'][$speciality_key]['services'][$spe_ser_key]['description'] = !empty($speciality_service['description']) ? $speciality_service['description'] : '';
                            }
                        }
                    }
                }
            } else {
                $user_detail['specialires_data'] = '';
            }
            if (!empty($experiences)) {
                foreach ($experiences as $experience_key => $user_experience) {
                    $user_detail['experiences'][$experience_key]['company_name'] = $user_experience['company_title'];
                    $user_detail['experiences'][$experience_key]['job_title'] = $user_experience['start_date'];
                    $user_detail['experiences'][$experience_key]['start'] = $user_experience['end_date'];
                    $user_detail['experiences'][$experience_key]['ending'] = $user_experience['job_title'];
                    $user_detail['experiences'][$experience_key]['description'] = $user_experience['job_desc'];
                }
            } else {
                $user_detail['experiences'] = [];
            }
            if (!empty($educations)) {
                foreach ($educations as $education_key => $education) {
                    $user_detail['educations'][$education_key]['degree_title'] = $education['degree_title'];
                    $user_detail['educations'][$education_key]['institute_name'] = $education['job_title'];
                    $user_detail['educations'][$education_key]['start'] = $education['start_date'];
                    $user_detail['educations'][$education_key]['ending'] = $education['end_date'];
                    $user_detail['educations'][$education_key]['edu_desc'] = $education['job_desc'];
                }
            } else {
                $user_detail['educations'] = [];
            }
            if (!empty($awards)) {
                $award_count = 0;
                foreach ($awards as $award_key => $award) {
                    $user_detail['awards'][$award_count]['title'] = $award['title'];
                    $user_detail['awards'][$award_count]['year'] = $award['year'];
                    $award_count++;
                }
            } else {
                $user_detail['awards'] = [];
            }
            if (!empty($memberships)) {
                $membership_count = 0;
                foreach ($memberships as $membership_key => $membership) {
                    $user_detail['memberships'][$membership_count]['title'] = $membership['title'];
                    $membership_count++;
                }
            } else {
                $user_detail['memberships'] = [];
            }
            $user_detail['registration_number'] = !empty($registration_details['registration_number']) ? $registration_details['registration_number'] : '';
            $user_detail['defult_download'] = asset('/images/icon-imgs/img-01.png');
            if (!empty($downloads)) {
                foreach ($downloads as $download_key => $download) {
                    $download_name = Helper::formateFileName($download);
                    if (file_exists('uploads/users/' . $user->id . '/' . $download)) {
                        $download_size = Helper::bytesToHuman(File::size(public_path('uploads/users/' . $user->id . '/' . $download)));
                    } else {
                        $download_size = 0;
                    }
                    $user_detail['downloads'][$download_key]['name'] = $download_name;
                    $user_detail['downloads'][$download_key]['size'] = $download_size;
                    $user_detail['downloads'][$download_key]['url'] = asset('uploads/users/' . $user->id . '/' . $download);
                }
            } else {
                $user_detail['downloads'] = [];
            }
            $user_detail['ID'] = $user->id;
            $user_detail['contents'] = $user->profile->short_desc;
            $user_detail['average_rating'] = $avg_rating;
            $user_detail['total_rating'] = (string) Feedback::where('user_id', $user->id)->count();
            $user_detail['percentage'] = '';
            $user_detail['medilcal_verified'] = $user->profile->verify_registration == 1 ? 'yes' : 'no';
            $user_detail['is_verified'] = $user->user_verified == 1 ? 'yes' : 'no';
            $user_detail['name'] = Helper::getUserName($user->id);
            $user_detail['sub_heading'] = $user->profile->sub_heading;
            if (!empty($specialities)) {
                foreach ($specialities as $sep_speciality_key => $user_speciality) {
                    $speciality = Helper::getSpecialityByID($user_speciality['speciality_id']);
                    if (!empty($speciality)) {
                        $user_detail['specialities'][$sep_speciality_key]['id'] = !empty($speciality->id) ? $speciality->id : '';
                        $user_detail['specialities'][$sep_speciality_key]['name'] = !empty($speciality->title) ? $speciality->title : '';
                        $user_detail['specialities'][$sep_speciality_key]['slug'] = !empty($speciality->slug) ? $speciality->slug : '';
                    }
                }
            } else {
                $user_detail['specialities'] = [];
            }
            $user_detail['image'] = asset(Helper::getImage('uploads/users/' . $user->id,  $user->profile->avatar, '', 'user.jpg'));
            $user_detail['banner'] = asset(Helper::getImage('uploads/users/' . $user->id,  $user->profile->banner, '', 'user-banner.jpg'));
            $user_detail['featured'] = '';
            $user_detail['wishlist'] = !empty($saved_doctors) && in_array($user->id, $saved_doctors) ? 'yes' : 'no';
            $user_detail['role'] = $role_type;
            return Response::json($user_detail, 200);
        } else {
            $json['type']        = 'error';
            $json['message']    = trans('lang.no_record');
            return Response::json($json, 203);
        }
    }

    /**
     * Get doctor detail data
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function getGallery()
    {
        $json = array();
        $profile_id = !empty($_GET['profile_id']) ? $_GET['profile_id'] : '';
        $user = !empty($profile_id) ? User::find($profile_id) : new User();
        $user_detail = array();
        if (!empty($user) && !empty($profile_id)) {
            $gallery_images = !empty($user->profile->gallery) ? Helper::getUnserializeData($user->profile->gallery) : array();
            foreach ($gallery_images as $key => $image) {
                $user_detail['gallery_images'][$key]['image'] = asset(Helper::getImage('uploads/users/' . $user->id . '/gallery/images/', $image, 'small-', ''));
            }
            // $user_detail['gallery_images'] = !empty($user->profile->gallery) ? Helper::getUnserializeData($user->profile->gallery) : array();
            $user_detail['gallery_videos'] = !empty($user->profile->gallery_videos) ? Helper::getUnserializeData($user->profile->gallery_videos) : array();
            return Response::json($user_detail, 200);
        } else {
            $json['type']        = 'error';
            $json['message']    = trans('lang.no_record');
            return Response::json($json, 203);
        }
    }

    /**
     * Get search results
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function getUsers()
    {
        $json = array();
        $type = !empty($_GET['listing_type']) ? $_GET['listing_type'] : '';
        $profile_id = !empty($_GET['profile_id']) ? $_GET['profile_id'] : '';
        $login_user = !empty($profile_id) ? User::find($profile_id) : '';
        $keyword = !empty($_GET['keyword']) ? $_GET['keyword'] : '';
        $search_locations = !empty($_GET['location']) ? $_GET['location'] : '';
        $search_speciality = !empty($_GET['specialities']) ? $_GET['specialities'] : '';
        $search_service = !empty($_GET['services']) ? $_GET['services'] : '';
        $searchby = !empty($_GET['searchby']) ? $_GET['searchby'] : '';
        $order_by = !empty($_GET['orderby']) ? $_GET['orderby'] : '';
        $order = !empty($_GET['order']) ? $_GET['order'] : '';
        $post_per_page = !empty($_GET['show_users']) ? $_GET['show_users'] : 10;
        $page_number = !empty($_GET['page_number']) ? $_GET['page_number'] : 1;
        $auth_id = !empty($_GET['auth_id']) ? $_GET['auth_id'] : '';
        $day_list = Helper::getAppointmentDays();
        Paginator::currentPageResolver(
            function () use ($page_number) {
                return $page_number;
            }
        );
        $user_detail = array();
        if (!empty($type)) {
            $auth_user = !empty($auth_id) ? User::find($auth_id) : '';
            if ($type == 'search') {
                $json = array();
                $user_id = array();
                $filters = array();
                $user_by_role = !empty($searchby) && $searchby != 'both' ? Helper::getUsersByRoleType($searchby) : Helper::getSearchableUsers();
                $users = DB::table('users')->whereIn('id', $user_by_role);
                $filters['type'] = $type;
                if (!empty($keyword)) {
                    $filters['keyword'] = $keyword;
                    $users->where('first_name', 'like', '%' . $keyword . '%');
                    $users->orWhere('last_name', 'like', '%' . $keyword . '%');
                    $users->orWhere('slug', 'like', '%' . $keyword . '%');
                    $users->whereIn('id', $user_by_role);
                }
                if (!empty($search_locations)) {
                    $filters['locations'] = $search_locations;
                    $locations = Location::select('id')->where('slug', $search_locations)
                        ->get()->pluck('id')->toArray();
                    $location_user = User::select('id')->whereIn('location_id', $locations)->get()->pluck('id')->toArray();
                    $users->whereIn('id', $location_user)->whereIn('id', $user_by_role);
                }
                if (!empty($search_service)) {
                    $filters['service'] = $search_service;
                    $selected_service = Service::select('id')->where('slug', $search_service)
                        ->first();
                    $service = Service::find($selected_service->id);
                    if ($service->users->count() > 0) {
                        foreach ($service->users as $service_key => $user_service) {
                            $user_id[] = $user_service->id;
                        }
                    }
                    $users->whereIn('id', $user_id)->whereIn('id', $user_by_role);
                }
                if (!empty($search_speciality)) {
                    $filters['speciality'] = $search_speciality;
                    $selected_speciality = Speciality::select('id')->where('slug', $search_speciality)
                        ->first();
                    $speciality_users = DB::table('user_service')->select('user_id')
                        ->where('speciality', $selected_speciality->id)
                        ->groupBy('user_id')
                        ->get()->pluck('user_id')->toArray();
                    $users->whereIn('id', $speciality_users)->whereIn('id', $user_by_role);
                }
                $order = !empty($order) ? $order : 'asc';
                if (!empty($order_by)) {
                    if ($order_by == 'name') {
                        $users = $users->orderBy('first_name', $order);
                    } elseif ($order_by == 'date') {
                        $users = $users->orderBy('created_at', $order);
                    } else {
                        $users = $users->orderBy($order_by, $order);
                    }
                }
                $users = $users->paginate($post_per_page);
            } else if ($type == 'featured') {
                // $featured_users = Helper::getFeaturedUsers();
                // $users = DB::table('users')->whereIn('id', $featured_users);
                $user_by_role = Helper::getUsersByRoleType('doctor');
                $users = DB::table('users')->whereIn('id', $user_by_role);
                $order = !empty($order) ? $order : 'asc';
                if (!empty($order_by)) {
                    if ($order_by == 'name') {
                        $users = $users->orderBy('first_name', $order);
                    } elseif ($order_by == 'date') {
                        $users = $users->orderBy('created_at', $order);
                    } else {
                        $users = $users->orderBy($order_by, $order);
                    }
                }
                $users = $users->paginate($post_per_page);
            }
            $featured_count = 0;
            if (!empty($users)) {
                foreach ($users as $key => $user) {
                    $user_profile = User::find($user->id);
                    $role_type = !empty($user->id) ? Helper::getRoleTypeByUserID($user->id) : '';
                    $column = !empty($user->id) && Helper::getRoleTypeByUserID($user->id) == 'doctor' ? 'saved_doctors' : 'saved_hospitals';
                    $saved_user = !empty($auth_user) && !empty($auth_user->profile->$column) ? unserialize($auth_user->profile->$column) : array();
                    $user_specialities = DB::table('user_service')->select('speciality')
                        ->where('user_id', $user->id)->groupBy('speciality')->get();
                    if ($user_specialities->count() > 0) {
                        $specialities = $user_specialities->pluck('speciality')->random(1)->toArray();
                    } else {
                        $specialities = '';
                    }
                    if ($type == 'featured') {
                        $current_package = Helper::getCurrentPackage($user_profile);
                        $featured = !empty($current_package) && !empty($current_package['featured']) ? $current_package['featured'] : 'false';
                        if ($featured == 'true') {
                            $user_detail[$featured_count]['ID'] = $user->id;
                            $avg_rating = Feedback::where('user_id', $user->id)->pluck('avg_rating')->first();
                            $user_detail[$featured_count]['average_rating'] = !empty($avg_rating) ? $avg_rating : '0';
                            $user_detail[$featured_count]['total_rating'] = (string) Feedback::where('user_id', $user->id)->count();
                            $user_detail[$featured_count]['percentage'] = '0';
                            $user_detail[$featured_count]['medilcal_verified'] = $user_profile->profile->verify_registration == 1 ? 'yes' : 'no';
                            $user_detail[$featured_count]['is_verified'] = $user->user_verified == 1 ? 'yes' : 'no';
                            $user_detail[$featured_count]['name'] = Helper::getUserName($user->id);
                            $user_detail[$featured_count]['sub_heading'] = !empty($user_profile->profile->sub_heading)  ? $user_profile->profile->sub_heading : '';
                            $user_detail[$featured_count]['location'] = !empty($user_profile->location->title) ? $user_profile->location->title : '';
                            if (!empty($specialities)) {
                                foreach ($specialities as $sep_speciality_key => $user_speciality) {
                                    $speciality = Helper::getSpecialityByID($user_speciality);
                                    if (!empty($speciality)) {
                                        $user_detail[$featured_count]['specialities'][$sep_speciality_key]['id'] = !empty($speciality->id) ? $speciality->id : '';
                                        $user_detail[$featured_count]['specialities'][$sep_speciality_key]['name'] = !empty($speciality->title) ? $speciality->title : '';
                                        $user_detail[$featured_count]['specialities'][$sep_speciality_key]['slug'] = !empty($speciality->slug) ? $speciality->slug : '';
                                    }
                                }
                            } else {
                                $user_detail[$featured_count]['specialities'] = [];
                            }
                            $user_detail[$featured_count]['image'] = asset(Helper::getImage('uploads/users/' . $user->id,  $user_profile->profile->avatar, '', 'user.jpg'));
                            $current_package = Helper::getCurrentPackage($user_profile);
                            $featured = !empty($current_package) && !empty($current_package['featured']) ? $current_package['featured'] : 'false';
                            $user_detail[$featured_count]['featured'] = $featured == 'true' ? 'yes' : 'no';
                            $user_detail[$featured_count]['wishlist'] = !empty($saved_user) && in_array($user->id, $saved_user) ? 'yes' : 'no';
                            $user_detail[$featured_count]['role'] = $role_type;
                            $featured_count++;
                        }
                    } else {
                        $user_detail[$key]['ID'] = $user->id;
                        $avg_rating = Feedback::where('user_id', $user->id)->pluck('avg_rating')->first();
                        $user_detail[$key]['average_rating'] = !empty($avg_rating) ? $avg_rating : '0';
                        $user_detail[$key]['total_rating'] = (string) Feedback::where('user_id', $user->id)->count();
                        $user_detail[$key]['percentage'] = '0';
                        $user_detail[$key]['medilcal_verified'] = $user_profile->profile->verify_registration == 1 ? 'yes' : 'no';
                        $user_detail[$key]['is_verified'] = $user->user_verified == 1 ? 'yes' : 'no';
                        $user_detail[$key]['name'] = Helper::getUserName($user->id);
                        $user_detail[$key]['sub_heading'] = !empty($user_profile->profile->sub_heading)  ? $user_profile->profile->sub_heading : '';
                        $user_detail[$key]['location'] = !empty($user_profile->location->title) ? $user_profile->location->title : '';
                        if (!empty($specialities)) {
                            foreach ($specialities as $sep_speciality_key => $user_speciality) {
                                $speciality = Helper::getSpecialityByID($user_speciality);
                                if (!empty($speciality)) {
                                    $user_detail[$key]['specialities'][$sep_speciality_key]['id'] = !empty($speciality->id) ? $speciality->id : '';
                                    $user_detail[$key]['specialities'][$sep_speciality_key]['name'] = !empty($speciality->title) ? $speciality->title : '';
                                    $user_detail[$key]['specialities'][$sep_speciality_key]['slug'] = !empty($speciality->slug) ? $speciality->slug : '';
                                }
                            }
                        } else {
                            $user_detail[$key]['specialities'] = [];
                        }
                        $user_detail[$key]['image'] = asset(Helper::getImage('uploads/users/' . $user->id,  $user_profile->profile->avatar, '', 'user.jpg'));
                        $current_package = Helper::getCurrentPackage($user_profile);
                        $featured = !empty($current_package) && !empty($current_package['featured']) ? $current_package['featured'] : 'false';
                        $user_detail[$key]['featured'] = $featured == 'true' ? 'yes' : 'no';
                        $user_detail[$key]['total'] = User::count();
                        $user_detail[$key]['wishlist'] = !empty($saved_user) && in_array($user->id, $saved_user) ? 'yes' : 'no';
                        $user_detail[$key]['role'] = $role_type;
                    }
                    
                }
                if ($type == 'featured') {
                    if ($featured_count > 0) {
                        $user_detail[0]['total'] = $featured_count;
                    } else {
                        unset($json[0]);
                        $json['type']        = 'error';
                        $json['message']    = trans('lang.no_record');
                        return Response::json($json, 203);
                    }
                }
                return Response::json($user_detail, 200);
            } else {
                unset($json[0]);
                $json['type']        = 'error';
                $json['message']    = trans('lang.no_record');
                return Response::json($json, 203);
            }
        } else {
            $json['type']        = 'error';
            $json['message']    = trans('lang.listing_type');
            return Response::json($json, 203);
        }
    }

    /**
     * Get hospital detail data
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function getHospital()
    {
        $json = array();
        $profile_id = !empty($_GET['profile_id']) ? $_GET['profile_id'] : '';
        $user = !empty($profile_id) ? User::find($profile_id) : new User();
        $currency   = SiteManagement::getMetaValue('payment_settings');
        $symbol = !empty($currency) && !empty($currency['currency']) ? Helper::currencyList($currency['currency']) : 'symbol';
        $user_detail = array();
        $auth_id = !empty($_GET['auth_id']) ? $_GET['auth_id'] : '';
        if (!empty($user) && !empty($profile_id)) {
            $auth_user = !empty($auth_id) ? User::find($auth_id) : '';
            $saved_hospitals = !empty($auth_user->profile->saved_hospitals) ? unserialize($auth_user->profile->saved_hospitals) : array();
            $specialities = !empty($user->profile->services) ? Helper::getUnserializeData($user->profile->services) : array();
            $registration_details = !empty($user->profile->verify_medical) ? Helper::getUnserializeData($user->profile->verify_medical) : array();
            $gender_title = !empty($user->profile->gender_title) ?  $user->profile->gender_title : '';
            if (!empty($specialities)) {
                foreach ($specialities as $speciality_key => $user_speciality) {
                    $speciality = Helper::getSpecialityByID($user_speciality['speciality_id']);
                    if (!empty($speciality)) {
                        $user_detail['specialires_data'][$speciality_key]['name'] = $speciality->title;
                        $user_detail['specialires_data'][$speciality_key]['logo'] = asset(Helper::getImage('uploads/specialities',  $speciality->image, '', 'default-speciality.png'));
                    }
                    if (!empty($user_speciality['services'])) {
                        foreach ($user_speciality['services'] as $spe_ser_key => $speciality_service) {
                            $service = Helper::getServiceByID($speciality_service['service']);
                            if (!empty($service)) {
                                $user_detail['specialires_data'][$speciality_key]['services'][$spe_ser_key]['title'] = $service->title;
                                $user_detail['specialires_data'][$speciality_key]['services'][$spe_ser_key]['price'] = (!empty($symbol['symbol']) ? $symbol['symbol'] : '$') . $speciality_service['price'];
                                $user_detail['specialires_data'][$speciality_key]['services'][$spe_ser_key]['description'] = $speciality_service['description'];
                            }
                        }
                    }
                }
            } else {
                $user_detail['specialires_data'] = '';
            }
            $user_detail['registration_number'] = !empty($registration_details['registration_number']) ? $registration_details['registration_number'] : '';
            $user_detail['ID'] = $user->id;
            $user_detail['contents'] = $user->profile->short_desc;
            $user_detail['medilcal_verified'] = $user->profile->verify_registration == 1 ? 'yes' : 'no';
            $user_detail['is_verified'] = $user->user_verified == 1 ? 'yes' : 'no';
            $user_detail['name'] = Helper::getUserName($user->id);
            $user_detail['sub_heading'] = $user->profile->sub_heading;
            if (!empty($specialities)) {
                foreach ($specialities as $sep_speciality_key => $user_speciality) {
                    $speciality = Helper::getSpecialityByID($user_speciality['speciality_id']);
                    if (!empty($speciality)) {
                        $user_detail['specialities'][$sep_speciality_key]['id'] = !empty($speciality->id) ? $speciality->id : '';
                        $user_detail['specialities'][$sep_speciality_key]['name'] = !empty($speciality->title) ? $speciality->title : '';
                        $user_detail['specialities'][$sep_speciality_key]['slug'] = !empty($speciality->slug) ? $speciality->slug : '';
                    }
                }
            } else {
                $user_detail['specialities'] = '';
            }
            $user_detail['image'] = asset(Helper::getImage('uploads/users/' . $user->id,  $user->profile->avatar, '', 'user.jpg'));
            $user_detail['featured'] = '';
            $user_detail['wishlist'] = !empty($saved_hospitals) && in_array($user->id, $saved_hospitals) ? 'yes' : 'no';
            return Response::json($user_detail, 200);
        } else {
            $json['type']        = 'error';
            $json['message']    = trans('lang.no_record');
            return Response::json($json, 203);
        }
    }

    /**
     * Get doctor consulation data
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function getDoctorConsulation()
    {
        $json = array();
        $profile_id = !empty($_GET['profile_id']) ? $_GET['profile_id'] : '';
        $page_number = !empty($_GET['page_number']) ? $_GET['page_number'] : 1;
        $post_per_page = !empty($_GET['show_users']) ? $_GET['show_users'] : 10;
        Paginator::currentPageResolver(
            function () use ($page_number) {
                return $page_number;
            }
        );
        $user = !empty($profile_id) ? User::find($profile_id) : new User();
        $user_detail = array();
        if (!empty($user) && !empty($profile_id)) {
            $answers = $user->answers()->paginate($post_per_page);
            if (!empty($answers) && $user->answers->count() > 0) {
                foreach ($answers as $key => $forum) {
                    $speciality = Helper::getSpecialityByID($forum->speciality_id);
                    if (!empty($speciality)) {
                        $user_detail[$key]['title'] = $forum->question_title;
                        $user_detail[$key]['description'] = $forum->question_description;
                        $user_detail[$key]['speciality_title'] = $speciality->title;
                        $user_detail[$key]['image_url'] = asset(Helper::getImage('uploads/specialities', $speciality->image, '', 'default-speciality.png'));
                        $user_detail[$key]['date'] =  Carbon::parse($forum->created_at)->format('M d, Y');
                        $user_detail[$key]['comment'] =  $forum->pivot->answer;
                        $user_detail[$key]['name'] =  Helper::getUserName($user->id);
                    }
                }
            }
            return Response::json($user_detail, 200);
        } else {
            $json['type']        = 'error';
            $json['message']    = trans('lang.no_record');
            return Response::json($json, 203);
        }
    }

    /**
     * Get doctor patient reviews
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function getDoctorFeedback()
    {
        $json = array();
        $profile_id = !empty($_GET['profile_id']) ? $_GET['profile_id'] : '';
        $page_number = !empty($_GET['page_number']) ? $_GET['page_number'] : 1;
        $post_per_page = !empty($_GET['show_users']) ? $_GET['show_users'] : 10;
        Paginator::currentPageResolver(
            function () use ($page_number) {
                return $page_number;
            }
        );
        $user = !empty($profile_id) ? User::find($profile_id) : new User();
        $user_detail = array();
        if (!empty($user) && !empty($profile_id)) {
            $feedbacks = $user->feedbacks()->paginate($post_per_page);
            if (!empty($feedbacks) && $user->feedbacks->count() > 0) {
                foreach ($feedbacks as $key => $feedback) {
                    $patient = !empty($feedback->patient_id) ? User::find($feedback->patient_id) : '';
                    $user_detail[$key]['recommend'] = $user->profile->votes > 0 ? 'yes' : 'no';
                    $user_detail[$key]['recommend_text'] = trans('lang.i_recomend');
                    $user_detail[$key]['publicly'] = $feedback->keep_anonymous == 'on' ? 'yes' : 'no';
                    $user_detail[$key]['is_verified'] = $patient->user_verified == 1 ? 'yes' : 'no';
                    $user_detail[$key]['name'] = Helper::getUserName($feedback->patient_id);
                    $user_detail[$key]['tag_line'] = $patient->profile->tagline;
                    $user_detail[$key]['image_url'] = asset(Helper::getImage('uploads/users/' . $patient->id . '/', $patient->profile->avatar, '', 'user-logo-def.jpg'));
                    $user_detail[$key]['date'] = Carbon::parse($feedback->created_at)->format('M d, Y');
                    $user_detail[$key]['content'] = $feedback->comment;
                }
            }
            return Response::json($user_detail, 200);
        } else {
            $json['type']        = 'error';
            $json['message']    = trans('lang.no_record');
            return Response::json($json, 203);
        }
    }

    /**
     * Get doctor appointment location
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function getDoctorLocation()
    {
        $json = array();
        $profile_id = !empty($_GET['profile_id']) ? $_GET['profile_id'] : '';
        $page_number = !empty($_GET['page_number']) ? $_GET['page_number'] : 1;
        $post_per_page = !empty($_GET['show_users']) ? $_GET['show_users'] : 10;
        Paginator::currentPageResolver(
            function () use ($page_number) {
                return $page_number;
            }
        );
        $user = !empty($profile_id) ? User::find($profile_id) : new User();
        $user_detail = array();
        if (!empty($user) && !empty($profile_id)) {
            $teams = Team::where('doctor_id', $user->id)->where('status', 'approved')->paginate($post_per_page);
            $doctor_hospitals = Team::getDoctorHospitals($user->id);
            if (!empty($teams) && $teams->count() > 0) {
                foreach ($teams as $key => $hospital_team) {
                    $slots = unserialize($hospital_team->slots);
                    $team = Team::findOrFail($hospital_team->id);
                    $hospital_obj = User::findOrFail($team->hospital->id);
                    $services = !empty($slots['services']) ? $slots['services'] : array();
                    $appointment_days = !empty($slots['days']) ? $slots['days'] : array();
                    $specialities = DB::table('user_service')->select('speciality')
                        ->where('user_id', $hospital_obj->id)->groupBy('speciality')->get()->pluck('speciality')->random(1)->toArray();
                    $role_type = Helper::getRoleTypeByUserID($team->hospital->id);
                    $user_detail[$key]['ID'] = $hospital_obj->id;
                    $user_detail[$key]['location'] = !empty($hospital_obj->location->title) ? $hospital_obj->location->title : '';
                    if (!empty($appointment_days)) {
                        foreach (Helper::getAppointmentDays() as $day_key => $day) {
                            if (in_array($day_key, $appointment_days)) {
                                $user_detail[$key]['bookings_days'][]['name'] = $day['title'];
                            }
                            // $user_detail[$key]['bookings_days'][]['name'] = $day['title'];
                        }
                    }
                    $user_detail[$key]['no_of_teams'] = $hospital_obj->approvedTeams()->count();
                    $user_detail[$key]['availability'] = $hospital_obj->profile->working_time == '24_hours' ? trans('lang.24_hours') : $hospital_obj->profile->working_time;
                    $user_detail[$key]['no_of_teams'] = $hospital_obj->approvedTeams()->count();
                    $user_detail[$key]['medilcal_verified'] = $hospital_obj->profile->verify_registration == 1 ? 'yes' : 'no';
                    $user_detail[$key]['is_verified'] = $hospital_obj->user_verified == 1 ? 'yes' : 'no';
                    $user_detail[$key]['name'] = Helper::getUserName($hospital_obj->id);
                    $user_detail[$key]['sub_heading'] = $hospital_obj->profile->sub_heading;
                    $user_detail[$key]['working_hour'] = $hospital_obj->profile->working_time == '24_hours' ? trans('lang.24_hours') : clean($hospital_obj->profile->working_time);
                    $user_detail[$key]['onboard_doctors'] = clean($hospital_obj->approvedTeams()->count());
                    if (!empty($specialities)) {
                        foreach ($specialities as $sep_speciality_key => $user_speciality) {
                            $speciality = Helper::getSpecialityByID($user_speciality);
                            if (!empty($speciality)) {
                                $user_detail[$key]['specialities'][$sep_speciality_key]['id'] = !empty($speciality->id) ? $speciality->id : '';
                                $user_detail[$key]['specialities'][$sep_speciality_key]['name'] = !empty($speciality->title) ? $speciality->title : '';
                                $user_detail[$key]['specialities'][$sep_speciality_key]['slug'] = !empty($speciality->slug) ? $speciality->slug : '';
                            }
                        }
                    } else {
                        $user_detail[$key]['specialities'] = '';
                    }
                    $user_detail[$key]['image'] = asset(Helper::getImage('uploads/users/' . $hospital_obj->id,  $hospital_obj->profile->avatar, '', 'user.jpg'));
                }
            }
            return Response::json($user_detail, 200);
        } else {
            $json['type']        = 'error';
            $json['message']    = trans('lang.no_record');
            return Response::json($json, 203);
        }
    }

    /**
     * Get doctor articles data
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function getDoctorArticles()
    {
        $json = array();
        $profile_id = !empty($_GET['profile_id']) ? $_GET['profile_id'] : '';
        $page_number = !empty($_GET['page_number']) ? $_GET['page_number'] : 1;
        $post_per_page = !empty($_GET['show_users']) ? $_GET['show_users'] : 10;
        $auth_id = !empty($_GET['auth_id']) ? $_GET['auth_id'] : '';
        Paginator::currentPageResolver(
            function () use ($page_number) {
                return $page_number;
            }
        );
        $user = !empty($profile_id) ? User::find($profile_id) : new User();
        $user_detail = array();
        if (!empty($user) && !empty($profile_id)) {
            $auth_user = !empty($auth_id) ? User::find($auth_id) : '';
            $saved_articles = !empty($auth_user) && !empty($auth_user->profile->saved_articles) ? unserialize($auth_user->profile->saved_articles) : array();
            $articles = $user->articles()->paginate($post_per_page);
            if (!empty($articles) && $articles->count() > 0) {
                foreach ($articles as $key => $article) {
                    $user_detail[$key]['likes'] = !empty($article->likes) ? $article->likes : 0;
                    $user_detail[$key]['views'] = !empty($article->views) ? $article->views : 0;
                    $user_detail[$key]['posted_date'] = Carbon::parse($article->created_at)->format('M d, Y');
                    $user_detail[$key]['ID'] = $article->id;
                    if (!empty($article->categories) && $article->categories->count() > 0) {
                        foreach ($article->categories as $category) {
                            $user_detail[$key]['categories']['id'] = $category->id;
                            $user_detail[$key]['categories']['name'] = $category->title;
                            $user_detail[$key]['categories']['slug'] = $category->slug;
                        }
                    } else {
                        $user_detail[$key]['categories'] = '';
                    }
                    $user_detail[$key]['image_url'] = asset(Helper::getImage('uploads/users/' . $user->id . '/articles/', $article->image, '', 'list-article-default.jpg'));
                    $user_detail[$key]['title'] = $article->title;
                    $user_detail[$key]['post_url'] = route('articleDetail', ['slug' => clean($article->slug)]);
                    $user_detail[$key]['wishlist'] = !empty($saved_articles) && in_array($article->id, $saved_articles) ? 'yes' : 'no';
                }
                return Response::json($user_detail, 200);
            } else {
                $json['type']        = 'error';
                $json['message']    = trans('lang.no_record');
                return Response::json($json, 203);
            }
        } else {
            $json['type']        = 'error';
            $json['message']    = trans('lang.no_record');
            return Response::json($json, 203);
        }
    }

    /**
     * Get articles data
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function getArticleDetail()
    {
        $json = array();
        $post_id = !empty($_GET['post_id']) ? $_GET['post_id'] : '';
        $page_number = !empty($_GET['page_number']) ? $_GET['page_number'] : 1;
        $auth_id = !empty($_GET['auth_id']) ? $_GET['auth_id'] : '';
        Paginator::currentPageResolver(
            function () use ($page_number) {
                return $page_number;
            }
        );
        $article = !empty($post_id) ? Article::find($post_id) : '';
        $article_detail = array();
        if (!empty($article) && !empty($post_id)) {
            $auth_user = !empty($auth_id) ? User::find($auth_id) : '';
            $saved_articles = !empty($auth_user) && !empty($auth_user->profile->saved_articles) ? unserialize($auth_user->profile->saved_articles) : array();
            $article_detail['user_name'] = Helper::getUserName($article->author->id);
            $article_detail['user_link'] = url('profile/' . $article->author->slug);
            $article_detail['user_contents'] = $article->author->short_desc;
            $article_detail['user_image'] = asset(Helper::getImage('uploads/users/' . $article->author->id . '/articles', $article->image, 'blog-single-', 'article-default.jpg'));
            $article_detail['user_date'] = Carbon::parse($article->author->created_at)->format('M d, Y');
            $article_detail['likes'] = !empty($article->likes) ? $article->likes : 0;
            $article_detail['views'] = !empty($article->views) ? $article->views : 0;
            $article_detail['post_content'] = !empty($article->description) ?  html_entity_decode(clean($article->description)) : '';
            $article_detail['post_tags'] = '';
            if (!empty($article->categories) && $article->categories->count() > 0) {
                foreach ($article->categories as $category) {
                    $cat = Category::where('id', $category->id)->select('title', 'slug')->first();
                    $article_detail['categories']['id'] = $cat->id;
                    $article_detail['categories']['name'] = $cat->title;
                    $article_detail['categories']['slug'] = $cat->slug;
                }
            } else {
                $article_detail['categories'] = '';
            }
            $article_detail['image_url'] = asset(Helper::getImage('uploads/users/' . $article->author->id . '/articles/', $article->image, '', 'list-article-default.jpg'));
            $article_detail['title'] = $article->title;
            $article_detail['post_url'] = route('articleDetail', ['slug' => clean($article->slug)]);
            $user_detail['wishlist'] = !empty($saved_articles) && in_array($article->id, $saved_articles) ? 'yes' : 'no';
            return Response::json($article_detail, 200);
        } else {
            $json['type']        = 'error';
            $json['message']    = trans('lang.no_record');
            return Response::json($json, 203);
        }
    }

    /**
     * Get hospital team
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function getHospitalTeam()
    {
        $json = array();
        $profile_id = !empty($_GET['profile_id']) ? $_GET['profile_id'] : '';
        $page_number = !empty($_GET['page_number']) ? $_GET['page_number'] : 1;
        $post_per_page = !empty($_GET['show_users']) ? $_GET['show_users'] : 10;
        $currency   = SiteManagement::getMetaValue('payment_settings');
        $symbol = !empty($currency) && !empty($currency['currency']) ? Helper::currencyList($currency['currency']) : 'symbol';
        Paginator::currentPageResolver(
            function () use ($page_number) {
                return $page_number;
            }
        );
        $user = !empty($profile_id) ? User::find($profile_id) : new User();
        $user_detail = array();
        if (!empty($user) && !empty($profile_id)) {
            $team = $user->approvedTeams()->paginate($post_per_page);
            if (!empty($team) && $team->count() > 0) {
                foreach ($team as $key => $doctor) {
                    $slots = unserialize($doctor->slots);
                    $doctor_obj = User::find($doctor->doctor_id);
                    $services = !empty($slots['services']) ? $slots['services'] : array();
                    $appointment_days = !empty($slots['days']) ? $slots['days'] : array();
                    $specialities = $doctor_obj->services->count() > 0 ? DB::table('user_service')->select('speciality')
                        ->where('user_id', $doctor_obj->id)->groupBy('speciality')->get()->pluck('speciality')->random(1)->toArray() : '';
                    $avg_rating = Feedback::where('user_id', $doctor_obj->id)->pluck('avg_rating')->first();
                    $user_detail[$key]['ID'] = $doctor_obj->id;
                    $user_detail[$key]['average_rating'] = $avg_rating;
                    $user_detail[$key]['total_rating'] = (string) Feedback::where('user_id', $doctor_obj->id)->count();
                    $user_detail[$key]['percentage'] = '';
                    $user_detail[$key]['medilcal_verified'] = $doctor_obj->profile->verify_registration == 1 ? 'yes' : 'no';
                    $user_detail[$key]['is_verified'] = $doctor_obj->user_verified == 1 ? 'yes' : 'no';
                    $user_detail[$key]['name'] = Helper::getUserName($doctor_obj->id);
                    $user_detail[$key]['sub_heading'] = $doctor_obj->profile->sub_heading;
                    if (!empty($specialities)) {
                        foreach ($specialities as $speciality_key => $user_speciality) {
                            $speciality = Helper::getSpecialityByID($user_speciality);
                            if (!empty($speciality)) {
                                $user_detail[$key]['specialities']['id'] = $speciality->id;
                                $user_detail[$key]['specialities']['name'] = $speciality->title;
                                $user_detail[$key]['specialities']['slug'] = $speciality->slug;
                            }
                        }
                    } else {
                        $user_detail[$key]['specialities'] = '';
                    }
                    $user_detail[$key]['image'] = asset(Helper::getImage('uploads/users/' . $doctor_obj->id,  $doctor_obj->profile->avatar, '', 'user.jpg'));
                    $user_detail[$key]['featured'] = '';
                    if (!empty($appointment_days)) {
                        foreach (Helper::getAppointmentDays() as $day_key => $day) {
                            $user_detail[$key]['bookings_days'][] = $day['title'];
                            $user_detail[$key]['current_day'] = strtolower(Carbon::now()->format('D'));
                        }
                    }
                    $user_detail[$key]['votes'] = $doctor_obj->profile->votes;
                    $user_detail[$key]['starting_price'] = !empty($doctor_obj->profile->starting_price) ? (!empty($symbol['symbol']) ? $symbol['symbol'] : '$') . $doctor_obj->profile->starting_price : '';
                }
            }
            return Response::json($user_detail, 200);
        } else {
            $json['type']        = 'error';
            $json['message']    = trans('lang.no_record');
            return Response::json($json, 203);
        }
    }

    /**
     * Login user for application
     *
     * @param \Illuminate\Http\Request $request request attributes
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function userLogin(Request $request)
    {
        $json = array();
        $credentials = $request->only('email', 'password');
        if (auth()->attempt($credentials)) {
            $json['type'] = 'success';
            $json['profile']['pmeta']['user_type'] = auth()->user()->getRoleNames()[0];
            $json['profile']['pmeta']['profile_img'] = asset(Helper::getImage('uploads/users/' . auth()->user()->id,  auth()->user()->profile->avatar, '', 'user.jpg'));
            $json['profile']['pmeta']['am_name_base'] = auth()->user()->profile->tagline;
            $json['profile']['pmeta']['am_sub_heading'] = !empty(auth()->user()->profile->sub_heading) ? auth()->user()->profile->sub_heading : '';
            $json['profile']['pmeta']['am_first_name'] = auth()->user()->first_name;
            $json['profile']['pmeta']['am_last_name'] = auth()->user()->last_name;
            $json['profile']['pmeta']['am_short_description'] = auth()->user()->profile->short_desc;
            $json['profile']['pmeta']['full_name'] = Helper::getUserName(auth()->user()->id);
            $json['profile']['umeta']['profile_id'] = auth()->user()->profile->id;
            $json['profile']['umeta']['id'] = auth()->user()->id;
            $json['profile']['umeta']['user_login'] = $request['email'];
            $json['profile']['umeta']['user_pass'] = $request['password'];
            $json['profile']['umeta']['user_email'] = $request['email'];
            return response()->json($json, 200);
        } else {
            return response()->json(['error' => 'UnAuthorized'], 203);
        }
    }

    /**
     * Logout user from application
     *
     * @param \Illuminate\Http\Request $request request attributes
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function userLogout(Request $request)
    {
        // $json = array();
        // if (auth()->user()) {
        //     auth()->logout();
        //     return response()->json($json, 200);
        // } else {
        //     return response()->json(['error' => 'UnAuthorized'], 203);
        // }
        // auth()->logout();
        // \Session::flush();
        // return response()->json($json, 200);
    }

    /**
     * Login user for application
     *
     * @param \Illuminate\Http\Request $request request attributes
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function resetPassword(Request $request)
    {
        $json = array();
        if ($request->expectsJson()) {
            return $response = Password::RESET_LINK_SENT
                ? response()->json(['status' => 'Success', 'message' => 'Reset Password Link Sent'], 201)
                : response()->json(['status' => 'Fail', 'message' => 'Reset Link Could Not Be Sent'], 401);
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $request returns request
     *
     * @return \App\User
     */
    protected function createUser(Request $request)
    {
        $json = array();
        $roles = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
        ];
        $validator = \Validator::make($request->all(), $roles);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 203);
        }
        if ($request['password'] == $request['verify_password']) {
            $user = new User();
            $register_form = SiteManagement::getMetaValue('reg_form_settings');
            $verification_step = !empty($register_form) && !empty($register_form['enable_verification']) ? $register_form['enable_verification'] : true;
            $verification_code = '';
            if ($verification_step == 'true') {
                $random_number = Helper::generateRandomCode(4);
                $verification_code = strtoupper($random_number);
            }
            $user_id = $user->storeUser($request, $verification_code, $verification_step);
            $role_type = Helper::getRoleTypeByUserID($user_id);
            if ($role_type == 'doctor') {
                $order = new Order();
                $order->status = 'pending';
                $order->payment_gateway = 'paypal';
                $order->user()->associate($user_id);
                $order->save();
                $order_id = DB::getPdo()->lastInsertId();
                $latest_order = Order::find($order_id);
                $order_type = new OrderMeta();
                $order_type->meta_key = 'type';
                $order_type->meta_value = 'package';
                $latest_order->orderMeta()->save($order_type);
                $package_data = array();
                $package = Package::where('trial', 1)->first()->toArray();
                if (!empty($package)) {
                    $option = !empty($package['options']) ? Helper::getUnserializeData($package['options']) : '';
                    foreach ($package as $package_key => $package_value) {
                        $package_data[$package_key] = $package_value;
                    }
                    $package_meta = new OrderMeta();
                    $package_meta->meta_key = 'package';
                    $package_meta->meta_value = serialize($package_data);
                    $latest_order->orderMeta()->save($package_meta);
                    $expiry = !empty($order) ? $order->created_at->addDays($option['duration']) : '';
                    $expiry_date = !empty($expiry) ? Carbon::parse($expiry)->toDateTimeString() : '';
                    $user = User::find($user_id);
                    $user->package_expiry = $expiry_date;
                    $user->save();
                }
            }
            session()->put(['user_id' => $user_id]);
            session()->put(['email' => $request['email']]);
            session()->put(['password' => $request['password']]);
            //Send Mail
            if (!empty(config('mail.username')) && !empty(config('mail.password'))) {
                $email_params = array();
                $template = DB::table('email_types')->select('id')
                    ->where('email_type', 'verification_code')->get()->first();
                if (!empty($template->id)) {
                    $template_data = EmailTemplate::getEmailTemplateByID($template->id);
                    $email_params['verification_code'] = $user->verification_code;
                    $email_params['name']  = Helper::getUserName($user->id);
                    $email_params['email'] = $user->email;
                    Mail::to($user->email)
                        ->send(
                            new GeneralEmailMailable(
                                'verification_code',
                                $template_data,
                                $email_params
                            )
                        );
                }
                $json['id'] = $user_id;
                $json['message'] = trans('lang.account_register');
                return response()->json($json, 200);
            } else {
                $id = Session::get('user_id');
                $user = User::find($id);
                Auth::login($user);
                $json['email'] = 'not_configured';
                $json['url'] = url($role_type . '/dashboard');
            }
            $json['type'] = 'success';
            return $json;
        } else {
            $json['message'] = trans('lang.password_not_match');
            return response()->json(['error' => 'UnAuthorized'], 203);
        }
    }

    /**
     * Get user settings API
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserProfileSettings()
    {
        $json = array();
        $id = !empty($_GET['id']) ? $_GET['id'] : '';
        $user = !empty($id) ? User::find($id) : new User();
        $user_detail = array();
        if (!empty($user) && !empty($id)) {
            $memberships = !empty($user->profile->memberships) ? Helper::getUnserializeData($user->profile->memberships) : array();
            $registration_details = !empty($user->profile->verify_medical) ? Helper::getUnserializeData($user->profile->verify_medical) : array();
            if (!empty($registration_details['registration_document'])) {
                if (file_exists(public_path('uploads/users/' . $id . '/' . $registration_details['registration_document']))) {
                    $document_size =  Helper::bytesToHuman(File::size(public_path('uploads/users/' . $id . '/' . $registration_details['registration_document'])));
                } else {
                    $document_size = 0;
                }
            } else {
                $document_size = 0;
            }

            $downloads = !empty($user->profile->downloads) ? Helper::getUnserializeData($user->profile->downloads) : array();
            $user_detail['am_name_base'] = !empty($user->profile->gender_title) ? $user->profile->gender_title : '';
            $user_detail['am_sub_heading'] = !empty($user->profile->sub_heading) ? $user->profile->sub_heading : '';
            $user_detail['am_first_name'] = !empty($user->first_name) ? $user->first_name : '';
            $user_detail['am_last_name'] = !empty($user->last_name) ? $user->last_name : '';
            $user_detail['display_name'] = Helper::getUserName($id);
            $user_detail['address'] = !empty($user->profile->address) ? $user->profile->address : '';
            $user_detail['latitude'] = !empty($user->profile->latitude) ? $user->profile->latitude : '';
            $user_detail['longitude'] = !empty($user->profile->longitude) ? $user->profile->longitude : '';
            $user_detail['location'] = !empty($user->location) && !empty($user->location->title) ? $user->location->title : '';
            $user_detail['content'] = !empty($user->profile->description) ? $user->profile->description : '';
            $user_detail['profile_attachment_id'] = '';
            $user_detail['profile_image_url'] = asset(Helper::getImage('uploads/users/' . $id . '/', $user->profile->avatar, '', 'doc-single-def.jpg'));
            $user_detail['profile_banner_url'] = asset(Helper::getImage('uploads/users/' . $id,  $user->profile->banner, '', 'user-banner.jpg'));
            $user_detail['am_short_description'] = !empty($user->profile->short_desc) ? $user->profile->short_desc : '';
            $user_detail['am_starting_price'] = !empty($user->profile->starting_price) ? $user->profile->starting_price : '';
            if (!empty($memberships)) {
                $membership_count = 0;
                foreach ($memberships as $membership_key => $membership) {
                    $user_detail['memberships'][$membership_count]['title'] = $membership['title'];
                    $membership_count++;
                }
            } else {
                $user_detail['am_memberships'] = '';
            }
            $experiences = !empty($user->profile->experiences) ? Helper::getUnserializeData($user->profile->experiences) : array();
            $educations = !empty($user->profile->educations) ? Helper::getUnserializeData($user->profile->educations) : array();
            $awards = !empty($user->profile->awards) ? Helper::getUnserializeData($user->profile->awards) : array();
            if (!empty($experiences)) {
                foreach ($experiences as $experience_key => $user_experience) {
                    $user_detail['am_experiences'][$experience_key]['company_name'] = $user_experience['company_title'];
                    $user_detail['am_experiences'][$experience_key]['job_title'] = $user_experience['start_date'];
                    $user_detail['am_experiences'][$experience_key]['start'] = $user_experience['end_date'];
                    $user_detail['am_experiences'][$experience_key]['ending'] = $user_experience['job_title'];
                    $user_detail['am_experiences'][$experience_key]['description'] = $user_experience['job_desc'];
                }
            } else {
                $user_detail['am_experiences'] = '';
            }
            if (!empty($educations)) {
                foreach ($educations as $education_key => $education) {
                    $user_detail['am_education'][$education_key]['degree_title'] = $education['degree_title'];
                    $user_detail['am_education'][$education_key]['institute_name'] = $education['job_title'];
                    $user_detail['am_education'][$education_key]['start'] = $education['start_date'];
                    $user_detail['am_education'][$education_key]['ending'] = $education['end_date'];
                    $user_detail['am_education'][$education_key]['edu_desc'] = $education['job_desc'];
                }
            } else {
                $user_detail['am_education'] = '';
            }
            if (!empty($awards)) {
                $award_count = 0;
                foreach ($awards as $award_key => $award) {
                    $user_detail['am_award'][$award_count]['title'] = $award['title'];
                    $user_detail['am_award'][$award_count]['year'] = $award['year'];
                    $award_count++;
                }
            } else {
                $user_detail['am_award'] = '';
            }
            $user_detail['document_url'] = !empty($registration_details['registration_document']) ? url('uploads/users/' . $id . '/' . $registration_details['registration_document']) : '';
            $user_detail['document_id'] = '';

            $user_detail['document_size'] = $document_size;
            $user_detail['document_name'] = !empty($registration_details['registration_document']) ? Helper::formateFileName($registration_details['registration_document']) : '';
            $user_detail['document_verified'] = $user->profile->verify_registration == 1 ? 'yes' : 'no';
            $user_detail['reg_number'] = !empty($registration_details['registration_number']) ? $registration_details['registration_number'] : '';
            $user_detail['am_downloads_image'] = asset('/images/icon-imgs/img-01.png');
            if (!empty($downloads)) {
                foreach ($downloads as $download_key => $download) {
                    if (file_exists(public_path('uploads/users/' . $user->id . '/' . $download))) {
                        $download_name = Helper::formateFileName($download);
                        $download_size = Helper::bytesToHuman(File::size(public_path('uploads/users/' . $user->id . '/' . $download)));
                    } else {
                        $download_name = '';
                        $download_size = 0;
                    }
                    $user_detail['am_downloads'][$download_key]['name'] = $download_name;
                    $user_detail['am_downloads'][$download_key]['size'] = $download_size;
                    $user_detail['am_downloads'][$download_key]['media'] = asset('uploads/users/' . $user->id . '/' . $download);
                    $user_detail['am_downloads'][$download_key]['id'] = '';
                }
            } else {
                $user_detail['downloads'] = '';
            }
            return Response::json($user_detail, 200);
        } else {
            $json['type']        = 'error';
            $json['message']    = trans('lang.no_record');
            return Response::json($json, 203);
        }
    }

    /**
     * Get delete reasons
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function getRemoveReasons()
    {
        $json = array();
        $reasons = Helper::getDeleteAccReason();
        $list_detail = array();
        $count = 0;
        if (!empty($reasons)) {
            foreach ($reasons as $key => $title) {
                $list_detail[$count]['key'] = $key;
                $list_detail[$count]['val'] = $title;
                $count++;
            }
            return Response::json($list_detail, 200);
        } else {
            $json['type']        = 'error';
            $json['message']    = trans('lang.no_record');
            return Response::json($json, 203);
        }
    }

    /**
     * Get taxonomies data
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function getTeam()
    {
        $json = array();
        $user_id = !empty($_GET['user_id']) ? $_GET['user_id'] : '';
        $status = !empty($_GET['status']) ? $_GET['status'] : '';
        $page_number = !empty($_GET['page_number']) ? $_GET['page_number'] : 1;
        $post_per_page = !empty($_GET['show_users']) ? $_GET['show_users'] : 10;
        Paginator::currentPageResolver(
            function () use ($page_number) {
                return $page_number;
            }
        );
        $count = 0;
        if (!empty($user_id)) {
            $hospital = User::findOrFail($user_id);
            if (!empty($status)) {
                $teams = !empty($hospital) ? $hospital->teams()->where('status', $status)->paginate($post_per_page) : array();
            } else {
                $teams = !empty($hospital) ? $hospital->teams()->paginate($post_per_page) : array();
            }

            if (!empty($teams) && $teams->count() > 0) {
                foreach ($teams as $key => $team) {
                    $user = User::findOrFail($team->doctor_id);
                    $json[$key]['name'] = ucwords(Helper::getUserName($user->id));
                    $json[$key]['ID'] = $team->id;
                    $json[$key]['status'] = $team->status;
                    $json[$key]['image'] = asset(Helper::getImage('uploads/users/' . $user->id, $user->profile->avatar, 'extra-small-', 'user.jpg'));
                }
                return Response::json($json, 200);
            } else {
                $json['type']        = 'error';
                $json['message']    = trans('lang.no_record');
                return Response::json($json, 203);
            }
        } else {
            $json['type']        = 'error';
            $json['message']    = trans('lang.no_record');
            return Response::json($json, 203);
        }
    }

    /**
     * Get taxonomies data
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function getTaxonomies()
    {
        $json = array();
        $list = !empty($_GET['list']) ? $_GET['list'] : '';
        $list_detail = array();
        $count = 0;
        if (!empty($list)) {
            if ($list == 'bas_name') {
                $gender_titles = Helper::getDoctorArray();
                foreach ($gender_titles as $key => $title) {
                    $list_detail[$count]['key'] = $key;
                    $list_detail[$count]['val'] = $title;
                    $count++;
                }
            } elseif ($list == 'location') {
                $locations = Location::all();
                if (!empty($locations)) {
                    foreach ($locations as $key => $location) {
                        $list_detail[$key]['id'] = $location->id;
                        $list_detail[$key]['slug'] = $location->slug;
                        $list_detail[$key]['title'] = $location->title;
                    }
                }
            } elseif ($list == 'working_time') {
                $working_time = array(
                    '1' => '0 to 15 min',
                    '2' => '15 to 30 min',
                    '3' => '30 to 1 hr',
                    '4' => 'More then hr'
                );
                foreach ($working_time as $key => $value) {
                    $list_detail[$count]['title'] = $value;
                    $list_detail[$count]['value'] = $key;
                    $count++;
                }
            } elseif ($list == 'imrpovement_options') {
                $feedback_questions = ImprovementOption::get();
                if (!empty($feedback_questions)) {
                    foreach ($feedback_questions as $key => $option) {
                        $list_detail[$key]['title'] = $option->title;
                    }
                }
            } elseif ($list == 'hospitals') {
                $list_detail = User::role('hospital')->select(
                    DB::raw("CONCAT(users.first_name,' ',users.last_name) AS name"),
                    "id"
                )->get()->toArray();
                if (empty($list_detail)) {
                    $json['type'] = 'error';
                    return Response::json($json, 203);
                }
            } elseif ($list == 'intervals') {
                $intervals = Helper::getAppointmentIntervals();
                foreach ($intervals as $key => $value) {
                    $list_detail[$count]['title'] = $value;
                    $list_detail[$count]['value'] = $key;
                    $count++;
                }
            } elseif ($list == 'duration') {
                $duration = Helper::getAppointmentDuration();
                foreach ($duration as $key => $value) {
                    $list_detail[$count]['title'] = $value;
                    $list_detail[$count]['value'] = $key;
                    $count++;
                }
            } elseif ($list == 'spaces') {
                $spaces = Helper::getAppointmentSpaces();
                foreach ($spaces as $key => $value) {
                    $list_detail[] = $value;
                }
            } elseif ($list == 'days') {
                $days = Helper::getAppointmentDays();
                foreach ($days as $key => $value) {
                    $list_detail[$count]['key'] = $key;
                    $list_detail[$count]['title'] = $value['title'];
                    $list_detail[$count]['name'] =  $value['name'];
                    $count++;
                }
            } elseif ($list == 'slots') {
                for ($i = 1; $i <= 30; $i++) {
                    $slot = $i > 1 ? 'slots' : 'slot';
                    $list_detail[$count]['value'] = $i . ' ' . $slot;
                    $count++;
                }
            } elseif ($list == 'services') {
                $speciality_id = !empty($_GET['speciality']) ? $_GET['speciality'] : '';
                $speciality = Speciality::find($speciality_id);
                if (!empty($speciality) && $speciality->services->count() > 0) {
                    $services = $speciality->services->toArray();
                    if (!empty($speciality)) {
                        $list_detail['services'] = $services;
                    } else {
                        $json['type'] = 'error';
                        $json['message'] = trans('lang.something_wrong');
                        return Response::json($json, 203);
                    }
                } else {
                    $json['type'] = 'error';
                    $json['message'] = trans('lang.something_wrong');
                    return Response::json($json, 203);
                }
            } elseif ($list == 'article_categories') {
                $categories = Category::all()->toArray();
                if (!empty($categories)) {
                    foreach ($categories as $key => $value) {
                        $list_detail[$key]['id'] = $value['id'];
                        $list_detail[$key]['title'] = $value['title'];
                    }
                }
            }
            return Response::json($list_detail, 200);
        } else {
            $json['type']        = 'error';
            $json['message']    = trans('lang.no_record');
            return Response::json($json, 203);
        }
    }

    /**
     * Get taxonomies data
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function getDoctorAppointments()
    {
        $user_id = !empty($_GET['user_id']) ? $_GET['user_id'] : '';
        $appointment_date = !empty($_GET['appointment_date']) ? $_GET['appointment_date'] : '';
        $json =  array();
        $list = array();
        $booking_settings = SiteManagement::getMetaValue('booking_settings');
        $online_payment = !empty($booking_settings['enable_booking']) ? $booking_settings['enable_booking'] : '';
        if (!empty($user_id)) {
            $user = User::find($user_id);
            $appointments = $user->appointments;
            if ($online_payment == 'true') {
                $appointments = $appointments->where('status', 'accepted');
            }
            if (!empty($appointment_date)) {
                $appointments = $appointments->where('appointment_date', $appointment_date);
            }
            $counter = 0;
            if ($appointments->count() > 0) {
                foreach ($appointments as $key => $appointment) {
                    $patient = !empty($appointment->patient_id) ? User::find($appointment->patient_id) : '';
                    $list[$counter]['patient_id'] = !empty($patient) ? $appointment->patient_id : '';
                    $list[$counter]['id'] = $appointment->id;
                    $list[$counter]['appointments'] = $appointments->count();
                    $list[$counter]['status'] = $appointment->status;
                    $list[$counter]['name'] = !empty($patient) && !empty($appointment->patient_id)
                        ? Helper::getUserName($appointment->patient_id) : '';
                    $list[$counter]['image'] = !empty($patient)
                        ? asset(Helper::getImage('uploads/users/' . $appointment->patient_id, $patient->profile->avatar, 'small-', 'user.jpg'))
                        : '';
                    $date = $appointment->appointment_date;
                    $patient_date = new Carbon($appointment->appointment_date);
                    $patient_appointment_date = explode("-", $date);
                    $list[$counter]['day'] = !empty($patient_appointment_date) ? $patient_appointment_date[2] : '';
                    $list[$counter]['month'] = !empty($patient_date) ? $patient_date->format('M') : '';
                    $list[$counter]['post_date'] = !empty($date) ? $date : '';
                    $counter++;
                }
                return Response::json($list, 200);
            } else {
                $json['type']        = 'error';
                $json['message']    = trans('lang.no_record');
                return Response::json($json, 203);
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.no_record');
            return $json;
        }
    }

    /**
     * Get taxonomies data
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function getDoctorAppointmentSingle()
    {
        $user_id = !empty($_GET['user_id']) ? $_GET['user_id'] : '';
        $booking_id = !empty($_GET['booking_id']) ? $_GET['booking_id'] : '';
        $json =  array();
        $list = array();
        $currency   = SiteManagement::getMetaValue('payment_settings');
        $symbol = !empty($currency) && !empty($currency['currency']) ? Helper::currencyList($currency['currency']) : 'symbol';
        if (!empty($booking_id)) {
            $appointment = Appointment::find($booking_id);
            $counter = 0;
            if (!empty($appointment)) {
                $patient = !empty($appointment->patient_id) ? User::find($appointment->patient_id) : '';
                $services = !empty($appointment->services) ? Helper::getUnserializeData($appointment->services) : '';
                $list['post_status'] = $appointment->status;
                $list['name'] = !empty($patient) && !empty($appointment->patient_id)
                    ? Helper::getUserName($appointment->patient_id) : '';
                $list['other_name'] = !empty($appointment->patient_name)
                    ? $appointment->patient_name : '';
                $list['other_relation'] = !empty($appointment->relation)
                    ? $appointment->relation : '';
                $list['image'] = !empty($patient)
                    ? asset(Helper::getImage('uploads/users/' . $appointment->patient_id, $patient->profile->avatar, 'small-', 'user.jpg'))
                    : '';
                $list['user_verify'] = !empty($patient) ? $patient->user_verified : 0;
                $list['country'] = !empty($patient->location) && $patient->location->count() > 0 ? $patient->location->title : '';
                $list['user_type'] = !empty($patient) ? $patient->getRoleNames()->first() : '';
                $list['loc_title'] = !empty($appointment->hospital_id) ? Helper::getUserName($appointment->hospital_id) : '';
                $date = $appointment->appointment_date;
                $patient_date = new Carbon($appointment->appointment_date);
                $patient_appointment_date = explode("-", $date);
                $day = !empty($patient_appointment_date) ? $patient_appointment_date[2] : '';
                $month = !empty($patient_date) ? $patient_date->format('F') : '';
                $year = !empty($patient_appointment_date) ? $patient_appointment_date[0] : '';
                $time = !empty($appointment->appointment_time) ? $appointment->appointment_time : '';
                $list['slots'] = $month . ' ' . $day . ', ' . $year . '-' . $time;
                $list['content'] = !empty($appointment->comments) ? $appointment->comments : '';
                $list['hospital_id'] = !empty($appointment->hospital_id) ? $appointment->hospital_id : '';
                $list['user_id'] = !empty($appointment->user_id) ? $appointment->user_id : '';
                $list['charges'] = !empty($appointment->charges) ? (!empty($symbol['symbol']) ? $symbol['symbol'] : '$') . $appointment->charges : '';
                if (!empty($services)) {
                    foreach ($services as $service_key => $service) {
                        if (!empty($service['service'])) {
                            $speciality = Helper::getSpecialityByID($service['speciality']);
                            $list['all_sp_serv'][$service_key]['speciality'] = !empty($speciality) ? $speciality->title : '';
                            foreach ($service['service'] as $speciality_service_key => $speciality_service) {
                                $service = Helper::getServiceByID($speciality_service);
                                $list['all_sp_serv'][$service_key]['services'][]['title'] = !empty($service) ? $service->title : '';
                            }
                        }
                    }
                }
                return Response::json($list, 200);
            } else {
                $json['type']        = 'error';
                $json['message']    = trans('lang.no_record');
                return Response::json($json, 203);
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.no_record');
            return Response::json($json, 203);
        }
    }

    /**
     * Accept patient appointment
     *
     * @param \Illuminate\Http\Request $request request attributes
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function updateAppointmentStatus(Request $request)
    {
        $json = array();
        $booking_id = !empty($request['booking_id']) ? $request['booking_id'] : '';
        $status = !empty($request['status']) ? $request['status'] : '';
        if (!empty($booking_id) && !empty($status)) {
            $appointment = Appointment::find($booking_id);
            $patient = User::find($appointment['patient_id']);
            $hospital = User::findOrFail($appointment['hospital_id']);
            $doctor = User::findOrFail($appointment['user_id']);
            DB::table('appointments')->where('id', $booking_id)->update(['status' => $status]);
            if (!empty(config('mail.username')) && !empty(config('mail.password'))) {
                if ($status == 'accepted') {
                    $email_params = array();
                    $template = DB::table('email_types')->select('id')->where('email_type', 'user_email_appointment_request_approved')->get()->first();
                    if (!empty($template->id)) {
                        $template_data = EmailTemplate::getEmailTemplateByID($template->id);
                        $email_params['user_name'] = Helper::getUserName($patient->id);
                        $email_params['hospital_name'] = Helper::getUserName($hospital->id);
                        $email_params['hospital_link'] = url('profile/' . $hospital->slug);
                        $email_params['doctor_name'] = Helper::getUserName($doctor->id);
                        $email_params['doctor_link'] = url('profile/' . $doctor->slug);
                        $email_params['appointment_date_time'] = Carbon::parse($appointment['appointment_date'])->format('d M, Y') . ' ' . $appointment['appointment_time'];
                        $email_params['description'] = $appointment['comments'];
                        Mail::to($patient->email)
                            ->send(
                                new GeneralEmailMailable(
                                    'user_email_appointment_request_approved',
                                    $template_data,
                                    $email_params
                                )
                            );
                    } elseif ($status == 'rejected') {
                        $email_params = array();
                        $template = DB::table('email_types')->select('id')->where('email_type', 'user_email_appointment_request_rejected')->get()->first();
                        if (!empty($template->id)) {
                            $template_data = EmailTemplate::getEmailTemplateByID($template->id);
                            $email_params['user_name'] = Helper::getUserName($patient->id);
                            $email_params['doctor_name'] = Helper::getUserName($doctor->id);
                            Mail::to($patient->email)
                                ->send(
                                    new GeneralEmailMailable(
                                        'user_email_appointment_request_rejected',
                                        $template_data,
                                        $email_params
                                    )
                                );
                        }
                    }
                }
            }
            $json['type'] = 'success';
            $json['message'] = trans('lang.appointment_updated');
            $json['status'] = trans($status);
            return Response::json($json, 200);
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something');
            return Response::json($json, 203);
        }
    }

    /**
     * Get appoinetments for specific date
     *
     * @param \Illuminate\Http\Request $request request attributes
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function getPatientAppointments(Request $request)
    {
        $json =  array();
        $list = array();
        $user_id = !empty($_GET['user_id']) ? $_GET['user_id'] : '';
        $appointment_date = !empty($_GET['appointment_date']) ? $_GET['appointment_date'] : '';
        $booking_settings = SiteManagement::getMetaValue('booking_settings');
        $online_payment = !empty($booking_settings['enable_booking']) ? $booking_settings['enable_booking'] : '';
        if (!empty($user_id)) {
            $user = User::find($user_id);
            $counter = 0;
            $orders = DB::table('appointments')->where('patient_id', $user_id);
            if ($online_payment == 'true') {
                $orders = $orders->where('status', 'accepted');
            }
            if (!empty($appointment_date)) {
                $orders = $orders->where('appointment_date', $appointment_date);
            }
            $orders = $orders->get();
            if (!empty($orders)) {
                foreach ($orders as $key => $appointment) {
                    if (!empty($appointment)) {
                        $doctor = !empty($appointment->user_id) ? User::find($appointment->user_id) : '';
                        $services = !empty($appointment->services) ? Helper::getUnserializeData($appointment->services) : '';
                        $list[$counter]['appointments'] = $orders->count();
                        $list[$counter]['user_id'] = !empty($appointment->patient_id) ? $appointment->patient_id : '';
                        $list[$counter]['id'] = $appointment->id;
                        $list[$counter]['status'] = $appointment->status;
                        $list[$counter]['user_name'] = !empty($doctor) && !empty($appointment->user_id)
                            ? Helper::getUserName($appointment->user_id) : '';
                        $list[$counter]['patient_name'] = !empty($appointment->patient_name)
                            ? $appointment->patient_name : '';
                        $list[$counter]['relation'] = !empty($appointment->relation)
                            ? $appointment->relation : '';
                        $list[$counter]['user_image'] = !empty($doctor)
                            ? asset(Helper::getImage('uploads/users/' . $doctor->id, $doctor->profile->avatar, 'small-', 'user.jpg'))
                            : '';
                        $list[$counter]['user_verify'] = !empty($doctor) ? $doctor->user_verified : 0;
                        $list[$counter]['user_location'] = !empty($doctor->location) && $doctor->location->count() > 0 ? $doctor->location->title : '';
                        $list[$counter]['user_type'] = !empty($doctor) ? $doctor->getRoleNames()->first() : '';
                        $list[$counter]['hospital'] = !empty($appointment->hospital_id) ? Helper::getUserName($appointment->hospital_id) : '';
                        $date = $appointment->appointment_date;
                        $patient_date = new Carbon($appointment->appointment_date);
                        $patient_appointment_date = explode("-", $date);
                        $list[$counter]['day'] = !empty($patient_appointment_date) ? $patient_appointment_date[2] : '';
                        $list[$counter]['month'] = !empty($patient_date) ? $patient_date->format('M') : '';
                        $list[$counter]['appointment_date'] = !empty($appointment->appointment_date) ? $appointment->appointment_date : '';
                        $list[$counter]['appointment_time'] = !empty($appointment->appointment_time) ? $appointment->appointment_time : '';
                    }
                    $counter++;
                }
                return Response::json($list, 200);
            } else {
                $json['type'] = 'error';
                $json['message'] = trans('lang.something');
                return Response::json($json, 203);
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something');
            return Response::json($json, 203);
        }
    }

    /**
     * Get appoinetments for specific date
     *
     * @param \Illuminate\Http\Request $request request attributes
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function getPatientAppointmentSingle(Request $request)
    {
        $json =  array();
        $list = array();
        $user_id = !empty($_GET['user_id']) ? $_GET['user_id'] : '';
        $booking_id = !empty($_GET['booking_id']) ? $_GET['booking_id'] : '';
        if (!empty($booking_id)) {
            $appointment = Appointment::find($booking_id);
            if (!empty($appointment)) {
                $doctor = !empty($appointment->user_id) ? User::find($appointment->user_id) : '';
                $services = !empty($appointment->services) ? Helper::getUnserializeData($appointment->services) : '';
                $list['user_id'] = !empty($appointment->patient_id) ? $appointment->patient_id : '';
                $list['id'] = $appointment->id;
                $list['status'] = $appointment->status;
                $list['user_name'] = !empty($doctor) && !empty($appointment->user_id)
                    ? Helper::getUserName($appointment->user_id) : '';
                $list['patient_name'] = !empty($appointment->patient_name)
                    ? $appointment->patient_name : '';
                $list['relation'] = !empty($appointment->relation)
                    ? $appointment->relation : '';
                $list['user_image'] = !empty($doctor)
                    ? asset(Helper::getImage('uploads/users/' . $doctor->id, $doctor->profile->avatar, 'small-', 'user.jpg'))
                    : '';
                $list['user_verify'] = !empty($doctor) ? $doctor->user_verified : 0;
                $list['user_location'] = !empty($doctor->location) && $doctor->location->count() > 0 ? $doctor->location->title : '';
                $list['user_type'] = !empty($doctor) ? $doctor->getRoleNames()->first() : '';
                $list['hospital'] = !empty($appointment->hospital_id) ? Helper::getUserName($appointment->hospital_id) : '';
                $date = $appointment->appointment_date;
                $patient_date = new Carbon($appointment->appointment_date);
                $patient_appointment_date = explode("-", $date);
                $day = !empty($patient_appointment_date) ? $patient_appointment_date[2] : '';
                $month = !empty($patient_date) ? $patient_date->format('F') : '';
                $year = !empty($patient_appointment_date) ? $patient_appointment_date[0] : '';
                $time = !empty($appointment->appointment_time) ? $appointment->appointment_time : '';
                $list['slots'] = $month . ' ' . $day . ', ' . $year . '-' . $time;
                $list['content'] = !empty($appointment->comments) ? $appointment->comments : '';
                if (!empty($services)) {
                    foreach ($services as $service_key => $service) {
                        if (!empty($service['service'])) {
                            $speciality = Helper::getSpecialityByID($service['speciality']);
                            $list['appointment_services'][$service_key]['speciality'] = !empty($speciality) ? $speciality->title : '';
                            foreach ($service['service'] as $speciality_service_key => $speciality_service) {
                                $service = Helper::getServiceByID($speciality_service);
                                if (!empty($service)) {
                                    $list['appointment_services'][$service_key]['services'][]['title'] = !empty($service) ? $service->title : '';
                                }
                            }
                        }
                    }
                }
            }
            return Response::json($list, 200);
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something');
            return Response::json($json, 203);
        }
    }

    /**
     * Get forum settings data
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function getForumSettings()
    {
        $json = array();
        $forum_settings  = SiteManagement::getMetaValue('forum_settings');
        $forum_banner_image = !empty($forum_settings) && !empty($forum_settings['hidden_forum_banner_image']) ? $forum_settings['hidden_forum_banner_image'] : '';
        $forum_banner_title = !empty($forum_settings) && !empty($forum_settings['forum_banner_title']) ? $forum_settings['forum_banner_title'] : '';
        $forum_banner_subtitle = !empty($forum_settings) && !empty($forum_settings['forum_banner_subtitle']) ? $forum_settings['forum_banner_subtitle'] : '';
        $forum_banner_desc = !empty($forum_settings) && !empty($forum_settings['forum_banner_desc']) ? $forum_settings['forum_banner_desc'] : '';
        if (!empty($forum_settings)) {
            $forum_settings = array();
            $forum_settings['hf_title'] = $forum_banner_title;
            $forum_settings['hf_sub_title'] = $forum_banner_subtitle;
            $forum_settings['hf_description'] = $forum_banner_desc;
            $forum_settings['hf_btn_text'] = '';
            $forum_settings['hf_image'] = asset('uploads/settings/general/' . $forum_banner_image);
            return Response::json($forum_settings, 200);
        } else {
            $json['type']        = 'error';
            $json['message']    = trans('lang.no_record');
            return Response::json($json, 203);
        }
    }

    /**
     * Get Forum list
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function getForumListing()
    {
        $json = array();
        $list = array();
        $page_number = !empty($_GET['page_number']) ? $_GET['page_number'] : 1;
        $post_per_page = !empty($_GET['show_users']) ? $_GET['show_users'] : 10;
        $sort_by = !empty($_GET['orderby']) ? $_GET['orderby'] : '';
        $keyword = !empty($_GET['search']) ? $_GET['search'] : '';
        $specialty = !empty($_GET['specialities']) ? $_GET['specialities'] : '';
        Paginator::currentPageResolver(
            function () use ($page_number) {
                return $page_number;
            }
        );
        $forum = new Forum();
        if (!empty($keyword)) {
            $forum = $forum->where('question_title', 'like', '%' . $keyword . '%');
        }
        if (!empty($specialty)) {
            $forum = $forum->where('speciality_id', $specialty);
        }
        if (!empty($sort_by)) {
            if ($sort_by == 'name') {
                $forum = $forum->orderBy('question_title', 'asc');
            } elseif ($sort_by == 'date') {
                $forum = $forum->orderBy('created_at', 'asc');
            } else {
                $forum = $forum->orderBy('id', 'asc');
            }
        }
        $forum = $forum->paginate($post_per_page);
        if (!empty($forum) && $forum->count() > 0) {
            foreach ($forum as $key => $question) {
                $speciality = Speciality::find($question->speciality_id);
                $speciality_image = !empty($speciality) && !empty($speciality->image) ? $speciality->image : '';
                $forum = Forum::findOrFail($question->id);
                $list[$key]['image'] = asset(Helper::getImage('uploads/specialities', $speciality_image, 'extra-small-', 'default-speciality.png'));
                $list[$key]['title'] = !empty($question->question_title) ? $question->question_title : '';
                $list[$key]['content'] = !empty($question->question_description) ? html_entity_decode(clean($question->question_description)) : '';
                $list[$key]['ID'] = !empty($question->id) ? $question->id : '';
                $list[$key]['post_date'] = Carbon::parse($question->created_at)->format('M d, Y');
                $list[$key]['answers'] = $forum->answers->count();
                $list[$key]['count'] = Forum::count();
            }
            return Response::json($list, 200);
        } else {
            $json['type']        = 'error';
            $json['message']    = trans('lang.no_record');
            return Response::json($json, 203);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param string $slug slug
     * 
     * @return view
     */
    public function getForumAnswers()
    {
        $json = array();
        $data = array();
        $user_id = !empty($_GET['user_id']) ? $_GET['user_id'] : '';
        $id = !empty($_GET['id']) ? $_GET['id'] : '';
        if (!empty($id)) {
            $auth_user = User::find($user_id);
            $forum = Forum::findOrFail($id);
            $user = $forum->questioner->count() > 0 ? User::findOrFail($forum->questioner->first()->id) : '';
            $liked_answers = !empty($auth_user->profile->liked_answers) ? unserialize($auth_user->profile->liked_answers) : array();
            if ($forum->answers->count() > 0 && !empty($forum->answers)) {
                foreach ($forum->answers as $key => $answer) {
                    $user = User::find($answer->pivot->user_id);
                    $data[$key]['user_image'] = asset(Helper::getImage('uploads/users/'.$user->id, $user->profile->avatar, 'small-', 'user.jpg'));
                    $data[$key]['user_name'] = Helper::getUserName($user->id);
                    $data[$key]['gender_title'] = !empty($user->profile->gender_title) ? $user->profile->gender_title : '';
                    $data[$key]['verifyUser'] = $user->user_verified;
                    if (in_array($answer->pivot->id, $liked_answers)) {
                        $data[$key]['liked'] = 'yes';
                    } else {
                        $data[$key]['liked'] = 'no';
                    }
                    $data[$key]['answer'] = $answer->pivot->answer;
                }   
                return Response::json($data, 200);
            } else {
                $json['type']        = 'error';
                $json['message']    = trans('lang.no_record');
                return Response::json($json, 203);
            }
        } else {
            $json['type']        = 'error';
            $json['message']    = trans('lang.no_record');
            return Response::json($json, 203);
        }
    }

    /**
     * Get Forum list
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function getSpecilities()
    {
        $json = array();
        $list = array();
        $specialities = Speciality::all();
        if (!empty($specialities) && $specialities->count() > 0) {
            foreach ($specialities as $key => $speciality) {
                $list[$key]['id']  = $speciality->id;
                $list[$key]['name']  = $speciality->title;
                $list[$key]['slug']  = $speciality->slug;
                $list[$key]['url']  = asset(Helper::getImage('uploads/specialities', $speciality->image, '', 'default-speciality.png'));
                $random_color = array(
                    'f44336', 'E91E63', '9C27B0', '673AB7', '3F51B5', '2196F3', '03A9F4', '00BCD4', '009688', '4CAF50',
                    '8BC34A', 'AFB42B', 'FBC02D', 'FFB300', 'FB8C00', 'F4511E', '795548', '757575', '607D8B', 'ff5252',
                    'D500F9', '448AFF', '00B8D4', '00C853', 'FFAB00'
                );
                // $rand_color = '#' . dechex(mt_rand(0, 16777215));
                $color_key = array_rand($random_color, 1);
                $list[$key]['color']  = '#' . $random_color[$color_key];
            }
            return Response::json($list, 200);
        } else {
            $json['type']        = 'error';
            $json['message']    = trans('lang.no_record');
            return Response::json($json, 203);
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $request returns request
     *
     * @return \App\User
     */
    public function removeAccount(Request $request)
    {
        if (!empty($request['user_id'])) {
            $this->validate(
                $request,
                [
                    'old_password' => 'required',
                    'retype_password'    => 'required',
                ]
            );
            $json = array();
            $user_id = $request['user_id'];
            $user = User::find($user_id);
            if (Hash::check($request->old_password, $user->password)) {
                if (!empty($user_id)) {
                    $user->profile()->delete();
                    $user->roles()->detach();
                    DB::table('appointments')->where('user_id', $user_id)
                        ->orWhere('hospital_id', $user_id)->orWhere('patient_id', $user_id)->delete();
                    if ($user->articles->count() > 0) {
                        foreach ($user->articles as $user_article) {
                            $article = Article::find($user_article->id);
                            if ($article->categories->count() > 0) {
                                $article->categories()->detach();
                            }
                        }
                        $user->articles()->delete();
                    }
                    DB::table('feedbacks')->where('user_id', $user_id)
                        ->orWhere('patient_id', $user_id)->delete();
                    DB::table('messages')->where('user_id', $user_id)
                        ->orWhere('receiver_id', $user_id)->delete();
                    if ($user->orders->count() > 0) {
                        foreach ($user->orders as $user_orders) {
                            DB::table('order_metas')->where('metable_id', $user_orders->id)->delete();
                        }
                        $user->orders()->delete();
                    }
                    DB::table('payouts')->where('user_id', $user_id)->delete();
                    DB::table('teams')->where('user_id', $user_id)
                        ->orWhere('doctor_id', $user_id)->delete();
                    $user->services()->detach();
                    if ($user->question->count() > 0) {
                        foreach ($user->question as $form) {
                            DB::table('forums')->where('id', $form->id)->delete();
                            DB::table('user_forum')->where('forum_id', $form->id)->delete();
                        }
                    }
                    DB::table('user_forum')->where('user_id', $user_id)->where('type', 'answer')->delete();
                    $user->delete();
                    if (!empty(config('mail.username')) && !empty(config('mail.password'))) {
                        $delete_reason = Helper::getDeleteAccReason($request['delete_reason']);
                        $email_params = array();
                        $template = DB::table('email_types')->select('id')->where('email_type', 'admin_email_delete_account')->get()->first();
                        if (!empty($template->id)) {
                            $template_data = EmailTemplate::getEmailTemplateByID($template->id);
                            $email_params['reason'] = $delete_reason;
                            Mail::to(config('mail.username'))
                                ->send(
                                    new AdminEmailMailable(
                                        'admin_email_delete_account',
                                        $template_data,
                                        $email_params
                                    )
                                );
                        }
                    }
                    Auth::logout();
                    $json['acc_del'] = trans('lang.acc_deleted');
                    return Response::json($json, 200);
                } else {
                    $json['type'] = 'warning';
                    $json['msg'] = trans('lang.something_wrong');
                    return Response::json($json, 203);
                }
            } else {
                $json['type'] = 'warning';
                $json['msg'] = trans('lang.pass_mismatched');
                return Response::json($json, 203);
            }
        } else {
            $json['type']        = 'error';
            $json['message']    = trans('lang.no_record');
            return Response::json($json, 203);
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $request returns request
     *
     * @return \App\User
     */
    public function submitQuestion(Request $request)
    {
        $json = array();
        if (!empty($request['user_id'])) {
            $server = Helper::doctieIsDemoSiteAjax();
            if (!empty($server)) {
                $response['type'] = 'error';
                $response['message'] = $server->getData()->message;
                return $response;
            }
            if (empty($request['speciality'])) {
                $response['type'] = 'error';
                $response['message'] = trans('lang.speciality_required');
                return Response::json($json, 203);
            }
            if (empty($request['question_title'])) {
                $response['type'] = 'error';
                $response['message'] = trans('lang.title_required');
                return Response::json($json, 203);
            }
            if (empty($request['question_desc'])) {
                $response['type'] = 'error';
                $response['message'] = trans('lang.desc_required');
                return Response::json($json, 203);
            }
            $form = new Forum();
            $post_quation = $form->postAPIQuestion($request, $request['user_id']);
            if ($post_quation == 'success') {
                $json['type'] = 'success';
                $json['message'] = trans('lang.quest_post_success');
                return Response::json($json, 200);
            } else {
                $json['type']   = 'error';
                $json['message']    = trans('lang.something_went_wrong');
                return Response::json($json, 203);
            }
        } else {
            $json['type']        = 'error';
            $json['message']    = trans('lang.need_to_reg');
            return Response::json($json, 203);
        }
    }

    /**
     * Post Answer.
     *
     * @param \Illuminate\Http\Request $request request attributes
     *
     * @return \Illuminate\Http\Response
     */
    public function postAnswer(Request $request)
    {
        $server = Helper::doctieIsDemoSiteAjax();
        if (!empty($server)) {
            $response['type'] = 'error';
            $response['message'] = $server->getData()->message;
            return $response;
        }
        if (!empty($request['user_id'])) {
            if (empty($request['forum_answer'])) {
                $response['type'] = 'error';
                $response['message'] = trans('lang.empty_field');
                return Response::json($json, 203);
            }
            if (empty($request['forum_id'])) {
                $response['type'] = 'error';
                $response['message'] = trans('lang.empty_field');
                return Response::json($json, 203);
            }
            $form = new Forum();
            $post_answer = $form->postAPIQuestion($request);
            if ($post_answer === 'success') {
                $json['type'] = 'success';
                $json['message'] = trans('lang.answer_post_success');
                return Response::json($json, 200);
            } else {
                $response['type'] = 'error';
                $response['message'] = trans('lang.something_went_wrong');
                return Response::json($json, 203);
            }
        } else {
            $response['type'] = 'error';
            $response['message'] = trans('lang.need_to_reg');
            return Response::json($json, 203);
        }
    }

    /**
     * Store data in storage
     *
     * @param \Illuminate\Http\Request $request request attributes
     * 
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function bookAppointment(Request $request)
    {
        $json = array();
        $appointment_data = array();
        if ($request['patient'] == 'someone') {
            if (empty($request['patient_name'])) {
                $json['type']        = 'error';
                $json['message']    = trans('lang.patient_name_req');
                return Response::json($json, 203);
            }
            if (empty($request['relation'])) {
                $json['type']        = 'error';
                $json['message']    = trans('lang.select_relation_req');
                return Response::json($json, 203);
            }
        }
        if (empty($request['hospital'])) {
            $json['type']        = 'error';
            $json['message']    = trans('lang.hospital_req');
            return Response::json($json, 203);
        }
        if (empty($request['time'])) {
            $json['type']        = 'error';
            $json['message']    = trans('lang.select_appointment_time');
            return Response::json($json, 203);
        }
        if (empty($request['doctor_id'])) {
            $json['type']        = 'error';
            $json['message']    = trans('lang.doctor_id_req');
            return Response::json($json, 203);
        }
        if (empty($request['patient_id'])) {
            $json['type']        = 'error';
            $json['message']    = trans('lang.patient_id_req');
            return Response::json($json, 203);
        }
        $appointment_speciality = array();
        $specialities = !empty($request['speciality']) ? $request['speciality'] : '';
        if (!empty($specialities)) {
            foreach ($specialities as $key => $speciality) {
                $appointment_speciality[$key]['speciality'] = $speciality['speciality'];
                if (!empty($speciality['service'])) {
                    foreach ($speciality['service'] as $service_key => $services) {
                        $appointment_speciality[$key]['service'][$service_key] = $services;
                    }
                }
            }
        }
        $appointment_data['patient'] = !empty($request['patient']) ? $request['patient'] : '';
        $appointment_data['patient_name'] = !empty($request['patient_name']) ? $request['patient_name'] : '';
        $appointment_data['relation'] = !empty($request['relation']) ? $request['relation'] : '';
        $appointment_data['hospital'] = !empty($request['hospital']) ? $request['hospital'] : '';
        $appointment_data['speciality'] = $appointment_speciality;
        $appointment_data['total_charges'] = !empty($request['total_charges']) ? $request['total_charges'] : '';
        $appointment_data['comments'] = !empty($request['comments']) ? $request['comments'] : '';
        $appointment_data['day'] = !empty($request['day']) ? $request['day'] : '';
        $appointment_data['date'] = !empty($request['date']) ? $request['date'] : '';
        $appointment_data['time'] = !empty($request['time']) ? $request['time'] : '';
        $appointment_data['doctor_id'] = !empty($request['doctor_id']) ? $request['doctor_id'] : '';
        $appointment_data['patient_id'] = !empty($request['patient_id']) ? $request['patient_id'] : '';
        $appointment = new Appointment();
        $patient_appointment = $appointment->submitAppointmentAPI($appointment_data);
        if ($patient_appointment['type'] == 'success') {
            $json['appointment_id'] = $patient_appointment['last_id'];
            $json['type'] = 'success';
            return Response::json($json, 200);
        } else {
            $json['type']        = 'error';
            $json['message']    = trans('lang.something_went_wrong');
            return Response::json($json, 203);
        }
    }

    /**
     * Verify Password
     *
     * @param \Illuminate\Http\Request $request request attributes
     * 
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function verifyAppointmentPassword(Request $request)
    {
        $server = Helper::doctieIsDemoSiteAjax();
        if (!empty($server)) {
            $response['type'] = 'error';
            $response['message'] = $server->getData()->message;
            return $response;
        }
        $json = array();
        $user = User::find($request['user_id']);
        if (Hash::check($request->password, $user->password)) {
            $random_number = Helper::generateRandomCode(4);
            $verification_code = strtoupper($random_number);
            $user->verification_code = $verification_code;
            $user->save();
            if (!empty(config('mail.username')) && !empty(config('mail.password'))) {
                $email_params = array();
                $template = DB::table('email_types')->select('id')
                    ->where('email_type', 'user_email_appointment_booking_verification_code')->get()->first();
                if (!empty($template->id)) {
                    $template_data = EmailTemplate::getEmailTemplateByID($template->id);
                    $email_params['verification_code'] = $user->verification_code;
                    $email_params['name']  = Helper::getUserName($user->id);
                    Mail::to($user->email)
                        ->send(
                            new GeneralEmailMailable(
                                'user_email_appointment_booking_verification_code',
                                $template_data,
                                $email_params
                            )
                        );
                }
            }
            $json['type'] = 'success';
            return Response::json($json, 200);
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.pass_mismatched');
            return Response::json($json, 203);
        }
    }

    /**
     * Verify Code
     *
     * @param mixed $request $request->attr
     *
     * @return \Illuminate\Http\Response
     */
    public function verifyAppointmentCode(Request $request)
    {
        $user = User::findOrFail($request['user_id']);
        if (!empty($user) && !empty($request['code'])) {
            if ($request['code'] === $user->verification_code) {
                $user->verification_code = null;
                $user->save();
                $json['type'] = 'success';
               return Response::json($json, 200);
            } else {
                $json['type'] = 'error';
                $json['message'] = trans('lang.verify_code_mismatched');
                return Response::json($json, 203);
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.verify_code');
            return Response::json($json, 203);
        }
    }

    /**
     * Get doctor locations
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function getAppointmentHospitals()
    {
        $json = array();
        $doctor_id = !empty($_GET['user_id']) ? $_GET['user_id'] : '';
        if (!empty($doctor_id)) {
            $doctor_hospitals = Team::getDoctorHospitals($doctor_id);
            if (!empty($doctor_hospitals)) {
                foreach ($doctor_hospitals as $key => $hospital) {
                    $json[$key]['id'] = $hospital['id'];
                    $json[$key]['name'] = $hospital['name'];
                }
                return Response::json($json, 200);
            } else {
                $json['type']        = 'error';
                $json['message']    = trans('lang.no_record');
                return Response::json($json, 203);
            }
        } else {
            $json['type']        = 'error';
            $json['message']    = trans('lang.doctor_id_req');
            return Response::json($json, 203);
        }
    }

    /**
     * Get doctor hospital services
     *
     * @param \Illuminate\Http\Request $request request attributes
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function getHospitalServices(Request $request)
    {
        $json =  array();
        $hospital_id = !empty($_GET['user_id']) ? $_GET['user_id'] : '';
        if (!empty($hospital_id)) {
            $team_info = Team::where('user_id', $hospital_id)->first();
            if (!empty($team_info)) {
                $slots = Helper::getUnserializeData($team_info['slots']);
                $list = array();
                if (!empty($slots['services'])) {
                    $count = 0;
                    foreach ($slots['services']['speciality'] as $key => $specialities) {
                        if (!empty($specialities['speciality_services'])) {
                            if ($key == 0) {
                                foreach ($slots['days'] as $slot_key => $day) {
                                    $list[$count]['fee'] = !empty($slots[$day]['consultation_fee']) 
                                                    ? $slots[$day]['consultation_fee'] 
                                                    : '0';
                                } 
                            }
                            $speciality = Speciality::find($specialities['speciality_id']);
                            if (!empty($speciality)) {
                                $counter = 0;
                                $list[$count]['speciality_id'] = $speciality->id;
                                $list[$count]['speciality_title'] = $speciality->title;
                                $list[$count]['speciality_image'] = asset(Helper::getImage('uploads/specialities', $speciality->image, '', 'default-speciality.png'));
                                foreach ($specialities['speciality_services'] as $service_key => $services) {
                                    $service = Helper::getServiceByID($services['service']);
                                    if (!empty($service)) {
                                        $list[$count]['services'][$counter]['service_id'] = $service->id;
                                        $list[$count]['services'][$counter]['service_title'] = $service->title;
                                        $list[$count]['services'][$counter]['service_price'] = $services['price'];
                                    }
                                    $counter++;
                                } 
                            }
                            $count++;
                        }
                    }
                    return Response::json($list, 200);
                } else {
                    $json['type'] = 'error';
                    $json['message'] = trans('lang.service_not_found');
                    return Response::json($json, 203);
                }
            } else {
                $json['type'] = 'error';
                $json['message'] = trans('lang.something');
                return Response::json($json, 203);
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.hospital_req');
            return Response::json($json, 203);
        }
    }

    /**
     * Get doctor's appointment slots
     *
     * @param \Illuminate\Http\Request $request request attributes
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function getAppointmentSlots(Request $request)
    {
        $json = array();
        $list = array();
        $hospital_id = !empty($_GET['hospital_id']) ? $_GET['hospital_id'] : '';
        $doctor_id = !empty($_GET['doctor_id']) ? $_GET['doctor_id'] : '';
        $appointment_date = !empty($_GET['date']) ? $_GET['date'] : '';
        $appointment_day = !empty($_GET['day']) ? $_GET['day'] : '';
        if (empty($_GET['doctor_id'])) {
            $json['type']        = 'error';
            $json['message']    = trans('lang.doctor_id_req');
            return Response::json($json, 203);
        }
        if (empty($_GET['hospital_id'])) {
            $json['type']        = 'error';
            $json['message']    = trans('lang.hospital_req');
            return Response::json($json, 203);
        }
        if (empty($_GET['date'])) {
            $json['type']        = 'error';
            $json['message']    = trans('lang.doctor_id_req');
            return Response::json($json, 203);
        }
        if (empty($_GET['day'])) {
            $json['type']        = 'error';
            $json['message']    = trans('lang.hospital_req');
            return Response::json($json, 203);
        }
        if (!empty($doctor_id) && !empty($hospital_id)) {
            $hospital = Team::select('slots')->where('doctor_id', $doctor_id)->where('user_id', $hospital_id)->first();
            if (!empty($hospital)) {
                $requested_date = Carbon::create($appointment_date);
                $date = new Carbon();
                $today = $date->now();
                $slots = Helper::getUnserializeData($hospital->slots);
                $requested_day_slots = !empty($slots[$appointment_day]) ? $slots[$appointment_day]['slots'] : array();
                if (!empty($requested_day_slots)) {
                    $counter = 0;
                    foreach ($requested_day_slots as $key => $slot) {
                        $time = explode('-', $key);
                        $list[$counter]['start_time'] = $time[0];
                        $bocked_appointments = DB::table('appointments')->where('appointment_time', $time[0])->count();
                        if ($requested_date->lessThan($today)) {
                            $list[$counter]['space'] = 0;
                        } else if ($bocked_appointments > 0) {
                            $list[$counter]['space'] = $slot['space'] - $bocked_appointments;
                        } else {
                            $list[$counter]['space'] = (int) ($slot['space']);
                        }
                        $counter++;
                    }
                }
                return Response::json($list, 200);
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something');
            return Response::json($json, 203);
        }
    }

    /**
     * Stote appointment location data in storage
     *
     * @param mixed $request get req attributes
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function storeAppointmentLocation(Request $request)
    {
        $server = Helper::doctieIsDemoSiteAjax();
        if (!empty($server)) {
            $response['type'] = 'error';
            $response['message'] = $server->getData()->message;
            return Response::json($response, 203);
        }
        if (!empty($_GET['user_id'])) {
            $hospital_location = Team::where('user_id', $_GET['hospital_id'])->where('doctor_id', $_GET['user_id'])->count();
            if ($hospital_location > 0) {
                $response['type'] = 'error';
                $response['message'] = trans('lang.hospital_already_selected');
                return Response::json($response, 203);
            }
            $location  = new Team();
            $team = $location->saveAppointmentLocation($_GET, $_GET['user_id']);
            if ($team == "success") {
                //send email to hospital
                if (!empty(config('mail.username')) && !empty(config('mail.password'))) {
                    $hospital = User::findOrFail($_GET['hospital_id']);
                    $slots = $_GET['slots'];
                    $days = $slots['appointment_days'];
                    $email_params = array();
                    $email_params['starttime'] = $slots['start_time'];
                    $email_params['endtime'] =  $slots['end_time'];
                    $email_params['appt_intervals'] = $slots['intervals'];
                    $email_params['appt_duration'] = $slots['duration'];
                    $email_params['appt_days'] = implode(',', $days);
                    $template_data = Helper::getEmailContent();
                    Mail::to($hospital->email)
                        ->send(
                            new HospitalEmailMailable(
                                'hospital_appointment_locations_added',
                                $template_data,
                                $email_params
                            )
                        );
                    Mail::to(config('mail.username'))
                        ->send(
                            new HospitalEmailMailable(
                                'hospital_appointment_locations_added',
                                $template_data,
                                $email_params
                            )
                        );
                    $req_template = DB::table('email_types')->select('id')
                        ->where('email_type', 'hospital_email_doctor_request_to_hospital')->get()->first();
                    if (!empty($req_template->id)) {
                        $doctor = User::find($_GET['user_id']);
                        $template_data = EmailTemplate::getEmailTemplateByID($req_template->id);
                        $email_params['doctor_name'] = Helper::getUserName($doctor->id);
                        $email_params['hospital_name']  = Helper::getUserName($hospital->id);
                        $email_params['doctor_link']  = url('profile/' . $doctor->slug);
                        Mail::to($hospital->email)
                            ->send(
                                new HospitalEmailMailable(
                                    'hospital_email_doctor_request_to_hospital',
                                    $template_data,
                                    $email_params
                                )
                            );
                    }
                }
                $json['type'] = 'success';
                $json['progressing'] = trans('lang.saving');
                $json['message'] = trans('lang.apt_location_saved');
                return Response::json($json, 200);
            } else {
                $json['type'] = 'error';
                return Response::json($json, 203);
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return Response::json($json, 203);
        }
    }

    /**
     * Get doctor locations
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function getAppointmentLocation()
    {
        $json = array();
        $user_id = !empty($_GET['doctor_id']) ? $_GET['doctor_id'] : '';
        if (!empty($user_id)) {
            $locations = Team::where('doctor_id', $user_id)->paginate(4);
            if (!empty($locations) && $locations->count() > 0) {
                foreach ($locations as $key => $location) {
                    $team = Team::findOrFail($location->id);
                    $slots = unserialize($team->slots);
                    $appointment_days = !empty($slots['days']) ? $slots['days'] : array();
                    $json[$key]['image'] = asset(Helper::getImage('uploads/users/' . $team->hospital->id, $team->hospital->profile->avatar, 'medium-', 'user.jpg'));
                    $json[$key]['status'] = $team->status;
                    $json[$key]['user_name'] = Helper::getUserName($team->hospital->id);
                    $json[$key]['verify'] = $team->hospital->user_verified;
                    $json[$key]['location_id'] = $team->id;
                    if (!empty($appointment_days)) {
                        foreach (Helper::getAppointmentDays() as $day_key => $day) {
                            if (in_array($day_key, $appointment_days)) {
                                $json[$key]['day'][]['title'] = html_entity_decode(clean($day['title']));
                            }
                        }
                    }
                }
                return Response::json($json, 200);
            } else {
                $json['type']        = 'error';
                $json['message']    = trans('lang.no_record');
                return Response::json($json, 203);
            }
        } else {
            $json['type']        = 'error';
            $json['message']    = trans('lang.doctor_id_req');
            return Response::json($json, 203);
        }
    }

    /**
     * Edit doctor appoinement location.
     *
     * @param string $id id
     *
     * @access public
     *
     * @return View
     */
    public function getLocationDetail()
    {
        $json = array();
        $id = !empty($_GET['id']) ? $_GET['id'] : '';
        $day_counter = 0;
        $counter = 0;
        if (!empty($id)) {
            $team = Team::find($id);
            if (!empty($team)) {
                $slots = unserialize($team->slots);
                $days = Helper::getAppointmentDays();
                foreach ($days as $key => $value) {
                    $json['days'][$counter]['title'] = $value['name'];
                    $counter++;
                }
                foreach ($slots['days'] as $key => $value) {
                    $json['selected_day'][$key]['title'] = $value;
                }
                $json['image'] = asset(Helper::getImage('uploads/users/' . $team->hospital->id, $team->hospital->profile->avatar, 'small-', 'user.jpg'));
                $json['status'] = $team->status;
                $json['user_name'] = Helper::getUserName($team->hospital->id);
                $json['verify'] = $team->hospital->user_verified;
                foreach ($days as $key => $day) {
                    if (!empty($slots[$key]['slots'])) {
                        // $selected_day = Helper::getAppointmentDays($key);
                        // $json['selected_day'][$day_counter]['title'] = html_entity_decode(clean($selected_day['name']));
                        if (!empty($slots[$key]['slots'])) {
                            $count = 0;
                            foreach ($slots[$key]['slots'] as $slot_key => $slot) {
                                $start_slot = explode("-", $slot_key);
                                $slot_id = $key . '-slot' . '-' . str_replace(array(':', ' '), '', $start_slot[0]);
                                $json[$key]['slots'][$count]['start_time'] = $start_slot[0];
                                $json[$key]['slots'][$count]['end_time'] = $start_slot[1];
                                $json[$key]['slots'][$count]['spaces'] = $slot['space'];
                                $json[$key]['slots'][$count]['id'] = $slot_id;
                                $count++;
                            }
                        }
                    } else {
                        $json[$key]['slots'] = [];
                    }
                    $day_counter++;
                }

                return Response::json($json, 200);
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return Response::json($json, 203);
        }
    }

    /**
     * Edit doctor appoinement location.
     *
     * @param string $id id
     *
     * @access public
     *
     * @return View
     */
    public function getLocationServices()
    {
        $json = array();
        $id = !empty($_GET['id']) ? $_GET['id'] : '';
        $user_id = !empty($_GET['user_id']) ? $_GET['user_id'] : '';
        if (!empty($user_id) && !empty($id)) {
            $user = User::find($user_id);
            $location = Team::find($id);
            $slots = unserialize($location->slots);
            $doctor_specialities = !empty($user->profile->services) ? Helper::getUnserializeData($user->profile->services) : array();
            $service_price = !empty($slots['services']['price']) ? $slots['services']['price'] : '';
            if (!empty($doctor_specialities)) {
                foreach ($doctor_specialities as $key => $doctor_speciality) {
                    $speciality = Helper::getSpecialityByID($doctor_speciality['speciality_id']);
                    if (!empty($speciality)) {
                        $json[$key]['id'] = $speciality->id;
                        $json[$key]['title'] = $speciality->title;
                        $json[$key]['total_services'] = $speciality->services()->count();
                        $json[$key]['icon'] = asset(Helper::getImage('uploads/specialities', $speciality->image, '', 'default-speciality.png'));
                        if (!empty($doctor_speciality['services'])) {
                            foreach ($doctor_speciality['services'] as $service_key => $services) {
                                $service = Helper::getServiceByID($services['service']);
                                $checked = !empty($slots['services']['speciality'][$key]['speciality_services'][$service_key]['service']) && $service->id == $slots['services']['speciality'][$key]['speciality_services'][$service_key]['service'] ? true : false;
                                $json[$key]['services'][$service_key]['id'] = $service->id;
                                $json[$key]['services'][$service_key]['title'] = $service->title;
                                $json[$key]['services'][$service_key]['price'] = $service->price;
                                $json[$key]['services'][$service_key]['selected'] = $checked;
                            }
                        }
                    }
                }
                return Response::json($json, 200);
            } else {
                $json['type'] = 'error';
                $json['message'] = trans('lang.no_record');
                return Response::json($json, 203);
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return Response::json($json, 203);
        }
    }

    /**
     * Delete appointment location time slot
     *
     * @param string $day day
     * @param int    $id  id
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteAllSlots($day, $id)
    {
        $day = !empty($_GET['day']) ? $_GET['day'] : '';
        $id = !empty($_GET['id']) ? $_GET['id'] : '';
        if (Auth::user()) {
            if (!empty($day) && !empty($id)) {
                $team  = new Team();
                $team_location = $team->deleteAllAppointmentSlots($day, $id);
                if ($team_location == 'success') {
                    $json['type'] = 'success';
                    $json['message'] = trans('lang.slot_deleted');
                    return Response::json($json, 200);
                }
            } else {
                $json['type'] = 'error';
                $json['message'] = trans('lang.param_missing');
                return Response::json($json, 203);
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something');
            return Response::json($json, 203);
        }
    }

    /**
     * Delete appointment location selected time slot
     *
     * @param string $slot slot
     * @param string $day  day
     * @param int    $id   id
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteSlot()
    {
        $day = !empty($_GET['day']) ? $_GET['day'] : '';
        $slot = !empty($_GET['slot']) ? $_GET['slot'] : '';
        $id = !empty($_GET['id']) ? $_GET['id'] : '';
        if (Auth::user()) {
            $team  = new Team();
            $team_location = $team->deleteAppointmentSlots($slot, $day, $id);
            if ($team_location == 'success') {
                $json['type'] = 'success';
                $json['message'] = trans('lang.slot_deleted');
                return Response::json($json, 200);
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something');
            return Response::json($json, 203);
        }
    }

    /**
     * Update doctor appointment slots
     *
     * @param string $id      id
     * @param mixed  $request get req attributes
     * 
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function updateSlots()
    {
        $user_id = !empty($request['user_id']) ? $request['user_id'] : '';
        if (!empty($user_id)) {
            $location  = new Team();
            $update_slots = $location->updateAppointmentSlots($request['id'], $request);
            if ($update_slots == 'success') {
                $json['type'] = 'success';
                $json['message'] = trans('lang.slot_updated');
                return Response::json($json, 200);
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something');
            return Response::json($json, 203);
        }
    }

    /**
     * Store appointment selected day slots
     *
     * @param string                   $id      id
     * @param \Illuminate\Http\Request $request request attributes
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function storeSelectedDaySlots(Request $request)
    {
        $user_id = !empty($request['user_id']) ? $request['user_id'] : '';
        if (!empty($user_id)) {
            $location  = new Team();
            $update_slots = $location->saveSelectedDaySlots($request['id'], $request);
            if ($update_slots == 'success') {
                $json['type'] = 'success';
                $json['message'] = trans('lang.slot_updated');
                return Response::json($json, 200);
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something');
            return Response::json($json, 203);
        }
    }

    /**
     * Add item in wishlist.
     *
     * @param mixed $request request->attributes
     *
     * @return \Illuminate\Http\Response
     */
    public function addWishlist(Request $request)
    {
        $server = Helper::doctieIsDemoSiteAjax();
        if (!empty($server)) {
            $response['message'] = $server->getData()->message;
            return $response;
        }
        $json = array();
        $column = !empty($request['column']) ? $request['column'] : '';
        $id = !empty($request['id']) ? $request['id'] : '';
        $user_id = !empty($request['user_id']) ? $request['user_id'] : '';
        if (!empty($user_id)) {
            $json['authentication'] = true;
            if (!empty($id)) {
                if (!empty($column)) {
                    if ($column === 'saved_hospitals' || $column === 'saved_doctors') {
                        if ($user_id == $id) {
                            $json['test'] = 'hello2';
                            $json['type'] = 'error';
                            $json['message'] = trans('lang.login_from_different_user');
                            return Response::json($json, 203);
                        }
                    } elseif ($column === 'saved_articles') {
                        $article = Article::find($id);
                        if ($user_id == $article->author->id) {
                            $json['type'] = 'error';
                            $json['message'] = trans('lang.login_from_different_user');
                            return Response::json($json, 203);
                        }
                    }
                }
                $user_meta = new UserMeta();
                $add_wishlist = $user_meta->addWishlist($column, $id, $user_id);
                $article_likes = '';
                if ($add_wishlist == "success") {
                    if ($column == 'saved_articles') {
                        $article = Article::find($id);
                        if (!empty($article->likes)) {
                            $article->likes = $article->likes + 1;
                        } else {
                            $article->likes = 1;
                        }
                        $article_likes = $article->likes;
                        $article->save();
                        $json['likes'] = $article_likes;
                    }
                    $json['type'] = 'success';
                    $json['message'] = trans('lang.added_to_wishlist');
                    return Response::json($json, 200);
                } else {
                    $json['type'] = 'error';
                    $json['message'] = trans('lang.something_wrong');
                    return Response::json($json, 203);
                }
            }
        } else {
            $json['test'] = 'hello1';
            $json['authentication'] = false;
            $json['message'] = trans('lang.need_to_reg');
            return Response::json($json, 203);
        }
    }

    /**
     * Get user saved item list
     *
     * @param mixed $request request attributes
     * @param int   $role    role
     *
     * @access public
     *
     * @return View
     */
    public function getSavedItems(Request $request)
    {
        $json = array();
        $profile_id = !empty($_GET['profile_id']) ? $_GET['profile_id'] : '';
        $type = !empty($_GET['type']) ? $_GET['type'] : '';
        if (!empty($profile_id)) {
            $user = User::find($profile_id);
            $icons = SiteManagement::getMetaValue('icons');
            $hidden_doctor_image = !empty($icons['hidden_doctor_image']) ? $icons['hidden_doctor_image'] : '';
            $hidden_hospital_image = !empty($icons['hidden_hospital_image']) ? $icons['hidden_hospital_image'] : '';
            $saved_doctors   = !empty($user->profile->saved_doctors) ? unserialize($user->profile->saved_doctors) : array();
            $saved_hospitals = !empty($user->profile->saved_hospitals) ? unserialize($user->profile->saved_hospitals) : array();
            $saved_articles  = !empty($user->profile->saved_articles) ? unserialize($user->profile->saved_articles) : array();
            $currency        = SiteManagement::getMetaValue('payment_settings');
            $symbol          = !empty($currency) && !empty($currency['currency']) ? Helper::currencyList($currency['currency']) : array();
            if ($type == 'doctors') {
                if (!empty($saved_doctors)) {
                    foreach ($saved_doctors as $key => $user_id) {
                        $user_obj = User::find($user_id);

                        $avg_rating = Feedback::where('user_id', $user_obj->id)->pluck('avg_rating')->first();
                        $specialities = $user_obj->services->count() > 0 ? DB::table('user_service')->select('speciality')
                            ->where('user_id', $user_obj->id)->groupBy('speciality')->get()->pluck('speciality')->random(1)->toArray() : '';
                        $json[$key]['id'] = $user_obj->id;
                        $json[$key]['image'] = asset(Helper::getImage('uploads/users/' . $user_obj->id, $user_obj->profile->avatar, 'saved_items-', 'user-logo-def.jpg'));
                        $json[$key]['link'] = route('userProfile', ['slug' => $user_obj->slug]);
                        $json[$key]['name'] = !empty($user_obj->profile->gender_title) ? Helper::getDoctorArray(clean($user_obj->profile->gender_title)) : '' . Helper::getUsername($user_obj->id);
                        $json[$key]['medilcal_verified'] = $user_obj->profile->verify_registration == 1 ? 'yes' : 'no';
                        $json[$key]['is_verified'] = $user_obj->user_verified == 1 ? 'yes' : 'no';
                        $json[$key]['subheading'] = $user_obj->profile->sub_heading;
                        $user_detail['average_rating'] = $avg_rating;
                        $user_detail['total_rating'] = (string) Feedback::where('user_id', $user->id)->count();
                        if (!empty($specialities)) {
                            foreach ($specialities as $speciality_key => $user_speciality) {
                                $speciality = Helper::getSpecialityByID($user_speciality);
                                if (!empty($speciality)) {
                                    $json[$key]['specialities']['id'] = $speciality->id;
                                    $json[$key]['specialities']['slug'] = $speciality->slug;
                                    $json[$key]['specialities']['title'] = $speciality->title;
                                }
                            }
                        } else {
                            $json[$hospital_key]['specialities'] = [];
                        }
                        $avg_rating = Feedback::where('user_id', $user_obj->id)->pluck('avg_rating')->first();
                        $json[$key]['average_rating'] = !empty($avg_rating) ? $avg_rating : '0';
                        $json[$key]['total_rating'] = (string) Feedback::where('user_id', $user->id)->count();
                    }
                    return Response::json($json, 200);
                } else {
                    $json['type']        = 'error';
                    $json['message']    = trans('lang.no_record');
                    return Response::json($json, 203);
                }
            } elseif ($type == 'hospitals') {
                if (!empty($saved_hospitals)) {
                    foreach ($saved_hospitals as $hospital_key => $user_id) {
                        $user_obj = User::find($user_id);
                        $specialities = $user_obj->services->count() > 0 ? DB::table('user_service')->select('speciality')
                            ->where('user_id', $user_obj->id)->groupBy('speciality')->get()->pluck('speciality')->random(1)->toArray() : '';
                        $json[$hospital_key]['id'] = $user_obj->id;
                        $json[$hospital_key]['image'] = asset(Helper::getImage('uploads/users/' . $user_obj->id, $user_obj->profile->avatar, 'saved_items-', 'user-logo-def.jpg'));
                        $json[$hospital_key]['link'] = route('userProfile', ['slug' => $user_obj->slug]);
                        $json[$hospital_key]['name'] = !empty($user_obj->profile->gender_title) ? Helper::getDoctorArray(clean($user_obj->profile->gender_title)) : '' . Helper::getUsername($user_obj->id);
                        $json[$hospital_key]['subheading'] = $user_obj->profile->sub_heading;
                        $json[$hospital_key]['medilcal_verified'] = $user_obj->profile->verify_registration == 1 ? 'yes' : 'no';
                        $json[$hospital_key]['is_verified'] = $user_obj->user_verified == 1 ? 'yes' : 'no';
                        if (!empty($specialities)) {
                            foreach ($specialities as $user_speciality_key => $user_speciality) {
                                $speciality = Helper::getSpecialityByID($user_speciality);
                                if (!empty($speciality)) {
                                    $json[$hospital_key]['specialities']['id'] = $speciality->id;
                                    $json[$hospital_key]['specialities']['slug'] = $speciality->slug;
                                    $json[$hospital_key]['specialities']['title'] = $speciality->title;
                                }
                            }
                        } else {
                            $json[$hospital_key]['specialities'] = [];
                        }
                        $avg_rating = Feedback::where('user_id', $user_obj->id)->pluck('avg_rating')->first();
                        $json[$hospital_key]['average_rating'] = !empty($avg_rating) ? $avg_rating : '0';
                        $json[$hospital_key]['total_rating'] = (string) Feedback::where('user_id', $user->id)->count();
                    }
                    return Response::json($json, 200);
                } else {
                    $json['type']        = 'error';
                    $json['message']    = trans('lang.no_record');
                    return Response::json($json, 203);
                }
            } elseif ($type == 'articles') {
                if (!empty($saved_articles)) {
                    foreach ($saved_articles as $article_key => $saved_article) {
                        $article = Article::find($saved_article);
                        $json[$article_key]['title'] = $article->title;
                        $json[$article_key]['image'] = asset(Helper::getImage('uploads/users/' . $article->author->id . '/articles/', $article->image, 'listing-', 'article-default.jpg'));
                        $json[$article_key]['author_image'] = asset(Helper::getImage('uploads/users/' . $article->author->id, User::find($article->author->id)->profile->avatar, 'extra-small-', 'user-login.png'));
                        $json[$article_key]['link'] = route('articleDetail', ['slug' => clean($article->slug)]);
                        $json[$article_key]['author_name'] = Helper::getUserName($article->author_id);

                        $json[$article_key]['publish_date'] = Carbon::parse($article->created_at)->format('M d, Y');
                        $json[$article_key]['likes'] = !empty($article->likes) ? clean($article->likes) : 0;
                        $json[$article_key]['views'] = !empty($article->views) ? clean($article->views) : 0;
                        $json[$article_key]['id'] = $article->id;
                        if (!empty($article->categories) && $article->categories->count() > 0) {
                            foreach ($article->categories as $category) {
                                $json[$article_key]['category']['link'] = route('articleListing', clean($category->slug));
                                $json[$article_key]['category']['title'] = clean($category->title);
                            }
                        } else {
                            $json[$article_key]['category']['link'] = '';
                            $json[$article_key]['category']['title'] = '';
                        }
                    }
                    return Response::json($json, 200);
                } else {
                    $json['type']        = 'error';
                    $json['message']    = trans('lang.no_record');
                    return Response::json($json, 203);
                }
            } else {
                $json['type'] = 'error';
                $json['message'] = trans('lang.something_wrong');
                return Response::json($json, 203);
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return Response::json($json, 203);
        }
    }

    /**
     * Submit feedback.
     *
     * @param mixed $request request->attr
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function submitFeedack(Request $request)
    {
        $doctor_id = !empty($request['doctor_id']) ? $request['doctor_id'] : '';
        $patient_id = !empty($request['patient_id']) ? $request['patient_id'] : '';
        $waiting_time = !empty($request['waiting_time']) ? $request['waiting_time'] : '';
        $feedbackpublicly = !empty($request['feedbackpublicly']) ? $request['feedbackpublicly'] : '';
        $comments = !empty($request['comments']) ? $request['comments'] : '';
        $rating = !empty($request['rating']) ? $request['rating'] : '';
        $votes = !empty($request['votes']) ? $request['votes'] : '';
        if (!empty($request)) {
            $feedback_submission = Feedback::submitFeedback($request, $patient_id);
            if ($feedback_submission == 'success') {
                $json['type'] =  'success';
                $json['message'] = trans('lang.feedback_submitted');
                return Response::json($json, 200);
            } else {
                $json['type'] = 'error';
                $json['message'] = trans('lang.something_wrong');
                return Response::json($json, 203);
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return Response::json($json, 203);
        }
    }

    /**
     * Store User profile settings.
     *
     * @param \Illuminate\Http\Request $request   request attributes
     * @param string                   $role_type role_type
     *
     * @return \Illuminate\Http\Response
     */
    public function storeProfileSettings(Request $request)
    {
        $server = Helper::doctieIsDemoSiteAjax();
        if (!empty($server)) {
            $response['type'] = 'error';
            $response['message'] = $server->getData()->message;
            return $response;
        }
        $json = array();
        $user_id = !empty($request['user_id']) ? $request['user_id'] : '';
        $user = User::find($user_id);
        $role_type = Helper::getRoleTypeByUserID($user_id);
        if (!empty($request['latitude']) || !empty($request['longitude'])) {
            $this->validate(
                $request,
                [
                    'latitude' => ['regex:/^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,6}$/'],
                    'longitude' => ['regex:/^-?([1]?[1-7][1-9]|[1]?[1-8][0]|[1-9]?[0-9])\.{1}\d{1,6}$/'],
                ]
            );
        }
        if ($role_type == 'admin') {
            if ($request['email'] != $user->email) {
                $this->validate(
                    $request,
                    [
                        'email' => 'unique:users|email',
                    ]
                );
            }
            $this->validate(
                $request,
                [
                    'first_name' => 'required',
                    'last_name' => 'required',
                ]
            );
        } else {
            $this->validate(
                $request,
                [
                    'first_name' => 'required',
                    'last_name' => 'required',
                ]
            );
        }
        if (!empty($user_id)) {
            $count = 0;
            $user_meta = new UserMeta();
            $profile = $user_meta->storeProfile($request, $user_id);
            if ($request['experience']) {
                return Response::json($request['experience'], 200);
                // foreach ($request['experience'] as $key => $experience) {
                //     if (
                //         empty($experience['job_title'])  || empty($experience['start_date'])
                //         || empty($experience['end_date']) || empty($experience['company_title'])
                //     ) {
                //         $json['type'] = 'error';
                //         $json['message'] = trans('lang.empty_fields_not_allowed');
                //          return Response::json($json, 200);
                //     }

                //     if ($experience['company_title'] == 'Company Title') {
                //         $json['type'] = 'error';
                //         $json['message'] = trans('lang.empty_fields_not_allowed');
                //          return Response::json($json, 200);
                //     }
                //     $request_experience[$count] = $experience;
                //     $count++;
                // }
            }
            $experience = !empty($request['experience']) ? serialize($request_experience) : '';
            $user_meta->user()->associate($user_id);
            $user_meta->experiences = $experience;
            $user_meta->save();
            // $save_experiences = $user_meta->saveExperiences($request, $user_id);
            // $save_education = $user_meta->saveEducations($request, $user_id);
            // $store_awards_downloads = $user_meta->storeAwardsDownloads($request, $user_id);
            // $store_registration = $user_meta->storeRegistration($request, $user_id);
            // $save_services = $user_meta->saveServices($request, $user_id);
            if (
                $profile == 'error' 
            ) {
                $json['type'] = 'error';
                $json['message'] = trans('lang.something_wrong');
                return Response::json($json, 203);
            } else {
                $json['type'] = 'success';
                $json['role'] = Helper::getAuthRoleType($user_id);
                $json['message'] = trans('lang.personal_details_saved');
                return Response::json($json, 200);
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.not_authorize');
            return Response::json($json, 203);
        }
    }

    /**
     * Get doctor locations
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function getDoctorServices()
    {
        $json = array();
        $doctor_id = !empty($_GET['user_id']) ? $_GET['user_id'] : '';
        if (!empty($doctor_id)) {
            $user = User::find($doctor_id);
            $specialities = !empty($user->profile->services) ? Helper::getUnserializeData($user->profile->services) : array();
            if (!empty($specialities)) {
                foreach ($specialities as $speciality_key => $user_speciality) {
                    $speciality = Helper::getSpecialityByID($user_speciality['speciality_id']);
                    if (!empty($speciality)) {
                        $json['speciality'][$speciality_key]['name'] = $speciality->title;
                        $json['speciality'][$speciality_key]['logo'] = asset(Helper::getImage('uploads/specialities',  $speciality->image, '', 'default-speciality.png'));
                    }
                    if (!empty($user_speciality['services'])) {
                        foreach ($user_speciality['services'] as $spe_ser_key => $speciality_service) {
                            $service = Helper::getServiceByID($speciality_service['service']);
                            if (!empty($service)) {
                                $json['speciality'][$speciality_key]['services'][$spe_ser_key]['title'] = $service->title;
                                $json['speciality'][$speciality_key]['services'][$spe_ser_key]['price'] = !empty($speciality_service['price']) ? (!empty($symbol['symbol']) ? $symbol['symbol'] : '$') . $speciality_service['price'] : '';
                                $json['speciality'][$speciality_key]['services'][$spe_ser_key]['description'] = !empty($speciality_service['description']) ? $speciality_service['description'] : '';
                            }
                        }
                    }
                }
                return Response::json($json, 200);
            } else {
                $json['type']        = 'error';
                $json['message']    = trans('lang.no_record');
                return Response::json($json, 203);
            }
        } else {
            $json['type']        = 'error';
            $json['message']    = trans('lang.doctor_id_req');
            return Response::json($json, 203);
        }
    }

    /**
     * Update Payout detail
     *
     * @param mixed $request request attributes
     * 
     * @return \Illuminate\Http\Response
     */
    public function submitPayoutSettings(Request $request)
    {
        $user_id = !empty($_GET['id']) ? $_GET['id'] : '';
        if (!empty($user_id)) {
            $user_meta = new UserMeta();
            $payout_setting = $user_meta->savePayoutDetail($request, $user_id);
            if ($payout_setting == 'success') {
                $json['type'] = 'success';
                $json['message'] = trans('lang.payout_saved_success');
                return Response::json($json, 200);
            } else {
                $json['type'] = 'error';
                $json['message'] = trans('lang.something_went_wrong');
                return Response::json($json, 203);
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.verify_code');
            return Response::json($json, 203);
        }
    }

    /**
     * Get doctor payouts.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDoctorPayouts()
    {
        $post_per_page = !empty($_GET['show_users']) ? $_GET['show_users'] : 10;
        $page_number = !empty($_GET['page_number']) ? $_GET['page_number'] : 1;
        Paginator::currentPageResolver(
            function () use ($page_number) {
                return $page_number;
            }
        );
        if (Auth::user()) {
            $user_id = !empty($_GET['user_id']) ? $_GET['user_id'] : '';
            if (!empty($user_id)) {
                $payouts =  Payout::where('user_id', $user_id)->paginate($post_per_page);
            } else {
                $json['type']        = 'error';
                $json['message']    = trans('lang.doctor_id_req');
                return Response::json($json, 203);
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_went_wrong');
            return Response::json($json, 203);
        }
    }

    /**
     * Get Users.
     *
     * @return \Illuminate\Http\Response
     */
    public function getChatUsers()
    {
        $user_id = !empty($_GET['user_id']) ? $_GET['user_id'] : '';
        // $role_type = Helper::getRoleTypeByUserID($user_id);
        if (!empty($user_id)) {
            $unreadNotifyClass  = '';
            $users = DB::select(
                DB::raw(
                    "SELECT * FROM messages
                    WHERE id IN (
                        SELECT MAX(id) AS id
                FROM (
                    SELECT id, user_id AS chat_sender
                    FROM messages
                    WHERE receiver_id = $user_id OR user_id = $user_id
                UNION ALL
                    SELECT id, receiver_id AS chat_sender
                    FROM messages
                    WHERE user_id = $user_id OR receiver_id = $user_id )
                    t GROUP BY chat_sender ) ORDER BY id DESC"
                )
            );
            $json = array();
            if (!empty($users)) {
                foreach ($users as $key => $userVal) {
                    $chat_user_id   = '';
                    if ($user_id == intval($userVal->user_id)) {
                        $chat_user_id = intval($userVal->receiver_id);
                    } else {
                        $chat_user_id = intval($userVal->user_id);
                    }
                    $json[$key]['id'] = $chat_user_id;
                    $json[$key]['image'] = asset(
                        Helper::getImage(
                            'uploads/users/' . $chat_user_id . '/',
                            User::find($chat_user_id)->profile->avatar,
                            'medium-',
                            'user-login.png'
                        )
                    );
                    $json[$key]['name'] = Helper::getUserName($chat_user_id);
                    $json[$key]['tagline'] = User::find($chat_user_id)->profile->tagline;
                    $json[$key]['image_name'] = User::find($chat_user_id)->profile->avatar;
                }
                $message_status = Message::where('receiver_id', $user_id)->where('status', 0)->count();
                if ($message_status > 0) {
                    $unreadNotifyClass = 'wt-dotnotification';
                }
                $json[$key]['status_class'] = $unreadNotifyClass;
                return Response::json($json, 200);
            } else {
                $json['type'] = 'error';
                $json['message'] = trans('lang.user_not_found');
                return Response::json($json, 203);
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.not_authorize');
            return Response::json($json, 203);
        }
    }

    /**
     * Get user messages.
     *
     * @param mixed $request $req->attr
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserMessages(Request $request)
    {
        $json = array();
        $user_id = !empty($_GET['user_id']) ? $_GET['user_id'] : '';
        $sender_id = !empty($_GET['sender_id']) ? $_GET['sender_id'] : '';
        if (!empty($sender_id) && !empty($user_id)) {
            // $user_id = auth()->user()->id;
            $receiver_id = $sender_id;
            $selected_user = User::find($receiver_id);
            DB::table('messages')
                ->where('user_id', $receiver_id)
                ->where('receiver_id', $user_id)
                ->update(['status' => 1]);
            $messages = DB::table('messages')->select('*')
                ->where(
                    function ($query) use ($user_id, $receiver_id) {
                        $query->where('user_id', '=', $user_id)
                            ->Where('receiver_id', '=', $receiver_id);
                    }
                )
                ->orWhere(
                    function ($query) use ($user_id, $receiver_id) {
                        $query->where('receiver_id', '=', $user_id)
                            ->Where('user_id', '=', $receiver_id);
                    }
                )
                ->get()->toArray();
            if (!empty($messages)) {
                foreach ($messages as $key => $message) {
                    $message_read = 'no';
                    if ($message->status == 1 && $message->user_id == $user_id) {
                        $message_read = 'yes';
                    }
                    $json['messages'][$key]['is_sender'] = 'no';
                    if ($message->user_id == $user_id) {
                        $json['messages'][$key]['is_sender'] = 'yes';
                    }
                    $json['messages'][$key]['id'] = $message->id;
                    $json['messages'][$key]['user_id'] = $message->user_id;
                    $json['messages'][$key]['image'] = asset(
                        Helper::getImage('uploads/users/' . $message->user_id . '/', User::find($message->user_id)->profile->avatar, 'medium-', 'user-login.png')
                    );
                    $json['messages'][$key]['message'] = $message->body;
                    $json['messages'][$key]['date'] =  $message->created_at;
                    $json['messages'][$key]['read_status'] = $message_read;
                }
                $json['selected']['selected_user_name'] = Helper::getUserName($receiver_id);
                $json['selected']['selected_user_slug'] = $selected_user->slug;
                $json['selected']['selected_user_tagline'] = $selected_user->profile->tagline;
                $json['selected']['selected_user_image'] = asset(
                    Helper::getImage(
                        'uploads/users/' . $receiver_id . '/',
                        User::find($receiver_id)->profile->avatar,
                        'medium-',
                        'user-login.png'
                    )
                );
                $json['selected']['selected_user_verified'] = $selected_user->user_verified;
                return Response::json($json, 200);
            } else {
                $json['type']        = 'error';
                $json['message']    = trans('lang.no_record');
                return Response::json($json, 203);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request request attributes
     *
     * @return \Illuminate\Http\Response
     */
    public function storeMessage(Request $request)
    {
        $json = array();
        $message = new Message();
        if (empty($request['message'])) {
            $json['type'] = 'error';
            $json['message'] = trans('lang.empty_field');
            return Response::json($json, 203);
        }
        $messsage = $message->sendMessage($request);
        $json['data'] = $messsage;
        $json['type'] = trans('lang.message_send');
        return Response::json($json, 200);
    }

    /**
     * Get chat settings
     *
     * @return \Illuminate\Http\Response
     */
    public function getChatSettings()
    {
        $json = array();
        $chat_settings = SiteManagement::getMetaValue('chat_settings');
        $host = !empty($chat_settings['host']) ? $chat_settings['host'] : 'http://localhost';
        $port = !empty($chat_settings['port']) ? $chat_settings['port'] : 3001;
        $json['host'] = $host;
        $json['port'] = $port;
        return Response::json($json, 200);
    }

    /**
     * Get chat settings
     *
     * @return \Illuminate\Http\Response
     */
    public function getPayPalSetting()
    {
        $json = array();
        $existing_payment_settings = SiteManagement::getMetaValue('paypal_settings');
        $client_id = !empty($existing_payment_settings['client_id']) ? $existing_payment_settings['client_id'] : '';
        $password = !empty($existing_payment_settings['paypal_password']) ? $existing_payment_settings['paypal_password'] : '';
        $secret = !empty($existing_payment_settings['paypal_secret']) ? $existing_payment_settings['paypal_secret'] : '';
        $sandbox = !empty($existing_payment_settings['enable_sandbox']) ? $existing_payment_settings['enable_sandbox'] : '';
        $json['client_id'] = $client_id;
        $json['password'] = $password;
        $json['secret'] = $secret;
        $json['sandbox'] = $sandbox;
        return Response::json($json, 200);
    }

    /**
     * Get chat settings
     *
     * @return \Illuminate\Http\Response
     */
    public function getStripeSetting()
    {
        $json = array();
        $stripe_settings = SiteManagement::getMetaValue('stripe_settings');
        $stripe_key = !empty($stripe_settings) ? $stripe_settings['stripe_key'] : '';
        $stripe_secret = !empty($stripe_settings) ? $stripe_settings['stripe_secret'] : '';
        $json['stripe_key'] = $stripe_key;
        $json['stripe_secret'] = $stripe_secret;
        return Response::json($json, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request request attributes
     *
     * @return \Illuminate\Http\Response
     */
    public function createOrder(Request $request)
    {
        $json = array();
        $id = !empty($_GET['product_id']) ? $_GET['product_id'] : '';
        $type = !empty($_GET['type']) ? $_GET['type'] : '';
        $user_id = !empty($_GET['user_id']) ? $_GET['user_id'] : '';
        $payment_gateway = !empty($_GET['payment_gateway']) ? $_GET['payment_gateway'] : '';
        if (empty($id)) {
            $json['type'] = 'error';
            $json['message'] = trans('lang.product_id_req');
            return Response::json($json, 203);
        }
        if (empty($type)) {
            $json['type'] = 'error';
            $json['message'] = trans('lang.type_req');
            return Response::json($json, 203);
        }
        if (empty($user_id)) {
            $json['type'] = 'error';
            $json['message'] = trans('lang.user_id_req');
            return Response::json($json, 203);
        }
        $order = new Order();
        $order->status = 'pending';
        $order->payment_gateway = $payment_gateway;
        if ($type == 'appointment') {
            $appointment = Appointment::find($id);
            $order->appointment_date = $appointment->appointment_date;
        }
        $order->user()->associate($user_id);
        $order->save();
        $order_id = DB::getPdo()->lastInsertId();
        $latest_order = Order::find($order_id);
        $order_type = new OrderMeta();
        $order_type->meta_key = 'type';
        $order_type->meta_value = $type;
        $latest_order->orderMeta()->save($order_type);
        if ($type == 'appointment') {
            $appointment_data = array();
            $appointment = Appointment::find($id);
            if (!empty($appointment->toArray())) {
                foreach ($appointment->toArray() as $appointment_key => $appointment_value) {
                    $appointment_data[$appointment_key] = $appointment_value;
                }
                $appointment_meta = new OrderMeta();
                $appointment_meta->meta_key = 'appointment';
                $appointment_meta->meta_value = serialize($appointment_data);
                $latest_order->orderMeta()->save($appointment_meta);
            }
        } else if ($type == 'package') {
            $package_data = array();
            $package = Package::find($id)->toArray();
            if (!empty($package)) {
                foreach ($package as $package_key => $package_value) {
                    $package_data[$package_key] = $package_value;
                }
                $package_meta = new OrderMeta();
                $package_meta->meta_key = 'package';
                $package_meta->meta_value = serialize($package_data);
                $latest_order->orderMeta()->save($package_meta);
            }
        }
        $json['type'] = 'success';
        return Response::json($json, 200);
    }

    /**
     * Create invoice
     *
     * @param mixed $cart           cart
     * @param mixed $status         status
     * @param mixed $payment_detail payment_detail
     *
     * @return \Illuminate\Http\Response
     */
    protected function createInvoice(Request $request)
    {
        $json = array();
        $id = !empty($_GET['product_id']) ? $_GET['product_id'] : '';
        $type = !empty($_GET['type']) ? $_GET['type'] : '';
        $user_id = !empty($_GET['user_id']) ? $_GET['user_id'] : '';
        $order_id = !empty($_GET['order_id']) ? $_GET['order_id'] : '';
        if (empty($id)) {
            $json['type'] = 'error';
            $json['message'] = trans('lang.product_id_req');
            return Response::json($json, 203);
        }
        if (empty($type)) {
            $json['type'] = 'error';
            $json['message'] = trans('lang.type_req');
            return Response::json($json, 203);
        }
        if (empty($user_id)) {
            $json['type'] = 'error';
            $json['message'] = trans('lang.user_id_req');
            return Response::json($json, 203);
        }
        $user = User::find($user_id);
        if (!empty($id) && !empty($type)) {
            $new_order = Order::find($order_id);
            $new_order->status = 'completed';
            $new_order->save();
            $payment_detail = !empty($request['payment_detail']) ? $request['payment_detail'] : '';
            if (!empty($payment_detail)) {
                foreach ($payment_detail as $key => $value) {
                    $meta = new OrderMeta();
                    $meta->meta_key = $key;
                    $meta->meta_value = $value;
                    $new_order->orderMeta()->save($meta);
                }
            }
            if ($type == 'appointment') {
                $appointment = Appointment::find($id);
                $appointment->status = 'accepted';
                $appointment->save();
                // appointment mail
                $hospital = User::findOrFail($appointment->hospital_id);
                $doctor = User::findOrFail($appointment->user_id);
                if (!empty(config('mail.username')) && !empty(config('mail.password'))) {
                    $email_params = array();
                    $template = DB::table('email_types')->select('id')->where('email_type', 'user_email_appointment_request_approved')->get()->first();
                    if (!empty($template->id)) {
                        $template_data = EmailTemplate::getEmailTemplateByID($template->id);
                        $email_params['user_name'] = Helper::getUserName($user_id);
                        $email_params['hospital_name'] = Helper::getUserName($hospital->id);
                        $email_params['hospital_link'] = url('profile/' . $hospital->slug);
                        $email_params['doctor_name'] = Helper::getUserName($doctor->id);
                        $email_params['doctor_link'] = url('profile/' . $doctor->slug);
                        $email_params['appointment_date_time'] = Carbon::parse($appointment->appointment_date)->format('d M, Y') . ' ' . $appointment->appointment_time;
                        $email_params['description'] = $appointment->comments;
                        Mail::to($user->email)
                            ->send(
                                new GeneralEmailMailable(
                                    'user_email_appointment_request_approved',
                                    $template_data,
                                    $email_params
                                )
                            );
                    }
                }
            } else if ($type == 'package') {
                $package = Package::find($id)->toArray();
                // Package Mail
                $package = Package::find($id);
                $option = !empty($package->options) ? unserialize($package->options) : '';
                $expiry = !empty($new_order) ? $new_order->created_at->addDays($option['duration']) : '';
                $expiry_date = !empty($expiry) ? Carbon::parse($expiry)->toDateTimeString() : '';
                $user = User::find($user_id);
                $user->package_expiry = $expiry_date;
                $user->save();
                // send mail
                if (!empty(config('mail.username')) && !empty(config('mail.password'))) {
                    $email_params = array();
                    $template = DB::table('email_types')->select('id')->where('email_type', 'doctor_email_package_subscribed')->get()->first();

                    if (!empty($template->id)) {
                        $template_data = EmailTemplate::getEmailTemplateByID($template->id);
                        $email_params['doctor_name'] = Helper::getUserName($user_id);
                        $email_params['pkg_title'] = $package->title;
                        $email_params['amount'] = $package->cost;
                        $email_params['date'] = Carbon::parse($new_order->created_at)->format('M d, Y');
                        $email_params['expiry_date'] = !empty($expiry) ? Carbon::parse($expiry)->format('M d, Y') : '';
                        Mail::to($user->email)
                            ->send(
                                new DoctorEmailMailable(
                                    'doctor_email_package_subscribed',
                                    $template_data,
                                    $email_params
                                )
                            );
                    }
                }
            }
        }
        $json['type'] = 'success';
        return Response::json($json, 200);
    }

    /**
     * Get Doctor Invoices.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserInvoices()
    {
        $json = array();
        $user_id = !empty($_GET['user_id']) ? $_GET['user_id'] : '';
        $currency   = SiteManagement::getMetaValue('payment_settings');
        $symbol = !empty($currency) && !empty($currency['currency']) ? Helper::currencyList($currency['currency']) : array();
        $currency_symbol = !empty($symbol) ? $symbol['symbol'] : '$';
        if (!empty($user_id)) {
            $user = User::find($user_id);
            $invoices = $user->orders()->get();
            if (!empty($invoices)) {
                foreach ($invoices as $key => $invoice) {
                    $order = Order::findOrFail($invoice->id);
                    $expiry_date = '';
                    if (!empty($order->metaValue('package'))) {
                        $options = unserialize($order->metaValue('package'));
                        $package_options = unserialize($options['options']);
                        if ($package_options['duration'] === '10') {
                            $expiry_date = !empty($order->created_at) ? $order->created_at->addDays(4) : '';
                        } elseif ($package_options['duration'] === '30') {
                            $expiry_date = !empty($order->created_at) ? $order->created_at->addDays(30) : '';
                        } elseif ($package_options['duration'] === '360') {
                            $expiry_date = !empty($order->created_at) ? $order->created_at->addDays(360) : '';
                        }
                    } else {
                        $expiry_date = '';
                    }
                    if (!empty($invoice)) {
                        $json[$key]['invoice_id'] = !empty($order->metaValue('invoice_id')) ? $order->metaValue('invoice_id') : trans('lang.not_available');
                        $json[$key]['purchase_date'] = Carbon::parse($order->created_at)->format('M d, Y');
                        $json[$key]['expiry_date'] = Carbon::parse($expiry_date)->format('M d, Y');
                        $json[$key]['amount'] =  $currency_symbol . intVal($options['cost']);
                        $json[$key]['status'] = $order->status;
                        $json[$key]['id'] = intVal(clean($order->id));
                    }
                }
                return Response::json($json, 200);
            } else {
                $json['type']        = 'error';
                $json['message']    = trans('lang.no_record');
                return Response::json($json, 203);
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.user_id_req');
            return Response::json($json, 203);
        }
    }

    /**
     * Get Invoices.
     *
     * @param integer $id roletype
     *
     * @return \Illuminate\Http\Response
     */
    public function showInvoice()
    {
        $json = array();
        $id = !empty($_GET['id']) ? $_GET['id'] : '';
        $user_id = !empty($_GET['user_id']) ? $_GET['user_id'] : '';
        if (!empty($id)) {
            $order = Order::findOrFail($id);
            $currency_code = !empty($order->metaValue('currency_code')) ? strtoupper($order->metaValue('currency_code')) : 'USD';
            $code = Helper::currencyList($currency_code);
            $symbol = !empty($code) ? $code['symbol'] : '$';
            $role_type = Helper::getRoleTypeByUserID($user_id);
            if ($role_type === 'doctor') {
                $options = unserialize($order->metaValue('package'));
                $package_options = unserialize($options['options']);
            } else if ($role_type === 'regular') {
                $options = unserialize($order->metaValue('appointment'));
            } else {
                $json['type'] = 'error';
                $json['message'] = trans('lang.not_authorize');
                return Response::json($json, 203);
            }
            if (!empty($order)) {
                $json['payer_name'] =  !empty($order->metaValue('payer_name')) ? $order->metaValue('payer_name') : '';
                $json['payment_date'] =  Carbon::parse($order->created_at)->diffForHumans() . ' ' . Carbon::parse($order->created_at)->format('l \\a\\t H:i:s');
                $json['transaction_id'] =  !empty($order->metaValue('transaction_id')) ? $order->metaValue('transaction_id') : '';
                $json['customer_id'] =  !empty($order->metaValue('customer_id')) ? $order->metaValue('customer_id') : '';
                $json['price'] = $options['cost'];
                $json['symbol'] = $symbol;
                $json['currency_code'] = $currency_code;
                $json['customer_id'] =  !empty($order->metaValue('customer_id')) ? $order->metaValue('customer_id') : '';
                $json['status'] =  !empty($order->status) ? $order->status : '';
                $json['purchase_total'] = $symbol . $options['cost'] . ' ' . $currency_code;
                $json['sales_tax'] =  !empty($order->metaValue('sales_tax')) ? $symbol . $order->metaValue('sales_tax') . ' ' . $currency_code : '';
                $json['shiping_amnt'] =  !empty($order->metaValue('shiping_amnt')) ? $symbol . $order->metaValue('shiping_amnt') . ' ' . $currency_code : '';
                $json['handling_amnt'] =  !empty($order->metaValue('handling_amnt')) ? $symbol . $order->metaValue('handling_amnt') . ' ' . $currency_code : '';
                $json['insurance_amount'] =  !empty($order->metaValue('insurance_amount')) ? $symbol . $order->metaValue('insurance_amount') . ' ' . $currency_code : '';
                $json['net_amnt'] =  $symbol . $options['cost'] . ' ' . $currency_code;
                $json['invoice_id'] =  !empty($order->metaValue('invoice_id')) ? $order->metaValue('invoice_id') : '';
                $json['paid_by'] =  trans('lang.pay_sender_note');
                if ($order->payment_gateway == "paypal") {
                    $json['need_help'] =  trans('lang.need_help');
                    $json['paypal_note'] =  trans('lang.paypal_note');
                    $json['paypal_warning_note'] =  trans('lang.paypal_warning_note');
                }
                $json['memo'] =  trans('lang.memo');
                return Response::json($json, 200);
            }
        } else {
            $json['type']        = 'error';
            $json['message']    = trans('lang.no_record');
            return Response::json($json, 203);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPackages()
    {
        $json = array();
        
        $user_id = !empty($_GET['user_id']) ? $_GET['user_id'] : '';
        $role_type = Helper::getRoleTypeByUserID($user_id);
        $counter = 0;
        if (!empty($user_id) && $role_type == "doctor") {
            $package_options = Helper::getPackageOptions();
            $packages = Package::all()->where('trial', 0);
            $currency   = SiteManagement::getMetaValue('payment_settings');
            $symbol = !empty($currency) && !empty($currency['currency']) ? Helper::currencyList($currency['currency']) : array();
            if (!empty($packages) && $packages->count() > 0) {
                foreach ($packages as $key => $package) {
                    $options = unserialize($package->options);
                    if (!empty($package)) {
                        $json[$counter]['title'] = !empty($package->title) ? $package->title : '';
                        $json[$counter]['slug'] = !empty($package->slug) ? $package->slug : '';
                        $json[$counter]['subtitle'] = !empty($package->subtitle) ? $package->subtitle : '';
                        $json[$counter]['symbol'] = !empty($symbol['symbol']) ? $symbol['symbol'] : '$';
                        $json[$counter]['price'] = !empty($package->cost) ? $package->cost : '';
                        $json[$counter]['id'] = $package->id;
                        $features = array();
                        $count = 0;
                        foreach ($options as $option_key => $option) {
                            if ($option_key == 'featured' || $option_key == 'private_chat' || $option_key == 'bookings') {
                                $class = $option == 'true' ? 'true' : 'false';
                                $json[$counter]['features'][$count]['title'] = $option_key;
                                $json[$counter]['features'][$count]['value'] = $class;
                            } else if ($option_key == 'duration') {
                                $json[$counter]['features'][$count]['title'] = $option_key;
                                $json[$counter]['features'][$count]['value'] = Helper::getPackageDurationList($options['duration']);
                            } else {
                                $json[$counter]['features'][$count]['title'] = $option_key;
                                $json[$counter]['features'][$count]['value'] = $option;
                            }
                            $count++;
                        }
                        // $json[$counter]['features'] = $features;
                    }
                    $counter++;
                }
                return Response::json($json, 200);
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.not_authorize');
            return Response::json($json, 203);
        }
    }

    /**
     * Post Article.
     *
     * @param \Illuminate\Http\Request $request request
     * 
     * @return \Illuminate\Http\Response
     */
    public function postArticle(Request $request)
    {
        $this->validate(
            $request,
            [
                'title' => 'required',
                'description' => 'required'
            ]
        );
        if (empty($request['categories'])) {
            $response['type'] = 'cat-required';
            $response['message'] = trans('lang.cat_required');
            return $response;
        }
        $server = Helper::doctieIsDemoSiteAjax();
        if (!empty($server)) {
            $response['type'] = 'error';
            $response['message'] = $server->getData()->message;
            return $response;
        }
        $json = array();
        if (!empty($request)) {
            if (!empty($request['id'])) {
                $image_size = Helper::getImageSizes('articles');
                $article = new Article();
                $post_article = $article->createArticleAPI($request, $image_size);
                if ($post_article == "success") {
                    $json['message'] = trans('lang.article_created');
                    return Response::json($json, 200);
                } else {
                    $json['type'] = 'error';
                    $json['message'] = trans('lang.something_wrong');
                    return Response::json($json, 203);
                }
            } else {
                $json['type'] = 'error';
                $json['message'] = trans('lang.something_wrong');
                return Response::json($json, 203);
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return Response::json($json, 203);
        }
    }

    /**
     * Show hospital team listing
     *
     * @param \Illuminate\Http\Request $request request attributes
     * 
     * @access public
     *
     * @return View
     */
    public function approveUser(Request $request)
    {
        
        $json = array();
        $server = Helper::doctieIsDemoSiteAjax();
        if (!empty($server)) {
            $response['type'] = 'error';
            $response['message'] = $server->getData()->message;
            return $response;
        }
        $user_id = !empty($_GET['user_id']) ? $_GET['user_id'] : '';
        $role_type = !empty($user_id) ? Helper::getRoleTypeByUserID($user_id) : '';
        if (!empty($role_type) && $role_type === 'hospital') {
            $team = Team::findOrFail($request['id']);
            $team->status = 'approved';
            $team->save();
            if (!empty(config('mail.username')) && !empty(config('mail.password'))) {
                $email_params = array();
                $doctor_approve_req_template = DB::table('email_types')->select('id')
                    ->where('email_type', 'doctor_email_doctor_request_approved')->get()->first();
                if (!empty($doctor_approve_req_template->id)) {
                    $hospital = User::findOrFail($team->user_id);
                    $doctor = User::findOrFail($team->doctor_id);
                    $template_data = EmailTemplate::getEmailTemplateByID($doctor_approve_req_template->id);
                    $email_params['doctor_name'] = Helper::getUserName($doctor->id);
                    $email_params['hospital_name']  = Helper::getUserName($hospital->id);
                    $email_params['hospital_link']  = url('profile/'.$hospital->slug);
                    Mail::to($doctor->email)
                        ->send(
                            new DoctorEmailMailable(
                                'doctor_email_doctor_request_approved',
                                $template_data,
                                $email_params
                            )
                        );
                }
            }
            $json['message'] = 'updated';
            return Response::json($json, 200);
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return Response::json($json, 203);
        }
    }

    /**
     * Show hospital team listing
     *
     * @access public
     *
     * @return View
     */
    public function doctorListing()
    {
        $json = array();
        $server = Helper::doctieIsDemoSiteAjax();
        if (!empty($server)) {
            $response['type'] = 'error';
            $response['message'] = $server->getData()->message;
            return $response;
        }
        $user_id = !empty($_GET['user_id']) ? $_GET['user_id'] : '';
        $role_type = !empty($user_id) ? Helper::getRoleTypeByUserID($user_id) : '';
        if (!empty($role_type) && $role_type === 'hospital') {
            $hospital = User::findOrFail($user_id);
            $teams = !empty($hospital) ? $hospital->teams()->paginate(8) : array();
            $json['team'] = $teams;
            return Response::json($json, 200);
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return Response::json($json, 203);
        }
    }

    /**
     * Set slug before saving in DB
     *
     * @param \Illuminate\Http\Request $request request attributes
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function verifyUserCode(Request $request)
    {
        $json = array();
        $id = $request['user_id'];
        $email = $request['email'];
        $password = $request['password'];
        $user = User::find($id);
        if (!empty($request['code'])) {
            if ($request['code'] === $user->verification_code) {
                $user->user_verified = 1;
                $user->verification_code = null;
                $user->save();
                $json['type'] = 'success';
                //send mail
                if (!empty(config('mail.username')) && !empty(config('mail.password'))) {
                    $email_params = array();
                    $template = DB::table('email_types')->select('id')->where('email_type', 'new_user')->get()->first();
                    if (!empty($template->id)) {
                        $template_data = EmailTemplate::getEmailTemplateByID($template->id);
                        $email_params['site'] = env('APP_NAME');
                        $email_params['name'] = Helper::getUserName($id);
                        $email_params['email'] = $email;
                        $email_params['password'] = $password;
                        Mail::to($email)
                            ->send(
                                new GeneralEmailMailable(
                                    'new_user',
                                    $template_data,
                                    $email_params
                                )
                            );
                    }
                    $admin_template = DB::table('email_types')->select('id')->where('email_type', 'admin_email_registration')->get()->first();
                    if (!empty($admin_template->id)) {
                        $template_data = EmailTemplate::getEmailTemplateByID($admin_template->id);
                        $email_params['name'] = Helper::getUserName($id);
                        $email_params['email'] = $email;
                        $email_params['link'] = url('profile/' . $user->slug);
                        Mail::to(config('mail.username'))
                            ->send(
                                new AdminEmailMailable(
                                    'admin_email_registration',
                                    $template_data,
                                    $email_params
                                )
                            );
                    }
                }
                return $json;
            } else {
                $json['type'] = 'error';
                $json['message'] = trans('lang.invalid_verified_code');
                return $json;
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.verify_code');
            return $json;
        }
    }

    /**
     * Get selected speciality service
     *
     * @param mixed $request request attributes
     * 
     * @return \Illuminate\Http\Response
     */
    public function getServices()
    {
        $json = array();
        $speciality = Speciality::find($_GET['id']);
        if (!empty($speciality) && $speciality->services->count() > 0) {
            $services = $speciality->services->toArray();
            if (!empty($speciality)) {
                if (!empty($speciality)) {
                    $json['services'] = $services;
                    return Response::json($json, 200);
                } else {
                    $json['type'] = 'error';
                    $json['message'] = trans('lang.something_wrong');
                    return Response::json($json, 203);
                }
            } else {
                $json['type'] = 'error';
                $json['message'] = trans('lang.something_wrong');
                return Response::json($json, 203);
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return Response::json($json, 203);
        }
    }

    /**
     * Get forum settings data
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function getBookingSettings()
    {
        $json = array();
        $booking_settings = SiteManagement::getMetaValue('booking_settings');
        $online_payment = !empty($booking_settings['enable_booking']) ? $booking_settings['enable_booking'] : '';
        if (!empty($online_payment)) {
            return Response::json($online_payment, 200);
        } else {
            $online_payment = false;
            return Response::json($online_payment, 203);
        }
    }

    /**
     * Send payment link
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function paymentLink(Request $request)
    {
        // $json = array();
        // $product_id = $_GET['product_id'];
        // $product_name = $_GET['product_name'];
        // $price = $_GET['price'];
        // $user_id = $_GET['user_id'];
        // $type = $_GET['type'];

        // session()->put(['product_id' => $product_id]);
        // session()->put(['price' => $price]);
        // session()->put(['name' => $product_name]);
        // session()->put(['type' => $type]);
        
    }

    /**
     * Store experiences in storage
     *
     * @param mixed $request get req attributes
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function storeExperiences(Request $request)
    {
        $server = Helper::doctieIsDemoSiteAjax();
        if (!empty($server)) {
            $response['type'] = 'error';
            $response['message'] = $server->getData()->message;
            return $response;
        }
        $user_id = !empty($request['user_id']) ? $request['user_id'] : '';
        if (!empty($request) && !empty($user_id)) {
            $user_meta = new UserMeta();
            $experiences = $user_meta->saveExperiences($request, $user_id);

            if ($experiences['type'] == 'success') {
                $json['type'] =  'success';
                $json['message'] = trans('lang.experience_updated');
                return Response::json($json, 200);
            } else {
                $json['type'] = 'error';
                $json['message'] = trans('lang.something_wrong');
                return Response::json($json, 203);
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return Response::json($json, 203);
        }
    }
    
    /**
     * Store educations in storage
     *
     * @param mixed $request get req attributes
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function storeEducations(Request $request)
    {
        $server = Helper::doctieIsDemoSiteAjax();
        if (!empty($server)) {
            $response['type'] = 'error';
            $response['message'] = $server->getData()->message;
            return $response;
        }
        $user_id = !empty($request['user_id']) ? $request['user_id'] : '';
        if (!empty($request) && !empty($user_id)) {
            $user_meta = new UserMeta();
            $educations = $user_meta->saveEducations($request, $user_id);
            if ($educations['type'] == "success") {
                $json['type'] = 'success';
                $json['message'] = trans('lang.education_updated');
                return Response::json($json, 200);
            } elseif ($educations == "error") {
                $json['type'] = 'error';
                $json['message'] = trans('lang.something_wrong');
                return Response::json($json, 203);
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return Response::json($json, 203);
        }
    }

    /**
     * Store awards downloads settings.
     *
     * @param \Illuminate\Http\Request $request request attributes
     *
     * @return \Illuminate\Http\Response
     */
    public function storeAwardDownloadSettings(Request $request)
    {
        $server = Helper::doctieIsDemoSiteAjax();
        if (!empty($server)) {
            $response['type'] = 'error';
            $response['message'] = $server->getData()->message;
            return $response;
        }
        $json = array();
        $user_id = !empty($request['user_id']) ? $request['user_id'] : '';
        if (!empty($request) && !empty($user_id)) {
            $user_meta = new UserMeta();
            $awards_downloads = $user_meta->storeAwardsDownloads($request, $user_id);
            if ($awards_downloads == 'success') {
                $json['type'] = 'success';
                $json['message'] = trans('lang.awards_downloads_saved');
                return Response::json($json, 200);
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.not_authorize');
            return Response::json($json, 203);
        }
    }

    /**
     * Store registration settings.
     *
     * @param \Illuminate\Http\Request $request request attributes
     *
     * @return \Illuminate\Http\Response
     */
    public function storeRegistrationSettings(Request $request)
    {
        $server = Helper::doctieIsDemoSiteAjax();
        if (!empty($server)) {
            $response['type'] = 'error';
            $response['message'] = $server->getData()->message;
            return $response;
        }
        $json = array();
        $this->validate(
            $request,
            [
                'registration_number' => 'required',
                'registration_document' => 'required',
            ]
        );
        $user_id = !empty($request['user_id']) ? $request['user_id'] : '';
        if (!empty($request) && !empty($user_id)) {
            $user_meta = new UserMeta();
            $registration = $user_meta->storeRegistration($request, $user_id);
            if ($registration == 'success') {
                $json['type'] = 'success';
                $json['message'] = trans('lang.registration_details_saved');
                return Response::json($json, 200);
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.not_authorize');
            return Response::json($json, 203);
        }
    }

    /**
     * Store User profile settings.
     *
     * @param \Illuminate\Http\Request $request   request attributes
     * @param string                   $role_type role_type
     *
     * @return \Illuminate\Http\Response
     */
    public function storeUserGallery(Request $request, $role_type)
    {
        $server = Helper::doctieIsDemoSiteAjax();
        if (!empty($server)) {
            $response['type'] = 'error';
            $response['message'] = $server->getData()->message;
            return $response;
        }
        $json = array();
        $user_id = !empty($request['user_id']) ? $request['user_id'] : '';
        if (!empty($request) && !empty($user_id)) {
            $user_meta = new UserMeta();
            $profile = $user_meta->storeGallery($request, $user_id);
            if ($profile == 'success') {
                $json['type'] = 'success';
                $json['message'] = trans('lang.gallery_updated_successfully');
                return Response::json($json, 200);
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.not_authorize');
            return Response::json($json, 203);
        }
    }

    /**
     * Get specialities
     *
     * @return \Illuminate\Http\Response
     */
    public function getSpecialities()
    {
        $json = array();
        $specialities = Speciality::all()->toArray();
        if (!empty($specialities)) {
            $json['type'] = 'success';
            $json['specialities'] = $specialities;
            return Response::json($json, 200);
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return Response::json($json, 203);
        }
    }

    /**
     * Get selected speciality service
     *
     * @param mixed $request request attributes
     * 
     * @return \Illuminate\Http\Response
     */
    public function getSpecialityService(Request $request)
    {
        $json = array();
        $speciality = Speciality::find($request['id']);
        $speciality->services->toArray();
        if (!empty($speciality)) {
            $json['type'] = 'success';
            $json['speciality'] = $speciality;
            return Response::json($json, 200);
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return Response::json($json, 203);
        }
    }

    /**
     * Store service data in storage
     *
     * @param string $request user-slug
     *
     * @access public
     *
     * @return View
     */
    public function storeDoctorServices(Request $request)
    {
        $json = array();
        $server = Helper::doctieIsDemoSiteAjax();
        if (!empty($server)) {
            $response['type'] = 'error';
            $response['message'] = $server->getData()->message;
            return $response;
        }
        $user_id = !empty($request['user_id']) ? $request['user_id'] : '';
        if (!empty($request)) {
            if (!empty($request['services'])) {
                $specialities  = array_pluck($request['services'], 'speciality');
                $temp_array  = array_unique($specialities);
                $duplicates = sizeof($temp_array) != sizeof($specialities);
                if ($duplicates == true) {
                    $json['type'] = 'error';
                    $json['message'] = trans('lang.duplicate_speciality');
                    return Response::json($json, 203);
                }
            }
            $user_meta = new UserMeta();
            $services = $user_meta->saveServices($request, $user_id);
            if ($services['type'] == 'speciality_required') {
                $json['type'] = 'error';
                $json['message'] = trans('lang.speciality_required');
                return Response::json($json, 203);
            } elseif ($services['type'] == 'service_required') {
                $json['type'] = 'error';
                $json['message'] = trans('lang.service_required');
                return Response::json($json, 203);
            } elseif ($services['type'] == "success") {
                $json['type'] = 'success';
                $json['message'] = trans('lang.speciality_updated');
                return Response::json($json, 200);
            } elseif ($services['type'] == "error") {
                $json['type'] = 'error';
                $json['message'] = $services['message'];
                return Response::json($json, 203);
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return Response::json($json, 203);
        }
    }

    /**
     * Get hospitals
     *
     * @return \Illuminate\Http\Response
     */
    public function getHospitals()
    {
        $json = array();
        $role = DB::table('roles')->select('name')->where('role_type', 'hospital')->first();
        $hospitals = User::role($role->name)->select(
            DB::raw("CONCAT(users.first_name,' ',users.last_name) AS name"),
            "id"
        )->get()->toArray();
        if (!empty($hospitals)) {
            $json['type'] = 'success';
            $json['hospitals'] = $hospitals;
            return Response::json($json, 200);
        } else {
            $json['type'] = 'error';
            return Response::json($json, 203);
        }
    }

    /**
     * Update appointment location services
     *
     * @param string                   $id      id
     * @param \Illuminate\Http\Request $request request attributes
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function updateLocationServices($id, Request $request)
    {
        $user_id = !empty($request['user_id']) ? $request['user_id'] : '';
        if (!empty($user_id)) {
            $location  = new Team();
            $update_slots = $location->updateAppointmentLocationServices($id, $request);
            if ($update_slots == 'success') {
                $json['type'] = 'success';
                $json['message'] = trans('lang.save_service');
                return Response::json($json, 200);
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something');
            return Response::json($json, 203);
        }
    }
}
