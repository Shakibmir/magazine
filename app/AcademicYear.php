<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'year', 'opening_date','closing_date','final_date',
    ];
}
