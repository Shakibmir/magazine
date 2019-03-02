<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contribution extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title','file_name','user_id','status', 'academic_year',
    ];

    public function acyear() {
		return $this->belongsTo(AcademicYear::class, 'academic_year', 'year');
	}

	public function user() {
		return $this->belongsTo(User::class, 'user_id');
	}
}
