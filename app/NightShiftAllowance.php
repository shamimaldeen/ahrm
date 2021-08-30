<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NightShiftAllowance extends Model
{
    protected $table = 'tbl_nightshiftallowance';
	protected $primaryKey = 'id';

	protected $fillable = [
	    'emp_type',
	    'allowance',
	    'execution_date',
	    'assigned_by',
	];

	public function assigned() {
		return $this->hasOne( Employee::class, 'emp_id', 'assigned_by' );
	}

	public function type() {
		return $this->hasOne( EmployeeTypes::class, 'id', 'emp_type' );
	}
}
