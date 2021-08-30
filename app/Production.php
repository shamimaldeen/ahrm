<?php
namespace App;
use Eloquent;

class Production extends Eloquent {

	protected $fillable = [
		'pro_empid',
		'pro_pi_id',
		'pro_no_dz',
		'pro_totalcost',
		'pro_startdate',
		'pro_enddate',
	];
	protected $primaryKey = 'pro_id';
	protected $table = 'tbl_production';

	public function employee() {
		return $this->hasOne( Employee::class, 'emp_id', 'pro_empid' )->select(['emp_id','emp_name','emp_empid','emp_machineid']);
	}

	public function piece() {
		return $this->hasOne( Piece::class, 'pi_id', 'pro_pi_id' );
	}

	public function payment() {
		return $this->hasMany( Payment::class, 'pro_id', 'pro_id' );
	}
}
