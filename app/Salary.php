<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    protected $table = 'tbl_salary';
	protected $primaryKey = 'emp_id';
	public $timestamps = false;

	protected $fillable = [
	    'gross',
	    'tin_no',
	    'grade',
	    'bank_account',
	    'bu_code',
	    'category',
	    'ten_steps',
	    'gender',
	    'basic_salary',
	    'house_rent',
	    'medical',
	    'living',
	    'conv',
	    'special',
	    'others',
	];

	public function employee() {
		return $this->hasOne( Employee::class, 'emp_id', 'emp_id' );
	}
}
