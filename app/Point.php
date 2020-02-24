<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    protected $fillable = ['name','coordinate_x','coordinate_y'];

    public function create($data){

    	$point = Point::firstOrCreate($data);
    	if(!$point){ return false; } 
    	return true;
    	/*$point = new Point;
        $point->name = $request->name;
        $point->coordinate_x = $request->coordinate_x;
        $point->coordinate_y = $request->coordinate_y;*/
    }

    public function modify($data, $id){

    	$point = Point::find($id);
        if(!$point){ return false; }

    	$point->name = $data['name'];
        $point->coordinate_x = $data['coordinate_x'];
        $point->coordinate_y = $data['coordinate_y'];
        if( !$point->save() ){ return false; }
        return true;
    }

    public function remove($id){

    	$point = Point::find($id);
    	if(!$point){ return false; }
        
        if( !$point->delete() ){ return false; }
        return true;
    }

    public function read($id){

    	$point = Point::find($id, ['name', 'coordinate_x','coordinate_y']);
    	if(!$point){ return false; }
    	return $point->toArray();
    }
}
