<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectDetails extends Model
{
    protected $table = 'tbl_projectdetails';
	protected $primaryKey = 'project_id';
	public $timestamps = false;

	protected $fillable = [
	    'project_name',
	    'project_company',
	    'project_details',
	    'project_address',
	    'project_contact'
	];
}
