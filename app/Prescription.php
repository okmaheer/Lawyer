<?php

namespace App;

use App\Helper;
use Auth;
use App\MartialStatus;
use App\Medication;
use App\MedicineUsage;
use App\MedicineDuration;
use App\MedicineType;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{

    protected $table = 'prescriptions';
    
    /**
     * Get the user that belongs to prescription.
     *
     * @return relation
     */
    public function user ()
    {
        return $this->belongsTo('App\User');
    }

    /**
     *  Get the martial status that belongs to prescription.
     *
     * @return relation
     */
    public function martial_status ()
    {
        return $this->belongsTo('App\MartialStatus');
    }

    /**
     * Get the childhood illnesses that  belongs to prescription.
     *
     * @return relation
     */
    public function childhood_illness ()
    {
        return $this->belongsToMany('App\ChildhoodIllness', 'childhood_illness_prescription')->withPivot('prescription_id');
    }

    /**
     * Get the diseases that belongs to prescription.
     *
     * @return relation
     */
    public function disease ()
    {
        return $this->belongsToMany('App\Disease', 'disease_prescription')->withPivot('prescription_id');;
    }

    /**
     * Get the laboratary tests that belongs to prescription.
     *
     * @return relation
     */
    public function laboratory_test ()
    {
        return $this->belongsToMany('App\LaboratoryTest', 'prescription_laboratory_test')->withPivot('prescription_id');;
    }
    
    /**
     * Get the vital signs that belongs to prescription.
     *
     * @return relation
     */
    public function vital_sign ()
    {
        return $this->belongsToMany('App\VitalSign', 'prescription_vital_sign')->withPivot('prescription_id', 'value');
    }
    
    /**
     * Get the medicines that  belongs to prescription.
     *
     * @return relation
     */
    public function medication ()
    {
        return $this->hasMany('App\Medication');
    }

    /**
     * For saving locations in Database
     *
     * @param mixed $request get file
     *
     * @return \Illuminate\Http\Response
     */
    public function storePrescription($request) {
        $json = array();
        if (!empty($request)) {
            $appointment = Appointment::findOrFail($request->appointment_id);
            if ($this::where('appointment_id', $request->appointment_id)->exists()) {
                $prescription = $this::where('appointment_id', $request->appointment_id)->first();
            } else {
                $prescription = $this;
            }
            $prescription->user_id = $appointment->patient_id;
            $prescription->patient_detail = !empty($request['patient_detail']) ? serialize($request['patient_detail']) : '';
            $prescription->history = !empty($request['medical_history']) ? $request['medical_history'] : '';
            $prescription->history = !empty($request['medical_history']) ? $request['medical_history'] : '';
            $prescription->doctor_id = Auth::user()->id;
            $prescription->appointment_id = $request->appointment_id;
            if (!empty($request['marital_status'])) {
                $marital_status = MartialStatus::find($request['marital_status']);
                $prescription->martial_status()->associate($marital_status);
            }
            $prescription->save();

            $childhood_illnesses = $request['childhood_illness'];
            $prescription->childhood_illness()->detach();
            if (!empty($childhood_illnesses)) {
                foreach ($childhood_illnesses as $item) {
                    $this->childhood_illness()->attach($item, ['prescription_id' => $prescription->id]);
                }
            }

            $diseases = $request['diseases'];
            $prescription->disease()->detach();
            if (!empty($diseases)) {
                foreach ($diseases as $item) {
                    $this->disease()->attach($item, ['prescription_id' => $prescription->id]);
                }
            }

            $laboratary_tests = $request['laboratary_tests'];
            $prescription->laboratory_test()->detach();
            if (!empty($laboratary_tests)) {
                foreach ($laboratary_tests as $item) {
                    $this->laboratory_test()->attach($item, ['prescription_id' => $prescription->id]);
                }
            }

            $common_issues = $request['common_issues'];
            $prescription->vital_sign()->detach();
            if (!empty($common_issues)) {
                foreach ($common_issues as $item) {
                    $this->vital_sign()->attach($item['vital_sign'], ['prescription_id' => $prescription->id, 'value' => $item['value']]);
                }
            }

            $medications = $request['medications'];
            $prescription->medication()->delete();
            if (!empty($medications)) {
                foreach ($medications as $medi) {
                    $medication = new Medication;
                    $medication->title = $medi['medicine_name'];
                    $medication->comment = $medi['comment'];
                    if (!empty($medi['medicine_type'])) {
                        $medicine_type = MedicineType::find($medi['medicine_type']);
                        $medication->medicine_type()->associate($medicine_type);
                    }
                    if (!empty($medi['medicine_duration'])) {
                        $medicine_duration = MedicineDuration::find($medi['medicine_duration']);
                        $medication->medicine_duration()->associate($medicine_duration);
                    }
                    if (!empty($medi['medicine_usage'])) {
                        $medicine_usage = MedicineUsage::find($medi['medicine_usage']);
                        $medication->medicine_usage()->associate($medicine_usage);
                    }
                    $medication->prescription()->associate($prescription);
                    $medication->save();
                }
            }
            $json['type'] = 'success';
            return $json;
        }
    }
}
