<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Educations extends Model
{
    protected $table = 'tbl_educations';
	protected $primaryKey = 'emp_id';
	public $timestamps = false;

	protected $fillable = [
	    'emp_id',
        'level_of_education',
        'exam_title',
        'group',
        'institute',
        'result',
        'cgpa',
        'scale',
        'year',
        'duration',
        'updated_by'

	];

	public function employee() {
		return $this->hasOne( Employee::class, 'emp_id', 'emp_id' )->select('emp_id','emp_name','emp_empid','emp_machineid');
	}

	public function by() {
		return $this->hasOne( Employee::class, 'emp_id', 'updated_by' )->select('emp_id','emp_name','emp_empid','emp_machineid');
	}
}
