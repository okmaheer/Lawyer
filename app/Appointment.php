<?php

/**
 * Class User.
 *
 * @category Doctry
 *
 * @package Doctry
 * @author  Amentotech <theamentotech@gmail.com>
 * @license http://www.amentotech.com Amentotech
 * @link    http://www.amentotech.com
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Serializable;
use Auth;

/**
 * Class Appointment
 *
 */
class Appointment extends Model
{
    /**
     * Get the doctor that owns the appointment.
     * 
     * @return relation
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * appointments can have multiple meta
     *
     * @return relation
     */
    public function meta()
    {
        return $this->morphMany('App\Meta', 'metable');
    }
    
    /**
     * appointments can have multiple meta
     *
     * @return relation
     */
    public function metaValue($meta_key)
    {
        return $this->morphMany('App\Meta', 'metable')->where('meta_key', $meta_key)->select('id', 'meta_value')->first();
    }


    /**
     * Store patient appointment data
     *
     * @param \Illuminate\Http\Request $request request
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function submitAppointment($request, $type)
    {
        $json = array();
        if (!empty($request)) {
            $user_id = Auth::user()->id;
            $user = User::findOrFail($request['user_id']);
            $this->user()->associate($user);
            $this->hospital_id = intval($request['hospital']);
            $this->patient_id = intval($user_id);
            if ($request['patient'] == 'someone') {
                $this->patient_name = filter_var($request['patient_name'], FILTER_SANITIZE_STRING);
                if ($request['relation'] == 'other' && !empty($request['other_relation'])) {
                    $this->other_relation = $request['other_relation'];
                }
                $this->relation = filter_var($request['relation'], FILTER_SANITIZE_STRING);
            }
            $this->services = !empty($request['speciality']) ? serialize($request['speciality']) : null;
            $this->comments = !empty($request['comments']) ? filter_var($request['comments'], FILTER_SANITIZE_STRING) : null;
            $this->appointment_time = $request['time'];
            $this->appointment_date = $request['date'];
            $this->charges = intval($request['total_charges']);
            $this->status = 'pending';
            $this->paid = 'pending';
            $this->type = $type;
            $this->save();
            $json['last_id'] = $this->id;
            $json['type'] = 'success';
            return $json;
        }
    }

    /**
     * Store patient appointment data
     *
     * @param \Illuminate\Http\Request $request request
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function submitAppointmentByDoctor($request, $patient_id, $type)
    {
        $json = array();
        if (!empty($request)) {
            $doc_id = Auth::user()->id;
            $doctor = User::findOrFail($doc_id);
            $this->user()->associate($doctor);
            $this->hospital_id = intval($request['hospital']);
            $this->patient_id = intval($patient_id);
            if ($request['visiting_user'] == 'someelse') {
                $this->patient_name = filter_var($request['other_patient_name'], FILTER_SANITIZE_STRING);
                if ($request['relation'] == 'other' && !empty($request['other_relation'])) {
                    $this->other_relation = $request['other_relation'];
                }
                $this->relation = filter_var($request['relation'], FILTER_SANITIZE_STRING);
            }
            $this->services = !empty($request['speciality']) ? serialize($request['speciality']) : null;
            $this->comments = !empty($request['comments']) ? filter_var($request['comments'], FILTER_SANITIZE_STRING) : null;
            $this->appointment_time = $request['appointment']['time'];
            $this->appointment_date = $request['appointment']['date'];
            $this->charges = intval($request['total_charges']);
            $this->status = 'accepted';
            $this->paid = 'pending';
            $this->type = 'offline';
            $this->save();
            if (!empty($request['phone'])) {
                $meta = new Meta();
                $meta->meta_key = 'appointment_mob_num';
                $meta->meta_value = $request['phone'];
                $this->meta()->save($meta);
            }
            $json['last_id'] = $this->id;
            $json['type'] = 'success';
            return $json;
        }
    }

    /**
     * Store patient appointment data
     *
     * @param \Illuminate\Http\Request $request request
     *
     * @access public
     *
     * @return \Illuminate\Http\Response
     */
    public function submitAppointmentAPI($request)
    {
        $json = array();
        if (!empty($request)) {
            $patient_id = $request['patient_id'];
            $doctor_id = $request['doctor_id'];
            $user = User::findOrFail($doctor_id);
            $this->user()->associate($user);
            $this->hospital_id = intval($request['hospital']);
            $this->patient_id = intval($patient_id);
            if ($request['patient'] == 'someone') {
                $this->patient_name = filter_var($request['patient_name'], FILTER_SANITIZE_STRING);
                $this->relation = filter_var($request['relation'], FILTER_SANITIZE_STRING);
            }
            $this->services = !empty($request['speciality']) ? serialize($request['speciality']) : null;
            $this->comments = !empty($request['comments']) ? filter_var($request['comments'], FILTER_SANITIZE_STRING) : null;
            $this->appointment_time = $request['time'];
            $this->appointment_date = $request['date'];
            $this->charges = intval($request['total_charges']);
            $this->status = 'pending';
            $this->save();
            $json['last_id'] = $this->id;
            $json['type'] = 'success';
            return $json;
        }
    }
}
