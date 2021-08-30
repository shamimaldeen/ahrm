<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mobilebill extends Model
{
    protected $table = 'tbl_mobilebill';
	protected $primaryKey = 'id';
	public $timestamps = false;

	protected $fillable = [
		'mobile_bill',
		'month',
	    'emp_id'    
	];

	public function employee() {
		return $this->hasOne( Employee::class, 'emp_id', 'emp_id' )->select(['emp_id','emp_name','emp_empid','emp_machineid','emp_desig_id']);
	}

	// public function heads() {
	// 	return $this->hasMany( PayrollSummeryHeads::class, 'ps_id', 'id' );
	// }

	// public function extends() {
	// 	return $this->hasMany( PayrollSummeryExtends::class, 'ps_id', 'id' );
	// }
}
