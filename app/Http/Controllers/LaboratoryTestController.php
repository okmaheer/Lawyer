<?php

namespace App\Http\Controllers;

use App\LaboratoryTest;
use App\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Session;
use View;

class LaboratoryTestController extends Controller
{
   /**
     * Create a new controller instance.
     *
     * @param mixed $laboratory_test get LaboratoryTest model
     *
     * @return void
     */
    public function __construct(LaboratoryTest $laboratory_test)
    {
        $this->laboratory_test = $laboratory_test;
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
            $laboratory_tests = $this->laboratory_test::where('title', 'like', '%' . $keyword . '%')->paginate(7)->setPath('');
            $pagination = $laboratory_tests->appends(
                array(
                    'keyword' => $request->get('keyword'),
                )
            );
        } else {
            $laboratory_tests = $this->laboratory_test->paginate(10);
        }
        return View::make(
            'back-end.admin.prescription.laboratory_test.index', compact('laboratory_tests')
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
        $this->laboratory_test->saveLaboratoryTest($request);
        Session::flash('message', trans('lang.save_laboratory_test'));
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
            $laboratory_test = $this->laboratory_test::where('id', $id)->first();
            if (!empty($laboratory_test)) {
                return View::make(
                    'back-end.admin.prescription.laboratory_test.edit', compact('id', 'laboratory_test')
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
        $this->laboratory_test->updateLaboratoryTest($request, $id);
        Session::flash('message', trans('lang.laboratory_test_updated'));
        return redirect()->route('laboratory_tests');
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
            $laboratory_test = $this->laboratory_test::find($id);
            $laboratory_test->delete();
            $json['type'] = 'success';
            $json['message'] = trans('lang.laboratory_test_deleted');
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
            $this->laboratory_test::where("id", $id)->delete();
        }
        if (!empty($checked)) {
            $json['type'] = 'success';
            $json['message'] = trans('lang.selected_laboratory_test(s)_deleted');
            return $json;
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return $json;
        }
    }
}
