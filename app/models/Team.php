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
     * @return Eloquent
     */
    public function newcomers()
    {
        return $this->hasMany('Newcomer');
    }

}
