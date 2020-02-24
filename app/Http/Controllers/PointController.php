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

        if ( $validator->fails() ) {
            return response()->json(['success' => false, 'message' => 'Point could not be saved.','errors' => $validator->errors()]);
        }
        
        $point = new Point();
        if( !$point->create( $request->all() ) ){
            return response()->json(['success' => false, 'message' => 'There was an error while saving the point.']);
        }
        return response()->json(['success' => true, 'message' => 'Point was saved succesfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Point  $point
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $point = new Point();
        $result = $point->read($id);
        if( !$result ){
            return response()->json(['success' => false, 'message' => 'Unable to find a point.'],404);
        }
        return response()->json(['success' => true, 'point' => array($result)]);
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
        //regex: evaluates if a coordinate value is between 1 and 4 integer digits and 1 or 2 decimals digits.
        $validator = Validator::make($request->all(), [
            'name' => ['required','string','max:20', Rule::unique('points')->ignore($id)],
            'coordinate_x' => 'required|numeric|regex:/^\d{1,4}(\.\d{1,2})?$/',
            'coordinate_y' => 'required|numeric|regex:/^\d{1,4}(\.\d{1,2})?$/'
        ]);

        if( $validator->fails() ) {
            return response()->json(['success' => false, 'message' => 'Point could not be updated.','errors' => $validator->errors()]);
        }
        
        $point = new Point();
        if( !$point->modify($request->all(), $id) ){
            return response()->json(['success' => false, 'message' => 'Point could not be updated.']);
        }
        return response()->json(['success' => true, 'message' => 'Point was updated succesfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Point  $point
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $point = new Point();
        if( !$point->remove($id) ){
            return response()->json(['success' => false, 'message' => 'Point could not be deleted.']);
        } 
        return response()->json(['success' => true, 'message' => 'Point was deleted succesfully.']);
    }

    public function getNearestPoints($id, $limit){

        $points = Point::all();

        dd($points);
    }
}
