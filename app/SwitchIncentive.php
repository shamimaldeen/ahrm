<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SwitchIncentive extends Model
{
    protected $fillable = [
		'incentive',
		'percentage',
		'updated_by',
	];

	protected $primaryKey = 'id';
	protected $table = 'tbl_switchincentive';

}
