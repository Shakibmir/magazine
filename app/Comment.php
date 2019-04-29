<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'con_id','user_id','comment',
    ];

    public function user() {
		return $this->belongsTo(User::class, 'user_id');
	}

	public function con() {
		return $this->belongsTo(Contribution::class, 'con_id');
	}

	public $timestamps = true;
}
