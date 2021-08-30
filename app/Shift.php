<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $table = 'tbl_shift';
	protected $primaryKey = 'shift_id';
	public $timestamps = false;

	protected $fillable = [
	    'shift_type',
	    'shift_stime',
	    'shift_etime',
	    'shift_status'
	];

	public function employee() {
		return $this->hasMany( Employee::class, 'emp_shiftid', 'shift_id' );
	}
}
