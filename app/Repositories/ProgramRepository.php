<?php 

namespace App\Repositories;
use App\Models\Program;

class ProgramRepository extends EloquentRepository
{
    public function model()
    {
        return Program::class;
    }

    public function listProgramArray()
    {
        return $this->model->pluck('name', 'id')->toArray();
    }
}
