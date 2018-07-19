<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChallengeRequest extends FormRequest
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
		$validation = [
			"description" => "required|max:140|string",
			"points" => "required|numeric",
			"deadline"=> "required"
		];
		if($this->has("challengeId")) {
			$validation["name"] = "required|unique:challenges,".$this->challengeId;
		} else {
			$validation["name"] = "required|unique:challenges";
		}
        return $validation;
    }
}
