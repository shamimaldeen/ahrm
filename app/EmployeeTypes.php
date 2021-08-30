<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeTypes extends Model
{
    protected $table = 'tbl_employee_types';
	protected $primaryKey = 'id';

	protected $fillable = [
	    'name',
	    'desc',
	    'status',
	    'created_by',
	];

	public function employees() {
		return $this->hasMany( Employee::class, 'emp_type', 'id' );
	}

	public function creator() {
		return $this->hasOne( Employee::class, 'emp_id', 'created_by' );
	}

	public function nightshiftallowances() {
		return $this->hasMany( NightShiftAllowance::class, 'emp_type', 'id' );
	}

	public function currentNightshiftallowance() {
		return $this->hasOne( NightShiftAllowance::class, 'emp_type', 'id' )->orderBy('execution_date','desc')->orderBy('id','desc');
	}
}
