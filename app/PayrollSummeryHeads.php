<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayrollSummeryHeads extends Model
{
    protected $table = 'tbl_payrollsummeryheads';
	protected $primaryKey = 'id';
	public $timestamps = false;

	protected $fillable = [
	    'ps_id',
	    'head_id',
	    'head_amount'
	];

	public function payrollsummery() {
		return $this->hasOne( PayrollSummery::class, 'id', 'ps_id' );
	}

	public function head() {
		return $this->hasOne( SalaryHead::class, 'head_id', 'head_id' );
	}
}
