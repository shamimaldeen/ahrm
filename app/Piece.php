<?php
namespace App;
use Eloquent;

class Piece extends Eloquent {

	protected $fillable = [
		'pi_code',
		'pi_name',
		'pi_styleid',
		'pi_price_dz',
	];
	protected $primaryKey = 'pi_id';
	protected $table = 'tbl_piece';

	public function style() {
		return $this->hasOne( Style::class, 'sty_id', 'pi_styleid' );
	}
}
