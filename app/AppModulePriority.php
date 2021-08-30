<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class AppModulePriority extends Model {
	protected $table = 'tbl_appmodulepriority';
	protected $primaryKey = 'appm_id';
	public $timestamps = false;

	protected $fillable = [
		'amp_prid',
    	'amp_appmid'
	];

	public function priority() {
		return $this->hasOne( Priority::class, 'pr_id', 'amp_prid' );
	}

	public function appmodule() {
		return $this->hasOne( AppModule::class, 'appm_id', 'amp_appmid' );
	}
}
