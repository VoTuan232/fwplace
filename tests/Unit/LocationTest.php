<?php

namespace Tests\Unit;

use App\Models\Location;
use App\Repositories\LocationRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Validator;
use App\Http\Requests\LocationAddRequest;
use App\Http\Requests\LocationUpdateRequest;
use Illuminate\Foundation\Http\FormRequest;
use DB;

class LocationTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->rules     = (new LocationAddRequest())->rules();
        $this->validator = $this->app['validator'];
    }

    /** @test */
    
    /* Check name validate of location */
    public function valid_name()
    {
        $this->assertTrue($this->validateField('name', 'Handico'));
        $this->assertTrue($this->validateField('name', 'Keangname'));
        $this->assertFalse($this->validateField('name', ''));
        $this->assertFalse($this->validateField('name', str_repeat('a', 192)));
    }

    /* Check total seat validate of location */
    public function valid_total_seat()
    {
    }

    /* Check workspace_id validate of location */
    public function valid_workspace_id()
    {
    }

    /* Check image validate of location */
    public function valid_image()
    {
    }

    protected function getFieldValidator($field, $value)
    {
        return $this->validator->make(
            [$field => $value], 
            [$field => $this->rules[$field]]
        );
    }

    protected function validateField($field, $value)
    {
        return $this->getFieldValidator($field, $value)->passes();
    }
    
    /* check function index */
    function test_index_function() {
        $url = '/workspace/list';
        $response = $this->get($url);
        if ((int)$response->status() !== 302) {
                echo  $url . ' (FAILED) did not return a 302.';
                $this->assertTrue(false);
            } else {
                echo $url . ' (success ?)';
                $this->assertTrue(true);
            }
    }

    /* check function update */
    public function test_update_function()
    {
        $location = factory(Location::class)->create();
        
        $data = [
            'name' => 'Handico',
            'image' => '111111111111111',
            'workspace_id' => 1,
            'color' => '#000000',
        ];
        $locationRepo = new LocationRepository();
        $update = $locationRepo->update($data, $location->id);
        $new_location = $locationRepo->find($location->id);
        
        $this->assertTrue($update);
        $this->assertEquals($data['name'], $new_location->name);
        $this->assertEquals($data['image'], $new_location->image);
        $this->assertEquals($data['workspace_id'], $new_location->workspace_id);
        $this->assertEquals($data['color'], $new_location->color);
    }

    /* check function find a position */
    public function test_find_function()
    {
        $location = factory(Location::class)->create();
        $locationRepo = new LocationRepository();
        $found = $locationRepo->find($location->id);
        
        $this->assertInstanceOf(Location::class, $found);
        $this->assertEquals($found->name, $location->name);
        $this->assertEquals($found->image, $location->image);
        $this->assertEquals($found->workspace_id, $location->workspace_id);
        $this->assertEquals($found->color, $location->color);
    }
    
    /* check function create */
    public function test_create_function()
    {
        $data = [
            'name' => 'Handico',
            'image' => '111111111111111',
            'workspace_id' => 1,
            'color' => '#000000',
        ];

        $locationRepo = new LocationRepository();
        $location = $locationRepo->create($data);
      
        $this->assertInstanceOf(Location::class, $location);
        $this->assertEquals($data['name'], $location->name);
        $this->assertEquals($data['image'], $location->image);
        $this->assertEquals($data['workspace_id'], $location->workspace_id);
        $this->assertEquals($data['color'], $location->color);
    }
}
