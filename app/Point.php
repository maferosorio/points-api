<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    protected $fillable = ['name','coordinate_x','coordinate_y'];

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
        //Based on Pythagoras' theorem
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
