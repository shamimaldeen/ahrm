<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable = [
		'name',
		'status',
	];

	protected $primaryKey = 'id';
	protected $table = 'tbl_skills';
	public $timestamps = false;

}
