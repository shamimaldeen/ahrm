<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class AppModule extends Model {
	protected $table = 'tbl_appmodule';
	protected $primaryKey = 'appm_id';
	public $timestamps = false;

	protected $fillable = [
		'appm_menuid',
	    'appm_submenuid',
	    'appm_name',
	    'appm_status'
	];

	public function mainmenu() {
		return $this->hasOne( MainMenu::class, 'id', 'appm_menuid' );
	}

	public function submenu() {
		return $this->hasOne( SubMenu::class, 'id', 'appm_submenuid' );
	}
}
