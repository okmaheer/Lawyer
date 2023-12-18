<?php

namespace App\Http\Controllers;

use App\MedicineUsage;
use App\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Session;
use View;

class MedicineUsageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param mixed $medicine_usage get MedicineUsage model
     *
     * @return void
     */
    public function __construct(MedicineUsage $medicine_usage)
    {
        $this->medicine_usage = $medicine_usage;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index (Request $request)
    {
        if (!empty($_GET['keyword'])) {
            $keyword = $_GET['keyword'];
            $medicine_usages = $this->medicine_usage::where('title', 'like', '%' . $keyword . '%')->paginate(7)->setPath('');
            $pagination = $medicine_usages->appends(
                array(
                    'keyword' => $request->get('keyword'),
                )
            );
        } else {
            $medicine_usages = $this->medicine_usage->paginate(10);
        }
        return View::make(
            'back-end.admin.prescription.medicine_usage.index', compact('medicine_usages')
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
        $this->validate(
            $request, [
                'title' => 'required',
            ]
        );
        $this->medicine_usage->saveMedicineUsage($request);
        Session::flash('message', trans('lang.save_medicine_usage'));
        return Redirect::back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!empty($id)) {
            $medicine_usage = $this->medicine_usage::where('id', $id)->first();
            if (!empty($medicine_usage)) {
                return View::make(
                    'back-end.admin.prescription.medicine_usage.edit', compact('id', 'medicine_usage')
                );
            }
        } else {
            return Redirect::to('/404');
        }
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
        $server_verification = Helper::doctieIsDemoSite();
        if (!empty($server_verification)) {
            Session::flash('error', $server_verification);
            return Redirect::back();
        }
        $this->validate(
            $request, [
                'title' => 'required',
            ]
        );
        $this->medicine_usage->updateMedicineUsage($request, $id);
        Session::flash('message', trans('lang.medicine_usage_updated'));
        return redirect()->route('medicine_usages');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $server = Helper::doctieIsDemoSiteAjax();
        if (!empty($server)) {
            $json['type'] = 'error';
            $json['message'] = $server->getData()->message;
            return $json;
        }
        $json = array();
        $id = $request['id'];
        if (!empty($id)) {
            $medicine_usage = $this->medicine_usage::find($id);
            $medicine_usage->delete();
            $json['type'] = 'success';
            $json['message'] = trans('lang.medicine_usage_deleted');
            return $json;
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return $json;
        }
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param mixed $request request attributes
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteSelected(Request $request)
    {
        $server = Helper::doctieIsDemoSiteAjax();
        if (!empty($server)) {
            $json['type'] = 'error';
            $json['message'] = $server->getData()->message;
            return $json;
        }
        $json = array();
        $checked = $request['ids'];
        foreach ($checked as $id) {
            $this->medicine_usage::where("id", $id)->delete();
        }
        if (!empty($checked)) {
            $json['type'] = 'success';
            $json['message'] = trans('lang.selected_medicine_usage(s)_deleted');
            return $json;
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return $json;
        }
    }
}
