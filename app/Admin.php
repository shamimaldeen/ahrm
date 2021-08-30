<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'tbl_sysuser';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function employee() {
        return $this->hasOne( Employee::class, 'emp_id', 'suser_empid' )->orderBy('tbl_employee.emp_name','asc');
    }

    public function priority() {
        return $this->hasOne( Priority::class, 'pr_id', 'suser_level' );
    }
}
