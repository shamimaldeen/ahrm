<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setup extends Model
{
    protected $table = 'tbl_setup';
	protected $primaryKey = 'id';
	public $timestamps = false;

	protected $fillable = [
	    'developer_mode',
	    'attendance_type',
	    'grace_time',
	    'absent_time',
	    'b_bonus',
	    'fitr_bonus',
	    'adha_bonus'
	];
}
