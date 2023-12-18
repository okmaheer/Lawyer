<?php

/**
 * Class PatientController
 *
 * @category Doctry
 *
 * @package Doctry
 * @author  Amentotech <theamentotech@gmail.com>
 * @license http://www.amentotech.com Amentotech
 * @link    http://www.amentotech.com
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use App\Order;
use App\Helper;
use Carbon\Carbon;
use DB;
use App\SiteManagement;
use App\Prescription;
use Illuminate\Support\Facades\Redirect;

/**
 * Class PatientController
 *
 */
class PatientController extends Controller
{
    /**
     * Show appoinement listing.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAppointments($appointment_id = '')
    {
        if (Auth::user()) {
            $new_appointment = !empty($appointment_id) ? DB::table('appointments')->select('appointment_date')->where('id', $appointment_id)->first() : '';
            $user = User::find(Auth::user()->id);
            $appointments = $user->appointments;
            $appointment_date = !empty($new_appointment) ? $new_appointment->appointment_date : '';
            if (file_exists(resource_path('views/extend/back-end/patients/appointments/index.blade.php'))) {
                return view(
                    'extend.back-end.patients.appointments.index',
                    compact('appointments', 'appointment_date')
                );
            } else {
                return view(
                    'back-end.patients.appointments.index',
                    compact('appointments', 'appointment_date')
                );
            }
        } else {
            return Redirect::to('/404');
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
    public function getAppointments(Request $request)
    {
        $json =  array();
        $list = array();
        $booking_settings = SiteManagement::getMetaValue('booking_settings');
        $online_payment = !empty($booking_settings['enable_booking']) ? $booking_settings['enable_booking'] : '';
        $payment_settings = SiteManagement::getMetaValue('payment_settings');
        $currency = !empty($payment_settings) && !empty($payment_settings['currency']) ? Helper::currencyList($payment_settings['currency']) : array();
        if (Auth::user()) {
            $user = User::find(Auth::user()->id);
            $counter = 0;
            $orders = DB::table('appointments')->where('patient_id', Auth::user()->id);
            if ($online_payment == 'true') {
                $orders = $orders->where('status', 'accepted');
            }
            if (!empty($request['date'])) {
                $orders = $orders->where('appointment_date', $request['date']);
            }
            $orders = $orders->orderBy('id', 'desc')->get();
            $list['count'] = $orders->count();
            if (!empty($orders)) {
                foreach ($orders as $key => $appointment) {
                    if (!empty($appointment)) {
                        $doctor = !empty($appointment->user_id) ? User::find($appointment->user_id) : '';
                        $services = !empty($appointment->services) ? Helper::getUnserializeData($appointment->services) : '';
                        $list['appointment'][$counter]['user_id'] = !empty($appointment->patient_id) ? $appointment->patient_id : '';
                        $list['appointment'][$counter][$appointment->patient_id]['doctor_id'] = !empty($appointment->user_id) ? $appointment->user_id : '';
                        $list['appointment'][$counter][$appointment->patient_id]['id'] = $appointment->id;
                        $list['appointment'][$counter][$appointment->patient_id]['status'] = $appointment->status;
                        $list['appointment'][$counter][$appointment->patient_id]['user_name'] = !empty($doctor) && !empty($appointment->user_id)
                            ? Helper::getUserName($appointment->user_id) : '';
                        $list['appointment'][$counter][$appointment->patient_id]['patient_name'] = !empty($appointment->patient_name)
                            ? $appointment->patient_name : '';
                        $list['appointment'][$counter][$appointment->patient_id]['relation'] = !empty($appointment->relation)
                            ? $appointment->relation : '';
                        $list['appointment'][$counter][$appointment->patient_id]['other_relation'] = !empty($appointment->other_relation)
                            ? $appointment->other_relation : '';
                        $list['appointment'][$counter][$appointment->patient_id]['user_image'] = !empty($doctor)
                            ? asset(Helper::getImage('uploads/users/' . $doctor->id, $doctor->profile->avatar, 'small-', 'user.jpg'))
                            : '';
                        $list['appointment'][$counter][$appointment->patient_id]['user_verify'] = !empty($doctor) ? $doctor->user_verified : 0;
                        $list['appointment'][$counter][$appointment->patient_id]['is_prescription']= Prescription::where('appointment_id', $appointment->id)->first() == null ? false : true;
                        $list['appointment'][$counter][$appointment->patient_id]['user_location'] = !empty($doctor->location) && $doctor->location->count() > 0 ? $doctor->location->title : '';
                        $list['appointment'][$counter][$appointment->patient_id]['user_type'] = !empty($doctor) ? $doctor->getRoleNames()->first() : '';
                        $list['appointment'][$counter][$appointment->patient_id]['hospital'] = !empty($appointment->hospital_id) ? Helper::getUserName($appointment->hospital_id) : '';
                        $date = $appointment->appointment_date;
                        $patient_date = new Carbon($appointment->appointment_date);
                        $patient_appointment_date = explode("-", $date);
                        $list['appointment'][$counter][$appointment->patient_id]['patient_appointment_date'] = !empty($patient_appointment_date) ? $patient_appointment_date[2] : '';
                        $list['appointment'][$counter][$appointment->patient_id]['patient_appointment_month'] = !empty($patient_date) ? $patient_date->format('F') : '';
                        $list['appointment'][$counter][$appointment->patient_id]['appointment_date'] = !empty($appointment->appointment_date) ? $appointment->appointment_date : '';
                        $list['appointment'][$counter][$appointment->patient_id]['appointment_time'] = !empty($appointment->appointment_time) ? $appointment->appointment_time : '';
                        $list['appointment'][$counter][$appointment->patient_id]['comments'] = !empty($appointment->comments) ? $appointment->comments : '';
                        $list['appointment'][$counter][$appointment->patient_id]['charges'] = !empty($appointment->charges) ? $currency['symbol'].$appointment->charges : '';
                        if (!empty($services)) {
                            foreach ($services as $service_key => $service) {
                                if (!empty($service['service'])) {
                                    $speciality = Helper::getSpecialityByID($service['speciality']);
                                    $list['appointment'][$counter][$appointment->patient_id]['appointment_services'][$service_key]['speciality'] = !empty($speciality) ? $speciality->title : '';
                                    foreach ($service['service'] as $speciality_service_key => $speciality_service) {
                                        $service = Helper::getServiceByID($speciality_service);
                                        if (!empty($service)) {
                                            $list['appointment'][$counter][$appointment->patient_id]['appointment_services'][$service_key]['services'][$speciality_service_key]['title'] = !empty($service) ? $service->title : '';
                                        }
                                    }
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
}