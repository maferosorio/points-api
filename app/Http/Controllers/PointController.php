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
        //Regex: evaluates if a coordinate value has 1-4 integer digits (positive or negatives ones) and 0-2 decimals digits.
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:points|max:20',
            'coordinate_x' => 'required|numeric|regex:/^-?\d{1,4}(\.\d{1,2})?$/',
            'coordinate_y' => 'required|numeric|regex:/^-?\d{1,4}(\.\d{1,2})?$/'
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
        return response()->json(['success' => true, 'point' => array( $this->formatCoordinates($result) )]);
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
        //Regex: evaluates if a coordinate value has 1-4 integer digits (positive or negatives ones) and 0-2 decimals digits. Name field must be unique.
        $validator = Validator::make($request->all(), [
            'name' => ['required','string','max:20', Rule::unique('points')->ignore($id)],
            'coordinate_x' => 'required|numeric|regex:/^-?\d{1,4}(\.\d{1,2})?$/',
            'coordinate_y' => 'required|numeric|regex:/^-?\d{1,4}(\.\d{1,2})?$/'
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

    public function getNearestPoints($id, $limit = null)
    {
        $point = new Point();
        $selectedPoint = $point->read($id);
        if( !$selectedPoint ){
            return response()->json(['success' => false, 'message' => 'Unable to find a point.'],404);
        } 
        if( ($limit != null && $limit <= 0) || $this->isDouble($limit) ){
            return response()->json(['success' => false, 'message' => 'Enter a valid limit value.'],404);
        }

        $pointsToCompare = $point->getPointsToCompare($id);
        if( !$pointsToCompare ){
            return response()->json(['success' => false, 'message' => 'There are no nearest points.']);
        }
        
        $selectedPoint = $this->formatCoordinates($selectedPoint);
        $distances = $this->calculateDistances($selectedPoint, $pointsToCompare, $limit);
        $nearestPoints = $this->getNeighborsData($distances, $pointsToCompare);

        if( count($nearestPoints) > 0 ){
            return response()->json(['success' => true, 'limit' => (int) $limit, 'point' => array($selectedPoint), 'nearest_points' => $nearestPoints]);
        }
        return response()->json(['success' => false, 'message' => 'There was an error trying to get the nearest points.']);
    }

    function isDouble($limit) {
        return is_float($limit) || ( is_numeric($limit) && ( (float) $limit != (int) $limit ) );
    }

    function formatCoordinates($point){
        $decimalPrecision = 2;
        $point['coordinate_x'] = round($point['coordinate_x'], $decimalPrecision);
        $point['coordinate_y'] = round($point['coordinate_y'], $decimalPrecision);
        return $point;
    }

    public function calculateDistances($selectedPoint, $pointsToCompare, $limit)
    {
        $distances = array(); $exponent = 2; $offset = 0;
        //Based in the Pythagoras theorem
        foreach ($pointsToCompare as $key => $point) {
            $distances[$point['id']] = sqrt( pow($selectedPoint['coordinate_x'] - $point['coordinate_x'], $exponent) + pow($selectedPoint['coordinate_y'] - $point['coordinate_y'], $exponent) );
        }
        asort($distances);
        //array_slice: get array elements based in the Limit value
        return ($limit != null) ? array_slice($distances, $offset, $limit, true) : $distances;
    }

    public function getNeighborsData($distances, $points)
    {
        $limitedPoints = array(); $index = 0; $decimalPrecision = 2;

        foreach ($distances as $key => $distance) {
            foreach ($points as $point) {
                if( $key == $point['id'] ) {
                    $limitedPoints[$index] = $point;
                    $limitedPoints[$index]['coordinate_x'] = round($point['coordinate_x'], $decimalPrecision);
                    $limitedPoints[$index]['coordinate_y'] = round($point['coordinate_y'], $decimalPrecision);
                    $limitedPoints[$index]['distance'] = round($distance, $decimalPrecision);
                    $index++;
                }
            }
        }
        return $limitedPoints;
    }
}