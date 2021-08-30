<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalaryHead extends Model
{
    protected $table = 'tbl_salaryhead';
	protected $primaryKey = 'head_id';
	public $timestamps = false;

	protected $fillable = [
	    'head_name',
	    'head_type',
	    'other_head_type',
	    'head_percentage',
	    'head_taxable',
	    'head_taxexempt',
	    'head_note',
	    'head_status'
	];

	public function payroll() {
		return $this->hasMany( Payroll::class, 'head_id', 'head_id' );
	}
}
