<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProvidentFund extends Model
{
    protected $table = 'tbl_provident_fund';
	protected $primaryKey = 'id';

	protected $fillable = [
	    'emp_id',
	    'year',
	    'month',
	    'employee_amount',
	    'company_amount',
	];

	public function employee() {
		return $this->hasOne( Employee::class, 'emp_id', 'emp_id' )->select(['emp_id','emp_name','emp_empid','emp_machineid']);
	}

	public function provident_month() {
		return $this->hasOne( Month::class, 'id', 'month' );

	}
}
