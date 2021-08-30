<?php
/**
 * Created by PhpStorm.
 * User: andrestntx
 * Date: 3/20/16
 * Time: 10:57 PM
 */

namespace App\Recruitment;


use Illuminate\Database\Eloquent\Model;

class Jobseeker extends Model
{
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $table = "r_jobseeker";
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'sex',
        'skills',
        'study',
        'salary',
        'experience'
    ];
}