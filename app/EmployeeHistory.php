<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeHistory extends Model
{
    protected $table = 'tbl_emphistory';
	protected $primaryKey = 'eh_id';
	public $timestamps = false;

	protected $fillable = [
	    'eh_empid',
	    'eh_log',
	];

	public function employee() {
		return $this->hasOne( Employee::class, 'emp_id', 'eh_empid' );
	}

	public function operator() {
		return $this->hasOne( Employee::class, 'emp_id', 'eh_operator' );
	}
}
