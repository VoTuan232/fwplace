<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Validator;
use App\Models\Position;

class PositionFormRequest extends FormRequest
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
            'name' => 'required|string|unique:positions',
        ];
    }

    public static function rulesStore($request)
    {
        $messages = [
            'name.required' => __('Position') . __('Required'),
            'name.string' => __('Position') . __('String'),
            'name.unique' => __('Position') . __('Unique'),
        ];

        return Validator::make(
            $request->all(),
            [
                'name' => 'required|string|unique:positions',
            ],
            $messages
        );
    }

    public static function rulesUpdate($request, $id)
    {
        $messages = [
            'name.required' => __('Position') . __('Required'),
            'name.string' => __('Position') . __('String'),
            'name.unique' => __('Position') . __('Unique'),
        ];

        $position = Position::findOrFail($id);

        if ($position->name == $request->name) {
            return Validator::make(
                $request->all(),
                [
                    'name' => 'required|string',
                ],
                $messages
            );
        } elseif ($position->name != $request->name) {
            return Validator::make(
                $request->all(),
                [
                    'name' => 'required|string|unique:positions',
                ],
                $messages
            );
        }
    }
}
