<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $fillable = [
		'title',
		'year',
		'status',
	];

	protected $primaryKey = 'id';
	protected $table = 'tbl_goals';

}
