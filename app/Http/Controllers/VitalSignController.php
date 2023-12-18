<?php

namespace App\Http\Controllers;

use App\VitalSign;
use App\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Session;
use View;

class VitalSignController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param mixed $vital_sign get VitalSign model
     *
     * @return void
     */
    public function __construct(VitalSign $vital_sign)
    {
        $this->vital_sign = $vital_sign;
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
            $vital_signs = $this->vital_sign::where('title', 'like', '%' . $keyword . '%')->paginate(7)->setPath('');
            $pagination = $vital_signs->appends(
                array(
                    'keyword' => $request->get('keyword'),
                )
            );
        } else {
            $vital_signs = $this->vital_sign->paginate(10);
        }
        return View::make(
            'back-end.admin.prescription.vital_sign.index', compact('vital_signs')
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
        $this->vital_sign->saveVitalSign($request);
        Session::flash('message', trans('lang.save_vital_sign'));
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
            $vital_sign = $this->vital_sign::where('id', $id)->first();
            if (!empty($vital_sign)) {
                return View::make(
                    'back-end.admin.prescription.vital_sign.edit', compact('id', 'vital_sign')
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
        $this->vital_sign->updateVitalSign($request, $id);
        Session::flash('message', trans('lang.vital_sign_updated'));
        return redirect()->route('vital_signs');
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
            $vital_sign = $this->vital_sign::find($id);
            $vital_sign->delete();
            $json['type'] = 'success';
            $json['message'] = trans('lang.vital_sign_deleted');
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
            $this->vital_sign::where("id", $id)->delete();
        }
        if (!empty($checked)) {
            $json['type'] = 'success';
            $json['message'] = trans('lang.selected_vital_sign(s)_deleted');
            return $json;
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return $json;
        }
    }
}
