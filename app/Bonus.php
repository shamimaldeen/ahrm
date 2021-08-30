<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    protected $table = 'tbl_bonus';
	protected $primaryKey = 'id';
	public $timestamps = false;

	protected $fillable = [
		'b_bonus',
		'fitr_bonus',
		'adha_bonus',
		'month',
	    'emp_id'    
	];

	public function employee() {
		return $this->hasOne( Employee::class, 'emp_id', 'emp_id' )->select(['emp_id','emp_name','emp_empid','emp_machineid','emp_desig_id']);
	}

	public function loan_month() {
		return $this->hasOne( Month::class, 'id', 'month' );
	}

	// public function heads() {
	// 	return $this->hasMany( PayrollSummeryHeads::class, 'ps_id', 'id' );
	// }

	// public function extends() {
	// 	return $this->hasMany( PayrollSummeryExtends::class, 'ps_id', 'id' );
	// }
}
