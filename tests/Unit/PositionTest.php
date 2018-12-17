<?php

namespace Tests\Unit;

use App\Models\Position;
use App\Repositories\PositionRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Validator;
use App\Http\Requests\PositionFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use DB;

class PositionTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->rules = (new PositionFormRequest())->rules();
        $this->validator = $this->app['validator'];
    }

    /** @test */
    
    /* Check name validate of position */
    public function valid_name()
    {
        $this->assertTrue($this->validateField('name', 'Tranee'));
        $this->assertTrue($this->validateField('name', 'Tranner'));
        $this->assertFalse($this->validateField('name', ''));
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
        $url = '/admin/positions';
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
        $position = factory(Position::class)->create();
        
        $data = [
            'name' => $this->faker->word,
            'is_fulltime' => $this->faker->unique()->numberBetween($min = 0, $max = 1),
            'allow_register' => $this->faker->unique()->numberBetween($min = 0, $max = 1),
        ];
        $positionRepo = new PositionRepository();
        $update = $positionRepo->update($data, $position->id);
        $new_position = $positionRepo->find($position->id);
        
        $this->assertTrue($update);
        $this->assertEquals($data['name'], $new_position->name);
        $this->assertEquals($data['is_fulltime'], $new_position->is_fulltime);
        $this->assertEquals($data['allow_register'], $new_position->allow_register);
    }

    /* check function find a position */
    public function test_find_function()
    {
        $position = factory(Position::class)->create();
        $positionRepo = new PositionRepository();
        $found = $positionRepo->find($position->id);
        
        $this->assertInstanceOf(Position::class, $found);
        $this->assertEquals($found->name, $position->name);
        $this->assertEquals($found->is_fulltime, $position->is_fulltime);
        $this->assertEquals($found->allow_register, $position->allow_register);
    }
    
    /* check function create */
    public function test_create_function()
    {
        $data = [
            'name' => $this->faker->word,
            'is_fulltime' => $this->faker->unique()->numberBetween($min = 0, $max = 1),
            'allow_register' => $this->faker->unique()->numberBetween($min = 0, $max = 1),
        ];

        $positionRepo = new PositionRepository();
        $position = $positionRepo->create($data);
      
        $this->assertInstanceOf(Position::class, $position);
        $this->assertEquals($data['name'], $position->name);
        $this->assertEquals($data['is_fulltime'], $position->is_fulltime);
        $this->assertEquals($data['allow_register'], $position->allow_register);
    }
}
