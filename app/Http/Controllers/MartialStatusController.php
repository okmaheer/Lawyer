<?php

namespace App\Http\Controllers;

use App\MartialStatus;
use App\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Session;
use View;


class MartialStatusController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param mixed $martial_status get MartialStatus model
     *
     * @return void
     */
    public function __construct(MartialStatus $martial_status)
    {
        $this->martial_status = $martial_status;
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
            $martial_statuses = $this->martial_status::where('title', 'like', '%' . $keyword . '%')->paginate(7)->setPath('');
            $pagination = $martial_statuses->appends(
                array(
                    'keyword' => $request->get('keyword'),
                )
            );
        } else {
            $martial_statuses = $this->martial_status->paginate(10);
        }
        return View::make(
            'back-end.admin.prescription.martial_status.index', compact('martial_statuses')
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
        $this->martial_status->saveMartialStatus($request);
        Session::flash('message', trans('lang.save_martial_status'));
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
            $martial_status = $this->martial_status::where('id', $id)->first();
            if (!empty($martial_status)) {
                return View::make(
                    'back-end.admin.prescription.martial_status.edit', compact('id', 'martial_status')
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
        $this->martial_status->updateMartialStatus($request, $id);
        Session::flash('message', trans('lang.martial_status_updated'));
        return redirect()->route('martial_status');
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
            $martial_status = $this->martial_status::find($id);
            $martial_status->delete();
            $json['type'] = 'success';
            $json['message'] = trans('lang.martial_status_deleted');
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
            $this->martial_status::where("id", $id)->delete();
        }
        if (!empty($checked)) {
            $json['type'] = 'success';
            $json['message'] = trans('lang.selected_martial_status(es)_deleted');
            return $json;
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return $json;
        }
    }
}
