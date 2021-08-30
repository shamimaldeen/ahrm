<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HOD extends Model
{
    protected $table = 'tbl_hod';
	protected $primaryKey = 'hod_id';
	public $timestamps = false;

	protected $fillable = [
	    'hod_depart_id',
	    'hod_empid',
	    'hod_superior',
	    'hod_note'
	];

	public function department() {
		return $this->hasOne( Department::class, 'depart_id', 'hod_depart_id' );
	}

	public function hod() {
		return $this->hasOne( Employee::class, 'emp_id', 'hod_empid' );
	}

	public function superior() {
		return $this->hasOne( Employee::class, 'emp_id', 'hod_superior' );
	}
}
