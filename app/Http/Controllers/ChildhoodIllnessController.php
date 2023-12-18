<?php

namespace App\Http\Controllers;

use App\ChildhoodIllness;
use App\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Session;
use View;

class ChildhoodIllnessController extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     * @param mixed $childhood_illness get ChildhoodIllness model
     *
     * @return void
     */
    public function __construct(ChildhoodIllness $childhood_illness)
    {
        $this->childhood_illness = $childhood_illness;
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
            $illnesses = $this->childhood_illness::where('title', 'like', '%' . $keyword . '%')->paginate(7)->setPath('');
            $pagination = $illnesses->appends(
                array(
                    'keyword' => $request->get('keyword'),
                )
            );
        } else {
            $illnesses = $this->childhood_illness->paginate(10);
        }
        return View::make(
            'back-end.admin.prescription.childhood-illness.index', compact('illnesses')
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
        $this->childhood_illness->saveChildhoodIllness($request);
        Session::flash('message', trans('lang.save_childhood_illness'));
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
            $illness = $this->childhood_illness::where('id', $id)->first();
            if (!empty($illness)) {
                return View::make(
                    'back-end.admin.prescription.childhood-illness.edit', compact('id', 'illness')
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
        $this->childhood_illness->updateChildhoodIllness($request, $id);
        Session::flash('message', trans('lang.childhood_illness_updated'));
        return redirect()->route('childhood_illness');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy (Request $request)
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
            $illness = $this->childhood_illness::find($id);
            $illness->delete();
            $json['type'] = 'success';
            $json['message'] = trans('lang.illness_deleted');
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
            $this->childhood_illness::where("id", $id)->delete();
        }
        if (!empty($checked)) {
            $json['type'] = 'success';
            $json['message'] = trans('lang.selected_diseases_deleted');
            return $json;
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return $json;
        }
    }
}
