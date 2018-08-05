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

    /**
     * Convert the ChallengeRequest object to an array
     * which contains all the info to create or update a challenge.
     * it is usefull in laravel 5.2, since you can't retrieve an array
     * from the request directly, or I did not find a way :|
     */
    public function toArray():array {
        return [
            "name" => $this->name,
            "description" => $this->description,
            "points" => $this->points,
            "deadline" => $this->deadline
        ];
    }
}
