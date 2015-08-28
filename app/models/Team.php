<?php

class Team extends Eloquent {

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
        return $this->hasMany('Newcomer');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function faction()
    {
        return $this->belongsTo('Faction');
    }

}
