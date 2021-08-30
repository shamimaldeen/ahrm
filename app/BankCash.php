<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankCash extends Model
{
    protected $table = 'tbl_bankcash';
	protected $primaryKey = 'id';
	public $timestamps = false;

	protected $fillable = [
	    'emp_id',
	    // 'head_date_of_execution',
	    'head_id',
	    // 'amount',
	    // 'type',
	    'status',
	];

	public function employee() {
		return $this->hasOne( Employee::class, 'emp_id', 'emp_id' );
	}

	public function head() {
		return $this->hasOne( SalaryHead::class, 'head_id', 'head_id' );
	}
}
