<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class PriorityRole extends Model {
	protected $table = 'tbl_priorityrole';
	protected $primaryKey = 'prrole_id';
	public $timestamps = false;

	protected $fillable = [
		'prrole_prid',
	    'prrole_menuid',
	    'prrole_submenuid'
	];

	public function priority() {
		return $this->hasOne( Priority::class, 'pr_id', 'prrole_prid' );
	}

	public function mainmenu() {
		return $this->hasOne( MainMenu::class, 'id', 'prrole_menuid' );
	}

	public function submenu() {
		return $this->hasOne( SubMenu::class, 'id', 'prrole_submenuid' );
	}
}
