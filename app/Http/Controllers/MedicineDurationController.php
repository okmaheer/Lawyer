<?php

namespace App\Http\Controllers;

use App\MedicineDuration;
use App\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Session;
use View;

class MedicineDurationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param mixed $medicine_duration get MedicineDuration model
     *
     * @return void
     */
    public function __construct(MedicineDuration $medicine_duration)
    {
        $this->medicine_duration = $medicine_duration;
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
            $medicine_durations = $this->medicine_duration::where('title', 'like', '%' . $keyword . '%')->paginate(7)->setPath('');
            $pagination = $medicine_durations->appends(
                array(
                    'keyword' => $request->get('keyword'),
                )
            );
        } else {
            $medicine_durations = $this->medicine_duration->paginate(10);
        }
        return View::make(
            'back-end.admin.prescription.medicine_duration.index', compact('medicine_durations')
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
        $this->medicine_duration->saveMedicineDuration($request);
        Session::flash('message', trans('lang.save_medicine_duration'));
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
            $medicine_duration = $this->medicine_duration::where('id', $id)->first();
            if (!empty($medicine_duration)) {
                return View::make(
                    'back-end.admin.prescription.medicine_duration.edit', compact('id', 'medicine_duration')
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
        $this->medicine_duration->updateMedicineDuration($request, $id);
        Session::flash('message', trans('lang.medicine_duration_updated'));
        return redirect()->route('medicine_durations');
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
            $medicine_duration = $this->medicine_duration::find($id);
            $medicine_duration->delete();
            $json['type'] = 'success';
            $json['message'] = trans('lang.medicine_duration_deleted');
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
            $this->medicine_duration::where("id", $id)->delete();
        }
        if (!empty($checked)) {
            $json['type'] = 'success';
            $json['message'] = trans('lang.selected_medicine_duration(s)_deleted');
            return $json;
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return $json;
        }
    }
}
