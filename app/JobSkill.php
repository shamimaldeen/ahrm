<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobSkill extends Model
{
    protected $fillable = [
    	'job_id',
        'skill_id'
    ];
    protected $table = "tbl_jobskill";

    protected $primaryKey = "id";

    public function job()
    {
    	return $this->belongsTo(Job::class, "id", "job_id");
    }

    public function skill() {
        return $this->hasOne(Skill::class, "id", "skill_id");
    }
}
