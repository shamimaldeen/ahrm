<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $table = 'tbl_designation';
	protected $primaryKey = 'desig_id';
	public $timestamps = false;

	protected $fillable = [
	    'desig_name',
	    'desig_specification',
	    'desig_status'
	];

	public function employee() {
		return $this->hasMany( Employee::class, 'emp_desig_id', 'desig_id' );
	}
}
