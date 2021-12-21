<?php

namespace App\Http\Controllers;

use App\Models\SchoolDivision;
use Illuminate\Http\Request;
use Response;
use DB;


class SchoolDivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SchoolDivision  $schoolDivision
     * @return \Illuminate\Http\Response
     */
    public function show(SchoolDivision $schoolDivision)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SchoolDivision  $schoolDivision
     * @return \Illuminate\Http\Response
     */
    public function edit(SchoolDivision $schoolDivision)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SchoolDivision  $schoolDivision
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SchoolDivision $schoolDivision)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SchoolDivision  $schoolDivision
     * @return \Illuminate\Http\Response
     */
    public function destroy(SchoolDivision $schoolDivision)
    {
        //
    }


    public function district_loading_ajax_hit(Request $request){
        $districts = DB::table('school_districts')->where('division_id', $request->division_id)->get();

       
        $pro_select_box  = '';
        $pro_select_box .= "<option value='0'>Please Select District</option>";

        if(count($districts) > 0){
            foreach ($districts as $key => $value) {
                $pro_select_box .= '<option value="'.$value->id.'">'.$value->name.'</option>';
            }
        }

        return Response::json($pro_select_box);
    }

    public function post_loading_ajax_hit(Request $request){
        $posts = DB::table('school_posts')->where('dist_id', $request->district_id)->get();

       
        $pro_select_box  = '';
        $pro_select_box .= "<option value='0'>Please Select Post</option>";

        if(count($posts) > 0){
            foreach ($posts as $key => $value) {
                $pro_select_box .= '<option value="'.$value->id.'">'.$value->name.'</option>';
            }
        }

        return Response::json($pro_select_box);
    }
}
