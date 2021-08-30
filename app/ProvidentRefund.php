<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProvidentRefund extends Model
{
    protected $table = 'tbl_provident_refund';
	protected $primaryKey = 'id';

	protected $fillable = [
	    'emp_id',
	    'apply_date',
	    'approved_date',
	    'purpose',
	    'requested_amount',
	    'approved_amount',
	    'status',
	    'reason_of_rejection',
	    'coopon_number',
	];

	public function employee() {
		return $this->hasOne( Employee::class, 'emp_id', 'emp_id' )->select(['emp_id','emp_name','emp_empid','emp_machineid']);
	}
}
