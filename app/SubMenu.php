<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class SubMenu extends Model {
	protected $table = 'adminsubmenu';
	protected $primaryKey = 'id';
	public $timestamps = false;

	protected $fillable = [
		'serialNo',
	    'Link_Name',
	    'routeName',
	    'icon',
	    'status',
	];

	public function mainmenu() {
		return $this->hasOne( MainMenu::class, 'id', 'mainmenuId' );
	}
}
