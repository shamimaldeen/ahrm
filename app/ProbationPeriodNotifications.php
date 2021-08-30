<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProbationPeriodNotifications extends Model
{
    protected $table = "tbl_probationperiodnotifications";
    protected $primaryKey = "id"; 

    protected $fillable = [
        'emp_id',
        'text',
        'approved_by',
    ];

    public function employee() {
    	return $this->hasOne(Employee::class, "emp_id", "emp_id")->select('emp_id','emp_name','emp_empid','emp_machineid','emp_joindate','emp_confirmdate');
    }

    public function approver() {
        return $this->hasOne(Employee::class, "emp_id", "approved_by")->select('emp_id','emp_name','emp_empid','emp_machineid');
    }
}
