<?php

namespace App\Recruitment;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    /**
     * The number of models to return for pagination.
     *
     * @var int
     */

    protected $table = 'r_jobs';
    protected $perPage = 10;

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'salary', 'closing_date', 'experience',
        'email', 'who_apply', 'offer', 'skills', 'inactive'
    ];
    
    /**
     * Get the applications for the job.
     */
    public function applications()
    {
        return $this->hasMany('App\Recruitment\Application');
    }
}
