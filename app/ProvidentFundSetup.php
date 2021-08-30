<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProvidentFundSetup extends Model
{
    protected $table = 'tbl_providentfundsetup';
	protected $primaryKey = 'id';

	protected $fillable = [
	    'provident_fund',
	    'employee_percentage',
	    'company_percentage'
	];
}
