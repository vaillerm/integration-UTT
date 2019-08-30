<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Illuminate\Validation\Rule;

class Event extends Model implements \MaddHatter\LaravelFullcalendar\Event
{
    public $table = 'events';
    public $timestamps = false;

    public $primaryKey = 'id';

    public $fillable = [
        'name',
        'description',
        'place',
        'categories',
        'start_at',
        'end_at'
    ];
    static public $categories_array = ['admin', 'newcomerTC', 'newcomerBranch', 'ce', 'orga', 'volunteer', 'referral'];

    /**
	 * Define constraints of the Model's attributes for store action
	 *
	 * @return array
	 */
	public static function storeRules() {
		return [
			'name' => 'required|string',
			'description' => 'required|string',
			'place' => 'required|string',
      'start_at_date' => 'required|date|date_format:Y-m-d|before_or_equal:end_at_date',
      'end_at_date'=> 'required|date|date_format:Y-m-d|after_or_equal:start_at_date',
      'start_at_hour'=> 'required|date_format:H:i',
      'end_at_hour'=> 'required|date_format:H:i',
      'categories' => 'required|array',
      'categories.*' => [
          Rule::in(Event::$categories_array)
      ],
		];
	}

    /**
     * Get the event's title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->name.' ('.implode(',',json_decode($this->categories)).' )';
    }

    /**
     * Is it an all day event?
     *
     * @return bool
     */
    public function isAllDay()
    {
        return false;
    }

    /**
     * Get the start time
     *
     * @return DateTime
     */
    public function getStart()
    {
        $d = new DateTime();
        return $d->setTimestamp($this->start_at);
    }

    /**
     * Get the end time
     *
     * @return DateTime
     */
    public function getEnd()
    {
        $d = new DateTime();
        return $d->setTimestamp($this->end_at);
    }

    public function getEventOptions()
    {
        return [
            'color' => '#77b2f9',
            'url' => url()->route('event.edit', ['id' => $this->id ])
        ];
    }
}
