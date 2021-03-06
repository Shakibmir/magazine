<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConImg extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'con_id','name','path',
    ];

    public function cont() {
		return $this->belongsTo(Contribution::class, 'con_id');
	}

    public $timestamps = true;
}
