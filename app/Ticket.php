<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tbl_ticket';
	protected $primaryKey = 'ticket_id';
	public $timestamps = false;

	protected $fillable = [
	    'ticket_code',
	    'ticket_topic',
	    'ticket_desc',
	    'ticket_submitted_by',
	    'ticket_parent_id',
	    'ticket_depart_id',
	    'ticket_status'
	];

	public function employee() {
		return $this->hasOne( Employee::class, 'emp_id', 'ticket_submitted_by' );
	}

	public function parent() {
		return $this->hasOne( Employee::class, 'emp_id', 'ticket_parent_id' );
	}

	public function department() {
		return $this->hasOne( Department::class, 'depart_id', 'ticket_depart_id' );
	}
}
