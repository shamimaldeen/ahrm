<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appraisal extends Model
{
    protected $fillable = [
    	"job_id",
    	"skill_id",
    	"skill_weight",
    	"target",
    	"achieve",
    	"incentive_target_amount",
    	"incentive_achieve_amount"
    ];

    protected $primaryKey = "id"; 

    protected $table = "tbl_skillappraisal";

    public function skill() {
    	return $this->belongsTo(Skill::class, "skill_id", "id");
    }

    public function job() {
    	return $this->belongsTo(Job::class, "job_id", "id");
    }
}
