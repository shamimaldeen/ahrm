<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    protected $table = 'tbl_leavetype';
	protected $primaryKey = 'li_id';
	public $timestamps = false;

	protected $fillable = [
	    'li_name',
	    'li_qoutaday',
	    'li_status'
	];

	public function leaveapplication() {
		return $this->hasMany( LeaveApplication::class, 'leave_typeid', 'li_id' );
	}
}
