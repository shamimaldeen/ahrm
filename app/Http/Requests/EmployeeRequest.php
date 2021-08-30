<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'emp_empid'=>'required',
            'emp_name'=>'required',
            'emp_type'=>'required',
            'emp_phone'=>'required',
            'emp_email'=>'required',
            'emp_sfid'=>'required',
            'emp_dob'=>'required',
            'emp_country'=>'required',
            'emp_desig_id'=>'required',
            'emp_depart_id'=>'required',
            'emp_sdepart_id'=>'required',
            'emp_blgrp'=>'required',
            'emp_education'=>'required',
            'emp_wknd'=>'required',
            'emp_vehicle'=>'required',
            'emp_workhr'=>'required',
            'emp_otent'=>'required',
            'emp_shiftid'=>'required',
            'emp_jlid'=>'required',
            'emp_joindate'=>'required',
            'emp_confirmdate'=>'required',
            'emp_machineid'=>'required',
        ];
    }
}
