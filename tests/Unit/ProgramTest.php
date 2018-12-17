<?php

namespace Tests\Unit;

use App\Models\Program;
use App\Repositories\ProgramRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Validator;
use App\Http\Requests\ProgramRequest;
use Illuminate\Foundation\Http\FormRequest;
use DB;

class ProgramTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->rules = (new ProgramRequest())->rules();
        $this->validator = $this->app['validator'];
    }

    /** @test */
    
    /* Check name validate */
    public function valid_name()
    {
        $this->assertTrue($this->validateField('name', 'PHP'));
        $this->assertTrue($this->validateField('name', 'Android'));
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
        $program = factory(Program::class)->create();
        
        $data = [
            'name' => $this->faker->word,
        ];
        $programRepo = new ProgramRepository();
        $update = $programRepo->update($data, $program->id);
        $new_program = $programRepo->find($program->id);
        
        $this->assertTrue($update);
        $this->assertEquals($data['name'], $new_program->name);
    }

    /* check function find a program */
    public function test_find_function()
    {
        $program = factory(Program::class)->create();
        $programRepo = new ProgramRepository();
        $found = $programRepo->find($program->id);
        
        $this->assertInstanceOf(Program::class, $found);
        $this->assertEquals($found->name, $program->name);
    }
    
    /* check function create */
    public function test_create_function()
    {
        $data = [
            'name' => $this->faker->word,
        ];

        $programRepo = new ProgramRepository();
        $program = $programRepo->create($data);
      
        $this->assertInstanceOf(Program::class, $program);
        $this->assertEquals($data['name'], $program->name);
    }
}
