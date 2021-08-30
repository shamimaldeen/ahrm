<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RemoteDevice extends Model
{
    protected $table = 'tbl_remotedevice';
	protected $primaryKey = 'id';
	public $timestamps = false;

	protected $fillable = [
	    'name', 
	    'type'
	];
}
