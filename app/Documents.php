<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Documents extends Model
{
    protected $table = 'tbl_documents';
	protected $primaryKey = 'id';
	public $timestamps = false;

	protected $fillable = [
	    'emp_id',
	    'title',
	    'description',
	    'name',
	    'file',
	    'uploaded_by',
	];

	public function employee() {
		return $this->hasOne( Employee::class, 'emp_id', 'emp_id' )->select('emp_id','emp_name','emp_empid','emp_machineid');
	}

	public function uploader() {
		return $this->hasOne( Employee::class, 'emp_id', 'uploaded_by' )->select('emp_id','emp_name','emp_empid','emp_machineid');
	}
}
