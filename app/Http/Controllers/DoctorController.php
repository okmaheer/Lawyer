<?php

/**
 * Class DoctorController
 *
 * @category Doctry
 *
 * @package Doctry
 * @author  Amentotech <theamentotech@gmail.com>
 * @license http://www.amentotech.com Amentotech
 * @link    http://www.amentotech.com
 */

namespace App\Http\Controllers;

use App\Helper;
use App\UserMeta;
use App\User;
use Auth;
use Illuminate\Http\Request;
use App\EmailTemplate;
use View;
use App\Team;
use App\Mail\HospitalEmailMailable;
use Illuminate\Support\Facades\Mail;
use function Opis\Closure\unserialize;
use App\Speciality;
use DB;
use App\Package;
use App\Payout;
use App\SiteManagement;
use Carbon\Carbon;
use App\Mail\GeneralEmailMailable;
use App\Appointment;
use App\Prescription;
use Illuminate\Support\Facades\Redirect;

/**
 * Class DoctorController
 *
 */
class DoctorController extends Controller
{
    /**
     * Defining scope of the variable
     *
     * @access protected
     * @var    array $doctor
     */
    protected $doctor;

    /**
     * Create a new controller instance.
     *
     * @param instance $doctor instance
     *
     * @return void
     */
    public function __construct(UserMeta $doctor)
    {
        $this->doctor = $doctor;
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
        if (Auth::user()) {
            $awards_downloads = $this->doctor->storeAwardsDownloads($request, Auth::user()->id);
            if ($awards_downloads == 'success') {
                $json['type'] = 'success';
                $json['progressing'] = trans('lang.saving');
                $json['message'] = trans('lang.awards_downloads_saved');
                return $json;
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.not_authorize');
            return $json;
        }
    }

    /**
     * Get doctor awards.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDoctorAwards()
    {
        $json = array();
        if (Auth::user()) {
            $awards = User::find(Auth::user()->id)->Profile->awards;
            $awards_list = !empty($awards) ? Helper::getUnserializeData($awards) : array();
            if (!empty($awards)) {
                $json['type'] = 'success';
                $json['awards'] = $awards_list;
                return $json;
            } else {
                $json['type'] = 'error';
                return $json;
            }
        } else {
            $json['type'] = 'error';
            return $json;
        }
    }

    /**
     * Get doctor's booking numbers.
     *
     * @param Request $request
     * @return void
     */
    public function getBookingNumbers () {
        $json = array();
        if (Auth::user()) {
            $user = Auth::user();
            $booking_numbers = !empty($user->metaValue('booking_nums')) ? unserialize($user->metaValue('booking_nums')['meta_value']) : '';
            if (!empty($booking_numbers)) {
                $json['type'] = 'success';
                $json['booking_numbers'] = $booking_numbers;
                return $json;
            } else {
                $json['type'] = 'error';
                return $json;
            }
        } else {
            $json['type'] = 'error';
            return $json;
        }
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
        if (!empty($request)) {
            $experiences = $this->doctor->saveExperiences($request, Auth::user()->id);
            if ($experiences['type'] == "success") {
                $json['type'] = 'success';
                $json['progressing'] = trans('lang.saving');
                $json['message'] = trans('lang.experience_updated');
                return $json;
            } elseif ($experiences == "error") {
                $json['type'] = 'error';
                return $json;
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return $json;
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
        if (!empty($request)) {
            $experiences = $this->doctor->saveEducations($request, Auth::user()->id);
            if ($experiences['type'] == "success") {
                $json['type'] = 'success';
                $json['progressing'] = trans('lang.saving');
                $json['message'] = trans('lang.education_updated');
                return $json;
            } elseif ($experiences == "error") {
                $json['type'] = 'error';
                return $json;
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return $json;
        }
    }

    /**
     * Store appointment location form
     *
     * @param mixed $request get req attributes
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function addLocation(Request $request)
    {
        if (Auth::user()) {
            $location_type = array();
            $booking_settings = SiteManagement::getMetaValue('booking_settings');
            $user = User::find(Auth::user()->id);
            $doctor_specialities = !empty($user->profile->services) ? Helper::getUnserializeData($user->profile->services) : array();
            $intervals = Helper::getAppointmentIntervals();
            $durations = Helper::getAppointmentDuration();
            $spaces = Helper::getAppointmentSpaces();
            $days = Helper::getAppointmentDays();
            $doctor_info = Team::where('doctor_id', $user->id)->paginate(4);
            $location_meta = !empty(Auth::user()->metaValue('default_location')) ? Auth::user()->metaValue('default_location')->toArray() : '';
            $default_location = !empty($location_meta) && !empty($location_meta['meta_value']) ? unserialize($location_meta['meta_value']) : '';
            if (!empty($booking_settings) && !empty($booking_settings['enable_booking_private_clinic']) && $booking_settings['enable_booking_private_clinic'] == 'true') {
                $location_type = Helper::getAppointmentLocationType();
                $check_private_clinic = DB::table('teams')->where('user_id', Auth::user()->id)->where('doctor_id', Auth::user()->id)->count();
                if ($check_private_clinic > 0) {
                    unset($location_type['private_clinic']);
                }
            }
            if (file_exists(resource_path('views/extend/back-end/doctors/appointment_locations/create/index.blade.php'))) {
                return View::make(
                    'extend.back-end.doctors.appointment_locations.create.index',
                    compact('intervals', 'durations', 'doctor_specialities', 'spaces', 'days', 'doctor_info', 'location_type', 'default_location')
                );
            } else {
                return View::make(
                    'back-end.doctors.appointment_locations.create.index',
                    compact('intervals', 'durations', 'doctor_specialities', 'spaces', 'days', 'doctor_info', 'location_type', 'default_location')
                );
            }
        } else {
            return Redirect::to('/404');
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
        if (Auth::user()) {
            $server = Helper::doctieIsDemoSiteAjax();
            if (!empty($server)) {
                $response['type'] = 'error';
                $response['message'] = $server->getData()->message;
                return $response;
            }
            if (!empty($request)) {
                $hospital_location = Team::where('user_id', $request['hospital_id'])->where('doctor_id', Auth::user()->id)->count();
                if ($hospital_location > 0) {
                    $response['type'] = 'error';
                    $response['message'] = trans('lang.hospital_already_selected');
                    return $response;
                }
                $hospital_name = User::where('id',$request['hospital_id'])->value('first_name');
                $hospital_available_days = $this->doctor->where('user_id', $request['hospital_id'])->value('available_days');
                $hospital_available_days = !empty($hospital_available_days) ? unserialize($hospital_available_days) : '';

                if (!empty($hospital_available_days) && !empty(array_diff($request['slots']['appointment_days'],$hospital_available_days))) {
                    $response['type'] = 'error';
                    $response['message'] = trans('lang.hospital_not_avail', ['name' => $hospital_name]);
                    return $response;
                }
                $location  = new Team();
                $services = $location->saveAppointmentLocation($request);
                if ($services == "success") {
                    if (!empty($request['location_type']) && $request['location_type'] == 'hospital' && !empty($request['hospital_id'])) { 
                        //send email to hospital
                        if (!empty(config('mail.username')) && !empty(config('mail.password'))) {
                            $hospital = User::findOrFail($request['hospital_id']);
                            $slots = $request['slots'];
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
                                $template_data = EmailTemplate::getEmailTemplateByID($req_template->id);
                                $email_params['doctor_name'] = Helper::getUserName(Auth::user()->id);
                                $email_params['hospital_name']  = Helper::getUserName($hospital->id);
                                $email_params['doctor_link']  = url('profile/' . Auth::user()->slug);
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
                    } 
                    $json['type'] = 'success';
                    $json['progressing'] = trans('lang.saving');
                    $json['message'] = trans('lang.apt_location_saved');
                    return $json;
                } else {
                    $json['type'] = 'error';
                    return $json;
                }
            } else {
                $json['type'] = 'error';
                $json['message'] = trans('lang.something_wrong');
                return $json;
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.not_authorise');
            return $json;
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
    public function editLocation($id)
    {
        if (Auth::user() && !empty($id)) {
            $user = User::find(Auth::user()->id);
            $location = Team::find($id);
            $slots = unserialize($location->slots);
            $days = Helper::getAppointmentDays();
            $intervals = Helper::getAppointmentIntervals();
            $durations = Helper::getAppointmentDuration();
            $spaces = Helper::getAppointmentSpaces();
            $appointment_days = !empty($slots['days']) ? $slots['days'] : array();
            $doctor_specialities = !empty($user->profile->services) ? Helper::getUnserializeData($user->profile->services) : array();
            $location_services = Helper::getLocationServices($slots['services'], 'services');
            $service_price = !empty($slots['services']['price']) ? $slots['services']['price'] : '';
            $location_meta = !empty(Auth::user()->metaValue('default_location')) ? Auth::user()->metaValue('default_location')->toArray() : '';
            $default_location = !empty($location_meta) && !empty($location_meta['meta_value']) ? unserialize($location_meta['meta_value']) : '';
            if (file_exists(resource_path('views/extend/back-end/doctors/appointment_locations/edit/index.blade.php'))) {
                return View::make(
                    'extend.back-end.doctors.appointment_locations.edit.index',
                    compact(
                        'id',
                        'location',
                        'slots',
                        'days',
                        'intervals',
                        'durations',
                        'spaces',
                        'doctor_specialities',
                        'service_price',
                        'appointment_days',
                        'location_services',
                        'default_location'
                    )
                );
            } else {
                return View::make(
                    'back-end.doctors.appointment_locations.edit.index',
                    compact(
                        'id',
                        'location',
                        'slots',
                        'days',
                        'intervals',
                        'durations',
                        'spaces',
                        'doctor_specialities',
                        'service_price',
                        'appointment_days',
                        'location_services',
                        'default_location'
                    )
                );
            }
        } else {
            return Redirect::to('/404');
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
    public function updateSlots($id, Request $request)
    {
        if (Auth::user()) {
            $location  = new Team();
            $update_slots = $location->updateAppointmentSlots($id, $request);
            if ($update_slots == 'success') {
                $json['type'] = 'success';
                $json['progressing'] = trans('lang.saving');
                $json['message'] = trans('lang.slot_updated');
                return $json;
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something');
            return $json;
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
        if (Auth::user()) {
            $location  = new Team();
            $update_slots = $location->updateAppointmentLocationServices($id, $request);
            if ($update_slots == 'success') {
                $json['type'] = 'success';
                $json['progressing'] = trans('lang.saving');
                $json['message'] = trans('lang.save_service');
                return $json;
            } else {
                $json['type'] = 'error';
                $json['message'] = trans('lang.something_wrong');
                return $json;
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return $json;
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
    public function storeSelectedDaySlots($id, Request $request)
    {
        if (Auth::user()) {
            $location  = new Team();
            $update_slots = $location->saveSelectedDaySlots($id, $request);
            if ($update_slots == 'success') {
                $json['type'] = 'success';
                $json['progressing'] = trans('lang.saving');
                $json['message'] = trans('lang.slot_updated');
                return $json;
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something');
            return $json;
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
    public function deleteSlots($slot, $day, $id)
    {
        if (Auth::user()) {
            $team  = new Team();
            $team_location = $team->deleteAppointmentSlots($slot, $day, $id);
            if ($team_location == 'success') {
                $json['type'] = 'success';
                $json['progressing'] = trans('lang.saving');
                $json['message'] = trans('lang.slot_deleted');
                return $json;
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something');
            return $json;
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
        if (Auth::user()) {
            $team  = new Team();
            $team_location = $team->deleteAllAppointmentSlots($day, $id);
            if ($team_location == 'success') {
                $json['type'] = 'success';
                $json['progressing'] = trans('lang.saving');
                $json['message'] = trans('lang.slot_deleted');
                return $json;
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something');
            return $json;
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
        if (Auth::user()) {
            $hospital = Team::select('slots')->where('doctor_id', $request['doctor_id'])->where('user_id', $request['hospital_id'])->first();
            if (!empty($hospital)) {
                $requested_date = Carbon::create($request['date']);
                $date = new Carbon();
                $today = $date->now();
                $slots = Helper::getUnserializeData($hospital->slots);
                $requested_day_slots = !empty($slots[$request['day']]) ? $slots[$request['day']]['slots'] : array();
                if (!empty($requested_day_slots)) {
                    $counter = 0;
                    foreach ($requested_day_slots as $key => $slot) {
                        $time = explode('-', $key);
                        $list[$counter]['start_time'] = $time[0];
                        $bocked_appointments = DB::table('appointments')->where('appointment_time', $time[0])->where('appointment_date', $request['date'])->count();
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
                $json['type'] = 'success';
                $json['slots'] = $list;
                return $json;
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something');
            return $json;
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
        if (Auth::user()) {
            $team_info = Team::where('user_id', $request['hospital'])->where('doctor_id', $request['doctor'])->first();
            if (!empty($team_info)) {
                $slots = Helper::getUnserializeData($team_info['slots']);
                $list = array();
                $list['price'] = !empty($slots['consultation_fee']) ? $slots['consultation_fee'] : 0;
                if (!empty($slots['services'])) {
                    $count = 0;
                    foreach ($slots['services']['speciality'] as $key => $specialities) {
                        if (!empty($specialities['speciality_services'])) {
                            $speciality = Speciality::find($specialities['speciality_id']);
                            if (!empty($speciality)) {
                                $list['specialities'][$count]['speciality_id'] = $speciality->id;
                                $list['specialities'][$count]['speciality_title'] = $speciality->title;
                                $list['specialities'][$count]['speciality_image'] = asset(Helper::getImage('uploads/specialities', $speciality->image, '', 'default-speciality.png'));
                                foreach ($specialities['speciality_services'] as $service_key => $services) {
                                    $service = Helper::getServiceByID($services['service']);
                                    if (!empty($service)) {
                                        $list['specialities'][$count]['services'][$service_key]['service_id'] = $service->id;
                                        $list['specialities'][$count]['services'][$service_key]['service_title'] = $service->title;
                                        $list['specialities'][$count]['services'][$service_key]['service_price'] = $services['price'];
                                    }
                                }
                            }
                            $count++;
                        }
                    }
                    $json['services'] = !empty($list) ? $list : '';
                    $json['type'] = 'success';
                    return $json;
                } else {
                    $json['type'] = 'error';
                    $json['message'] = trans('lang.service_not_found');
                    return $json;
                }
            } else {
                $json['type'] = 'error';
                $json['message'] = trans('lang.something');
                return $json;
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something');
            return $json;
        }
    }

    /**
     * Show Doctor appoinement listing.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAppointments()
    {
        if (Auth::user()) {
            if (Helper::getAuthRoleType() == 'admin') {
                $appointments = Appointment::all();
                $user_type = 'admin';
            } else {
                $user = User::find(Auth::user()->id);
                $appointments = $user->appointments;
                $user_type = 'doctor';
            }
            if (file_exists(resource_path('views/extend/back-end/doctors/appointments/index.blade.php'))) {
                return view(
                    'extend.back-end.doctor.appointments.index',
                    compact('appointments', 'user_type')
                );
            } else {
                return view(
                    'back-end.doctors.appointments.index',
                    compact('appointments', 'user_type')
                );
            }
        } else {
            return Redirect::to('/404');
        }
    }

    /**
     * Get doctor appoinetments for specific date
     *
     * @param \Illuminate\Http\Request $request request attributes
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function getAppointments(Request $request)
    {
        $json =  array();
        $list = array();
        $booking_settings = SiteManagement::getMetaValue('booking_settings');
        $online_payment = !empty($booking_settings['enable_booking']) ? $booking_settings['enable_booking'] : '';
        $payment_settings = SiteManagement::getMetaValue('payment_settings');
        $currency = !empty($payment_settings) && !empty($payment_settings['currency']) ? Helper::currencyList($payment_settings['currency']) : array();
        if (Auth::user()) {
            if (Helper::getAuthRoleType() == 'admin') {
                $appointments = Appointment::select('*')->orderBy('id', 'desc')->get();
                $current_user = 'admin';
            } else {
                $user = User::find(Auth::user()->id);
                $appointments = $user->appointments()->orderBy('id', 'desc')->get();
                $current_user = 'doctor';
            }
            if ($online_payment == 'true') {
                $appointments = $appointments->where('status', 'accepted');
            }
            if (!empty($request['date'])) {
                $appointments = $appointments->where('appointment_date', $request['date']);
            }
            $counter = 0;
            if ($appointments->count() > 0) {
                $list['count'] = $appointments->count();
                foreach ($appointments as $key => $appointment) {
                    $patient = !empty($appointment->patient_id) ? User::find($appointment->patient_id) : '';
                    $services = !empty($appointment->services) ? Helper::getUnserializeData($appointment->services) : '';
                    $list['appointment'][$counter]['user_id'] = !empty($patient) ? $appointment->patient_id : '';
                    $list['appointment'][$counter][$appointment->patient_id]['id'] = $appointment->id;
                    $list['appointment'][$counter][$appointment->patient_id]['status'] = $appointment->status;
                    $list['appointment'][$counter][$appointment->patient_id]['user_name'] = !empty($patient) && !empty($appointment->patient_id)
                        ? Helper::getUserName($appointment->patient_id) : '';
                    $list['appointment'][$counter][$appointment->patient_id]['patient_name'] = !empty($appointment->patient_name)
                        ? $appointment->patient_name : '';
                    $list['appointment'][$counter][$appointment->patient_id]['relation'] = !empty($appointment->relation)
                        ? $appointment->relation : '';
                    $list['appointment'][$counter][$appointment->patient_id]['other_relation'] = !empty($appointment->other_relation)
                        ? $appointment->other_relation : '';
                    $list['appointment'][$counter][$appointment->patient_id]['user_image'] = !empty($patient)
                        ? asset(Helper::getImage('uploads/users/' . $appointment->patient_id, $patient->profile->avatar, 'small-', 'user.jpg'))
                        : '';
                    $list['appointment'][$counter][$appointment->patient_id]['user_verify'] = !empty($patient) ? $patient->user_verified : 0;
                    $list['appointment'][$counter][$appointment->patient_id]['is_prescription']= Prescription::where('appointment_id', $appointment->id)->first() == null ? false : true;
                    $list['appointment'][$counter][$appointment->patient_id]['user_location'] = !empty($patient->location) && $patient->location->count() > 0 ? $patient->location->title : '';
                    $list['appointment'][$counter][$appointment->patient_id]['user_type'] = !empty($patient) ? $patient->getRoleNames()->first() : '';
                    $list['appointment'][$counter][$appointment->patient_id]['hospital'] = !empty($appointment->hospital_id) ? Helper::getUserName($appointment->hospital_id) : '';
                    $date = $appointment->appointment_date;
                    $patient_date = new Carbon($appointment->appointment_date);
                    $patient_appointment_date = explode("-", $date);
                    $list['appointment'][$counter][$appointment->patient_id]['patient_appointment_date'] = !empty($patient_appointment_date) ? $patient_appointment_date[2] : '';
                    $list['appointment'][$counter][$appointment->patient_id]['patient_appointment_month'] = !empty($patient_date) ? $patient_date->format('F') : '';
                    $list['appointment'][$counter][$appointment->patient_id]['appointment_date'] = !empty($date) ? $date : '';
                    $list['appointment'][$counter][$appointment->patient_id]['appointment_time'] = !empty($appointment->appointment_time) ? $appointment->appointment_time : '';
                    $list['appointment'][$counter][$appointment->patient_id]['comments'] = !empty($appointment->comments) ? $appointment->comments : '';
                    $list['appointment'][$counter][$appointment->patient_id]['hospital_id'] = !empty($appointment->hospital_id) ? $appointment->hospital_id : '';
                    $list['appointment'][$counter][$appointment->patient_id]['user_id'] = !empty($appointment->user_id) ? $appointment->user_id : '';
                    $list['appointment'][$counter][$appointment->patient_id]['doctor'] = !empty($appointment->user_id) ? Helper::getUserName($appointment->user_id) : '';
                    $list['appointment'][$counter][$appointment->patient_id]['charges'] = !empty($appointment->charges) ? $currency['symbol'].$appointment->charges : '';
                    if (!empty($services) && is_array($services)) {
                        foreach ($services as $service_key => $service) {
                            if (!empty($service['service']) && is_array($service['service'])) {
                                $speciality = Helper::getSpecialityByID($service['speciality']);
                                $list['appointment'][$counter][$appointment->patient_id]['appointment_services'][$service_key]['speciality'] = !empty($speciality) ? $speciality->title : '';
                                foreach ($service['service'] as $speciality_service_key => $speciality_service) {
                                    $service = Helper::getServiceByID($speciality_service);
                                    $list['appointment'][$counter][$appointment->patient_id]['appointment_services'][$service_key]['services'][$speciality_service_key]['title'] = !empty($service) ? $service->title : '';
                                }
                            }
                        }
                    }
                    $counter++;
                }
                $json['appointments'] = $list;
                $json['type'] = 'success';
                return $json;
            } else {
                $list['count'] = $appointments->count();
                $list['appointment'] = array();
                $json['appointments'] = $list;
                return $json;
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something');
            return $json;
        }
    }

    /**
     * Checkout Page.
     *
     * @param \Illuminate\Http\Request $id ID
     *
     * @return \Illuminate\Http\Response
     */
    public function checkout($id)
    {
        if (!empty($id) && Auth::user()) {
            $package = Package::find($id);
            $package_options = Helper::getPackageOptions(Auth::user()->getRoleNames());
            $payment_settings = SiteManagement::getMetaValue('payment_settings');
            $payment_gateway = !empty($payment_settings) && !empty($payment_settings['payment_method']) ? $payment_settings['payment_method'] : array();
            $symbol = !empty($payment_settings) && !empty($payment_settings['currency']) ? Helper::currencyList($payment_settings['currency']) : array();
            if (file_exists(resource_path('views/extend/back-end/doctors/package/checkout.blade.php'))) {
                return view::make('extend.back-end.doctors.package.checkout', compact('package', 'package_options', 'payment_gateway', 'symbol'));
            } else {
                return view::make('back-end.doctors.package.checkout', compact('package', 'package_options', 'payment_gateway', 'symbol'));
            }
        } else {
            return Redirect::to('/404');
        }
    }

    /**
     * Print Thankyou.
     *
     * @return \Illuminate\Http\Response
     */
    public function thankyou()
    {
        if (Auth::user()) {
            echo trans('lang.thankyou');
        } else {
            return Redirect::to('/404');
        }
    }

    /**
     * Get doctor payouts.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPayouts()
    {
        if (Auth::user()) {
            $payouts =  Payout::where('user_id', Auth::user()->id)->paginate(10);
            if (file_exists(resource_path('views/extend/back-end/doctors/payouts.blade.php'))) {
                return view(
                    'extend.back-end.doctors.payouts.payouts',
                    compact('payouts')
                );
            } else {
                return view(
                    'back-end.doctors.payouts.payouts',
                    compact('payouts')
                );
            }
        } else {
            return Redirect::to('/404');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function payoutSettings()
    {
        if (Auth::user()) {
            $payrols = Helper::getPayoutsList();
            $user = User::find(Auth::user()->id);
            $payout_settings = $user->profile->count() > 0 ? Helper::getUnserializeData($user->profile->payout_settings) : '';
            if (file_exists(resource_path('views/extend/back-end/doctors/payouts/payout_settings.blade.php'))) {
                return view(
                    'extend.back-end.doctors.payouts.payout_settings',
                    compact('payrols', 'payout_settings')
                );
            } else {
                return view(
                    'back-end.doctors.payouts.payout_settings',
                    compact('payrols', 'payout_settings')
                );
            }
        } else {
            return Redirect::to('/404');
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
    public function acceptAppointment(Request $request)
    {
        $json = array();
        if (Auth::user()) {
            if (!empty($request['appointment']) && !empty($request['patient_id'])) {
                $appointment = $request['appointment'];
                $patient = User::find($request['patient_id']);
                $hospital = User::findOrFail($appointment['hospital_id']);
                $doctor = User::findOrFail($appointment['user_id']);
                DB::table('appointments')->where('id', $appointment['id'])->update(['status' => 'accepted']);
                if (!empty(config('mail.username')) && !empty(config('mail.password'))) {
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
                    }
                }
                $json['type'] = 'success';
                $json['message'] = trans('lang.appointment_updated');
                $json['status'] = trans('lang.accepted');
                return $json;
            } else {
                $json['type'] = 'error';
                $json['message'] = trans('lang.something');
                return $json;
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something');
            return $json;
        }
    }

    /**
     * Reject patient appointment
     *
     * @param \Illuminate\Http\Request $request request attributes
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function declineAppointment(Request $request)
    {
        $json = array();
        if (Auth::user()) {
            if (!empty($request['appointment']) && !empty($request['patient_id'])) {
                $appointment = $request['appointment'];
                $patient = User::find($request['patient_id']);
                $doctor = User::findOrFail($appointment['user_id']);
                DB::table('appointments')->where('id', $appointment['id'])->update(['status' => 'rejected']);
                if (!empty(config('mail.username')) && !empty(config('mail.password'))) {
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
                $json['type'] = 'success';
                $json['message'] = trans('lang.appointment_updated');
                $json['status'] = trans('lang.rejected');
                return $json;
            } else {
                $json['type'] = 'error';
                $json['message'] = trans('lang.something');
                return $json;
            }
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something');
            return $json;
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
    public function getRequiredAppointmentData () {
        $json = array();
        $doc_id = Auth::user()->id;
        $doctor_hospitals = Team::getDoctorHospitals($doc_id);
        $currency   = SiteManagement::getMetaValue('payment_settings');
        $symbol = !empty($currency) && !empty($currency['currency']) ? Helper::currencyList($currency['currency']) : 'symbol';
        $json['type'] = 'success';
        $json['doctor_hospitals'] = $doctor_hospitals;
        $json['doc_id'] = $doc_id;
        $json['symbol'] = !empty($symbol['symbol']) ? $symbol['symbol'] : '$';
        return $json;
    }

    /**
     * Store patient appointment
     *
     * @param \Illuminate\Http\Request $request request attributes
     * 
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function submitAppointmentByDoctor(Request $request)
    {
        $json = array();
        $booking_type= '';
        if (Auth::user()) {
            $user = User::where('email', $request['email'])->first();
            if (empty($user)) {
                $this->validate(
                    $request, [
                        'first_name' => 'required',
                        'last_name' =>  'required',
                        'email' => 'required|email',
                    ]
                );
                $user = new User ();
                $user->first_name = $request['first_name'];
                $user->last_name = $request['last_name'];
                $user->slug = filter_var($request['first_name'], FILTER_SANITIZE_STRING) . '-' .
                filter_var($request['last_name'], FILTER_SANITIZE_STRING);
                $user->password = bcrypt('google');
                $user->email = $request['email'];
                $role = DB::table('roles')->select('name')->where('role_type', 'regular')->first();
                $user->assignRole($role->name);
                $user->save();
            } 
        
            $patient_id = !empty($user) && !empty($user->id) ? $user->id : '';
            $appointment = new Appointment();
            $patient_appointment = $appointment->submitAppointmentByDoctor($request, $patient_id, $booking_type);
            if (!empty(config('mail.username')) && !empty(config('mail.password'))) {
                $email_params = array();
                if (!empty($user)) {
                    $user_appt_req_template = DB::table('email_types')->select('id')
                        ->where('email_type', 'user_email_appointment_request')->get()->first();
                    if (!empty($user_appt_req_template->id)) {
                        $appointment = $request['appointment'];
                        $template_data = EmailTemplate::getEmailTemplateByID($user_appt_req_template->id);
                        $email_params['user_name'] = Helper::getUserName($user->id);
                        $email_params['hospital_name']  = Helper::getUserName($request['hospital']);
                        $email_params['appointment_date']  = Carbon::parse($appointment['date'])->format('d M, Y');
                        $email_params['description']  = $request['comments'];
                        Mail::to($user->email)
                            ->send(
                                new GeneralEmailMailable(
                                    'user_email_appointment_request',
                                    $template_data,
                                    $email_params
                                )
                            );
                    }
                }
            }
            if ($patient_appointment['type'] == 'success') {
                $json['appointment_id'] = $patient_appointment['last_id'];
                $json['type'] = 'success';
                return $json;
            }
        } else {
            $json['type'] = 'error';
            return $json;
        }
    }

}
