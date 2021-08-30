<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalaryHeadExtends extends Model
{
    protected $table = 'tbl_salaryheadextends';
	protected $primaryKey = 'head_id';
	public $timestamps = false;

	protected $fillable = [
	    'head_name',
	    'head_unit_for_absent',
	    'head_percentage_for_basic',
	    'head_percentage_for_total',
	    'head_percentage_status',
	    'head_note',
	    'head_type',
	    'head_status'
	];
}
