<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailyShift extends Model
{
    protected $table = 'tbl_dailyshift';
	protected $primaryKey = 'shift_id';
	public $timestamps = false;

	protected $fillable = [
	    'ds_empid',
	    'ds_shiftid',
	    'ds_date',
	];

	public function shift() {
		return $this->hasOne( Shift::class, 'shift_id', 'ds_shiftid' );
	}

	public function employee() {
		return $this->hasOne( Employee::class, 'emp_id', 'ds_empid' );
	}
}
