<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $table = 'kqz_card';
	protected $primaryKey = 'CardID';
	public $timestamps = false;

	protected $fillable = [
	    'EmployeeID',
	    'CardTime',
	    'CardTypeID',
	    'DevID',
	    'DevClass',
	];

	public function kqzemployee() {
		return $this->hasOne( KQZEmployee::class, 'EmployeeID', 'EmployeeID' );
	}

	public function entrydevice() {
		return $this->hasOne( Device::class, 'DevID', 'DevID' );
	}

	public function exitdevice() {
		return $this->hasOne( Device::class, 'DevID', 'DevID' )->where([['DevID','<','7'],'DevType'=>'0']);
	}

	public function entryremotedevice() {
		return $this->hasOne( RemoteDevice::class, 'DevID', 'DevID' )->where([['DevID','>','6'],'DevType'=>'0']);
	}

	public function exitremotedevice() {
		return $this->hasOne( RemoteDevice::class, 'DevID', 'DevID' )->where([['DevID','>','6'],'DevType'=>'0']);
	}
}
