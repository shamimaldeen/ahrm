<?php
namespace App\Http\Controllers\BackEndCon;
use Illuminate\Http\Request;
use Auth;
use DB;
use Session;
use App\Salary;
use App\SalaryHead;
use App\Payroll;
use App\Employee;
use App\Educations;

Class UploadController extends Controller{

	public function upload()
	{
		$id =   Auth::guard('admin')->user();
	    $mainlink = $this->adminmainmenu();
	    $sublink = $this->adminsubmenu();
	    $Adminminlink = $this->adminlink();
	    $adminsublink = $this->adminsublink();

	    return view('Admin.upload.index',compact('id','mainlink','sublink','Adminminlink','adminsublink'));
	}


    public function getColumnNumber($module){
    	if($module == 'employeeData'){
    		return 16;
    	}elseif($module == 'insuranceData'){
    		return 18;
    	}elseif($module == 'salaryData'){
    		return 1;
    	}elseif($module == 'salaryHeadData'){
    		return count(SalaryHead::get())+1;
    	}
    }

    public function getColumnName($module){
    	if($module == 'employeeData'){

    		// return [
	     //        'b' => 'emp_empid',
		    //     'c' => 'emp_name',
		    //     'd' => 'emp_type',
		    //     'e' => 'emp_phone',
		    //     'f' => 'emp_dob',
		    //     'g' => 'emp_country',
		    //     'h' => 'emp_desig_id',
		    //     'i' => 'emp_depart_id',
		    //     'j' => 'emp_sdepart_id',
		    //     'k' => 'emp_blgrp',
		    //     'l' => 'emp_education',
		    //     'm' => 'emp_wknd',
		    //     'n' => 'emp_vehicle',
		    //     'o' => 'emp_workhr',
		    //     'p' => 'emp_otent',
		    //     'q' => 'emp_shiftid',
		    //     'r' => 'emp_email',
		    //     's' => 'emp_jlid',
		    //     't' => 'emp_joindate',
		    //     'u' => 'emp_confirmdate',
		    //     'v' => 'emp_emjcontact',
		    //     'w' => 'emp_crntaddress',
		    //     'x' => 'emp_prmntaddress',
		    //     'y' => 'emp_machineid',
		    //     'z' => 'tin_no',
		    //     'aa' => 'bu_code',
		    //     'ab' => 'emp_nid',
		    //     'ac' => 'emp_name',
		    //     'ad' => 'emp_seniorid',
		    //     'ae' => 'emp_name',
		    //     'af' => 'emp_authperson', 
		    //     'ag' => 'emp_status'
      //       ];
            return [
	            'a' => 'emp_empid',
		        'b' => 'emp_name',
		        'c' => 'emp_phone',
		        'd' => 'emp_dob',
		        'e' => 'emp_desig_id',
		        'f' => 'emp_depart_id',
		        'g' => 'emp_sdepart_id',
		        'h' => 'emp_email',
		        'i' => 'emp_joindate',
		        'j' => 'emp_emjcontact',
		        'k' => 'emp_crntaddress',
		        'l' => 'emp_prmntaddress',
		        'm' => 'emp_nid',
		        'n' => 'emp_status',
		        'o' => 'emp_machineid',
		        'p' => 'emp_type'
            ];
    		// return [
	       //      'a' => 'emp_empid',
		      //   'b' => 'emp_name',
		      //   'c' => 'emp_type',
		      //   'd' => 'emp_desig_id',
		      //   'e' => 'emp_depart_id',
		      //   'f' => 'emp_sdepart_id',
		      //   'g' => 'emp_seniorid',
		      //   'h' => 'emp_machineid',
        //     ];

          //   return [
	         //    'a' => 'emp_empid',
		        // 'b' => 'bank_account',
          //   ];
        }elseif($module == 'insuranceData'){
        	return [
                'a' => 'emp_empid',
                'b' => 'self_member_id',
                'c' => 'effective_date',
                'd' => 'spouse_member_id',
                'e' => 'spouse_name',
                'f' => 'spouse_dob',
                'g' => 'spouse_start_date',
                'h' => 'spouse_end_date',
                'i' => 'child1_member_id',
                'j' => 'child1_name',
                'k' => 'child1_dob',
                'l' => 'child1_start_date',
                'm' => 'child1_end_date',
                'n' => 'child2_member_id',
                'o' => 'child2_name',
                'p' => 'child2_dob',
                'q' => 'child2_start_date',
                'r' => 'child2_end_date',
            ];
        }elseif($module == 'salaryData'){
        	return [
                'a' => 'emp_empid',
                'b' => 'tin_no',
                'c' => 'grade',
                'd' => 'bank_account',
                'e' => 'bu_code',
                'f' => 'category',
                'g' => 'ten_steps',
                'h' => 'gender',
                'i' => 'basic_salary',
                'j' => 'house_rent',
                'k' => 'medical',
                'l' => 'living',
                'm' => 'conv',
                'n' => 'special',
                'o' => 'others',
            ];
        }elseif($module == 'salaryHeadData'){
        	$ColumnName = array();
        	$ColumnName["a"]="emp_empid";
        	$head=SalaryHead::get();
        	foreach ($head as $key => $value) {
        		$ColumnName[$this->getAlphabet($key+1)]=$value["head_id"];
        	}
        	return $ColumnName;
        }
    }

    public function getColumnOption($module){
    	if($module == 'employeeData'){

    		// return [
      //           'emp_empid' => 'Employee ID',
		    //     'emp_name' => 'Name',
		    //     'emp_type' => 'Employee Type',
		    //     'emp_phone' => 'Phone',
		    //     'emp_dob' => 'Date Of Birth',
		    //     'emp_country' => 'Country',
		    //     'emp_desig_id' => 'Designation',
		    //     'emp_depart_id' => 'Department',
		    //     'emp_sdepart_id' => 'Sub-Department',
		    //     'emp_blgrp' => 'Blood Group',
		    //     'emp_education' => 'Employee Education',
		    //     'emp_wknd' => 'Employee Weekend',
		    //     'emp_vehicle' => 'Vehicle',
		    //     'emp_workhr' => 'Working Hour', 
		    //     'emp_otent' => 'OT',
		    //     'emp_shiftid' => 'Shift',
		    //     'emp_email' => 'Email',
		    //     'emp_jlid' => 'Location',
		    //     'emp_joindate' => 'Join Date',
		    //     'emp_confirmdate' => 'Confirm Date',
		    //     'emp_emjcontact' => 'Emergency Contact',
		    //     'emp_crntaddress' => 'Current Address',
		    //     'emp_prmntaddress' => 'Permanent Address',
		    //     'emp_machineid' => 'Face ID',
		    //     'tin_no' => 'TIN NO',
		    //     'bu_code' => 'BU Code',
		    //     'emp_nid' => 'NID No.',
		    //     'emp_name' => 'Senior Name',
		    //     'emp_seniorid' => 'Senior ID',
		    //     'emp_name' => 'Auth Person Name',
		    //     'emp_authperson' => 'Auth Person ID',
		    //     'emp_status' => 'Status'
      //       ];

            return [
                'emp_empid' => 'Employee ID',
		        'emp_name' => 'Name',
		        'emp_phone' => 'Phone',
		        'emp_dob' => 'Date Of Birth',
		        'emp_desig_id' => 'Designation',
		        'emp_depart_id' => 'Department',
		        'emp_sdepart_id' => 'Sub-Department',
		        'emp_email' => 'Email',
		        'emp_joindate' => 'Join Date',
		        'emp_emjcontact' => 'Emergency Contact',
		        'emp_crntaddress' => 'Current Address',
		        'emp_prmntaddress' => 'Permanent Address',
		        'emp_nid' => 'NID No.',
		        'emp_status' => 'Status',
		        'emp_machineid' => 'Face ID',
		        'emp_type' => 'Employee Type'
            ];

          //   return [
          //       'emp_empid' => 'Employee ID',
		        // 'emp_name' => 'Name',
		        // 'emp_type' => 'Type',
		        // 'emp_desig_id' => 'Designation',
		        // 'emp_depart_id' => 'Department',
		        // 'emp_sdepart_id' => 'Sub-Department',
		        // 'emp_seniorid' => 'Supervisor',
		        // 'emp_machineid' => 'Face ID',
          //   ];
          //   return [
          //       'emp_empid' => 'Employee ID',
		        // 'bank_account' => 'Bank Account',
          //   ];
        }elseif ($module == 'insuranceData') {
        	return [
                'emp_empid'=>'Employee ID',
                'self_member_id'=>'Self Member ID',
                'effective_date'=>'Effective Date',
                'spouse_member_id'=>'Spouse Member ID',
                'spouse_name'=>'Name',
                'spouse_dob'=>'Date Of Birth',
                'spouse_start_date'=>'Insurance Start Date',
                'spouse_end_date'=>'End Date',
                'child1_member_id'=>'Child-1 Member ID',
                'child1_name'=>'Name',
                'child1_dob'=>'Date Of Birth',
                'child1_start_date'=>'Insurance Start Date',
                'child1_end_date'=>'End Date',
                'child2_member_id'=>'Child-2 Member ID',
                'child2_name'=>'Name',
                'child2_dob'=>'Date Of Birth',
                'child2_start_date'=>'Insurance Start Date',
                'child2_end_date'=>'End Date',
            ];
        }elseif ($module == 'salaryData') {
        	return [
                'emp_empid' => 'Employee ID',
                'tin_no' => 'TIN No.',
                'grade' => 'Grade',
                'bank_account' => 'Bank Account',
                'bu_code' => 'BU Code',
                'category' => 'Category',
                'ten_steps' => '10 Steps',
                'gender' => 'Gender',
                'basic_salary' => 'Basic Salary',
                'house_rent' => 'House Rent',
                'medical' => 'Medical Allowance',
                'living' => 'Living Allowance',
                'conv' => 'Conveyance ',
                'special' => 'Special Allowance',
                'others' => 'Others Allowance',
            ];
        }elseif ($module == 'salaryHeadData') {
        	$ColumnOption = array();
        	$ColumnOption["emp_empid"]='Employee ID';
        	$ColumnName=$this->getColumnName($module);
        	foreach ($ColumnName as $key => $value) {
        		if($value!="emp_empid"){
    				$head=SalaryHead::find($value);
    				if($head->head_type=="1"){
    					$ColumnOption[$value]='<span class="text-success">'.$head->head_name.'</span>';
    				}else{
    					$ColumnOption[$value]='<span class="text-danger">'.$head->head_name.'</span>';
    				}
        		}
        	}
        	return $ColumnOption;
        }
    }

    public function getAlphabet($i){
	    $data = [
	        '0' => 'a',
	        '1' => 'b',
	        '2' => 'c',
	        '3' => 'd',
	        '4' => 'e',
	        '5' => 'f',
	        '6' => 'g',
	        '7' => 'h',
	        '8' => 'i',
	        '9' => 'j',
	        '10' => 'k',
	        '11' => 'l',
	        '12' => 'm',
	        '13' => 'n',
	        '14' => 'o',
	        '15' => 'p',
	        '16' => 'q',
	        '17' => 'r',
	        '18' => 's',
	        '19' => 't',
	        '20' => 'u',
	        '21' => 'v',
	        '22' => 'w',
	        '23' => 'x',
	        '24' => 'y',
	        '25' => 'z',
	        '26' => 'aa',
	        '27' => 'ab',
	        '28' => 'ac',
	        '29' => 'ad',
	        '30' => 'ae',
	        '31' => 'af',
	        '32' => 'ag',
	        '33' => 'ah',
	        '34' => 'ai',
	        '35' => 'aj', 
	        '36' => 'ak', 
	        '37' => 'al' 
	    ];

	    return $data[$i];
	}

	public function uploadPreview(Request $request){
		$module=$request->module;
		$id =   Auth::guard('admin')->user();
	    $mainlink = $this->adminmainmenu();
	    $sublink = $this->adminsubmenu();
	    $Adminminlink = $this->adminlink();
	    $adminsublink = $this->adminsublink();


		if(!in_array($module, ['employeeData','insuranceData','salaryData','salaryHeadData'])){
			session()->flash('error','Something Went Wrong! Module Not Found!');
            return redirect()->back();
		}

        if(!$request->file('file')){
        	session()->flash('error','Please Choose a file to upload');
            return redirect()->back();
        }

		$filename = 'BVFILE-'.$module;
        $extension = $request->file('file')->getClientOriginalExtension();

        $allowed_file_types = ['csv'];
        if(!in_array($extension, $allowed_file_types)){
        	session()->flash('error','Please Choose a (.CSV) file to upload');
            return redirect()->back();
        }

        if(file_exists(storage_path().'/app/upload/'.$filename.".".$extension)){
        	unlink(storage_path().'/app/upload/'.$filename.".".$extension);
        }

        $file = $request->file('file')->storeAs('upload', $filename.".".$extension);
        $filename_extension = storage_path().'/app/upload/'.$filename.".".$extension;
        session(['uploaded_file' => $filename.'.'.$extension]);
        session(['upload_date_of_execution' => $request->upload_date_of_execution]);

        include(base_path().'/app/Helper/ExcelReader/SpreadsheetReader.php');

        $data = array();
        $xls_datas = array();
        $Reader = new \SpreadsheetReader($filename_extension);
        $i = 0;
        foreach ($Reader as $key => $row){ 
        	$no_of_column = $this->getColumnNumber($module);
        	for($j = 0; $j < $no_of_column; $j++){
        		$data[$this->getAlphabet($j)] = array_key_exists($j, $row) ? rtrim($row[$j]) : null;
        		if($data[$this->getAlphabet($j)]=="\N"){
        			$data[$this->getAlphabet($j)]='';
        		}
        	}
        	$xls_datas[] = $data;
        	unset($data);
        }

        if(!count($xls_datas)){
        	session()->flash('error','Something Went Wrong! No Data Found from your file!');
            return redirect()->back();
        }
        	
        $columns = $this->getColumnName($module);
        $column_options = $this->getColumnOption($module);
        return view('Admin.upload.preview',compact('xls_datas','columns','module','column_options','id','mainlink','sublink','Adminminlink','adminsublink'));
	}

	public function employeeType($data)
	{
		if(empty($data)){
			return '0';
		}
		$search=DB::table('tbl_employee_types')->where(DB::raw('trim(name)'),trim($data))->first();
		if(isset($search)){
			return $search->id;
		}else{
			DB::table('tbl_employee_types')->insert(['name'=>trim($data)]);
			return DB::table('tbl_employee_types')->max('id');
		}
	}

	public function designation($data)
	{
		if(empty($data)){
			return '0';
		}
		$search=DB::table('tbl_designation')->where(DB::raw('trim(desig_name)'),trim($data))->first();
		if(isset($search)){
			return $search->desig_id;
		}else{
			DB::table('tbl_designation')->insert(['desig_name'=>trim($data)]);
			return DB::table('tbl_designation')->max('desig_id');
		}
	}

	public function department($data)
	{
		if(empty($data)){
			return '0';
		}
		$search=DB::table('tbl_department')->where(DB::raw('trim(depart_name)'),trim($data))->first();
		if(isset($search)){
			return $search->depart_id;
		}else{
			DB::table('tbl_department')->insert(['depart_name'=>trim($data)]);
			return DB::table('tbl_department')->max('depart_id');
		}
	}

	public function tinno($data)
	{
		if(empty($data)){
			return '0';
		}
		$search=DB::table('tbl_salary')->where(DB::raw('trim(tin_no)'),trim($data))->first();
		if(isset($search)){
			return $search->tin_no;
		}else{
			DB::table('tbl_salary')->insert(['tin_no'=>trim($data)]);
			return DB::table('tbl_salary')->max('tin_no');
		}
	}

	public function bucode($data)
	{
		if(empty($data)){
			return '0';
		}
		$search=DB::table('tbl_salary')->where(DB::raw('trim(bu_code)'),trim($data))->first();
		if(isset($search)){
			return $search->bu_code;
		}else{
			DB::table('tbl_salary')->insert(['bu_code'=>trim($data)]);
			return DB::table('tbl_salary')->max('bu_code');
		}
	}

	public function subdepartment($depart_name,$sdepart_name)
	{
		if(empty($sdepart_name)){
			return '0';
		}
		$sdepart_departid=$this->department($depart_name);
		$search=DB::table('tbl_subdepartment')->where('sdepart_departid',$sdepart_departid)->where(DB::raw('trim(sdepart_name)'),trim($sdepart_name))->first();
		if(isset($search)){
			return $search->sdepart_id;
		}else{
			DB::table('tbl_subdepartment')->insert(['sdepart_departid'=>$sdepart_departid,'sdepart_name'=>trim($sdepart_name)]);
			return DB::table('tbl_subdepartment')->max('sdepart_id');
		}
	}

	public function joblocation($jl_name)
	{
		if(empty($jl_name)){
			return '0';
		}
		$search=DB::table('tbl_joblocation')->where(DB::raw('trim(jl_name)'),trim($jl_name))->first();
		if(isset($search)){
			return $search->jl_id;
		}else{
			DB::table('tbl_joblocation')->insert(['jl_name'=>trim($jl_name)]);
			return DB::table('tbl_joblocation')->max('jl_id');
		}
	}

	public function createNewEmployee($emp_empid)
	{
		$id =   Auth::guard('admin')->user();
		$emp_empid=str_replace(' ','',$emp_empid);
		if(!empty($emp_empid)){
			DB::table('tbl_employee')->insert(['emp_empid'=>trim($emp_empid)]);
			$emp_id=DB::table('tbl_employee')->max('emp_id');
			DB::table('tbl_insurance')->insert(['emp_id'=>$emp_id]);
			DB::table('tbl_salary')->insert(['emp_id'=>$emp_id]);
	        $this->empHistory($emp_id,'Employee Information Created');

	        $emp_empid=str_replace(' ','',$emp_empid);
	        if(strlen($emp_empid)>0){
	        $search=DB::table('tbl_sysuser')->where('email',$emp_empid)->first();
	            if(isset($search) && count($search)>0){

	            }else{
	                $explode=explode('-',$emp_empid);
	                if(isset($explode[1]) && $explode[1]!=""){
	                    $email=$emp_empid;
	                    $password=$explode[1];
	                    $insert=DB::table('tbl_sysuser')
	                        ->insert([
	                            'suser_empid'=>$emp_id,
	                            'email'=>$email,
	                            'password'=>bcrypt($password),
	                            'suser_level'=>'5',
	                        ]);
	                    if($insert){
	                        $this->empHistory($emp_id,'System User Account Has Been Created');
	                    }
	                }else{
	                    $email=$emp_empid;
	                    $password=substr($emp_empid,6,10);
	                    $insert=DB::table('tbl_sysuser')
	                        ->insert([
	                            'suser_empid'=>$emp_id,
	                            'email'=>$email,
	                            'password'=>bcrypt($password),
	                            'suser_level'=>'5',
	                        ]);
	                    if($insert){
	                        $this->empHistory($emp_id,'System User Account Has Been Created');
	                    }
	                }
	            }
	        }

	        $UpdateShift=DB::table('tbl_dailyshift')
	            ->insert([
	                'ds_empid'=>$emp_id,
	                'ds_shiftid'=>'1',
	                'ds_date'=>'2018-01-01',
	                'ds_createdat'=>date('Y-m-d H:i:s'),
	                'ds_createdby'=>$id->suser_empid,
	            ]);
	        if($UpdateShift){
	            $this->empHistory($emp_id,'Shift Created To : <b>'.$this->getShiftInfo('1').'</b>');
	        }

	        return $emp_id;
		}else{
			return '0';
		}
		
	}

	public function checkEmployeeExist($data)
	{
		$data=str_replace(' ','',$data);
		$search=DB::table('tbl_employee')->where(DB::raw("REPLACE(`emp_empid`,' ','')"),$data)->first();
		if(isset($search)){
			return $search->emp_id;
		}else{
			DB::table('tbl_employee')->insert(['emp_empid'=>$data]);
			return DB::table('tbl_employee')->max('emp_id');
		}
	}

	public function dataGenerator($module,$filename_extension)
	{
		include(base_path().'/app/Helper/ExcelReader/SpreadsheetReader.php');
        $Reader = new \SpreadsheetReader($filename_extension);
		$xls_datas = array();

		if($module=="employeeData"){
			foreach ($Reader as $key => $row){
				if($key>0){
					$search=DB::table('tbl_employee')->where(DB::raw('trim(emp_empid)'),trim($row[0]))->first();
					if(isset($search)){
						$emp_empid=$search->emp_id;
					}else{
						$emp_empid=$this->createNewEmployee($row[0]);
					}

					// $xls_datas[] = array(
					//    'emp_empid' => str_replace(' ','',$emp_empid),
				 //       'emp_name'  => trim($row[2]),
				 //       'emp_type'  => trim($row[3]),
				 //       'emp_phone' => trim($row[4]),
				 //       'emp_sfid'  => 1,
				 //       'emp_dob'      => date('Y-m-d',strtotime(trim($row[5]))),
				 //       'emp_country'  => '18',
				 //       'emp_desig_id' => $this->designation($row[7]),
				 //       'emp_depart_id' => $this->department($row[8]),
				 //       'emp_sdepart_id' => $this->subdepartment($row[8],$row[9]),
				 //       'emp_blgrp' => trim($row[10]),
				 //       'emp_education' => trim($row[11]),
				 //       'emp_wknd' => trim($row[12]),
				 //       'emp_vehicle' => trim($row[13]),
				 //       'emp_workhr' => trim($row[14]),
				 //       'emp_otent' => trim($row[15]),
				 //       'emp_shiftid' => trim($row[16]),
				 //       'emp_email' => trim($row[17]),
				 //       'emp_jlid' => trim($row[18]),
				 //       'emp_joindate' => date('Y-m-d',strtotime(trim($row[19]))),
				 //       'emp_confirmdate' => date('Y-m-d', strtotime("+180 day", strtotime(date('Y-m-d',strtotime($row[20]))))),
				 //       'emp_emjcontact' => trim($row[21]),
				 //       'emp_crntaddress' => trim($row[22]),
				 //       'emp_prmntaddress' => trim($row[23]),
				 //       'emp_machineid' => trim($row[24]),
				 //       // 'tin_no' => $this->tinno($row[25]),
				 //       // 'bu_code' => $this->bucode($row[26]),
				 //       'emp_nid' => 1,
				 //       // 'emp_name' => trim($row[28]),
				 //       'emp_seniorid' => 1,
				 //       // 'emp_name' => trim($row[30]),
				 //       'emp_authperson' => 1,
				 //       'emp_status' => 1

				 //       // 'emp_type' => 1
				 //       // 'gross_salary' => str_replace(',','',trim($row[4]))
	    //     		);

		            $xls_datas[] = array(
					   'emp_empid' => str_replace(' ','',$emp_empid),
				       'emp_name'  => trim($row[1]),
				       'emp_phone' => trim($row[2]),
				       'emp_sfid'  => trim($row[4]),
				       'emp_dob'      => date('Y-m-d',strtotime(trim($row[3]))),
				       'emp_country'  => '18',
				       'emp_desig_id' => $this->designation($row[4]),
				       'emp_depart_id' => $this->department($row[5]),
				       'emp_sdepart_id' => $this->subdepartment($row[5],$row[5]),
				       'emp_email' => trim($row[7]),
				       'emp_joindate' => date('Y-m-d',strtotime(trim($row[8]))),
				       'emp_confirmdate' => date('Y-m-d', strtotime("+180 day", strtotime(date('Y-m-d',strtotime($row[8]))))),
				       'emp_emjcontact' => trim($row[9]),
				       'emp_crntaddress' => trim($row[10]),
				       'emp_prmntaddress' => trim($row[11]),
				       'emp_nid' => trim($row[12]),
				       'emp_status' => 1,
				       // 'emp_type' => 1
				       'emp_machineid' => trim($row[14]),
				       'emp_type' => $this->employeeType($row[15])
				       // 'gross_salary' => str_replace(',','',trim($row[4]))
	        		);

	       //  		$xls_datas[] = array(
					   // 'emp_empid' => str_replace(' ','',trim($emp_empid)),
				    //    'emp_name' => trim($row[1]),
				    //    'emp_type' => $this->employeeType($row[2]),
				    //    'emp_desig_id' => $this->designation($row[3]),
				    //    'emp_depart_id' => $this->department($row[4]),
				    //    'emp_sdepart_id' => $this->subdepartment($row[4],$row[5]),
				    //    'emp_seniorid' => $this->checkEmployeeExist($row[6]),
				    //    'emp_machineid' => trim($row[7]),
	       //  		);
					// $xls_datas[] = array(
					//    'emp_empid' => str_replace(' ','',trim($emp_empid)),
				 //       'bank_account' => trim($row[1]),
	    //     		);
			    }
	        }
		}elseif ($module=="insuranceData") {
			foreach ($Reader as $key => $row){
				if($key>0){
					$search=DB::table('tbl_employee')->where(DB::raw('trim(emp_empid)'),trim($row[0]))->first();
					if(isset($search)){
						$emp_empid=$search->emp_id;
					}else{
						$emp_empid=$this->createNewEmployee($row[0]);
					}

					$xls_datas[] = array(
						'emp_empid' => $emp_empid,
						'self_member_id' => $this->formatting($row[1]),
						'effective_date' => $this->formatting($row[2]),
						'spouse_member_id' => $this->formatting($row[3]),
						'spouse_name' => $this->formatting($row[4]),
						'spouse_dob' => $this->formatting($row[5]),
						'spouse_start_date' => $this->formatting($row[6]),
						'spouse_end_date' => $this->formatting($row[7]),
						'child1_member_id' => $this->formatting($row[8]),
						'child1_name' => $this->formatting($row[9]),
						'child1_dob' => $this->formatting($row[10]),
						'child1_start_date' => $this->formatting($row[11]),
						'child1_end_date' => $this->formatting($row[12]),
						'child2_member_id' => $this->formatting($row[13]),
						'child2_name' => $this->formatting($row[14]),
						'child2_dob' => $this->formatting($row[15]),
						'child2_start_date' => $this->formatting($row[16]),
						'child2_end_date' => $this->formatting($row[17]),
		            );
		        }
	        }
		}elseif ($module=="salaryData") {
			foreach ($Reader as $key => $row){
				if($key>0){
					$search=DB::table('tbl_employee')->where(DB::raw('trim(emp_empid)'),trim($row[0]))->first();
					if(isset($search)){
						$emp_empid=$search->emp_id;
					}else{
						$emp_empid=$this->createNewEmployee($row[0]);
					}

		            $xls_datas[] = array(
						'emp_empid' => $emp_empid,
		                'tin_no' => trim($row[1]),
		                'grade' => trim($row[1]),
		                'bank_account' => trim($row[1]),
		                'bu_code' => trim($row[1]),
		                'category' => trim($row[1]),
		                'ten_steps' => trim($row[1]),
		                'gender' => trim($row[1]),
		                'basic_salary' => trim($row[1]),
		                'house_rent' => trim($row[1]),
		                'medical' => trim($row[1]),
		                'living' => trim($row[1]),
		                'conv' => trim($row[1]),
		                'special' => trim($row[1]),
		                'others' => trim($row[1]),
		            );
		        }
	        }
		}elseif ($module=="salaryHeadData") {
			$i=-1;
			foreach ($Reader as $key => $row){
				if($key>0){
					$i++;
					$search=DB::table('tbl_employee')->where(DB::raw('trim(emp_empid)'),trim($row[0]))->first();
					if(isset($search)){
						$emp_empid=$search->emp_id;
					}else{
						$emp_empid=$this->createNewEmployee($row[0]);
					}
		            $xls_datas[$i]['emp_empid']=$emp_empid;
		            $ColumnName=$this->getColumnName($module);
		            $c=0;
		        	foreach ($ColumnName as $key => $value) {
		        		if($value!="emp_empid" && $value!="5"){
		        			$c++;
		    				$head=SalaryHead::find($value)->first();
		    				if(isset($row[$c])){
		    					$row_show=$row[$c];
		    				}else{
		    					$row_show=0;
		    				}
		    				$xls_datas[$i][$value]=$row_show;
		        		}elseif($value=="5"){
		        			$xls_datas[$i][$value]=250;
		        		}
		        	}

		        }
	        }
		}
		
        return $xls_datas; 
	}

	public function formatting($data)
	{
		$data=str_replace("\\N", '', $data);
		return $data;
	}

	public function dataCheck($module,$data)
	{
		$msg='';
		if ($module=="employeeData") {
			foreach ($data as $key => $value) {
				$error=0;
				if($key>0){
					if($value['emp_empid']=="0"){
						$error++;
						$msg.='Employee ID not matched in row : '.$key.'.';
					}

					if($value['emp_name']==""){
						$error++;
						$msg.='Employee Name is empty in row : '.$key.'.';
					}

					if($value['emp_desig_id']=="0"){
						$error++;
						$msg.='Employee Designation is empty in row : '.$key.'.';
					}

					if($value['emp_depart_id']=="0"){
						$error++;
						$msg.='Employee Department is empty in row : '.$key.'.';
					}
				}

				if($error>0){
					$msg.='<br><br>';
					$error=0;
				}
			}
		} elseif ($module=="insuranceData") {
			foreach ($data as $key => $value) {
				$error=0;
				if($key>0){
					if($value['emp_empid']=="0"){
						$error++;
						$msg.='Employee ID not matched in row : '.$key.'.';
					}
				}
				if($error>0){
					$msg.='<br><br>';
					$error=0;
				}
			}
		} elseif ($module=="salaryData") {
			foreach ($data as $key => $value) {
				$error=0;
				if($key>0){
					if($value['emp_empid']=="0"){
						$error++;
						$msg.='Employee ID not matched in row : '.$key.'.';
					}
				}
				if($error>0){
					$msg.='<br><br>';
					$error=0;
				}
			}
		} elseif ($module=="salaryHeadData") {
			foreach ($data as $key => $value) {
				$error=0;
				if($key>0){
					if($value['emp_empid']=="0" || $value['emp_empid']==""){
						//$error++;
						//$msg.='Employee ID not matched in row : '.$key.'.';
					}
				}
				if($error>0){
					$msg.='<br><br>';
					$error=0;
				}
			}
		}

		if(strlen($msg)>1){
			return response()->json([
				"success"=>false,
				"msg"=>$msg
			]);
		}else{
			return response()->json([
				"success"=>true,
			]);
		}
	}

	public function uploader($module,$data)
	{
		$id =   Auth::guard('admin')->user();
		$success=0;
		$error=0;
		$success_msg='';
		$error_msg='';
		if ($module=="employeeData") {
			foreach ($data as $key => $value) {
				$emp_empid=$value['emp_empid'];
				if(!empty($emp_empid)){
					$employeeData=array_shift($value);
					// $upload=Salary::where('emp_id',$emp_empid)->update($value);
					$upload=DB::table('tbl_employee')
						->where('emp_id',$emp_empid)
						->update($value);
					if($upload){
						
						if(isset($value["gross_salary"])){
							$gross=$value["gross_salary"];
						}else{
							$gross=0;
						}

						$salary=array();
						$salary[1]=0;
						$salary[2]=0;
						$salary[3]=0;
						$salary[4]=0;
						if($gross>0){
							$salary[1]=$gross*(40/100);
							$salary[2]=$gross*(40/100);
							$salary[3]=$gross*(10/100);
							$salary[4]=$gross*(10/100);
						}
						$SalaryHead=SalaryHead::get();
						foreach ($SalaryHead as $key => $head) {
							$search=Payroll::where([
								'emp_id' => $emp_empid,
							    'head_date_of_execution' => '2019-01-01',
							    'head_id' => $head->head_id,
							])
							->first();
							if(!isset($search->emp_id)){
								Payroll::insert([
									'emp_id' => $emp_empid,
								    'head_date_of_execution' => '2019-01-01',
								    'head_id' => $head->head_id,
								    'amount' => 18000,
								    'type' => 1
								]);
							}
						}

						// $em = Employee::orderBy('emp_id', 'DESC')->first();
				        $ed = new Educations;
				        $ed->emp_id = $emp_empid; 
				        $ed->save();

						$success++;
					}else{
						$error++;
					}
				}else{
					$error++;
				}
			}
		} elseif ($module=="insuranceData") {
			foreach ($data as $key => $value) {
				$emp_empid=$value['emp_empid'];
				if(!empty($emp_empid)){
					$insuranceData=array_shift($value);
					$upload=DB::table('tbl_insurance')
						->where('emp_id',$emp_empid)
						->update($value);
					if($upload){
						$success++;
					}else{
						$error++;
					}
				}else{
					$error++;
				}
			}
		} elseif ($module=="salaryData") {
			foreach ($data as $key => $value) {
				$emp_empid=$value['emp_empid'];
				if(!empty($emp_empid)){
					$salaryData=array_shift($value);
					$upload=DB::table('tbl_salary')
						->where('emp_id',$emp_empid)
						->update($value);
					if($upload){
						$success++;
					}else{
						$error++;
					}
				}else{
					$error++;
				}
			}
		} elseif ($module=="salaryHeadData") {
			foreach ($data as $key => $value) {
				$emp_empid=$value['emp_empid'];
				if(!empty($emp_empid)){
					unset($value['emp_empid']);
					$SalaryHead=SalaryHead::get();
					$gross=0;
					foreach ($SalaryHead as $head) {
						$gross+=$value[$head->head_id];
						$search=Payroll::where([
									'emp_id' => $emp_empid,
								    'head_date_of_execution' => session('upload_date_of_execution'),
								    'head_id' => $head->head_id
								])
							    ->first();
						if(isset($search->emp_id)){
							$upload=Payroll::where([
									'emp_id' => $emp_empid,
								    'head_date_of_execution' => session('upload_date_of_execution'),
								    'head_id' => $head->head_id
								])
								->update([
								    'amount' => $value[$head->head_id],
								    'updated_at' => date('Y-m-d H:i:s'),
								    'updated_by' => $id->suser_empid,
								]);
						}else{
							$upload=Payroll::insert([
								'emp_id' => $emp_empid,
							    'head_date_of_execution' => session('upload_date_of_execution'),
							    'head_id' => $head->head_id,
							    'amount' => $value[$head->head_id],
							    'updated_at' => date('Y-m-d H:i:s'),
							    'updated_by' => $id->suser_empid,
							]);
						}
						if($upload){
							Salary::where('emp_id',$emp_empid)->update([
								'gross' => $gross
							]);
							$success++;
						}else{
							$error++;
						}
					}
				}else{
					$error++;
				}
			}
		}

		if($success>0){
			$success_msg.=$success.' row has been uploaded successfully.';
		}

		if($error>0){
			$error_msg.=$error.' row cannot be uploaded due to error or inappropiate data.';
		}

		return response()->json([
			"success"=>true,
			"success_msg"=>$success_msg,
			"error_msg"=>$error_msg,
		]);
	}

	public function uploadToTable(Request $request){
		$module=$request->module;

        $file = session('uploaded_file');
        $filename_array = explode('.', $file);
        $filename = $filename_array[0];

        $filename_extension = storage_path().'/app/upload/'.$file;

        if(session()->has('uploaded_file') && \File::exists($filename_extension)){
        	$data=$this->dataGenerator($module,$filename_extension);
	        
	        if(count($data)){
	            $check=$this->dataCheck($module,$data)->getData();
				if($check->success){
					$uploader=$this->uploader($module,$data)->getData();
					if($uploader->success){
						if(strlen($uploader->success_msg)>1){
							session()->flash('success',$uploader->success_msg);
						}
						if(strlen($uploader->error_msg)>1){
							session()->flash('error',$uploader->error_msg);
						}
						session()->forget('uploaded_file');
						session()->forget('upload_date_of_execution');
					}
				}else{
					session()->flash('error',$check->msg);
				}
	        }else{
	        	session()->flash('error','No Data Found!');
	        }
        }else{
        	session()->flash('error','something Went Wrong!');
        }

        return redirect('upload');
    }
}