<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkingDayAdjustment extends Model
{
    protected $table = 'tbl_workingdayadjustment';
	protected $primaryKey = 'id';

	protected $fillable = [
	    'emp_id',
	    'to',
	    'for',
	    'status',
	    'examined_by',
	];

	public function employee() {
		return $this->hasOne( Employee::class, 'emp_id', 'emp_id' )->select('emp_id','emp_name','emp_empid','emp_machineid');
	}

	public function examiner() {
		return $this->hasOne( Employee::class, 'emp_id', 'examined_by' )->select('emp_id','emp_name','emp_empid','emp_machineid');
	}
}
