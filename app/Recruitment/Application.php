<?php

namespace App\Recruitment;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'r_applications';
    protected $fillable = ['intro', 'jobseeker_id', 'job_id'];
    
    /**
     * Get the job that owns the application.
     */
    public function job()
    {
        return $this->belongsTo('App\Recruitment\Job');
    }

    /**
     * Get the job that owns the application.
     */
    public function jobseeker()
    {
        return $this->belongsTo('App\Recruitment\Jobseeker');
    }
}
