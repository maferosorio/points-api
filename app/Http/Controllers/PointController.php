<?php

namespace App\Http\Controllers;

use App\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests;

class PointController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        $point = new Point;

        $point->name = $request->name;
        $point->coordinate_x = $request->coordinate_x;
        $point->coordinate_y = $request->coordinate_y;

        $point->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Point  $point
     * @return \Illuminate\Http\Response
     */
    public function show(Point $point)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Point  $point
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //dd($id);
        //dd($request->input('name')); 
        //dd($request->all()); 
        $pointToUpdate = Point::findOrFail($id);
        $pointToUpdate->name = $request->name;
        $pointToUpdate->coordinate_x = $request->coordinate_x;
        $pointToUpdate->coordinate_y = $request->coordinate_y;
        $pointToUpdate->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Point  $point
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //dd($id);
        //$flight = App\Flight::find(1);
        $pointToDelete = Point::findOrFail($id);
        $pointToDelete->delete();
    }

    public function getNearestPoints(Point $point, $limit){

    }
}
