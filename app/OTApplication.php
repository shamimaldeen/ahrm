<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OTApplication extends Model
{
    protected $table = 'tbl_otapplication';
	protected $primaryKey = 'otapp_id';
	public $timestamps = false;

	protected $fillable = [
	    'otapp_empid',
	    'otapp_reqdate',
	    'otapp_perdate',
	    'otapp_fromtime',
	    'otapp_totime',
	    'otapp_totalhrs',
	];

	public function employee() {
		return $this->hasOne( Employee::class, 'emp_id', 'otapp_empid' );
	}

	public function approvedby() {
		return $this->hasOne( Employee::class, 'emp_id', 'otapp_appuserid' );
	}

	public function assignedby() {
		return $this->hasOne( Employee::class, 'emp_id', 'otapp_assignedby' );
	}
}
