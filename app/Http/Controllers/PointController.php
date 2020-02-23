<?php

namespace App\Http\Controllers;

use App\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
        //regex: evaluates if a coordinate value is between 1 and 4 integer digits and 1 or 2 decimals digits.
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:points|max:20',
            'coordinate_x' => 'required|numeric|regex:/^\d{1,4}(\.\d{1,2})?$/',
            'coordinate_y' => 'required|numeric|regex:/^\d{1,4}(\.\d{1,2})?$/'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Point could not be saved.','errors' => $validator->errors()]);
        }
        $point = new Point;
        $point->name = $request->name;
        $point->coordinate_x = $request->coordinate_x;
        $point->coordinate_y = $request->coordinate_y;
        
        if(!$point->save()){
            return response()->json(['message' => 'There was an error while saving the point.']);
        }
        return response()->json(['message' => 'Point was saved succesfully.']);
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
        $pointToUpdate = Point::find($id);
        if(!$pointToUpdate){
            return response()->json(['message' => 'Unable to find the point.'],404);
        }
        //regex: evaluates if a coordinate value is between 1 and 4 integer digits and 1 or 2 decimals digits.
        $validator = Validator::make($request->all(), [
            'name' => ['required','string','max:20', Rule::unique('points')->ignore($id)],
            'coordinate_x' => 'required|numeric|regex:/^\d{1,4}(\.\d{1,2})?$/',
            'coordinate_y' => 'required|numeric|regex:/^\d{1,4}(\.\d{1,2})?$/'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Point could not be updated.','errors' => $validator->errors()]);
        }
        $pointToUpdate->name = $request->name;
        $pointToUpdate->coordinate_x = $request->coordinate_x;
        $pointToUpdate->coordinate_y = $request->coordinate_y;
        
        if(!$pointToUpdate->save()){
            return response()->json(['message' => 'There was an error while updating the point.']);
        }
        return response()->json(['message' => 'Point was updated succesfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Point  $point
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $point = Point::find($id);
        if(!$point){
            return response()->json(['message' => 'Unable to find the point.'],404);
        }

        if(!$point->delete()){
            return response()->json(['message' => 'There was an error while deleting the point.']);
        } 
        return response()->json(['message' => 'Point was deleted succesfully.']);
    }

    public function getNearestPoints($id, $limit){

        $points = Point::all();

        dd($points);
    }
}
