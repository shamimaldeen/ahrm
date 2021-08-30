<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $fillable = [
		'project_id',
		'job_title',
		'emp_id',
		'job_weight',
		'completion',
		'start_date',
		'end_date',
		'status'
	];

	protected $primaryKey = 'id';
	protected $table = 'tbl_job';

	public function employee()
	{
		return $this->belongsTo(Employee::class, 'emp_id', 'emp_id')->select('emp_id','emp_name','emp_empid','emp_machineid');
	}

	public function project()
	{
		return $this->belongsTo(Project::class, "project_id", "id");
	} 

	public function skill()
	{
		return $this->hasMany(JobSkill::class, "job_id", "id");
	}

	public function appraisal()
	{
		return $this->hasMany(Appraisal::class, "job_id", "id");
	}

	public function reviewer()
	{
		return $this->hasMany(JobReviewer::class, "job_id", "id");
	}

	public function notes()
	{
		return $this->hasMany(JobNote::class, "job_id", "id");
	}
}
