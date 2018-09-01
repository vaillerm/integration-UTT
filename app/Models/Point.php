<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Team;

class Point extends Model
{
    public $table = 'points';

    public $primaryKey = 'id';

    public $fillable = [
        'reason',
        'amount',
        'team_id',
        'added_by'
    ];

    /**
	 * Define constraints of the Model's attributes for store action
	 *
	 * @return array
	 */
	public static function storeRules() {
		return [
			'reason' => 'required|string',
			'amount' => 'required|integer',
			'team_id' => 'required|exists:teams,id',
			'added_by' => 'required|exists:users,id',
		];
	}

    public function team() {
        return $this->belongsTo("App\Models\Team");
    }

    public function author()
    {
        return $this->belongsTo("App\Models\User", "added_by");
    }
}
