<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Month extends Model
{
    protected $table = 'tbl_months';
	protected $primaryKey = 'id';

	protected $fillable = [
	    'month',
	];

	public function loan() {
		return $this->hasMany( Loan::class, 'month', 'id' );
	}
}
