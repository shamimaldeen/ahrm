<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobLocation extends Model
{
    protected $table = 'tbl_joblocation';
	protected $primaryKey = 'jl_id';
	public $timestamps = false;

	protected $fillable = [
	    'jl_name', 
	    'jl_status'
	];

	public function employee() {
		return $this->hasMany( Employee::class, 'emp_jlid', 'jl_id' );
	}
}
