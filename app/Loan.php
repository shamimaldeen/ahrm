<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $table = 'tbl_loans';
	protected $primaryKey = 'id';

	protected $fillable = [
	    'emp_id',
	    'purpose',
	    'amount',
	    'month',
	    'year',
	    'flag',
	];

	public function employee() {
		return $this->hasOne( Employee::class, 'emp_id', 'emp_id' )->select(['emp_id','emp_name','emp_empid','emp_machineid']);
	}

	public function loanapprove() {
		return $this->hasMany( loanApprove::class, 'loan_id', 'id' );
	}

	public function loan_month() {
		return $this->hasOne( Month::class, 'id', 'month' );
	}
}
