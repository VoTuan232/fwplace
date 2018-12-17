<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Validator;
use App\Models\Program;

class ProgramRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|unique:programs',
        ];
    }

    public static function rulesStore($request)
    {
        $messages = [
            'name.required' => __('Program') . __('Required'),
            'name.string' => __('Program') . __('String'),
            'name.unique' => __('Program') . __('Unique'),
        ];

        return Validator::make(
            $request->all(),
            [
                'name' => 'required|string|unique:programs',
            ],
            $messages
        );
    }

    public static function rulesUpdate($request, $id)
    {
        $messages = [
            'name.required' => __('Program') . __('Required'),
            'name.string' => __('Program') . __('String'),
            'name.unique' => __('Program') . __('Unique'),
        ];

        $program = Program::findOrFail($id);
        
        if ($program->name == $request->name) {
            return Validator::make(
                $request->all(),
                [
                    'name' => 'required|string',
                ],
                $messages
            );
        } elseif ($program->name != $request->name) {
            return Validator::make(
                $request->all(),
                [
                    'name' => 'required|string|unique:programs',
                ],
                $messages
            );
        }
    }
}
