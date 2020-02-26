<?php

use Illuminate\Database\Seeder;
use App\Point;
use Illuminate\Support\Facades\DB;

class PointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = 10;
        DB::table('points')->truncate();
        factory(Point::class, $count)->create();
    }
}