<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return view('welcome');
})->name('main');

Route::get('/admin/dashboard', function () {
    return view('pages/admin/dashboard');
});

Auth::routes();

Route::get('/dashboard', 'HomeController@index')->name('dashboard');
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('logout');

Route::get('create', 'EmployeeController@create');
Route::get('index', 'EmployeeController@index');

/******************************  Dashboard routes  ************************** */
Route::get('/refreshDashboard/Institute', 'DashboardController@getNewDashboardContents_institute');
Route::get('/refreshDashboard/Business', 'DashboardController@getNewDashboardContents_business');



//////////////////////////////////// SMS ///////////////////////////////////////
Route::post('/dlr/deliveryStatus','SMSController@postDeliveryStatus');

//////////////////////////////////////////////////////////////////////////////
//////////////////////////// Super Admins////////////////////////////////////
Route::get('/super/companies/add', function () {
    return view('pages/super/company/addCompany');
})->name('addCompany');

Route::get('/admin/companies/view', 'SuperController@getCompanies')->name('companies');

/// Posting to database routes
Route::post('/addCompany', 'SuperController@addCompany');

////////////////////////////////////////////////////////////////////////////
/////////////////////////// Company Admins ////////////////////////////////

/////////////// Student


/// for dashboard
Route::get('/getTotalStudents/','StudentDashboardController@getTotalStudents');
Route::get('/getAbsentStudents/','StudentDashboardController@getAbsentStudents');
Route::get('/getPresentStudents/','StudentDashboardController@getPresentStudents');

Route::get('/getTotalEmployees/','DashboardController@getTotalEmployees');
Route::get('/getAbsentEmployees/','DashboardController@getAbsentEmployees');
Route::get('/getPresentEmployees/','DashboardController@getPresentEmployees');
Route::get('/getLateEmployees/','DashboardController@getLateEmployees');



///

Route::get('/admin/institute/shifts','InstituteController@getInstituteShifts')->name('viewInstitutionShifts');
Route::get('/getInstitutionShiftById/{id}','InstituteController@getInstitutionShiftByid');
Route::post('/addInstitutionShift','InstituteController@addInstitutionShift');
Route::delete('/deleteInstitutionShift/{id}','InstituteController@deleteInstitutionShift');
Route::put('/updateInstitutionShift/{id}','InstituteController@updateInstitutionShift');

Route::get('/admin/students','StudentController@getStudents')->name('viewStudents');
Route::get('/getStudentById/{id}','StudentController@getStudentByid');
Route::post('/addStudent','StudentController@addStudent');
Route::delete('/deleteStudent/{id}','StudentController@deleteStudent');
Route::put('/updateStudent/{id}','StudentController@updateStudent');

Route::get('sectionsOfGrade/{grade_id}', 'StudentController@getSectionsOfGrade');

Route::get('/admin/grades','StudentController@getGrades')->name('viewGrades');
Route::get('/getGradeById/{id}','StudentController@getGradeById');
Route::post('/addGrade','StudentController@addGrade');
Route::put('/updateGrade/{id}','StudentController@updateGrade');
Route::delete('/deleteGrade/{id}','StudentController@deleteGrade');

Route::get('/admin/sections','StudentController@getSections')->name('viewSections');
Route::get('/getSectionById/{id}','StudentController@getSectionById');
Route::post('/addSection','StudentController@addSection');
Route::put('/updateSection/{id}','StudentController@updateSection');
Route::delete('/deleteSection/{id}','StudentController@deleteSection');


Route::get('/admin/sections','StudentController@getSections')->name('viewSections');

///////////////// Reports Employee
Route::get('/admin/reports', 'ReportController@viewEmployeeReportPage')->name('reportSelect');


Route::get('/getEmployees','ReportController@getEmployees');
Route::get('/generateReport','ReportController@generateEmployeeReport');


Route::get('/pdfview',array('as'=>'pdfview','uses'=>'ReportController@pdfview'));
Route::get('/reports/mismatch','ReportController@mismatchReport');

//////////////////////// Reports Student
Route::get('/admin/reports/student', 'ReportController@viewStudentReportPage')->name('reportSelectStudent');
Route::get('/generateReportStudent','ReportController@generateReportStudent');
Route::get('/getStudents','ReportController@getStudents');

////////////////////// Employee
Route::get('/admin/employee/add','AdminController@showAddEmployeePage')->name('addEmployee');

Route::get('/admin/employees/view', 'EmployeeController@getEmployees')->name('employees');
Route::get('/getEmployeeById/{id}','EmployeeController@getEmployeeById');
Route::post('/addEmployee','EmployeeController@addEmployee');
Route::put('/editEmployee/{id}','EmployeeController@updateEmployee');
Route::delete('/deleteEmployee/{id}','EmployeeController@deleteEmployee');
Route::put('/updateEmployee/{id}','EmployeeController@updateEmployee');

Route::get('/employees/branch/{id}','EmployeeController@getEmployeesByBranch');
Route::get('/allEmployees','EmployeeController@getAllEmployeesOfCompany');

Route::get('/findCardNumber/{number}','EmployeeController@findCardNumber');


//////////////// Branch
Route::get('/admin/branch/add', function () {
    return view('pages/admin/branch/addBranch');
})->name('addBranch');

Route::get('/admin/branches/view', 'BranchController@getBranches')->name('branches');
Route::get('/getBranchById/{id}', 'BranchController@getBranchById');
Route::delete('/deleteBranch/{id}', 'BranchController@deleteBranch');
Route::put('/updateBranch/{id?}', 'BranchController@updateBranch');
Route::post('/addBranch', 'BranchController@addBranch');
///////////////////////////////////////////////
//////////////Department
Route::get('/admin/department/add', function () {
    return view('pages/admin/department/addDepartment');
})->name('addDepartment');

Route::get('/admin/departments/view', 'DepartmentController@getDepartments')->name('departments');

Route::get('/getDepartmentById/{id}', 'DepartmentController@getDepartmentById');
Route::delete('/deleteDepartment/{id}', 'DepartmentController@deleteDepartment');
Route::put('/updateDepartment/{id?}', 'DepartmentController@updateDepartment');
Route::post('/addDepartment','DepartmentController@addDepartment');


//////////////////////////////////
//////// Leave Master
Route::get('/admin/leave/master/add', 'LeaveMasterController@getDataForLeaveMaster')->name('addLeaveMaster');
Route::get('/getLeaveMasterById/{id}','LeaveMasterController@getLeaveMasterById');


Route::get('/admin/leaves/view','LeaveMasterController@getLeaveMaster')->name('leaveMasterData');

Route::put('updateLeaveMaster/{id?}', 'LeaveMasterController@updateLeaveMaster');
Route::delete('/deleteLeaveMaster/{id}', 'LeaveMasterController@deleteLeaveMaster');
Route::post('/addLeaveMaster','LeaveMasterController@addLeaveMaster');
///////////////////////////////////////////////////////////////
/////////////////// Leave Type

Route::get('/admin/leave/types/view','LeaveTypeController@getLeaveTypes')->name('leaveTypes');
Route::get('/getLeaveTypeById/{leave_id}/{branch_id}','LeaveTypeController@getLeaveTypeById');
Route::post('/addLeaveToBranch', 'LeaveTypeController@addLeaveToBranch');
Route::put('updateLeaveType/{leave_id}/{branch_id}', 'LeaveTypeController@updateLeaveType');
Route::delete('/deleteLeaveType/{leave_id}/{branch_id}','LeaveTypeController@deleteLeaveType');

Route::get('/admin/leave/type/add','LeaveTypeController@addLeaveTypes')->name('addLeaveType');

Route::get('/admin/getBranchLeaves/{branch_id}','LeaveTypeController@getBranchLeaves');
//////////////////////////////////////////////////////////////////////////////////////////////
//Leave quota
Route::get('/admin/leave/quota/view','LeaveQuotaController@getAllEmployeesLeaveQuota')->name('leaveQuotas');

Route::get('/getLeaveQuotaById/{id}','LeaveQuotaController@getLeaveQuotaById');
Route::post('/addLeaveQuota', 'LeaveQuotaController@addLeaveQuota');
Route::put('/updateLeaveQuota/{id}', 'LeaveQuotaController@updateLeaveQuota');
Route::delete('/deleteLeaveQuota/{id}','LeaveQuotaController@deleteLeaveQuota');

/////////////////////////////////////////////////////////////////////////////////////////////
//////////// Leave Requests

Route::get('/leaveRequests','AdminController@getLeaveRequests')->name('leaveRequests');
Route::get('/employee/leaveTypes/{id}','AdminController@getEmployeeLeaveDetails');
Route::get('/employee/leaveStatus/{eId}/{lId}','AdminController@getEmployeeLeaveStatus');

Route::delete('/deleteAppliedLeave/{id}','AdminController@deleteAppliedLeave');
Route::put('/updateLeave','AdminController@updateAppliedLeave');
Route::get('/getAppliedLeave/{id}','AdminController@getAppliedLeaveById');
Route::post('/applyLeave','AdminController@applyLeaveOfEmployee');


//////////////////////////////////////////////////////////////////////
////////////////////// Category

Route::get('/admin/category/add', function () {
    return view('pages/admin/category/addCategory');
})->name('addCategory');

Route::get('/admin/categories/view', 'CategoryController@getCategories')->name('categories');
Route::get('/getCategoryById/{id?}','CategoryController@getCategoriesById');
Route::put('/updateCategory/{id?}','CategoryController@updateCategory');
Route::delete('/deleteCategory/{id?}','CategoryController@deleteCategory');

Route::post('/addCategory','CategoryController@addCategory');
/////////////////////////////////////////////////////////////////////////////////////////////////
///////////////// Designation
Route::get('/admin/designations/view', 'DesignationController@getDesignations')->name('designations');
Route::get('/getDesignationById/{id}','DesignationController@getDesignationById');
Route::put('/updateDesignation/{id}','DesignationController@updateDesignation');
Route::delete('/deleteDesignation/{id}','DesignationController@deleteDesignation');

Route::post('/addDesignation', 'DesignationController@addDesignation');


Route::get('/admin/designation/add', function () {
    return view('pages/admin/designation/addDesignation');
})->name('addDesignation');

//////////////////////////////////////////////////////////////////////
///////// Shift
Route::get('/admin/shift/add', function () {
    return view('pages/admin/shift/addShift');
})->name('addShift');

Route::get('/admin/shifts/view', 'ShiftController@getShifts')->name('shifts');
Route::get('/getShiftById/{id}','ShiftController@getShiftById');
Route::post('/addShift', 'ShiftController@addShift');
Route::put('/updateShift/{id}','ShiftController@updateShift');
Route::delete('/deleteShift/{id}','ShiftController@deleteShift');

///////////////////////////////////////////////////////////////////////////////
////////////////////// Holiday 

Route::get('/admin/holiday/add', function () {
    return view('pages/admin/holiday/addHoliday');
})->name('addHoliday');

Route::get('/admin/holidays/view', function() {
    return view('pages/admin/holiday/viewHolidays');
})->name('holidays');

Route::get('/getHolidays','HolidayController@getHolidays');

Route::post('/addHoliday','HolidayController@addHoliday');
Route::delete('/deleteHoliday/{id}','HolidayController@deleteHoliday');

/////////////////////////////////////////////////////////////////////////
//////////// Rosters
Route::get('/admin/rosters/view', 'RosterController@rosters')->name('rosters');
Route::post('/generateRoster', 'RosterController@generate');
Route::post('/viewRoster', 'RosterController@view');
Route::post('/generateStudentRoster', 'RosterController@generateStudentRoster');

Route::get('/getRosterData/{id}','RosterController@getRosterData'); // Employee
Route::get('/getStudentRosterData/{id}','RosterController@getStudentRosterData');// Student
Route::put('/updateRoster','RosterController@updateRoster'); //Employee
Route::put('/updateStudentRoster','RosterController@updateStudentRoster'); //Student
Route::delete('/deleteRoster/{id}','RosterController@deleteRoster'); //Employee
Route::delete('/deleteStudentRoster/{id}','RosterController@deleteStudentRoster'); //Student

Route::put('/updateRosterDetails', 'RosterController@updateRosterDetails');

Route::get('/getRosterDetails/{branch_id}/{employee_id}/{date}', 'AdminController@getRosterDetails');

Route::get('/viewStudentRoster/{month}', 'RosterController@viewStudentRoster')->name('viewStudentRoster');
Route::get('/students/grade/{grade}', 'StudentController@getStudentsOfGrade');
Route::get('/studentRoster','RosterController@viewStudentRosterOfDay');

//Route::get('/students/grade', )

////////////////////////////////////////////////////////////////////////
///////////// Manual Entry (punch)
Route::get('/admin/punch/editPunch','AdminController@editPunch')->name('manualPunch');

Route::get('/getPunchDetails/{branch_id}/{employee_id}/{date}', 'AdminController@getPunchDetails');

Route::post('/updatePunchRecord/{id}', 'AdminController@updatePunch');
Route::post('/insertPunchRecord', 'AdminController@insertPunchRecord');
Route::delete('/deletePunchRecord/{id}', 'AdminController@deletePunch');


///////////////////////////////////////////////////////////////////////////////
/////////////// File Upload
Route::get('/file/upload',function (){
    return view('pages/admin/upload/upload');
})->name('upload');

Route::post('/uploadFile', 'AdminController@uploadFile');


///////////////////////////////////////////////////////////////////////
//////////////////// Employee

Route::get('/employee/dashboard', function () {
    return view('pages/employee/dashboard');
})->name('employeeDashboard');

Route::get('/employee/roster/{month}/{year}','EmployeeController@getEmployeeMonthlyLogDetails');












