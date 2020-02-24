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

        //$points = Point::all();
        $point = new Point();
        $selectedPoint = $point->read($id);
        if( !$selectedPoint ){
            return response()->json(['success' => false, 'message' => 'Unable to find a point.'],404);
        }
        if($limit <= 0){
            return response()->json(['success' => false, 'message' => 'Enter a valid limit value.']);
        }
        echo "<pre>"; //print_r($selectedPoint);
        $pointsToCompare = $point->getPointsToCompare($id);
        if( !$selectedPoint ){
            return response()->json(['success' => false, 'message' => 'There are no more points.']);
        }

        $distances = $this->calculateDistances($selectedPoint, $pointsToCompare, $limit);
        $nearestPoints = $this->getNeighborsData($distances, $pointsToCompare);
        
        if( count($nearestPoints) > 0 ){
            return response()->json(['success' => true, 'data' => $nearestPoints]);
        }
        return response()->json(['success' => false, 'message' => 'There was an error trying to get the nearest points.']);
    }

    public function calculateDistances($selectedPoint, $pointsToCompare, $limit){

        $distances = array();
        $exponent = 2;
        //$source = array();
        foreach ($pointsToCompare as $key => $point) {
            //echo " selectedPoint: x: ".$selectedPoint['coordinate_x']." y: ".$selectedPoint['coordinate_y'];
            //echo " point: x: ".$point['coordinate_x']." y: ".$point['coordinate_y'];
            $distances[$point['id']] = sqrt( pow($selectedPoint['coordinate_x'] - $point['coordinate_x'], $exponent) + pow($selectedPoint['coordinate_y'] - $point['coordinate_y'], $exponent) );
            //echo " distances:"; print_r($distances);
        }
        $distance = $distances;
        echo "<pre>"; //print_r($distances);
        asort($distances);
        echo "asort: ";
        print_r( $distances );
        $pointsPerQuantity = array_slice($distances, 0, $limit, true);
        echo "<pre>"; print_r(array_slice($distances, 0, $limit, true));
        return $pointsPerQuantity;
    }

    public function getNeighborsData($distances, $points){
        
        $limitedPoints = array();
        $index = 0;
        echo "<pre>"; 
        foreach ($distances as $key => $distance) {
            foreach ($points as $point) {
                if($key == $point['id']) {
                    $limitedPoints[$index] = $point;
                    $index++;
                }
            }
        }
        print_r( $limitedPoints );
        return $limitedPoints;
    }


}
