<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
		'goal_id',
		'project_name',
		'project_amount',
		'incentive_amount',
		'start_date',
		'end_date',
		'status'
	];

	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $table = 'tbl_project';

	public function goal()
	{
		return $this->hasOne(Goal::class, 'id', 'goal_id');
	}
	public function jobs()
	{
		return $this->hasMany(Job::class, "project_id", "id");
	}
}