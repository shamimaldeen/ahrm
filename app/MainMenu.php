<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class MainMenu extends Model {
	protected $table = 'adminmainmenu';
	protected $primaryKey = 'id';
	public $timestamps = false;

	protected $fillable = [
		'serialNo',
	    'Link_Name',
	    'routeName',
	    'icon',
	    'status',
	];

	public function submenu() {
		return $this->hasMany( SubMenu::class, 'mainmenuId', 'id' );
	}
}
