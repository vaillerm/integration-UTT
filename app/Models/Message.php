<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Message extends Model
{

    public $table = 'messages';
    public $timestamps = true;

    public $primaryKey = 'id';

    public $fillable = [
        'text',
        'channel'
    ];

    /**
	 * Define constraints of the Model's attributes for store action
	 *
	 * @return array
	 */
	public static function storeRules() {
		return [
			'channel' => [
                'required',
                'string',
                Rule::in(['general', 'admin'])
            ],
			'text' => 'required|string',
		];
	}

    /**
     * Define the One-to-Many relation with User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student()
    {
        return $this->belongsTo('App\Models\User');
    }
}
