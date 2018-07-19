<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{

    public $table = 'teams';
    public $timestamps = true;

    public $primaryKey = 'id';

    public $fillable = [
        'name',
        'description',
        'img_url'
    ];

    /**
     * Define the One-to-Many relation with Newcomer;
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function newcomers()
    {
        return $this->hasMany('App\Models\User')->where('is_newcomer', '1');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function faction()
    {
        return $this->belongsTo('App\Models\Faction');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ce()
    {
        return $this->hasMany('App\Models\User')->where('is_newcomer', '0');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function respo()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Check if this is a TC Team
     * @return boolean true if it's a TC team
     */
    public function isTC() {
        return $this->respo->branch == "TC" && $this->respo->level < 4;
    }

    /*
     * If it's a tc team, automaticaly tell branch is for TC
     */
    public function getBranchAttribute($value)
    {
        if(!$value && $this->isTC()) {
            return 'TC';
        }
        return $value;
    }
}
