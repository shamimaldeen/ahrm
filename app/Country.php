<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'tbl_country';
	protected $primaryKey = 'id';
	public $timestamps = false;

	protected $fillable = [
	    'country_code',
    	'country_name'
	];

	public function employee() {
		return $this->hasMany( Employee::class, 'emp_country', 'id' );
	}
}
