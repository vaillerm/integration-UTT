<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PointsRequest extends FormRequest
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

    public function toArray() : array 
    {
        return [
            "reason" => $this->reason,
            "amount" => $this->amount,
            "team_id" => $this->team_id
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "reason" => "string|required",
            "amount" => "integer|required",
            "team_id" => "integer|required|exists:teams,id"
        ];
    }
}
