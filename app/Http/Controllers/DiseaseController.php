<?php

namespace App\Http\Controllers;

use App\Disease;
use App\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Session;
use View;

class DiseaseController extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     * @param mixed $disease get Disease model
     *
     * @return void
     */
    public function __construct(Disease $disease)
    {
        $this->disease = $disease;
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
            $diseases = $this->disease::where('title', 'like', '%' . $keyword . '%')->paginate(7)->setPath('');
            $pagination = $diseases->appends(
                array(
                    'keyword' => $request->get('keyword'),
                )
            );
        } else {
            $diseases = $this->disease->paginate(10);
        }
        return View::make(
            'back-end.admin.prescription.diseases.index', compact('diseases')
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $this->disease->saveDisease($request);
        Session::flash('message', trans('lang.save_disease'));
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
            $disease = $this->disease::where('id', $id)->first();
            if (!empty($disease)) {
                return View::make(
                    'back-end.admin.prescription.diseases.edit', compact('id', 'disease')
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
        $this->disease->updateDisease($request, $id);
        Session::flash('message', trans('lang.disease_updated'));
        return redirect()->route('diseases');
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
            $disease = $this->disease::find($id);
            $disease->delete();
            $json['type'] = 'success';
            $json['message'] = trans('lang.disease_deleted');
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
            $this->disease::where("id", $id)->delete();
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
