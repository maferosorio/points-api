<?php

namespace Tests\Feature;

use App\Point;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PointTest extends TestCase
{
    use RefreshDatabase;

    public function testCanAddPoint()
    {
        $point = [
            'name' => $this->faker->unique()->numerify('Point ###'),
            'coordinate_x' => $this->faker->numberBetween(-100, 100),
            'coordinate_y' => $this->faker->numberBetween(-100, 100)
        ];
        
        $this->post(route('points.store'), $point)
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);
    }

    public function testCanUpdatePoint()
    {
        $point = factory(Point::class)->create();
        
        $data = [
            'name' => $this->faker->unique()->numerify('Point ###'),
            'coordinate_x' => $this->faker->numberBetween(-100, 100),
            'coordinate_y' => $this->faker->numberBetween(-100, 100)
        ];
        
        $this->put(route('points.update', $point->id), $data)
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);
    }

    public function testCanDeletePoint()
    {
        $point = factory(Point::class)->create();
        
        $this->delete(route('points.destroy', $point->id))
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);
    }

    public function testCanShowPoint()
    {
        $point = factory(Point::class)->create();
        
        $this->get(route('points.show', $point->id))
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);
    }

    public function testCanGetNearestPoints()
    {
        $selectedPoint = factory(Point::class)->create();
        $points = factory(Point::class, 10)->create();
        $numberOfPoints = 5;

        $this->get(route('points.nearest_points', ['id' => $selectedPoint->id, 'limit' => $numberOfPoints]))
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);
    }

    public function testCoordinateYIsEmptyWhenAddingAPoint()
    {
        $point = [
            'name' => $this->faker->unique()->numerify('Point ###'),
            'coordinate_x' => $this->faker->numberBetween(-100, 100)
        ];
        
        $this->post(route('points.store'), $point)
            ->assertStatus(200)
            ->assertJson([
                'success' => false,
                'message' => 'Point could not be saved.',
            ]);
    }

    public function testCoordinateYIsEmptyWhenUpdatingAPoint()
    {
        $point = factory(Point::class)->create();
        $data = [
            'name' => $this->faker->unique()->numerify('Point ###'),
            'coordinate_x' => $this->faker->numberBetween(-100, 100)
        ];
        
        $this->put(route('points.update',$point->id), $data)
            ->assertStatus(200)
            ->assertJson([
                'success' => false,
                'message' => 'Point could not be updated.',
            ]);
    }

    public function testCannotDeletePoint()
    {
        $point = factory(Point::class)->create();
        $invalidId = 10;

        $this->delete(route('points.destroy', $invalidId))
            ->assertNotFound();
    }

    public function testCannotShowPoint()
    {
        $point = factory(Point::class)->create();
        $invalidId = 10;

        $this->get(route('points.show', $invalidId))
            ->assertNotFound();
    }

    public function testInvalidIdWhenGettingNearestPoints()
    {
        $points = factory(Point::class, 5)->create();
        $invalidId = 10;
        $numberOfPoints = 5;
        
        $this->get(route('points.nearest_points', ['id' => $invalidId, 'limit' => $numberOfPoints]))
            ->assertNotFound();
    }

    public function testInvalidLimitWhenGettingNearestPoints()
    {
        $points = factory(Point::class, 5)->create();
        $pointId = 1;
        $numberOfPoints = -1;
        //->dump();
        $this->get(route('points.nearest_points', ['id' => $pointId, 'limit' => $numberOfPoints]))
            ->assertStatus(200)
            ->assertJson([
                'success' => false, 
                'message' => 'Enter a valid limit value.'
            ]);
    }

    public function testThereAreNoNearestPoints()
    {
        $point = factory(Point::class)->create();
        $numberOfPoints = 2;
        //->dump();
        $this->get(route('points.nearest_points', ['id' => $point->id, 'limit' => $numberOfPoints]))
            ->assertStatus(200)
            ->assertJson([
                'success' => false, 
                'message' => 'There are no nearest points.'
            ]);
    }


    /*public function testBasicExample()
    {
        $response = $this->postJson('/user', ['name' => 'Sally']);

        $response
            ->assertStatus(201)
            ->assertJson([
                'created' => true,
            ]);
    }*/
}
