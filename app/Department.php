<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'tbl_department';
	protected $primaryKey = 'depart_id';
	public $timestamps = false;

	protected $fillable = [
	    'depart_name',
    	'depart_status'
	];

	public function employee() {
		return $this->hasMany( Employee::class, 'emp_depart_id', 'depart_id' );
	}
}
