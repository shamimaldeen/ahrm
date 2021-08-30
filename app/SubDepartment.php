<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubDepartment extends Model
{
    protected $table = 'tbl_subdepartment';
	protected $primaryKey = 'sdepart_id';
	public $timestamps = false;

	protected $fillable = [
	    'sdepart_name',
	    'sdepart_departid',
    	'sdepart_status'
	];

	public function employee() {
		return $this->hasMany( Employee::class, 'emp_sdepart_id', 'sdepart_id' );
	}

	public function department() {
		return $this->hasOne( Department::class, 'depart_id', 'sdepart_departid' );
	}
}
