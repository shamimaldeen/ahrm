<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $table = 'tbl_holiday';
	protected $primaryKey = 'holiday_id';
	public $timestamps = false;

	protected $fillable = [
	    'holiday_date',
	    'holiday_description',
	    'holiday_status'
	];
}
