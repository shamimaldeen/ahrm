<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoanApprove extends Model
{
    protected $table = 'tbl_loanapprove';
	protected $primaryKey = 'id';

	protected $fillable = [
	    'loan_id',
	    'month',
	    'year',
	    'flag',
	    'approved_by',
	    'received_by',
	];

	public function loan() {
		return $this->hasOne( Loan::class, 'id', 'loan_id' );
	}

	public function install_month() {
		return $this->hasOne( Month::class, 'id', 'month' );
	}

	public function approve() {
		return $this->hasOne( Employee::class, 'emp_id', 'approved_by' )->select('emp_id','emp_name','emp_empid','emp_machineid');
	}

	public function receive() {
		return $this->hasOne( Employee::class, 'emp_id', 'received_by' )->select('emp_id','emp_name','emp_empid','emp_machineid');
	}
}
