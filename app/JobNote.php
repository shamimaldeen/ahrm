<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobNote extends Model
{
    protected $fillable = [
    	"emp_id",
    	"job_id", 
    	"date",
    	"message",
    	"seen"
    ];
    protected $table = "tbl_jobnote";

    protected $primaryKey = "id";
    public $timestamps = false;

    public function job()
    {
    	return $this->belongsTo(Job::class, "job_id", "id");
    }
    public function employee() {
        return $this->belongsTo(Employee::class, "emp_id", "emp_id")->select('emp_id','emp_name','emp_empid','emp_machineid');
    }
}
