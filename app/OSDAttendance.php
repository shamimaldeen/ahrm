<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OSDAttendance extends Model
{
    protected $table = 'tbl_osdattendance';
	protected $primaryKey = 'osd_id';
	public $timestamps = false;

	protected $fillable = [
	    'osd_uniquecode',
	    'osd_done_by',
	    'osd_date',
	    'osd_starttime',
	    'osd_endtime',
	    'osd_duration',
	    'osd_location',
	    'osd_description',
	    'osd_status',
	    'osd_approved_by'
	];

	public function employee() {
		return $this->hasOne( Employee::class, 'emp_id', 'osd_done_by' );
	}
	
	public function approvedby() {
		return $this->hasOne( Employee::class, 'emp_id', 'osd_approved_by' );
	}
}
