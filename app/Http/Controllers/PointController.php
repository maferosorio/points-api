<?php

namespace App\Http\Controllers;

use App\Point;
use Illuminate\Http\Request;
use App\Http\Requests\PointRequest;

class PointController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PointRequest $request)
    {
        if( Point::create( $request->all() ) ){
            return response()->json(['success' => true, 'message' => 'Point was saved succesfully.']);
        }
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
        $result = $point->findOrFail($id, ['id','name', 'coordinate_x','coordinate_y'])->toArray();
        if( $result ){
            return response()->json(['success' => true, 'point' => array( $point->formatCoordinates($result) )]);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Point  $point
     * @return \Illuminate\Http\Response
     */
    public function update(PointRequest $request, $id)
    {
        $point = Point::findOrFail($id);
        if( $point->update($request->all()) ){
            return response()->json(['success' => true, 'message' => 'Point was updated succesfully.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Point  $point
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $point = Point::findOrFail($id);
        if( $point->delete() ){
            return response()->json(['success' => true, 'message' => 'Point was deleted succesfully.']);
        } 
    }

    public function getNearestPoints($id, $limit = null)
    {   
        $point = new Point();
        $selectedPoint = $point->findOrFail($id, ['id','name', 'coordinate_x','coordinate_y'])->toArray();
    
        if( ($limit != null && $limit <= 0) || $point->isDouble($limit) ){
            return response()->json(['success' => false, 'message' => 'Enter a valid limit value.']);
        }

        $pointsToCompare = $point->select(['id','name', 'coordinate_x','coordinate_y'])->where('id', '!=' , $id)->get()->toArray();
        if( !$pointsToCompare ){
            return response()->json(['success' => false, 'message' => 'There are no nearest points.']);
        }
        
        $selectedPoint = $point->formatCoordinates($selectedPoint);
        $distances = $point->calculateDistances($selectedPoint, $pointsToCompare, $limit);
        $nearestPoints = $point->getNeighborsData($distances, $pointsToCompare);

        if( count($nearestPoints) > 0 ){
            return response()->json(['success' => true, 'limit' => (int) $limit, 'point' => array($selectedPoint), 'nearest_points' => $nearestPoints]);
        }
    }
}