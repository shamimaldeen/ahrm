<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TotalWorkingHours extends Model
{
    protected $table = 'tbl_totalworkinghours';
	protected $primaryKey = 'id';
	public $timestamps = false;

	protected $fillable = [
	    'emp_id',
	    'date',
	    '',
	    'date',
	    'shift_id',
	    'entry_time',
	    'exit_time',
	    'late_entry',
	    'early_exit',
	    'twh',
	    'total_osd',
	    'ot',
	    'night',
	    'status',
	];

	public function employee() {
		return $this->hasOne( Employee::class, 'emp_id', 'emp_id' )->select('emp_id','emp_name','emp_empid','emp_machineid');
	}

	public function shift() {
		return $this->hasOne( Shift::class, 'shift_id', 'shift_id' );
	}
}
