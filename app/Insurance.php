<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    protected $table = 'tbl_insurance';
	protected $primaryKey = 'emp_id';
	public $timestamps = false;

	protected $fillable = [
	    'self_member_id',
	    'effective_date',
	    'spouse_member_id',
	    'spouse_name',
	    'spouse_dob',
	    'spouse_start_date',
	    'spouse_end_date',
	    'child1_member_id',
	    'child1_name',
	    'child1_dob',
	    'child1_start_date',
	    'child1_end_date',
	    'child2_member_id',
	    'child2_name',
	    'child2_dob',
	    'child2_start_date',
	    'child2_end_date',
	];

	public function employee() {
		return $this->hasOne( Employee::class, 'emp_id', 'emp_id' );
	}
}
