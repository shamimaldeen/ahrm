<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KQZEmployee extends Model
{
    protected $table = 'kqz_employee';
	protected $primaryKey = 'EmployeeID';
	public $timestamps = false;

	protected $fillable = [
	    'BrchID',
	    'BrchName',
	    'EmployeeCode',
	    'EmployeeName',
	    'Gender',
	    'Birthday',
	    'Nation',
	    'NATIVEPLACE',
	    'ZhengZhi',
	    'MARITALSTATUS',
	    'Health',
	    'Title',
	    'APOSITION',
	    'EDUCATION',
	    'School',
	    'Graduation',
	    'WorkPhone',
	    'EnrollDate',
	    'Privilege',
	    'Photo',
	    'IDCard',
	    'AddrHome',
	    'CardCode',
	    'Mobile',
	    'IsCheck',
	    'OnRule',
	    'OffRule',
	    'CanOvertime',
	    'HaveFesta',
	    'DevType',
	    'HDCPInfo',
	    'RegisterType',
	    'Comment',
	    'EmployeeLogPwd',
	    'CheckType',
	    'OpendoorType',
	    'ModelNum',
	    'HavePwd',
	    'StaffStatus',
	    'LastCardID',
	    'Reserved1',
	    'Reserved2',
	    'Reserved3',
	    'Reserved4',
	    'devtypestr'
	];

	public function employee() {
		return $this->hasOne( Employee::class, 'emp_machineid', 'EmployeeCode' );
	}
}
