<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayrollSummeryExtends extends Model
{
    protected $table = 'tbl_payrollsummeryextends';
	protected $primaryKey = 'ex_id';
	public $timestamps = false;

	protected $fillable = [
	    'ps_id',
	    'head_id',
	    'head_quantity',
	    'head_amount'
	];

	public function payrollsummery() {
		return $this->hasOne( PayrollSummery::class, 'id', 'ps_id' );
	}

	public function head() {
		return $this->hasOne( SalaryHeadExtends::class, 'head_id', 'head_id' );
	}
}
