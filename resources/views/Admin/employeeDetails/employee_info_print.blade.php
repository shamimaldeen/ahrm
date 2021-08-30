
@php
use App\Http\Controllers\BackEndCon\Controller;
use Illuminate\Support\Facades\Route;
$route=Route::getFacadeRoot()->current()->uri();
@endphp

<style type="text/css">
.bold{
    font-weight: bold;
    font-size:14px;
    text-align: right;
}
*{
	margin:0;
	padding: 0;
	box-sizing: border-box;
}
h3{
   text-align: center;
   padding: 0.5rem;
   background-color: #f3f3f3;
}
.company-logo{
	width:80px;
	max-width: 80px;
  height: 60px;
}
.company-logo img{
	width:100%;
  height:100%;
}
/*.user-image{
	width: 100px;
	height: 100px;
	border: 1px solid black;
	margin-left:auto;
	margin-right: 0;
	
}*/
.user-imgae img{
	width:100%;
	height: 100%;
}
.employee-information-wrapper{
	background-color: #f1f1f1;

}
.d-flex{
    display:flex;
    justify-content: center;
    align-items: center;
    
}
.d-flex>div{
    margin-right:1rem;
 
}

.print-information label{
   background-color:#DBBEBE;
   display: inline-block;
   padding: 0.2rem 0.4rem;
   border-radius:1px;
/*   border:1px solid grey;
*/   width: 180px;
   max-width: 180px;

}

table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}
.border-none th{
    border:0px;
    padding:0px;
}

</style>
<style type="text/css">
        @media print{
            #p {
                display: none;
            }
        }
</style>
<input type="button" id="p" value="print" onclick="printPage();" class="btn btn-label-success btn-sm btn-upper">

          
          <!--header information part-->
          
    <header>
    <div class="container">
      <div class="row">
            <div class="col-md-12 d-flex">
              <div class="company-logo">
                <img src="{{url('/')}}/public/Main_logo.jpg" alt="comapnylogo-image"/>
              </div>
               <div class="company-name mx-3">
                <h4> PFI Securities Limited </h4>
                <small>56-57, 7th& 8th Floor,PFL Tower,Dilkusha C/A,Dhaka 1000</small>
              </div>
            </div>
      </div>
    </div>
   </header>
  
              
            
      
    <div class="print-information">
        <div class="container" style="width: 95%; margin: auto;">
            <div class="row">
              <div class="col-md-12">
                   <div class="user-image ">
                  
                      @if($employeeDetails->emp_imgext!="")
                      <img style="height: 100px;border: 1px solid black;padding: 5px;float: right;" src="{{URL::to('public/EmployeeImage')}}/{{$employeeDetails->emp_id}}.{{$employeeDetails->emp_imgext}}">
                      @else
                      <img  style="height: 100px;border: 1px solid black;padding: 5px;float: right;" src="{{URL::to('public')}}/male.jpg"/>
                      @endif

                   
              </div>

              <h3 class="text-center">
                <br>
                <br>
                <br>
               Employee Information </h3>
             
          </div>
    <table style='width:100%;'>
     <tr class="border-none">
        <th style='width:20%;'></th>
        <th style='width:30%;'></th>
        <th style='width:20%;'></th>
        <th style='width:30%;'></th>
     </tr>
     <tr>
        <td class='bold'>Employee Name : </td>
        <td> {{$employeeDetails->emp_name}}</td>
        <td class='bold'>Employee Type : </td>
        <td>{{$employeeDetails->type->name}}</td>
     </tr>
      
     <tr>
        <td class='bold'>Employee ID  : </td>
        <td> {{$employeeDetails->emp_empid}}</td>
        <td class='bold'>SF ID : </td>
        <td>{{$employeeDetails->emp_sfid}}</td>
     </tr>
      
     <tr>
        <td class='bold'>Contact Number:  </td>
        <td> {{$employeeDetails->emp_phone}}</td>
        <td class='bold'>Date of Birth: </td>
        <td> {{$employeeDetails->emp_dob}}</td>
     </tr>
      
     <tr>
        <td class='bold'>Country: </td>
        <td>  {{$employeeDetails->country->country_name}}</td>
        <td class='bold'>Designation:  </td>
        <td>  {{$employeeDetails->designation->desig_name}} </td>
     </tr>
     <tr>
        <td class='bold'>Department: </td>
        <td>  {{$employeeDetails->department->depart_name}}</td>
        <td class='bold'>Sub-Department:  </td>
        <td>
          @if($employeeDetails->subdepartment)
          {{$employeeDetails->subdepartment->sdepart_name}}
          @endif
        </td>
     </tr>
      @php
        $senior=App\Employee::where('emp_seniorid', $employeeDetails->emp_seniorid)->first();
        $auth_person=App\Employee::where('emp_authperson', $employeeDetails->emp_authperson)->first();
      @endphp
    <tr>
        <td class='bold'> Supervisor:  </td>
        <td>  {{$senior->emp_name}}    </td>
        <td class='bold'>Authorized Person: </td>
        <td>{{$auth_person->emp_name}} </td>
     </tr>
    <tr>
        <td class='bold'> Weekend:   </td>
        <td>
          @if($employeeDetails->emp_wknd==1)
          Friday
          @elseif($employeeDetails->emp_wknd==2)
          Saturday
          @elseif($employeeDetails->emp_wknd==3)
          Sunday
          @elseif($employeeDetails->emp_wknd==4)
          Monday
          @elseif($employeeDetails->emp_wknd==5)
          Tuesday
          @elseif($employeeDetails->emp_wknd==6)
          Wednesday
          @elseif($employeeDetails->emp_wknd==7)
          Thursday
          @else

          @endif
        </td>
        <td class='bold'>Vehicle Entitlement:</td>
        <td> 
                                @if($employeeDetails->emp_vehicle=="1")
                              Yes
                                                 
                                @elseif($employeeDetails->emp_vehicle=="0")
                               No
                                
                               
                                @endif
      </td>
     </tr>
    <tr>
        <td class='bold'> Blood Group: </td>
        <td>       {{$employeeDetails->emp_blgrp}}  </td>
        <td class='bold'>Education Qualification:</td>
        <td>   {{$employeeDetails->emp_education}}</td>
     </tr>
      
    <tr>
        <td class='bold'> Daily Working Hour: </td>
        <td>
             @if($employeeDetails->emp_workhr=="1")
                                        7 hrs 
                                                          
                                      @elseif($employeeDetails->emp_workhr=="2")
                                        8 hrs 
                                       
                                        @elseif($employeeDetails->emp_workhr=="3")
                                       
                                         6 hrs 
                                     
                                      @endif       
        </td>
        
        <td class='bold'>OT Entitlement: </td>
        <td>   @if($employeeDetails->emp_otent=="1")
                         Yes 
                       
                      @elseif($employeeDetails->emp_otent=="2")
                         No
                         
        </td>
     </tr>
    <tr>
        <td class='bold'> Shift:  </td>
        <td> 
            {{$employeeDetails->shift->shift_stime}} to {{ $employeeDetails->shift->shift_etime }} 

        </td>
        <td class='bold'>Nid No.:</td>
        <td>    {{$employeeDetails->emp_nid}} </td>
     </tr>
    <tr>
        <td class='bold'> Face ID: </td>
        <td>          {{$employeeDetails->emp_machineid}}   </td>
        <td class='bold'>Job Location:</td>
        <td>  {{$employeeDetails->joblocation->jl_name}}
        </td>
     </tr>
      
    <tr>
        <td class='bold'> Joining Date: </td>
        <td>        {{ $employeeDetails->emp_joindate }}  </td>
    
        <td class='bold'>Confirmation Date:</td>
        <td>       {{ $employeeDetails->emp_confirmdate }}</td>
    </tr>
      
     <tr>
        <td class='bold'> E-Mail address: </td>
        <td>          {{$employeeDetails->emp_email}}   </td>
        <td class='bold'>Father's Name: </td>
        <td>   {{$employeeDetails->emp_father}}</td>
     </tr>
      
     <tr>
        <td class='bold'> Mother's Name:  </td>
        <td>       {{$employeeDetails->emp_mother}}    </td>
        
        <td class='bold'>Emergency Contact No:</td>
        <td>    {{$employeeDetails->emp_emjcontact}} </td>
    </tr>
            
    <tr>
        <td class='bold'> Current Address:   </td>
        <td>      {{ $employeeDetails->emp_crntaddress }}   </td>
        
        <td class='bold'>Permanent Address:</td>
        <td>     {{$employeeDetails->emp_prmntaddress}}  </td>
    </tr>
     
</table>   
              <h3 class=""> Insurance Data </h3>
              <table style='width:100%;'>
                    <tr class="border-none">
                            <th style='width:20%;'></th>
                            <th style='width:30%;'></th>
                            <th style='width:20%;'></th>
                            <th style='width:30%;'></th>
                    </tr>
        <tr>
            <td class='bold'>Self Member ID:  </td>
            <td> {{$employeeDetails->insurance->self_member_id}} </td>
            <td class='bold'>Insurance Effective Date:  </td>
            <td>{{$employeeDetails->insurance->effective_date}}</td>
         </tr>
        <tr>
            <td class='bold'>Spouse Name: </td>
            <td>  {{$employeeDetails->insurance->spouse_name}} </td>
            <td class='bold'>Spouse Member Id: </td>
            @php
              $ins_info=App\Insurance::where('emp_id', $employeeDetails->emp_id)->first();
            @endphp
            <td> {{ $ins_info->self_member_id }}</td>
      </tr>
      
          <tr>
            <td class='bold'>Date Of Birth:</td>
            <td>    {{$employeeDetails->insurance->spouse_dob}} </td>
            <td class='bold'>Insurance Start From: </td>
            <td>   {{ $ins_info->effective_date }}</td>
      </tr>
          <tr>
            <td class='bold'>Spouse Phone:</td>
            <td>   {{$employeeDetails->spouse_phone}}  </td>
            <td class='bold'>Child-1 Name: </td>
            <td>    {{$employeeDetails->insurance->child1_name}}</td>
      </tr>
      
              <tr>
            <td class='bold'>Child-2 Name: </td>
            <td>    {{$employeeDetails->insurance->child2_name}} </td>
            <td class='bold'>Date Of Birth:  </td>
            <td>     {{$employeeDetails->insurance->child2_dob}}</td>
      </tr>
      </table>
      
            <h3 class="text-center "> Salary Data  </h3>
            
            <table style='width:100%;'>
      <tr class="border-none">
        <th style='width:20%;'></th>
        <th style='width:30%;'></th>
        <th style='width:20%;'></th>
        <th style='width:30%;'></th>
      </tr>
      <tr>
        <td class='bold'>Provident Fund:</td>
        <td>  @if($employeeDetails->pf=="1") Yes @endif
                    

                     @if($employeeDetails->pf=="0") No @endif 
            </td>
        <td class='bold'>Washing Allowance : </td>
        <td>    @if($employeeDetails->washing=="1") Yes @endif 
                    @if($employeeDetails->washing=="0") No @endif
        </td>
      </tr>
      
    <tr>
        <td class='bold'>Leave Festival Allowance: </td>
        <td>      @if($employeeDetails->lfa=="1") Yes @endif 
                     @if($employeeDetails->lfa=="0") No @endif
            </td>
        <td class='bold'>Tax Allowance :  </td>
        <td>    @if($employeeDetails->tax_allow=="1") Yes @endif @if($employeeDetails->tax_allow=="0") No @endif
        </td>
     </tr>
    <tr>
                <td class='bold'>Official Mobile :  </td>
                <td>       {{$employeeDetails->emp_officecontact}}
                    </td>
                <td class='bold'>Handset/Mobile Allocation Date :   </td>
                <td>      {{$employeeDetails->emp_handsetallocdate}}
        </td>
     </tr>
      
         <tr>
                <td class='bold'>Mobile Bill Allocation Amount : </td>
                <td>        {{$employeeDetails->emp_allocamount}} 
                    </td>
                <td class='bold'>TIN No.  </td>
                <td>     {{$employeeDetails->salary->tin_no}} 
        </td>
     </tr>
         <tr>
                <td class='bold'>Grade: </td>
                <td>        {{$employeeDetails->salary->grade}} 
                    </td>
                <td class='bold'>Bank Account </td>
                <td>     {{$employeeDetails->salary->bank_account}}
        </td>
     </tr>
         <tr>
                   <td class='bold'>Bank Name: </td>
                <td>     
                {{$employeeDetails->salary->bu_code}}  
                </td>
                
                <td class='bold'>Gender: </td>
                <td>  @if($employeeDetails->salary->gender=="1") Male @endif @if($employeeDetails->salary->gender=="2") Female @endif
                    </td>
                
     </tr>
           <tr>
                    
                <td class='bold'>Date of execution Starts From :  </td>
                <td>
                @if(isset($head_date_of_execution))
                {{$head_date_of_execution->head_date_of_execution}}
                @endif
                    @else
                    
                    @endif
                  
                 
           
      </tr>
      <tr>
        </td>
                <td class='bold'>Basic:   </td>
                <td>
                  @if(isset($payroll[0]))
                 {{ $payroll[0]->amount }} 
                 @endif 
                
        </td>
        <td class='bold'>House rent:</td>
                <td>     @if(isset($payroll[1]))
                 {{ $payroll[1]->amount }} 
                 @endif 
                
                    </td>
      </tr>

           <tr>

                
                <td class='bold'>Medical Allowance:  </td>
                <td>   
                 @if(isset($payroll[2]))
                 {{ $payroll[2]->amount }} 
                 @endif 
        </td>
        <td class='bold'>Conveyance Allowance:</td>
                <td>  @if(isset($payroll[3]))
                 {{ $payroll[3]->amount }} 
                 @endif 
                
                </td>
      </tr>
        <tr>
                
                <td class='bold'>Washing Allowance:  </td>
                <td>   
                 @if(isset($payroll[4]))
                 {{ $payroll[4]->amount }} 
                 @endif 
              </td>
              <td class='bold'>Leave Festival Allowance:</td>
                <td>  @if(isset($payroll[5]))
                 {{ $payroll[5]->amount }} 
                 @endif  
                
                </td>
      </tr>
      
        <tr>
                
               
      </tr>
      
          
      </table>
            
     

</div>
</div>
</div>
             
        <!--          <div class="form-group">-->
        <!--            <label class="col-md-2 control-label"></label>-->
        <!--            <div class="col-md-4"></div>-->
        <!--            <label class="col-md-2 control-label"></label>-->

        <!--            <div class="col-md-4">-->
        <!--              @if($employeeDetails->emp_imgext!="")-->
        <!--              <img style="height: 100px;border: 1px solid black;padding: 5px;float: right;" src="{{URL::to('public/EmployeeImage')}}/{{$employeeDetails->emp_id}}.{{$employeeDetails->emp_imgext}}">-->
        <!--              @else-->
        <!--              <img  style="height: 100px;border: 1px solid black;padding: 5px;float: right;" src="{{URL::to('public')}}/male.jpg"/>-->
        <!--              @endif-->

        <!--            </div>-->
        <!--          </div>-->
              
        <!--@if($id->suser_level=="1" or $id->suser_level=="3")-->
        



      <!--        <div class="form-group">-->
      <!--          <div class="col-md-12">-->
      <!--            @if(isset($payroll[0]))-->
      <!--            @foreach ($payroll as $pay)-->
      <!--              <div class="col-md-5">-->
      <!--                <label for="basic_salary">{{$pay->head->head_name}}</label>-->
      <!--                @if($pay->head_id == 1)-->
      <!--               {{$payroll[0]->amount}}-->

                     
      <!--                @if($bank_cash[0]->bankcash_status=='1' && $bank_cash[0]->head_id=='1')-->
      <!--                 Bank-->
                      

                      
                    
      <!--                @else-->
                     

      <!--                Cash-->
                     
      <!--                @endif-->
                      

      <!--                @elseif($pay->head_id == 2)-->
      <!--                <input type="hidden" name="" id="rent_percent" value="{{ $payroll[1]->head->head_percentage }}">-->

      <!--                {{ $payroll[1]->amount }}-->

      <!--                @if($bank_cash[1]->bankcash_status=='1' && $bank_cash[1]->head_id=='2')-->
      <!--                 Bank-->
                     

                      
      <!--                @else-->
                     

      <!--                Cash-->
                    
      <!--                @endif-->

      <!--                 @elseif($pay->head_id == 3)-->
      <!--                <input type="hidden" name="" id="medical_percent" value="{{ $payroll[2]->head->head_percentage }}">-->

      <!--                {{ $payroll[2]->amount }}-->

      <!--                @if($bank_cash[2]->bankcash_status=='1' && $bank_cash[2]->head_id=='3')-->
      <!--                Bank-->
                      

                    
      <!--                @else-->
                     

      <!--                Cash-->
                     
      <!--                @endif-->

      <!--                 @elseif($pay->head_id == 4)-->
      <!--                 <input type="hidden" name="" id="conv_percent" value="{{ $payroll[3]->head->head_percentage }}">-->

      <!--               {{ $payroll[3]->amount }}-->

      <!--                 @if($bank_cash[3]->bankcash_status=='1' && $bank_cash[3]->head_id=='4')-->
      <!--                 Bank-->
                     

                    
      <!--                @else-->
                      

      <!--                Cash-->
                    
      <!--                @endif-->

      <!--                 @elseif($pay->head_id == 5)-->

      <!--                  <input type="hidden" name="" id="washing_percent" value="{{ $payroll[4]->head->head_percentage }}">-->
      <!--                  @if($employeeDetails->washing=='1')-->
      <!--              {{ $payroll[4]->amount }}-->
      <!--                @else-->
                      
      <!--                 @endif-->

      <!--                  @if($bank_cash[4]->bankcash_status=='1' && $bank_cash[4]->head_id=='5')-->
      <!--                   Bank-->
                  

                     
      <!--                @else-->
                     

      <!--                Cash-->
                     
      <!--                @endif-->


      <!--                  @elseif($pay->head_id == 6)-->
                       
      <!--                   <input type="hidden" name="" id="lfa_percent" value="{{ $payroll[5]->head->head_percentage }}">-->
      <!--                  @if($employeeDetails->lfa=='1')-->
      <!--               {{ $payroll[5]->amount }}-->
      <!--                @else-->
      <!--                0-->
      <!--                 @endif-->


      <!--                  @if($bank_cash[5]->bankcash_status=='1' && $bank_cash[5]->head_id=='6')-->
      <!--                  Bank-->
                    

                     
      <!--                @else-->
                     

      <!--                Cash-->
                     
      <!--                @endif-->
      <!--                @else-->
      <!--               0-->
      <!--                @endif-->
      <!--              </div>-->
      <!--            @endforeach-->
      <!--            @endif-->
      <!--          </div>-->
      <!--        </div>-->
      <!--@endif-->
              <!-- <input type="text" name="" id="sample"> -->





 

<script src="{{URL::to('/')}}/public/js/matrix.tables.js"></script>
<script type="text/javascript">
        function printPage() {
            window.print();
        }
</script>
