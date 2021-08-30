<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Priority extends Model {
	protected $table = 'tbl_priority';
	protected $primaryKey = 'pr_id';
	public $timestamps = false;

	protected $fillable = [
		'pr_id', 
		'pr_name', 
		'pr_status'
	];

	public function priorityrole() {
		return $this->hasMany( PriorityRole::class, 'prrole_prid', 'pr_id' );
	}

	public function user() {
		return $this->hasMany( Admin::class, 'suser_level', 'pr_id' );
	}
}
