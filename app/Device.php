<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $table = 'tbl_device';
	protected $primaryKey = 'id';
	public $timestamps = false;

	protected $fillable = [
	    'name',
	    'type',
	];
}
