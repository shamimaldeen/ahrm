<?php
namespace App;
use Eloquent;

class Style extends Eloquent {

	protected $fillable = [
		'sty_code',
		'sty_desc',
		'sty_empid',
		'sty_status',
	];
	protected $primaryKey = 'sty_id';
	protected $table = 'tbl_style';

	public function employee() {
		return $this->hasOne( Employee::class, 'emp_id', 'sty_empid' )->select(['emp_id','emp_name','emp_empid','emp_machineid']);
	}
}
