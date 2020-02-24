<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    protected $fillable = ['name','coordinate_x','coordinate_y'];

    public function create($data){

    	$point = Point::firstOrCreate($data);
    	if($point){ return true; } else { return false; }
    	/*$point = new Point;
        $point->name = $request->name;
        $point->coordinate_x = $request->coordinate_x;
        $point->coordinate_y = $request->coordinate_y;*/
    }

    public function modify($data, $point){
    	
    	$point->name = $data['name'];
        $point->coordinate_x = $data['coordinate_x'];
        $point->coordinate_y = $data['coordinate_y'];
        if(!$point->save()){ return false; }
        return $point;
    }

    public function verifyIfExists($id){

    	$point = Point::find($id);
        if(!$point){ return false; }
        return $point;
    }
}
