<?php

class Faction extends Eloquent {

    /**
     * @var string
     */
    public $table = 'factions';

    /**
     * @var boolean
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teams()
    {
        return $this->hasMany('Team');
    }

}
