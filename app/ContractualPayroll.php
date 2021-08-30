<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContractualPayroll extends Model
{
    protected $table = 'tbl_contractualpayroll';
	protected $primaryKey = 'id';
	public $timestamps = false;

	protected $fillable = [
	    'emp_id',
	    'emp_type',
	    'payroll_date_from',
	    'payroll_date_to',
	    'due',
	    'b_bonus',
	    'fitr_bonus',
	    'adha_bonus',
	    'pa',
	    'nights',
	    'night_allowance',
	    'advance',
	    'tax',
	    'original_tax',
	    'provident_fund',
	    'addition',
	    'deduction',
	    'salary',
	    'generated_at',
	    'updated_at',
	    'updated_count',
	    'generated_by',
	    'updated_by',
	    
	    'basic',
	    'gross',
	    'conv',
	    'net_paid',
	    'target_percent',
	    'targeted_income',
	    'net_income',
	    'percent_achivement',
	    'balance',
	    'other_deduction',
	    'net_pay',
	    'prev_cal',
	    'net_pay_with_prev',
	    'remarks',
	];

	public function employee() {
		return $this->hasOne( Employee::class, 'emp_id', 'emp_id' )->select(['emp_id','emp_name','emp_empid','emp_machineid','emp_desig_id']);
	}

	public function heads() {
		return $this->hasMany( PayrollSummeryHeads::class, 'ps_id', 'id' );
	}

	public function extends() {
		return $this->hasMany( PayrollSummeryExtends::class, 'ps_id', 'id' );
	}
}
