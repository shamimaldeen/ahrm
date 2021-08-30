<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForgetPassword extends Model
{
    protected $table = 'tbl_forgetpassword';
	protected $primaryKey = 'fp_id';
	public $timestamps = false;

	protected $fillable = [
	    'suser_empid',
	    'submitted_to',
	    'submitted_at',
	    'solved_by',
	    'solved_at',
	    'fp_status'
	];

	public function employee() {
		return $this->hasOne( Employee::class, 'emp_id', 'suser_empid' );
	}

	public function submittedto() {
		return $this->hasOne( Employee::class, 'emp_id', 'submitted_to' );
	}

	public function solvedby() {
		return $this->hasOne( Employee::class, 'emp_id', 'solved_by' );
	}
}
