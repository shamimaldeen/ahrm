<?php
namespace App;
use Eloquent;

class Payment extends Eloquent {

	protected $fillable = [
		'id',
		'pro_id',
		'amount',
		'given_by',
	];

	protected $primaryKey = 'id';
	protected $table = 'tbl_payment';

	public function givenby() {
		return $this->hasOne( Employee::class, 'emp_id', 'given_by' )->select(['emp_id','emp_name','emp_empid','emp_machineid']);
	}

	public function production() {
		return $this->hasOne( Production::class, 'pro_id', 'pro_id' );
	}
}
