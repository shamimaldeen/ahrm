<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'tbl_employee';
	protected $primaryKey = 'emp_id';
	public $timestamps = false;

	protected $fillable = [
        'emp_empid',
        'emp_name',
        'emp_type',
        'emp_phone',
        'emp_sfid',
        'emp_dob',
        'emp_country',
        'emp_desig_id',
        'emp_depart_id',
        'emp_sdepart_id',
        'emp_seniorid',
        'emp_authperson',
        'emp_blgrp',
        'emp_education',
        'emp_wknd',
        'emp_vehicle',
        'emp_workhr',
        'emp_otent',
        'emp_shiftid',
        'emp_email',
        'emp_cicoid',
        'emp_jlid',
        'emp_joindate',
        'emp_confirmdate',
        'emp_workhistoryfrom',
        'emp_father',
        'emp_mother',
        'emp_spname',
        'emp_child1',
        'emp_child2',
        'emp_emjcontact',
        'emp_crntaddress',
        'emp_prmntaddress',
        'emp_imgext',
        'emp_machineid',
        'emp_nid',
        'pf',
        'washing',
        'lfa',
        'tax_allow',
        'emp_status',
        'status_condition',
        'inactive_datetime',
        'emp_rejected_at',
        'spouse_phone',
        'emp_officecontact',
        'emp_handsetallocdate',
        'emp_allocamount',
        'min_salary',
        'target_percent'
	];

	public function mobilebill() {
		return $this->hasOne( Mobilebill::class, 'emp_id', 'emp_id' );
	}
	public function type() {
		return $this->hasOne( EmployeeTypes::class, 'id', 'emp_type' );
	}

	public function country() {
		return $this->hasOne( Country::class, 'id', 'emp_country' );
	}

	public function designation() {
		return $this->hasOne( Designation::class, 'desig_id', 'emp_desig_id' );
	}

	public function department() {
		return $this->hasOne( Department::class, 'depart_id', 'emp_depart_id' );
	}

	public function subdepartment() {
		return $this->hasOne( SubDepartment::class, 'sdepart_id', 'emp_sdepart_id' );
	}

	public function joblocation() {
		return $this->hasOne( JobLocation::class, 'jl_id', 'emp_jlid' );
	}

	public function shift() {
		return $this->hasOne( Shift::class, 'shift_id', 'emp_shiftid' );
	}

	public function dailyshift() {
		return $this->hasMany( DailyShift::class, 'ds_empid', 'emp_id' );
	}

	public function insurance() {
		return $this->hasOne( Insurance::class, 'emp_id', 'emp_id' );
	}

	public function salary() {
		return $this->hasOne( Salary::class, 'emp_id', 'emp_id' );
	}

	public function payroll() {
		return $this->hasMany( Payroll::class, 'emp_id', 'emp_id' );
	}

	public function ticket() {
		return $this->hasMany( Ticket::class, 'ticket_submitted_by', 'emp_id' );
	}

	public function history() {
		return $this->hasMany( EmployeeHistory::class, 'eh_empid', 'emp_id' );
	}

	public function otapplication() {
		return $this->hasMany( OTApplication::class, 'otapp_empid', 'emp_id' );
	}

	public function leaveapplication() {
		return $this->hasMany( LeaveApplication::class, 'leave_empid', 'emp_id' );
	}

	public function osdapplication() {
		return $this->hasMany( OSDApplication::class, 'osd_done_by', 'emp_id' );
	}

	public function kqzemployee() {
		return $this->hasOne( KQZEmployee::class, 'EmployeeCode', 'emp_machineid' );
	}

	public function jobs() {
		return $this->hasMany( Job::class, 'emp_id', 'emp_id' );
	}

	public function runningjobs() {
		return $this->hasMany( Job::class, 'emp_id', 'emp_id' )->where('completion','<','100');
	}

	public function finishedjobs() {
		return $this->hasMany( Job::class, 'emp_id', 'emp_id' )->where('completion','100');
	}

	public function documents() {
		return $this->hasMany( Documents::class, 'emp_id', 'emp_id' );
	}

	public function education() {
		return $this->hasOne( Educations::class, 'emp_id', 'emp_id' );
	}

	// public function device() {
	// 	return $this->hasOne( device::class, 'emp_machineid', 'emp_id' );
	// }
}
