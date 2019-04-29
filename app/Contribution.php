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
        'title','file_name','user_id','status', 'academic_year','dep_id',
    ];

    public function acyear() {
		return $this->belongsTo(AcademicYear::class, 'academic_year', 'id');
	}

	public function user() {
		return $this->belongsTo(User::class, 'user_id');
	}

    public function dep() {
        return $this->belongsTo(Department::class, 'dep_id');
    }
    public function conimgs() {
        return $this->hasMany(ConImg::class, 'con_id');
    }

    public $timestamps = true;
}
