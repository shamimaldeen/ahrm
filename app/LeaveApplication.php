<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaveApplication extends Model
{
    protected $table = 'tbl_leaveapplication';
	protected $primaryKey = 'leave_id';
	public $timestamps = false;

	protected $fillable = [
	    'leave_empid',
	    'leave_typeid',
	    'leave_start_date',
	    'leave_end_date',
	    'leave_day',
	    'leave_reason',
	    'leave_replacement_date',
	    'leave_replacement',
	    'leave_docext',
	    'leave_requested_date',
	    'leave_status',
	    'leave_approved_userid',
	    'leave_approved_date',
	    'leave_note'
	];

	public function employee() {
		return $this->hasOne( Employee::class, 'emp_id', 'leave_empid' );
	}

	public function leavetype() {
		return $this->hasOne( LeaveType::class, 'li_id', 'leave_typeid' );
	}

	public function leave_replacement() {
		return $this->hasOne( Employee::class, 'emp_id', 'leave_replacement' );
	}

	public function approvedby() {
		return $this->hasOne( Employee::class, 'emp_id', 'leave_approved_userid' );
	}
}
