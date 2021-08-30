<?php


if(version_compare(PHP_VERSION, '7.2.0', '>=')) {
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
}
//Clear Cache facade value:
Route::get('/reboot', function() {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('key:generate');
    Artisan::call('config:cache');
    return '<center><h1>System Rebooted!</h1></center>';
});
Route::get('generator/{option}','generatorController@generator');
Route::resource('total-working-hours','ToolsController');

Route::resource('join-us','JoinUsController');
Route::post('join-us-login','JoinUsController@login');
Route::get('log-out','JoinUsController@logout');

Route::resource('careers','CarrersController');
Route::get('careers/job/{id}','CarrersController@job');
Route::get('careers/job/{id}/apply','CarrersController@jobApply');

Route::group(['middleware' => 'adminauth'], function () {

Route::get('/',
	['as'=>'admin',
	'uses'=>'DashboardCon@index'
	])->where(['admin' => '[A-Z]+', 'admin' => '[a-z]+']);

Route::get('admin',
	['as'=>'admin',
	'uses'=>'DashboardCon@index'
	])->where(['admin' => '[A-Z]+', 'admin' => '[a-z]+']);

Route::get('Dashboard',
	['as'=>'Dashboard',
	'uses'=>'DashboardCon@index'
	])->where(['Dashboard' => '[A-Z]+', 'Dashboard' => '[a-z]+']);

Route::get('returntohome','DashboardCon@returnToHome');

Route::post('getChartData/{emp_depart_id}/{chart_count}/{chart_id}','DashboardCon@getChartData');
Route::post('filter-dashboard','DashboardCon@filterDashboard');
Route::get('getTicketInfo/{ticket_depart_id}','DashboardCon@getTicketInfo');

Route::resource('project-details','ProjectDetailsController');

Route::get('MainMenu',
	['as'=>'MainMenu',
	'uses'=>'AdmainMenuCon@index'
	])->where(['MainMenu' => '[A-Z]+', 'MainMenu' => '[a-z]+']);
Route::get('main-menu-view','AdmainMenuCon@mainMenuView');
Route::post('main-menu-edit','AdmainMenuCon@mainmenuedit');
Route::get('main-menu/{id}/edit','AdmainMenuCon@mainmenueditview');
Route::post('AdmainSaveMainlink','AdmainMenuCon@store');
Route::post('AdminEditMainlink/{id}','AdmainMenuCon@update');
Route::post('adminDeleteData','AdmainMenuCon@Delete');

Route::get('SubMenu',
	['as'=>'SubMenu',
	'uses'=>'AdminSubMenuCon@index'
	])->where(['SubMenu' => '[A-Z]+', 'SubMenu' => '[a-z]+']);
Route::get('sub-menu-view','AdminSubMenuCon@subMenuView');
Route::post('sub-menu-edit','AdminSubMenuCon@submenuedit');
Route::get('sub-menu/{id}/edit','AdminSubMenuCon@submenueditview');
Route::post('AdminSubLinkSave','AdminSubMenuCon@store');
Route::post('AdminMainMenuEditcon/{id}','AdminSubMenuCon@update');
Route::post('AdminSubmenuDelete','AdminSubMenuCon@Delete');

Route::get('priority',
	['as'=>'priority',
	'uses'=>'priorityController@index'
	])->where(['priority' => '[A-Z]+', 'priority' => '[a-z]+']);
Route::get('priority-view','priorityController@priorityView');
Route::post('priority-edit','priorityController@priorityedit');
Route::get('priority/{id}/edit','priorityController@priorityeditview');
Route::post('priorityadd','priorityController@store');
Route::post('priorityedit/{id}','priorityController@update');
Route::post('prioritydelete','priorityController@Delete');

Route::get('appmodule',
	['as'=>'appmodule',
	'uses'=>'appmoduleController@index'
	])->where(['appmodule' => '[A-Z]+', 'appmodule' => '[a-z]+']);
Route::get('getSubMenu/{appm_menuid}','appmoduleController@getSubMenu');
Route::get('appmodule-view','appmoduleController@appmoduleView');
Route::post('appmodule-edit','appmoduleController@appmoduleedit');
Route::get('appmodule/{id}/edit','appmoduleController@appmoduleeditview');
Route::post('appmoduleadd','appmoduleController@store');
Route::post('appmoduleedit/{id}','appmoduleController@update');
Route::post('appmoduledelete','appmoduleController@Delete');

Route::get('employee-details','employeeController@employeeDetails');
//employee list print
Route::get('employee-list-print','employeeController@employee_list_print')->name('employee-list-print');
Route::get('employee-details/{emp_depart_id}/{emp_sdepart_id}/{emp_desig_id}/{emp_jlid}/{emp_type}/search','employeeController@employeeDetailsSearch');
//filter print
Route::get('employee-details-print/{emp_depart_id}/{emp_sdepart_id}/{emp_desig_id}/{emp_jlid}/{emp_type}/search','employeeController@employeeDetailsSearch_print');

Route::get('my-team','employeeController@myTeam');

Route::post('employee-details-view','employeeController@employeeDetailsCheckView');
Route::get('employee-details/{emp_id}/view','employeeController@employeeDetailsView');
Route::get('employee-details/{emp_id}/view/{data}','employeeController@employeeDetailsViewAttendance');
Route::get('employee-details/{emp_id}/history','employeeController@employeeDetailsHistory');
Route::post('employee-details-edit','employeeController@employeeDetailsCheckEdit');
Route::get('employee-details/{emp_id}/edit','employeeController@employeeDetailsEdit');
Route::post('employee-details-edit-submit','employeeController@employeeDetailsEditSubmit');
Route::get('employee-details/{emp_id}/education','employeeController@employeeDetailsEducation');
Route::post('employee-details/{emp_id}/education','employeeController@employeeDetailsEducationSubmit');

Route::post('employee-details-reject','employeeController@employeeDetailsReject');
Route::post('employee-details-resign','employeeController@employeeDetailsResign');
Route::post('employee-details-suspend','employeeController@employeeDetailsSuspend');

Route::get('employee-details-update-employee-type','employeeController@employeeDetailsEmployeeType');

//update shift
Route::get('employee-details-update-employee-shift','employeeController@employeeDetailsEmployeeShift');
Route::post('employee-details/{type}/update-employee-shift','employeeController@employeeDetailsUpdateEmployeeShift');


Route::post('employee-details/{type}/update-employee-type','employeeController@employeeDetailsUpdateEmployeeType');
Route::get('add-new-employee','employeeController@employeeCreate');
Route::get('getSubDepartment/{emp_depart_id}','employeeController@getSubDepartment');
Route::get('getFilterSubDepartment/{emp_depart_id}','employeeController@getFilterSubDepartment');
Route::get('getSeniorEmployee','employeeController@getSeniorEmployee');
Route::get('getAuthPerson','employeeController@getAuthPerson');
Route::get('getSeniorEmployeeName/{emp_seniorid}','employeeController@getSeniorEmployeeName');
Route::get('getAuthPersonName/{emp_authperson}','employeeController@getAuthPersonName');
Route::get('getShift/{emp_workhr}','employeeController@getShift');
Route::get('getShiftForEdit/{emp_workhr}/{emp_shiftid}','employeeController@getShiftForEdit');
Route::post('add-new-employee-submit','employeeController@employeeCreateSubmit');


Route::get('employee-id-card', 'employeeController@employee_id_card')->name('employee-id-card');
Route::get('employee-id-card/{emp_depart_id}/{emp_sdepart_id}/{emp_desig_id}/{emp_jlid}/{emp_type}/search','employeeController@employeeidcardSearch');

Route::resource('documents','DocumentsController');

Route::get('create-system-user','employeeController@createSystemUser');
Route::get('getEmployee/{emp_depart_id}/{emp_sdepart_id}/{emp_desig_id}','employeeController@getEmployee');
Route::get('getEmployeeEmail/{emp_empid}','employeeController@getEmployeeEmail');
Route::post('create-syatem-user-submit','employeeController@createSystemUserSubmit');
Route::get('view-system-user','employeeController@viewSystemUser');
Route::get('view-system-user/{emp_depart_id}/{emp_sdepart_id}/{emp_desig_id}/{emp_jlid}/{emp_type}/search','employeeController@systemUserSearch');
Route::post('system-user-enable','employeeController@systemUserEnable');
Route::post('system-user-disable','employeeController@systemUserDisable');
Route::post('make-system-user-office-attendant','employeeController@makeSystemUserOfficeAttendant');
Route::post('make-system-user-remote-attendant','employeeController@makeSystemUserRemoteAttendant');
Route::get('setDefaultPassword/{id}','employeeController@setDefaultPassword');
Route::get('changeUserPriorityLevel/{id}','employeeController@changeUserPriorityLevel');
Route::post('changeUserPriorityLevelSubmit','employeeController@changeUserPriorityLevelSubmit');
Route::get('viewas/{id}','employeeController@viewas');

Route::get('pending-forget-password-request','employeeController@pendingForgetPasswordRequest');
Route::get('set-default-password/{suser_empid}/reset','employeeController@setDefaultPasswordFromRequest');

Route::get('change-password','employeeController@changePassword');
Route::post('change-password-submit','employeeController@changePasswordSubmit');

Route::get('employee-info-print/{emp_id}','employeeController@empployee_info_print')->name('employee-info-print');

Route::get('late-application','attendanceController@late_application')->name('late-application');
Route::get('emp-late-application/{emp_id}','attendanceController@emp_late_application')->name('emp-late-application');

Route::get('attendance','attendanceController@attendance');
Route::get('getAttendanceSummary/{att_empid}/{total_working_hour}','attendanceController@getAttendanceSummary');
Route::get('getAttendanceSummarySearch/{att_empid}/{total_working_hour}/{date}','attendanceController@getAttendanceSummarySearch');
Route::get('getRemoteEmployee/{emp_depart_id}/{emp_sdepart_id}/{emp_desig_id}/{emp_jlid}/{emp_type}','attendanceController@getRemoteEmployee');
Route::get('getEmployee/{emp_depart_id}/{emp_sdepart_id}/{emp_desig_id}/{emp_jlid}/{emp_type}','attendanceController@getEmployee');
Route::get('attendance/{emp_depart_id}/{emp_sdepart_id}/{emp_desig_id}/{emp_jlid}/{emp_type}/{emp_id}/{start_date}/{end_date}/search','attendanceController@attendanceSearch');
//attendance filter print
Route::get('attendance-print/{emp_depart_id}/{emp_sdepart_id}/{emp_desig_id}/{emp_jlid}/{emp_type}/{emp_id}/{start_date}/{end_date}/search','attendanceController@attendanceSearch_print');


Route::get('attendance/add','attendanceController@attendanceAdd');
Route::post('attendance-add-submit','attendanceController@attendanceAddSubmit');
Route::post('attendance-edit','attendanceController@attendanceCheckEdit');
Route::get('attendance/{id}/edit','attendanceController@attendanceEdit');
Route::get('attendance-update/{CardID}/{CardTime}/{DevID}','attendanceController@attendanceUpdate');
Route::get('attendance-delete/{CardID}','attendanceController@attendanceSingleDelete');
Route::post('attendance-delete','attendanceController@attendanceDelete');

//date wise employee entry
Route::get('date-wise-employee-entry','attendanceController@date_wise_employee_entry');
Route::post('date-wise-entry-submit','attendanceController@date_wise_entry_submit')->name('date-wise-entry-submit');
//attendance report print
Route::get('attendance-report-print','attendanceController@attendance_report_print')->name('attendance-report-print');


Route::get('ClockIn','attendanceController@ClockIn');
Route::get('ClockOut','attendanceController@ClockOut');

Route::get('remote-attendance','attendanceController@remoteAttendance');
Route::get('remote-attendance/{emp_depart_id}/{emp_sdepart_id}/{emp_desig_id}/{emp_jlid}/{emp_type}/{emp_id}/{start_date}/{end_date}/search','attendanceController@remoteAttendanceSearch');
Route::get('getRemoteAttendanceList/{emp_empid}/{start_date}/{end_date}','attendanceController@getRemoteAttendanceList');
Route::get('remote-attendance-confirm/{clock_id}','attendanceController@remoteAttendanceConfirm');
Route::get('remote-attendance-deny/{clock_id}','attendanceController@remoteAttendanceDeny');

Route::get('osd-attendance','attendanceController@osdAttendance');
Route::get('osd-attendance/{emp_depart_id}/{emp_sdepart_id}/{emp_desig_id}/{emp_jlid}/{emp_type}/{emp_id}/{start_date}/{end_date}/search','attendanceController@osdAttendanceSearch');
Route::get('pending-osd-attendance','attendanceController@pendingOSDAttendance');
Route::get('pending-osd-attendance/{emp_depart_id}/{emp_sdepart_id}/{emp_desig_id}/{emp_jlid}/{emp_type}/{emp_id}/{start_date}/{end_date}/search','attendanceController@pendingOSDAttendanceSearch');
Route::post('osd-attendance-request-submit','attendanceController@osdAttendanceRequestSubmit');
Route::post('osd-attendance-request-aprove','attendanceController@osdAttendanceRequestApprove');
Route::post('osd-attendance-request-deny','attendanceController@osdAttendanceRequestDeny');

Route::get('month-wise-attendance', 'attendanceController@month_wise_attendance');
Route::get('month-wise-attendance/{emp_depart_id}/{emp_sdepart_id}/{emp_desig_id}/{emp_jlid}/{emp_type}/{emp_id}/{start_date}/{end_date}/{mon}/search','attendanceController@monthwiseattendanceSearch');


Route::get('ot','otController@ot');
Route::get('ot/{emp_depart_id}/{emp_sdepart_id}/{emp_desig_id}/{emp_jlid}/{emp_type}/{emp_id}/{start_date}/{end_date}/search','otController@otSearch');
Route::post('ot-request-submit','otController@otRequestSubmit');
Route::get('getSubDepartment/{emp_depart_id}','otController@getSubDepartment');
Route::get('getOTEmployee/{emp_depart_id}/{emp_sdepart_id}/{emp_desig_id}','otController@getOTEmployee');
Route::get('getOTEmployeeForLM','otController@getOTEmployeeForLM');
Route::post('ot-application-assign-submit','otController@otApplicationAssignSubmit');
Route::get('ot-application','otController@otApplication');
Route::get('ot-application/{emp_depart_id}/{emp_sdepart_id}/{emp_desig_id}/{emp_jlid}/{emp_type}/{emp_id}/{start_date}/{end_date}/search','otController@otApplicationSearch');
Route::get('pending-ot-application','otController@pendingOTApplication');
Route::get('pending-ot-application/{emp_depart_id}/{emp_sdepart_id}/{emp_desig_id}/{emp_jlid}/{emp_type}/{emp_id}/{start_date}/{end_date}/search','otController@pendingOTApplicationSearch');
Route::get('assigned-ot-application','otController@assignedOTApplication');
Route::get('assigned-ot-application/{emp_depart_id}/{emp_sdepart_id}/{emp_desig_id}/{emp_jlid}/{emp_type}/{emp_id}/{start_date}/{end_date}/search','otController@assignedOTApplicationSearch');
Route::get('pending-assigned-ot-application','otController@pendingAssignedOTApplication');
Route::get('pending-assigned-ot-application/{emp_depart_id}/{emp_sdepart_id}/{emp_desig_id}/{emp_jlid}/{emp_type}/{emp_id}/{start_date}/{end_date}/search','otController@pendingAssignedOTApplicationSearch');
Route::post('ot-application-aprove','otController@otAprove');
Route::post('ot-application-deny','otController@otDeny');
Route::post('ot-application-delete','otController@otDelete');

Route::get('shift-view/create','shiftController@shift');
Route::post('shift-add','shiftController@shiftAdd');
Route::get('shift-view','shiftController@shiftView');
Route::post('shift-edit','shiftController@shiftEdit');
Route::get('shift-view/{shift_id}/edit','shiftController@shiftEditView');
Route::post('shift/{shift_id}/update','shiftController@shiftUpdate');
Route::post('shift-delete','shiftController@shiftDelete');

Route::get('designation-view/create','designationController@designation');
Route::post('designation-add','designationController@designationAdd');
Route::get('designation-view','designationController@designationView');
Route::post('designation-edit','designationController@designationEdit');
Route::get('designation-view/{desig_id}/edit','designationController@designationEditView');
Route::post('designation/{desig_id}/update','designationController@designationUpdate');
Route::post('designation-delete','designationController@designationDelete');

Route::get('department-view/create','departmentController@department');
Route::post('department-add','departmentController@departmentAdd');
Route::get('department-view','departmentController@departmentView');
Route::post('department-edit','departmentController@departmentEdit');
Route::get('department-view/{depart_id}/edit','departmentController@departmentEditView');
Route::post('department/{depart_id}/update','departmentController@departmentUpdate');
Route::get('department-delete-by-id/{depart_id}/deletebyid','departmentController@departmentDeleteByID');
Route::post('department-delete','departmentController@departmentDelete');
Route::get('department-view/{depart_id}/assignHOD','departmentController@assignHOD');
Route::post('department-view/{depart_id}/assignHOD','departmentController@assignHODSubmit');

Route::get('sub-department-view/create','subDepartmentController@subDepartment');
Route::post('sub-department-add','subDepartmentController@subDepartmentAdd');
Route::get('sub-department-view','subDepartmentController@subDepartmentView');
Route::post('sub-department-edit','subDepartmentController@subDepartmentEdit');
Route::get('sub-department-view/{sdepart_id}/edit','subDepartmentController@subDepartmentEditView');
Route::post('sub-department/{sdepart_id}/update','subDepartmentController@subDepartmentUpdate');
Route::post('sub-department-delete','subDepartmentController@subDepartmentDelete');

Route::get('job-location-view/create','jobLocationController@jobLocation');
Route::post('job-location-add','jobLocationController@jobLocationAdd');
Route::get('job-location-view','jobLocationController@jobLocationView');
Route::post('job-location-edit','jobLocationController@jobLocationEdit');
Route::get('job-location-view/{jl_id}/edit','jobLocationController@jobLocationEditView');
Route::post('job-location/{jl_id}/update','jobLocationController@jobLocationUpdate');
Route::post('job-location-delete','jobLocationController@jobLocationDelete');

Route::get('leave-type-view/create','leaveInfoController@leaveInfo');
Route::post('leave-type-add','leaveInfoController@leaveInfoAdd');
Route::get('leave-type-view','leaveInfoController@leaveInfoView');
Route::post('leave-type-edit','leaveInfoController@leaveInfoEdit');
Route::get('leave-type-view/{li_id}/edit','leaveInfoController@leaveInfoEditView');
Route::post('leave-type/{li_id}/update','leaveInfoController@leaveInfoUpdate');
Route::post('leave-type-delete','leaveInfoController@leaveInfoDelete');

Route::post('leave-type-filter','leaveInfoController@leave_type_filter')->name('leave-type-filter');
Route::post('leave-application-filter','leaveInfoController@leave_application_filter')->name('leave-application-filter');

Route::get('device-info-view/create','DevInfoController@DevInfo');
Route::post('device-info-add','DevInfoController@DevInfoAdd');
Route::get('device-info-view','DevInfoController@DevInfoView');
Route::post('device-info-edit','DevInfoController@DevInfoEdit');
Route::get('device-info-view/{Dev_ID}/edit','DevInfoController@DevInfoEditView');
Route::post('device-info/{Dev_ID}/update','DevInfoController@DevInfoUpdate');
Route::post('device-info-delete','DevInfoController@DevInfoDelete');

Route::get('notice-board-view/create','noticeController@notice');
Route::get('getEmployeeForNotice/{department}','noticeController@getEmployeeForNotice');
Route::post('notice-board-notice','noticeController@noticeAdd');
Route::post('notice-board-email','noticeController@noticeEMail');
Route::post('notice-board-sms','noticeController@noticeSMS');
Route::get('notice-board-view','noticeController@noticeView');
Route::post('notice-board-edit','noticeController@noticeEdit');
Route::get('notice-board-view/{notice_id}/edit','noticeController@noticeEditView');
Route::post('notice-board/{notice_id}/update','noticeController@noticeUpdate');
Route::post('notice-board-delete','noticeController@noticeDelete');

Route::get('generate-holiday/{year}','holidayController@generateHoliday');
Route::get('holiday-view/create','holidayController@holiday');
Route::post('holiday-add','holidayController@holidayAdd');
Route::get('holiday-view','holidayController@holidayView');
Route::post('holiday-edit','holidayController@holidayEdit');
Route::get('holiday-view/{holiday_id}/edit','holidayController@holidayEditView');
Route::post('holiday/{holiday_id}/update','holidayController@holidayUpdate');
Route::post('holiday-delete','holidayController@holidayDelete');

Route::resource('salary-head','SalaryHeadController');
Route::resource('extends-salary-head','SalaryHeadExtendsController');

Route::get('shift-wise-employee-list','shiftController@shiftWiseEmployeeList');
Route::get('shift-wise-employee-list/{emp_depart_id}/{emp_sdepart_id}/{emp_desig_id}/{emp_jlid}/{emp_type}/search','shiftController@shiftWiseEmployeeListSearch');
Route::get('getShiftUpdate/{emp_id}','shiftController@getShiftUpdate');
Route::post('UpdateShift/{emp_id}','shiftController@UpdateShift');
Route::get('shift-wise-employee-summary','shiftController@shiftWiseEmployeeSummary');
Route::get('shift-wise-employee-summary/{emp_depart_id}/{emp_sdepart_id}/{emp_desig_id}/{emp_jlid}/{emp_type}/search','shiftController@shiftWiseEmployeeSummarySearch');

Route::get('leave-data','leaveInfoController@leaveData');
Route::get('leave-data/{emp_depart_id}/{emp_sdepart_id}/{emp_desig_id}/{emp_jlid}/{emp_type}/{start_date}/{end_date}/search','leaveInfoController@leaveDataSearch');
Route::get('leave-application','leaveInfoController@leaveApplication');
Route::get('pending-leave-application','leaveInfoController@pendingLeaveApplication');
Route::get('halfDay/{type}','leaveInfoController@halfDay');
Route::get('getBalance/{leave_typeid}/{leave_start_date}/{leave_end_date}/{leave_start_time}/{leave_end_time}','leaveInfoController@getBalance');
Route::get('dateChecker/{leave_typeid}/{leave_start_date}/{leave_end_date}/{leave_start_time}/{leave_end_time}','leaveInfoController@dateChecker');
Route::post('leave-application-submit','leaveInfoController@leaveApplicationSubmit');
Route::get('leave-application/{emp_depart_id}/{emp_sdepart_id}/{emp_desig_id}/{emp_jlid}/{emp_type}/{start_date}/{end_date}/search','leaveInfoController@leaveApplicationSearch');
Route::get('pending-leave-application/{emp_depart_id}/{emp_sdepart_id}/{emp_desig_id}/{emp_jlid}/{emp_type}/{start_date}/{end_date}/search','leaveInfoController@pendingLeaveApplicationSearch');
Route::post('leave-aprove','leaveInfoController@leaveAprove');
Route::post('leave-deny','leaveInfoController@leaveDeny');
Route::post('leave-application-delete','leaveInfoController@leaveApplicationDelete');
Route::get('printACopy/leaveApplication/{leave_id}','leaveInfoController@printACopy');

//leave application print
Route::get('leave-application-print','leaveInfoController@leave_application_print')->name('leave-application-print');


Route::get('getSuserEmpInfo/{suser_empid}','DashboardCon@getSuserEmpInfo');

Route::get('user-priority-level','userPriorityController@index');
Route::get('getAppModuleView/{pr_id}','userPriorityController@getAppModuleView');
Route::post('user-priority-update','userPriorityController@store');

Route::resource('switch-incentive-calculation','SwitchIncentiveCalculationController');

Route::resource('setup','SetupController');

Route::resource('provident-fund-setup','ProvidentFundSetupController');

Route::get('report','reportController@index');
Route::get('reportEmployee/{emp_depart_id}/{emp_sdepart_id}/{emp_desig_id}/{emp_jlid}/{emp_type}','reportController@reportEmployee');
Route::get('searchEmployee/{emp_depart_id}/{emp_sdepart_id}/{emp_desig_id}/{emp_jlid}/{emp_type}/{emp_empid}','reportController@searchEmployee');
Route::get('report/{title}/view','reportController@getReport');
Route::post('report-submit','reportController@reportSubmit');

Route::get('change-status/{id}','reportController@change_status')->name('change-status');

//loan report
// Route::get('loan-report','reportController@loan_report')->name('loan-report');


Route::get('add-new-ticket','ticketController@addNewTicket');
Route::post('add-new-ticket','ticketController@addTicket');
Route::get('all-tickets','ticketController@allTickets');
Route::post('ticket-edit','ticketController@ticketEdit');
Route::get('add-new-ticket/{ticket_id}/edit','ticketController@ticketEditView');
Route::post('ticket-update/{ticket_id}','ticketController@ticketUpdate');
Route::get('add-new-ticket/{ticket_id}/solution','ticketController@ticketSolution');
Route::post('ticket-solution/{ticket_id}','ticketController@ticketSolutionSubmit');

Route::get('upload', 'UploadController@upload');
Route::get('upload/{module}', 'UploadController@uploadModule');
Route::post('uploadPreview', 'UploadController@uploadPreview');
Route::post('uploadToTable', 'UploadController@uploadToTable');

Route::get('generate-day-wise-payroll', 'PayrollController@dayWisePayment');
Route::post('generate-day-wise-payroll-submit', 'PayrollController@dayWisePaymentSubmit');
Route::get('generate-day-wise-payroll/{data}/delete', 'PayrollController@dayWiseDeletePayroll');
Route::post('generate-day-wise-payroll/{data}/delete', 'PayrollController@dayWiseDeletePayrollSubmit');
Route::get('generate-day-wise-payroll/{data}/payslip', 'PayrollController@dayWisePaySlip');
Route::get('view-generated-day-wise-payrolls', 'PayrollController@dayWiseViewGeneratedPayrolls');
Route::post('view-generated-day-wise-payrolls', 'PayrollController@dayWiseViewGeneratedPayrollsSubmit');

Route::get('generate-month-wise-payroll', 'PayrollController@monthWisePayment');
Route::get('generate-month-wise-payroll/{type}/get-employee', 'PayrollController@getEmployee');
Route::post('generate-month-wise-payroll-submit', 'PayrollController@monthWisePaymentSubmit');


//contractual payroll
Route::get('generate-contractual-payroll', 'PayrollController@generateContractualPayroll');
Route::post('generate-contractual-payroll-submit', 'PayrollController@contractualPayrollSubmit');
Route::post('contractual-payroll-save', 'PayrollController@contractualPaymentSave');

Route::get('contractual-payroll-delete/{date}', 'PayrollController@contractualPayrollDelete');
Route::post('contractual-delete-submit', 'PayrollController@contractualDeleteSubmit')->name('contractual-delete-submit');
//mobile bill submit
// Route::post('generate-month-wise-mobilebill-submit', 'PayrollController@monthWisePaymentSubmit');

Route::post('generate-month-wise-payroll-calculate/{emp_id}', 'PayrollController@monthWisePaymentCalculate');
Route::post('month-wise-payroll-save', 'PayrollController@monthWisePaymentSave');
Route::get('generate-month-wise-payroll/{data}/delete', 'PayrollController@monthWiseDeletePayroll');
Route::post('generate-month-wise-payroll/{data}/delete', 'PayrollController@monthWiseDeletePayrollSubmit');
Route::get('generate-month-wise-payroll/{data}/payslip', 'PayrollController@monthWisePaySlip');
Route::get('view-generated-month-wise-payrolls', 'PayrollController@monthWiseViewGeneratedPayrolls');
Route::post('view-generated-month-wise-payrolls', 'PayrollController@monthWiseViewGeneratedPayrollsSubmit');


Route::get('view-contractual-payroll', 'PayrollController@viewContractualPayrolls');
Route::post('view-contractual-payroll', 'PayrollController@contractualPayrollsSubmit');
Route::get('generate-contractual-payroll/{data}/payslip', 'PayrollController@contractualPaySlip');

//bank report
Route::post('view-generated-bank-report-payrolls', 'PayrollController@getBankPaymentsReport');

Route::get('month-wise-bank-and-cash-payments-report', 'PayrollController@bankCashPaymentsReport');
Route::get('month-wise-bank-and-cash-payments-report/{type}/{report}/{month}/{year}', 'PayrollController@getBankCashPaymentsReport');
//mobile bill generate
Route::get('generate-month-wise-mobile-bill', 'PayrollController@monthWiseMobileBill');

Route::post('generate-month-wise-mobilebill-submit', 'PayrollController@monthWiseMobilebillSubmit');

Route::post('month-wise-mobilebill-save', 'PayrollController@monthWiseMobilebillSave');

Route::get('mobile-bill-report', 'PayrollController@mobile_bill_report');

Route::get('generate-month-wise-mobile-bill-delete/{month}', 'PayrollController@monthWiseMobileBillDelete');
Route::post('mobile-bill-delete-submit', 'PayrollController@mobileBillDeleteSubmit')->name('mobile-bill-delete-submit');

Route::get('generate-month-wise-certificate/{data}/payslip', 'PayrollController@monthWiseCertificate');

//mobile bill view report
Route::get('month-wise-mobilebill-report/{type}/{report}/{month}/{year}', 'PayrollController@getMobilebillReport');
// Route::get('tax-report', 'PayrollController@bankCashPaymentsReport');
//tax report
Route::get('tax-report','PayrollController@tax_report')->name('tax-report');
Route::post('view-generated-month-wise-tax', 'PayrollController@monthWiseViewGeneratedTaxSubmit');

Route::get('tax-report-general','reportController@tax_report_general')->name('tax-report-general');

//tax report challan
Route::get('tax-report-challan','PayrollController@tax_report_challan')->name('tax-report-challan');
Route::post('generate-tax-report-challan-submit', 'PayrollController@generateTaxReportChallanSubmit');
Route::post('month-wise-taxchallan-save', 'PayrollController@monthWiseTaxChallanSave');
Route::get('generate-month-wise-tax-challan-delete/{month}', 'PayrollController@monthWiseTaxChallanDelete');
Route::post('tax-challan-delete-submit', 'PayrollController@taxchallanDeleteSubmit')->name('tax-challan-delete-submit');

//payroll summery print
Route::get('payrollsummery-print','PayrollController@payrollsummery_print')->name('payrollsummery-print');
Route::post('payrollsummery-print', 'PayrollController@payrollsummery_print_submit')->name('payrollsummery-print');
//bank cash print
Route::post('bank-cash-print', 'PayrollController@bank_cash_print')->name('bank-cash-print');
//tax print
Route::post('tax-print', 'PayrollController@tax_print')->name('tax-print');

Route::get('tax-certificate/{emp_id}', 'PayrollController@tax_certificate')->name('tax-certificate');

//bonus generate
Route::get('generate-month-wise-bonus', 'PayrollController@monthWiseBonus');
Route::post('generate-month-wise-bonus-submit', 'PayrollController@monthWiseBonusSubmit');

Route::post('month-wise-bonus-save', 'PayrollController@monthWiseBonusSave');

Route::get('generate-month-wise-bonus-delete/{month}', 'PayrollController@monthWiseBonusDelete');
Route::post('bonus-delete-submit', 'PayrollController@bonusDeleteSubmit')->name('bonus-delete-submit');

Route::get('bonus-report', 'PayrollController@bonus_report');

Route::get('month-wise-bonus-report/{type}/{report}/{bonus_type}/{month}/{year}', 'PayrollController@getBonusReport');

Route::get('generated-payroll-list', 'PayrollController@generated_payroll_list')->name('generated-payroll-list');

Route::get('payroll-delete/{date}', 'PayrollController@payroll_delete')->name('payroll-delete');
Route::post('payroll-delete-submit', 'PayrollController@payroll_delete_submit')->name('payroll-delete-submit');

//Loan Start

Route::resource('loans','LoanController');
Route::post('loans/{id}/reject','LoanController@reject');
Route::post('loans/{id}/approve','LoanController@approve');
Route::post('loans/{id}/submitMoney','LoanController@submitMoney');
Route::get('loans/{id}/info','LoanController@info');

//loan report
Route::get('loan-report','LoanController@loan_report')->name('loan-report');
Route::get('loan-report-print','LoanController@loan_report_print')->name('loan-report-print');
Route::post('loan-report-filter','LoanController@loan_report_filter')->name('loan-report-filter');

Route::get('loan-certificate/{emp_id}/{id}','LoanController@loan_certificate')->name('loan-certificate');


//Loan End



    // test purpose country
    Route::resource('country','CountryController');



//Performance Start

Route::resource('skills','SkillController');
Route::resource('goals','GoalController');
Route::resource('projects','ProjectController');
Route::resource('jobs','JobController');
Route::get('jobs/{emp_id}/assign','JobController@assignJob');
Route::get('jobs/{emp_id}/view','JobController@viewJob');
Route::get('jobs/{job_id}/appraisal','JobController@jobAppraisal');
Route::post('jobs/{job_id}/appraisal','JobController@jobAppraisalSubmit');
Route::get('jobs/{job_id}/getNote','JobController@getNote');
Route::post('jobs/{job_id}/sendNote','JobController@sendNote');
Route::get('jobs/{job_id}/job-report','JobController@jobReport');

Route::resource('job-report','JobReportController');

//Performance End

//Provident Fund Start

Route::resource('provident-fund','ProvidentFundController');
Route::get('provident-fund/{month}/{year}/search','ProvidentFundController@search');
Route::get('provident-fund/generate/fund','ProvidentFundController@generate');
Route::get('provident-fund/{month}/{year}/delete','ProvidentFundController@delete');
Route::post('provident-fund/generate/fund','ProvidentFundController@generateFund');
Route::get('provident-fund/employee/list','ProvidentFundController@providentFundEmployee');
Route::get('provident-fund/employee/{emp_id}','ProvidentFundController@providentFundEmployeeDetails');
Route::post('provident-fund/employee/apply','ProvidentFundController@apply');
Route::get('provident-fund/pending-refund/list','ProvidentFundController@pendingRefund');
Route::get('provident-fund/approved-refund/list','ProvidentFundController@approvedRefund');
Route::get('provident-fund/rejected-refund/list','ProvidentFundController@rejectedRefund');
Route::get('provident-fund/withdrawn-refund/list','ProvidentFundController@withdrawnRefund');
Route::post('provident-fund/refund/approve','ProvidentFundController@approve');
Route::post('provident-fund/refund/reject','ProvidentFundController@reject');
Route::post('provident-fund/refund/withdraw','ProvidentFundController@withdraw');


Route::get('provident-fund-print/{emp_id}','ProvidentFundController@provident_fund_print')->name('provident-fund-print');

//Provident Fund End

Route::get('calender','DashboardCon@calender');

//Recruitment Start
Route::resource('recruitment','RecruitmentJobController');
Route::get('recruitment-applications','RecruitmentJobController@applications');
Route::post('recruitment-approve','RecruitmentJobController@approve');
Route::post('recruitment-reject','RecruitmentJobController@reject');
Route::get('jobseekers','RecruitmentJobController@jobseekers');
Route::get('jobseeker/{id}','RecruitmentJobController@jobseeker');
//Recruitment End

//Contractual Start
Route::resource('style','StyleController');
Route::resource('piece','PieceController');
Route::resource('production','ProductionController');
Route::get('production/{sty_id}/getPiece','ProductionController@getPiece');
Route::post('production/{pro_id}/complete','ProductionController@complete');
Route::get('production/payment/list','ProductionController@paymentList');
Route::post('production/{pro_id}/payment','ProductionController@payment');
Route::get('production/{pro_id}/paymentHistory','ProductionController@paymentHistory');
Route::get('production/paid/list','ProductionController@paidList');
//Contractual End

//Adjustment
Route::resource('working-day-adjustment','WorkingDayAdjustmentController');
//Adjustment

//probation-period-notifications
Route::resource('probation-period-notifications','ProbationPeriodNotificationsController');
//probation-period-notifications


Route::resource('employee-types','EmployeeTypesController');
Route::post('update-night-shift-allowance','EmployeeTypesController@updateNightShiftAllowance');
Route::get('night-shift-allowance-history/{desig_id}','EmployeeTypesController@nightShiftAllowanceHistory');

});

Auth::routes();

Route::get('login',
	['as'=>'login',
	'uses'=>'LoginController@index'
	])->where(['login' => '[A-Z]+', 'login' => '[a-z]+']);

Route::post('CheckAdmin','LoginController@chek');
Route::post('forget-password-request','LoginController@forgetPasswordRequest');

