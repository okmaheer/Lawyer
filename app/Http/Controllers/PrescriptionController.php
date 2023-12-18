<?php

namespace App\Http\Controllers;

use DB;
use PDF;
use App\Helper;
use App\Prescription;
use App\Appointment;
use App\MartialStatus;
use App\ChildhoodIllness;
use App\Disease;
use App\LaboratoryTest;
use App\VitalSign;
use App\MedicineType;
use App\MedicineDuration;
use App\MedicineUsage;
use App\User;
use App\UserMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Session;
use View;

class PrescriptionController extends Controller
{
     /**
     * Defining scope of the variable
     *
     * @access public
     * @var    array $prescription
     */
    protected $prescription;

    /**
     * Create a new controller instance.
     *
     * @param mixed $prescription Prescription instance
     *
     * @return void
     */
    public function __construct(Prescription $prescription)
    {
        $this->prescription = $prescription;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index ($appointment_id)
    {
        $appointment = Appointment::findOrFail($appointment_id);
        $saved_prescription = Prescription::where('appointment_id', $appointment_id)->first();
        $update_mode = false;
        $saved_patient_detail = array();
        $saved_laboratory_test = array();
        $saved_vital_sign = array();
        $saved_childhood_illness = array();
        $saved_diseases = array();
        $saved_medications = array();
        if (!empty($saved_prescription)) {
            $update_mode = true;
            $saved_patient_detail = !empty($saved_prescription->patient_detail) ? unserialize($saved_prescription->patient_detail) : '';
            $saved_laboratory_test = !empty($saved_prescription->laboratory_test) ? $saved_prescription->laboratory_test->pluck('id')->toArray() : '';
            $saved_vital_sign = !empty($saved_prescription->vital_sign) ? $saved_prescription->vital_sign->pluck('pivot')->map->only('vital_sign_id', 'value')->toArray()  : '';
            $saved_childhood_illness = !empty($saved_prescription->childhood_illness) ? $saved_prescription->childhood_illness->pluck('id')->toArray() : '';
            $saved_diseases = !empty($saved_prescription->disease) ? $saved_prescription->disease->pluck('id')->toArray() : '';
            $saved_medications = !empty($saved_prescription->medication) ? $saved_prescription->medication->map->only('title', 'medicine_type_id', 'medicine_duration_id', 'medicine_usage_id', 'comment')->toArray() : '';
        }
        $appointment_user = User::findOrFail($appointment->patient_id);
        $appointment_user_mob_num = !empty($appointment_user->metaValue('personal_mob_num')) ? $appointment_user->metaValue('personal_mob_num')['meta_value'] : '';
        $appointment_user_name = Helper::getUserName($appointment->patient_id);
        $martial_statuses = MartialStatus::select('id', 'title', 'slug')->get()->toArray();
        $childhood_illnesses = ChildhoodIllness::select('id', 'title', 'slug')->get()->toArray();
        $diseases = Disease::select('id', 'title', 'slug')->get()->toArray();
        $laboratory_tests = LaboratoryTest::select('id', 'title', 'slug')->get()->toArray();
        $vital_signs = VitalSign::select('id', 'title', 'slug')->get()->toArray();
        $medicine_types = MedicineType::select('id', 'title', 'slug')->get()->toArray();
        $medicine_durations = MedicineDuration::select('id', 'title', 'slug')->get()->toArray();
        $medicine_usages = MedicineUsage::select('id', 'title', 'slug')->get()->toArray();
        $appointment_user_address = '';
        if (empty($appointment->relation)) {
            $appointment_user_address = UserMeta::where('user_id', $appointment->patient_id)->value('address');
        }
        $patient_name = !empty($appointment->patient_name) ? $appointment->patient_name : $appointment_user_name;
        $appointment_mob_num = !empty($appointment->metaValue('appointment_mob_num')) ? $appointment->metaValue('appointment_mob_num')['meta_value'] : $appointment_user_mob_num;
        return View::make (
            'back-end.doctors.appointments.prescription-form', 
            compact (
                'patient_name', 'appointment_user_address', 'martial_statuses', 'childhood_illnesses', 'appointment_id',
                'diseases', 'laboratory_tests', 'vital_signs', 'medicine_types', 'medicine_durations', 'medicine_usages', 'saved_prescription'
                ,'saved_patient_detail', 'saved_laboratory_test', 'saved_vital_sign', 'saved_childhood_illness', 'saved_diseases', 'saved_medications'
                , 'update_mode', 'appointment_mob_num'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $server_verification = Helper::doctieIsDemoSite();
        if (!empty($server_verification)) {
            Session::flash('error', $server_verification);
            return Redirect::back();
        }
        $json = array();
        if (!empty($request)) {
            $save_prescription = $this->prescription->storePrescription($request);
            if ($save_prescription['type'] == 'success') {
                $json['type'] = 'success';
                $json['message'] = trans('lang.prescription_saved');
                return $json;
            }
            // Session::flash('message', trans('lang.save_location'));
            // return Redirect::back();
        }
        // dd($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    
    /**
     * Generate PDF.
     *
     * @param date $year  year
     * @param date $month month
     * 
     * @return \Illuminate\Http\Response
     */
    public function generatePrescriptionPDF($appointment_id)
    {
        $saved_prescription = Prescription::where('appointment_id', $appointment_id)->first();
        $hospital_id = Appointment::where('id', $appointment_id)->value('hospital_id');
        $hospital = User::findOrFail($hospital_id);
        $hospital_meta = UserMeta::where('user_id', $hospital_id)->select('address', 'avatar')->get()->toArray();
        $hospital_detail['email'] = $hospital->email;
        $hospital_detail['location'] = $hospital->location()->value('title');
        $hospital_detail['name'] = Helper::getUserName($hospital_id);
        $hospital_detail['address'] = $hospital_meta[0]['address'];
        $saved_patient_detail = array();
        $saved_laboratory_test = array();
        $saved_vital_sign = array();
        $saved_childhood_illness = array();
        $saved_diseases = array();
        $saved_medications = array();
        $martial_status = array();
        if (!empty($saved_prescription)) {
            $update_mode = true;
            $martial_status = !empty($saved_prescription->martial_status) ? $saved_prescription->martial_status()->value('title') : '';
            $saved_patient_detail = !empty($saved_prescription->patient_detail) ? unserialize($saved_prescription->patient_detail) : '';
            $saved_laboratory_test = !empty($saved_prescription->laboratory_test) ? $saved_prescription->laboratory_test->pluck('title')->toArray() : '';
            $saved_vital_sign = !empty($saved_prescription->vital_sign) ? $saved_prescription->vital_sign->map->only(['title', 'pivot'])->toArray()  : '';
            $saved_childhood_illness = !empty($saved_prescription->childhood_illness) ? $saved_prescription->childhood_illness->pluck('title')->toArray() : '';
            $saved_diseases = !empty($saved_prescription->disease) ? $saved_prescription->disease->pluck('title')->toArray() : '';
            $saved_medications = !empty($saved_prescription->medication) ? $saved_prescription->medication->map->only('title', 'medicine_type_id', 'medicine_duration_id', 'medicine_usage_id', 'comment')->toArray() : '';
        } else {
            Session::flash('error', trans('lang.prescription_not_found'));
            return Redirect::back();
        }
        if (isset($_SERVER["SERVER_NAME"]) && $_SERVER["SERVER_NAME"] != '127.0.0.1') {
            $header_image = url('images/pdf/shape-02.png');
            $footer_image = url('images/pdf/shape-01.png');
            $hospital_detail['avatar'] = url(Helper::getImage('uploads/users/'.$hospital_id, $hospital_meta[0]['avatar'], '', 'user.jpg'));
        } else {
            $header_image = public_path('\images\pdf\shape-02.png');
            $footer_image = public_path('\images\pdf\shape-01.png');
            $hospital_detail['avatar'] = public_path(Helper::getImage('uploads/users/'.$hospital_id, $hospital_meta[0]['avatar'], '', 'user.jpg'));
        }
        $pdf = PDF::loadView('back-end.doctors.appointments.prescription-pdf',
                     compact ('saved_prescription', 'hospital_detail', 'saved_patient_detail', 'saved_laboratory_test', 'martial_status', 'saved_vital_sign',
                     'saved_childhood_illness', 'saved_diseases', 'saved_medications', 'footer_image', 'header_image'));
        return $pdf->download('prescription-' . $saved_prescription->id . '.pdf');
    }
}
