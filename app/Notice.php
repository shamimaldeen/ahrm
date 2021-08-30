<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $table = 'tbl_notice';
	protected $primaryKey = 'notice_id';
	public $timestamps = false;

	protected $fillable = [
	    'notice_title',
	    'notice_desc',
	    'notice_department',
	    'notice_employee',
	    'notice_added_by',
	    'notice_publish_from',
	    'notice_publish_to',
	    'notice_status'
	];

	public function addedby() {
		return $this->hasOne( Employee::class, 'emp_id', 'notice_added_by' )->select('emp_id','emp_name','emp_empid','emp_machineid');
	}

	public function department() {
		return $this->hasOne( Department::class, 'depart_id', 'notice_department' );
	}
}
