<?php

use Illuminate\Support\Facades\Route;

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

// Authentication Routes
Auth::routes();

// To disable register login
//Auth::routes(["register" => false]);


Route::get('/dashboard', function () {
    return view('welcome');
});

Route::get('/forgot-password',function (){
	return view ('auth/forgot-password');
});





Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get("loading_menu_item_ajax_hit/{id}", [App\Http\Controllers\MenuSetupController::class, 'loading_menu_item_ajax_hit']);
Route::get("menus/{id}", [App\Http\Controllers\MenuSetupController::class, 'destroy'])->name("menu.destroy");
Route::resource('menus', App\Http\Controllers\MenuSetupController::class, [
    'names' => [
        'index' => 'menu',
        'store' => 'menu.store',
    ]
]);


Route::get('user-type', 'App\Http\Controllers\UserTypeController@index')->name('userType');
Route::post('store-user-type', 'App\Http\Controllers\UserTypeController@store')->name('storeUserType');
Route::get("usertype/{id}", 'App\Http\Controllers\UserTypeController@destroy')->name("UserTypeDestroy");
Route::get("edit-user-type/{id}", 'App\Http\Controllers\UserTypeController@edit_user_type');


// School
Route::resource('school-types', App\Http\Controllers\SchoolTypeController::class, [
    'names' => [
        'index' => 'school_types',
        'store' => 'school_types.store',
    ]
]);

Route::get("edit-school-type/{id}", 'App\Http\Controllers\SchoolTypeController@editSchoolType');
Route::get("destroy-school-type/{id}", 'App\Http\Controllers\SchoolTypeController@destroy')->name('DeleteSchoolType');

// School Info
/*<mowmita>*/
Route::get("school-info/destroy/{id}", [App\Http\Controllers\SchoolInfoController::class, 'destroy'])->name("school_info.destroy");
Route::get("school-info/restore/{id}", [App\Http\Controllers\SchoolInfoController::class, 'restore'])->name("school_info.restore");
Route::get("school-info/approve/{id}", [App\Http\Controllers\SchoolInfoController::class, 'approve'])->name("school_info.approve");
Route::get("school-info/pending/{id}", [App\Http\Controllers\SchoolInfoController::class, 'pending'])->name("school_info.pending");
Route::get("school-info/search", [App\Http\Controllers\SchoolInfoController::class, 'search'])->name("school_info.search");
Route::get("school-info/terminate/{id}", [App\Http\Controllers\SchoolInfoController::class, 'terminate'])->name("school_info.terminate");

/*<mowmita>*/

Route::get("school-info/show/{id}", [App\Http\Controllers\SchoolInfoController::class, 'show'])->name("school_info.show");
Route::get("school-info/prefetch-approved/{id}", [App\Http\Controllers\SchoolInfoController::class, 'approvedView'])->name("school_info.prefetch-approved");
Route::get("school-info/approved/{id}", [App\Http\Controllers\SchoolInfoController::class, 'approved'])->name("school_info.approved");
Route::get("school-info/prefetch-denied/{id}", [App\Http\Controllers\SchoolInfoController::class, 'deniedView'])->name("school_info.prefetch-denied");

Route::get("loading_school_info_ajax_hit/{id}", [App\Http\Controllers\SchoolInfoController::class, 'loading_school_info_ajax_hit'])->name("school_info.loading_school_info_ajax_hit");

Route::get("pendingOnboardRequestList", [App\Http\Controllers\SchoolInfoController::class, 'pendingOnboardRequestList'])->name("school_info.pendingOnboardRequestList");


Route::get("changeApprovedToActive/{id}", [App\Http\Controllers\SchoolInfoController::class, 'changeApprovedToActive'])->name("school_info.changeApprovedToActive");

Route::resource('school-info', App\Http\Controllers\SchoolInfoController::class, [
    'names' => [
        'index' => 'school_info',
        'store' => 'school_info.store',
    ]
]);

Route::get('new-onboard-schools', 'App\Http\Controllers\SchoolInfoController@new_onboard_schools');
Route::get('monthly-active-payment', 'App\Http\Controllers\SchoolInfoController@monthly_active_payment');
Route::get('payment-dues', 'App\Http\Controllers\SchoolInfoController@payment_dues');

// ./ School Info


// Class Info

Route::get("loading_class_info_item_ajax_hit/{id}", [App\Http\Controllers\ClassInfoController::class, 'loading_class_info_item_ajax_hit']);
Route::get("class-info/destroy/{id}", [App\Http\Controllers\ClassInfoController::class, 'destroy'])->name("class_info.destroy");
Route::get("class-info/restore/{id}", [App\Http\Controllers\ClassInfoController::class, 'restore'])->name("class_info.restore");

Route::resource('class-info', App\Http\Controllers\ClassInfoController::class, [
    'names' => [
        'index' => 'class_info',
        'store' => 'class_info.store',
    ]
]);
// ./ Class Info



// Shift Info

Route::get("loading_shift_info_item_ajax_hit/{id}", [App\Http\Controllers\ShiftController::class, 'loading_shift_info_item_ajax_hit']);
Route::get("shift-info/{id}", [App\Http\Controllers\ShiftController::class, 'destroy'])->name("shift_info.destroy");
Route::get("shift-info/restore/{id}", [App\Http\Controllers\ShiftController::class, 'restore'])->name("shift_info.restore");

Route::resource('shift-info', App\Http\Controllers\ShiftController::class, [
    'names' => [
        'index' => 'shift_info',
        'store' => 'shift_info.store',
    ]
]);
// ./ Shift Info



// Section Info

Route::get("loading_section_info_item_ajax_hit/{id}", [App\Http\Controllers\SectionController::class, 'loading_section_info_item_ajax_hit']);
Route::get("section-info/{id}", [App\Http\Controllers\SectionController::class, 'destroy'])->name("section_info.destroy");
Route::get("section-info/restore/{id}", [App\Http\Controllers\SectionController::class, 'restore'])->name("section_info.restore");

Route::get("section-info/restore/{id}", [App\Http\Controllers\SectionController::class, 'restore'])->name("section_info.restore");

Route::resource('section-info', App\Http\Controllers\SectionController::class, [
    'names' => [
        'index' => 'section_info',
        'store' => 'section_info.store',
    ]
]);
// ./ Section Info

/*<mowmita>*/

//my student list
Route::resource('my_student', App\Http\Controllers\schoolpanel\MyStudentController::class, [
    'names' => [
        'index' => 'my_student',
    ]
]);
Route::post('my_student/create', 'App\Http\Controllers\schoolpanel\MyStudentController@create')->name("my_student.create");
Route::get("my_student/destroy/{id}", [App\Http\Controllers\schoolpanel\MyStudentController::class, 'destroy'])->name("my_student.destroy");
Route::post("my_student/search", [App\Http\Controllers\schoolpanel\MyStudentController::class, 'search'])->name("my_student.search");
Route::get("my_student/restore/{id}", [App\Http\Controllers\schoolpanel\MyStudentController::class, 'restore'])->name("my_student.restore");
Route::get("my_student/details/{id}", [App\Http\Controllers\schoolpanel\MyStudentController::class,'details'])->name("my_student.details");
Route::post("my_student/store", [App\Http\Controllers\schoolpanel\MyStudentController::class, 'store'])->name("my_student.store");


//Notifications -mowmita
Route::resource('all_notification', App\Http\Controllers\AllNotificationController::class, [
    'names' => [
        'index' => 'all_notification',
    ]
]);
Route::post('all_notification/create', 'App\Http\Controllers\AllNotificationController@create');
Route::get("all_notification/details/{id}", [App\Http\Controllers\AllNotificationController::class,'details'])->name("all_notification.details");
Route::get("all_notification/destroy/{id}", [App\Http\Controllers\AllNotificationController::class, 'destroy'])->name("all_notification.destroy");
Route::get("all_notification/edit/{id}", ['App\Http\Controllers\AllNotificationController@edit']);
Route::get("all_notification/restore/{id}", [App\Http\Controllers\AllNotificationController::class, 'restore'])->name("all_notification.restore");
Route::get("all_notification/activate/{id}", [App\Http\Controllers\AllNotificationController::class, 'activate'])->name("all_notification.activate");
Route::get("all_notification/inactivate/{id}", [App\Http\Controllers\AllNotificationController::class, 'inactivate'])->name("all_notification.inactivate");
Route::post('all_notification/store', 'App\Http\Controllers\AllNotificationController@store');
Route::get('notification/all_notification', [App\Http\Controllers\AllNotificationController::class, 'all_notification']);

//payment-setup

Route::resource('payment_setup', App\Http\Controllers\PaymentSetupController::class, [
    'names' => [
        'index' => 'payment_setup',
    ]
]);
Route::post('payment_setup/create', 'App\Http\Controllers\PaymentSetupController@create');
Route::get("payment_setup/details/{id}", [App\Http\Controllers\PaymentSetupController::class,'details'])->name("payment_setup.details");
Route::get("payment_setup/destroy/{id}", [App\Http\Controllers\PaymentSetupController::class, 'destroy'])->name("payment_setup.destroy");
Route::get("payment_setup/edit/{id}", ['App\Http\Controllers\PaymentSetupController@edit']);
Route::get("payment_setup/restore/{id}", [App\Http\Controllers\PaymentSetupController::class, 'restore'])->name("payment_setup.restore");
Route::post('payment_setup/store', 'App\Http\Controllers\PaymentSetupController@store');

//myclass_info
Route::resource('myclass_info', App\Http\Controllers\schoolpanel\MyClassInfoController::class, [
    'names' => [
        'index' => 'myclass_info',
    ]
]);
Route::post('myclass_info/create', 'App\Http\Controllers\schoolpanel\MyClassInfoController@create');
Route::get("myclass_info/details/{id}", [App\Http\Controllers\schoolpanel\MyClassInfoController::class,'details'])->name("myclass_info.details");
Route::get("myclass_info/destroy/{id}", [App\Http\Controllers\schoolpanel\MyClassInfoController::class, 'destroy'])->name("myclass_info.destroy");
Route::get("myclass_info/edit/{id}", ['App\Http\Controllers\schoolpanel\MyClassInfoController@edit']);
Route::get("myclass_info/restore/{id}", [App\Http\Controllers\schoolpanel\MyClassInfoController::class, 'restore'])->name("myclass_info.restore");
Route::post('myclass_info/store', 'App\Http\Controllers\schoolpanel\MyClassInfoController@store');
//settlement
Route::get('settlement/list', 'App\Http\Controllers\SettlementController@index');
Route::get('withdraw/list', 'App\Http\Controllers\WithdrawController@index');
Route::post('withdraw/list/search', 'App\Http\Controllers\WithdrawController@search_withdraw')->name("search_withdraw");
Route::get('withdraw/list/{id}', 'App\Http\Controllers\WithdrawController@pay')->name("pay_withdraw");
Route::get('withdraw/denied/{id}', 'App\Http\Controllers\WithdrawController@deny')->name("denied");

Route::post('settled/list/search', 'App\Http\Controllers\SettlementController@search_settle')->name("search_settled");
Route::get('settled/list/{id}', 'App\Http\Controllers\SettlementController@settled')->name("settled_withdraw");

//assign_section
Route::resource('assign_section', App\Http\Controllers\schoolpanel\AssignSectionController::class, [
    'names' => [
        'index' => 'assign_section',
    ]
]);
Route::get('assign_section/section/{id}', 'App\Http\Controllers\schoolpanel\AssignSectionController@section_load');

Route::post('assign_section/create', 'App\Http\Controllers\schoolpanel\AssignSectionController@create');
Route::get("assign_section/destroy/{id}", [App\Http\Controllers\schoolpanel\AssignSectionController::class, 'destroy'])->name("assign_section.destroy");
Route::get("assign_section/restore/{id}", [App\Http\Controllers\schoolpanel\AssignSectionController::class, 'restore'])->name("assign_section.restore");
Route::post('assign_section/store', 'App\Http\Controllers\schoolpanel\AssignSectionController@store');

//assign_particulars
Route::resource('assign_particulars', App\Http\Controllers\schoolpanel\AssignParticularController::class, [
    'names' => [
        'index' => 'assign_particulars',
    ]
]);
Route::get('assign_particulars/section/{id}', 'App\Http\Controllers\schoolpanel\AssignParticularController@section_load');

Route::post('assign_particulars/create', 'App\Http\Controllers\schoolpanel\AssignParticularController@create');
Route::get("assign_particulars/destroy/{id}", [App\Http\Controllers\schoolpanel\AssignParticularController::class, 'destroy'])->name("assign_particulars.destroy");
Route::get("assign_particulars/restore/{id}", [App\Http\Controllers\schoolpanel\AssignParticularController::class, 'restore'])->name("assign_particulars.restore");
Route::post('assign_particulars/store', 'App\Http\Controllers\schoolpanel\AssignParticularController@store');

//assign_session
Route::resource('assign_session', App\Http\Controllers\schoolpanel\AssignSessionController::class, [
    'names' => [
        'index' => 'assign_session',
    ]
]);
Route::post('assign_session/create', 'App\Http\Controllers\schoolpanel\AssignSessionController@create');
Route::get("assign_session/destroy/{id}", [App\Http\Controllers\schoolpanel\AssignSessionController::class, 'destroy'])->name("assign_session.destroy");
Route::get("assign_session/restore/{id}", [App\Http\Controllers\schoolpanel\AssignSessionController::class, 'restore'])->name("assign_session.restore");
Route::post('assign_session/store', 'App\Http\Controllers\schoolpanel\AssignSessionController@store');

//assign_shift
Route::resource('assign_shift', App\Http\Controllers\schoolpanel\AssignShiftController::class, [
    'names' => [
        'index' => 'assign_shift',
    ]
]);
Route::post('assign_shift/create', 'App\Http\Controllers\schoolpanel\AssignShiftController@create');
Route::get("assign_shift/destroy/{id}", [App\Http\Controllers\schoolpanel\AssignShiftController::class, 'destroy'])->name("assign_shift.destroy");
Route::get("assign_shift/restore/{id}", [App\Http\Controllers\schoolpanel\AssignShiftController::class, 'restore'])->name("assign_shift.restore");
Route::post('assign_shift/store', 'App\Http\Controllers\schoolpanel\AssignShiftController@store');

//myclass_wise_fees
Route::resource('myclass_wise_fees', App\Http\Controllers\schoolpanel\MyClassWiseFeesController::class, [
    'names' => [
        'index' => 'myclass_wise_fees',
    ]
]);
Route::post('myclass_wise_fees/store', 'App\Http\Controllers\schoolpanel\MyClassWiseFeesController@store')->name("class_wise_fees.store");
Route::get('fees_amount/{sid}/{cid}/{yid}', 'App\Http\Controllers\schoolpanel\MyClassWiseFeesController@retrieve_fees_amount');

//Student Transaction list
Route::get('student_transactions', 'App\Http\Controllers\schoolpanel\StudentTransactionListController@index');
Route::get('student_transactions_details/{id}', 'App\Http\Controllers\schoolpanel\StudentTransactionListController@show_details')->name('student_trxn_details');
Route::get('student_edit_transactions/{id}', 'App\Http\Controllers\schoolpanel\StudentTransactionListController@edit_trxn');
Route::post('student_transactions_update', 'App\Http\Controllers\schoolpanel\StudentTransactionListController@update_trxn')->name('student_update_trxn');
Route::post('student_search_transactions', 'App\Http\Controllers\schoolpanel\StudentTransactionListController@search_trxn')->name('student_search_trxn');

//bulk-upload
Route::post("download-excel-format", 'App\Http\Controllers\schoolpanel\MyStudentController@downloadExcelFormat')->name("downloadExcelFormat");
Route::get("schoolpanel/upload-mystudents", [App\Http\Controllers\schoolpanel\MyStudentController::class, 'createUploadStudent'])->name("mystudents.create_upload_student");
Route::post("schoolpanel/upload-mystudents/store", [App\Http\Controllers\schoolpanel\MyStudentController::class, 'uploadStudent'])->name("mystudents.upload_student");


//Tellerpanel
Route::get('tellerpanel', 'App\Http\Controllers\tellerpanel\TellerPanelController@index')->name('tellerpanel');
Route::post('tellerpanel/payment', 'App\Http\Controllers\tellerpanel\TellerPanelController@payment_done')->name('tellerpanel.payment');
Route::any('tellerpanel/search', 'App\Http\Controllers\tellerpanel\TellerPanelController@search')->name('tellerpanel.search');
Route::post('tellerpanel/store', 'App\Http\Controllers\tellerpanel\TellerPanelController@store')->name('tellerpanel.store');
//StudentLedger
Route::get('student_ledger', 'App\Http\Controllers\tellerpanel\StudentLedgerController@index')->name('student_ledger');
Route::post('student_ledger/search', 'App\Http\Controllers\tellerpanel\StudentLedgerController@search')->name('student_ledger.search');
// panel
Route::get('officerpanel', 'App\Http\Controllers\officerpanel\OfficerPanelController@index')->name('officerpanel');
Route::post('officerpanel/payment', 'App\Http\Controllers\officerpanel\OfficerPanelController@payment')->name('officerpanel.payment');
Route::post('officerpanel/search', 'App\Http\Controllers\officerpanel\OfficerPanelController@search')->name('officerpanel.search');
Route::post('officerpanel/store', 'App\Http\Controllers\officerpanel\OfficerPanelController@store')->name('officerpanel.store');
Route::get('officerpanel/student_ledger', 'App\Http\Controllers\officerpanel\StudentLedgerController@index')->name('officerpanel_student_ledger');
Route::post('officerpanel/student_ledger/search', 'App\Http\Controllers\tellerpanel\StudentLedgerController@search')->name('officerpanel_student_ledger.search');
Route::get('officerpanel/student_transactions', 'App\Http\Controllers\officerpanel\TransactionListController@index');
Route::post('officerpanel/student_search_transactions', 'App\Http\Controllers\officerpanel\TransactionListController@search_trxn')->name('officerpanel_search_trxn');
Route::get('officerpanel/class-wise-fees', 'App\Http\Controllers\officerpanel\ClassWiseFeesController@index');
Route::post('officerpanel/store-class-wise-fees', 'App\Http\Controllers\officerpanel\ClassWiseFeesController@store')->name('officerpanel_store_class_wise_fees');
Route::get('officerpanel/fees-amount/{sid}/{cid}/{yid}', 'App\Http\Controllers\officerpanel\ClassWiseFeesController@retrieve_fees_amount');
Route::get('officerpanel/transaction_summary', 'App\Http\Controllers\officerpanel\TransactionSummaryController@index');
Route::post('officerpanel/search_transaction_summary', 'App\Http\Controllers\officerpanel\TransactionSummaryController@search_trxn')->name('transaction_summary_search_trxn');

//school_accounts_panel
//assign_section
Route::get('school_accounts_panel/assign_section', 'App\Http\Controllers\school_accounts_panel\AssignSectionController@index')->name('school_accounts_panel-assign_section');
Route::post('school_accounts_panel/assign_section/create', 'App\Http\Controllers\school_accounts_panel\AssignSectionController@create')->name('school_accounts_panel-assign_section.create');
Route::get("school_accounts_panel/assign_section/destroy/{id}", [App\Http\Controllers\school_accounts_panel\AssignSectionController::class, 'destroy'])->name("school_accounts_panel-assign_section.destroy");
Route::get("school_accounts_panel/assign_section/restore/{id}", [App\Http\Controllers\school_accounts_panel\AssignSectionController::class, 'restore'])->name("school_accounts_panel-assign_section.restore");
Route::post('school_accounts_panel/assign_section/store', 'App\Http\Controllers\school_accounts_panel\AssignSectionController@store')->name('school_accounts_panel-assign_section.store');

//assign_session
Route::get('school_accounts_panel/assign_session', 'App\Http\Controllers\school_accounts_panel\AssignSessionController@index')->name('school_accounts_panel-assign_session');
Route::post('school_accounts_panel/assign_session/create', 'App\Http\Controllers\school_accounts_panel\AssignSessionController@create')->name("school_accounts_panel-assign_session.create");
Route::get("school_accounts_panel/assign_session/destroy/{id}", [App\Http\Controllers\school_accounts_panel\AssignSessionController::class, 'destroy'])->name("school_accounts_panel-assign_session.destroy");
Route::get("school_accounts_panel/assign_session/restore/{id}", [App\Http\Controllers\school_accounts_panel\AssignSessionController::class, 'restore'])->name("school_accounts_panel-assign_session.restore");
Route::post('school_accounts_panel/assign_session/store', 'App\Http\Controllers\school_accounts_panel\AssignSessionController@store')->name("school_accounts_panel-assign_session.store");

//assign_shift
Route::get('school_accounts_panel/assign_shift', 'App\Http\Controllers\school_accounts_panel\AssignShiftController@index')->name('school_accounts_panel-assign_shift');
Route::post('school_accounts_panel/assign_shift/create', 'App\Http\Controllers\school_accounts_panel\AssignShiftController@create')->name("school_accounts_panel-assign_shift.create");
Route::get("school_accounts_panel/assign_shift/destroy/{id}", [App\Http\Controllers\school_accounts_panel\AssignShiftController::class, 'destroy'])->name("school_accounts_panel-assign_shift.destroy");
Route::get("school_accounts_panel/assign_shift/restore/{id}", [App\Http\Controllers\school_accounts_panel\AssignShiftController::class, 'restore'])->name("school_accounts_panel-assign_shift.restore");
Route::post('school_accounts_panel/assign_shift/store', 'App\Http\Controllers\school_accounts_panel\AssignShiftController@store')->name("school_accounts_panel-assign_shift.store");

//myclass_wise_fees
Route::get('school_accounts_panel/myclass_wise_fees', 'App\Http\Controllers\school_accounts_panel\MyClassWiseFeesController@index')->name('school_accounts_panel-myclass_wise_fees');
Route::post('school_accounts_panel/myclass_wise_fees/store', 'App\Http\Controllers\school_accounts_panel\MyClassWiseFeesController@store')->name("school_accounts_panel-myclass_wise_fees.store");
Route::get('school_accounts_panel/fees_amount/{sid}/{cid}/{yid}', 'App\Http\Controllers\school_accounts_panel\MyClassWiseFeesController@retrieve_fees_amount')->name("school_accounts_panel-class_wise_fees.retrieve_fees_amount");
//fees_collection
Route::get('school_accounts_panel/fees_collection', 'App\Http\Controllers\school_accounts_panel\FeesCollectionController@index')->name('school_accounts_panel-fees_collection');
Route::post('school_accounts_panel/fees_collection/payment', 'App\Http\Controllers\school_accounts_panel\FeesCollectionController@payment')->name('school_accounts_panel-fees_collection.payment');
Route::post('school_accounts_panel/fees_collection/search', 'App\Http\Controllers\school_accounts_panel\FeesCollectionController@search')->name('school_accounts_panel-fees_collection.search');
Route::post('school_accounts_panel/fees_collection/store', 'App\Http\Controllers\school_accounts_panel\FeesCollectionController@store')->name('school_accounts_panel-fees_collection.store');
//income statement
Route::get('school_accounts_panel/income_statement', 'App\Http\Controllers\school_accounts_panel\IncomeStatementController@index');
Route::post('school_accounts_panel/income_statement/search', 'App\Http\Controllers\school_accounts_panel\IncomeStatementController@search_trxn')->name('income_statement.search_trxn');
//class_wise_payment_summary
Route::get('school_accounts_panel/class_wise_payment_summary', 'App\Http\Controllers\school_accounts_panel\ClassWisePaymentSummary@index');
Route::post('school_accounts_panel/class_wise_payment_summary/search', 'App\Http\Controllers\school_accounts_panel\ClassWisePaymentSummary@search_trxn')->name('class_wise_payment_summary.search_trxn');
//Mycollection
Route::get('school_accounts_panel/my_collection', 'App\Http\Controllers\school_accounts_panel\MyCollectionController@index');
Route::post('school_accounts_panel/my_collection/search', 'App\Http\Controllers\school_accounts_panel\MyCollectionController@search_trxn')->name('my_collection.search_trxn');
//daily transaction
Route::get('school_accounts_panel/daily_transaction', 'App\Http\Controllers\school_accounts_panel\DailyTransactionController@index');
Route::post('school_accounts_panel/daily_transaction/search', 'App\Http\Controllers\school_accounts_panel\DailyTransactionController@search_trxn')->name('daily_transaction.search_trxn');

/*</mowmita>*/

// Session Info

Route::get("loading_session_info_item_ajax_hit/{id}", [App\Http\Controllers\SessionController::class, 'loading_session_info_item_ajax_hit']);
Route::get("session-info/restore/{id}", [App\Http\Controllers\SessionController::class, 'restore'])->name("session_info.restore");

Route::get("session-info/{id}", [App\Http\Controllers\SessionController::class, 'destroy'])->name("session_info.destroy");
Route::resource('session-info', App\Http\Controllers\SessionController::class, [
    'names' => [
        'index' => 'session_info',
        'store' => 'session_info.store',
    ]
]);
// ./ Session Info


// Group Info
Route::get("loading_group_info_item_ajax_hit/{id}", [App\Http\Controllers\GroupController::class, 'loading_group_info_item_ajax_hit']);
Route::get("group-info/{id}", [App\Http\Controllers\GroupController::class, 'destroy'])->name("group_info.destroy");
Route::resource('group-info', App\Http\Controllers\GroupController::class, [
    'names' => [
        'index' => 'group_info',
        'store' => 'group_info.store',
    ]
]);
// ./ Group Info


// Medium Info
Route::get("loading_medium_info_item_ajax_hit/{id}", [App\Http\Controllers\MediumController::class, 'loading_medium_info_item_ajax_hit']);
Route::get("medium-info/{id}", [App\Http\Controllers\MediumController::class, 'destroy'])->name("medium_info.destroy");
Route::resource('medium-info', App\Http\Controllers\MediumController::class, [
    'names' => [
        'index' => 'medium_info',
        'store' => 'medium_info.store',
    ]
]);
// ./ Group Info


// Student
Route::get("students/create/upload-students", [App\Http\Controllers\StudentController::class, 'createUploadStudent'])->name("students.create_upload_student");
Route::post("upload-students", [App\Http\Controllers\StudentController::class, 'uploadStudent'])->name("students.upload_student");

Route::get("students/view_details/{id}", [App\Http\Controllers\StudentController::class, 'view_details'])->name("students.view_details");
Route::get("loading_student_item_ajax_hit/{id}", [App\Http\Controllers\StudentController::class, 'loading_student_item_ajax_hit']);
Route::get("students/restore/{id}", [App\Http\Controllers\StudentController::class, 'restore'])->name("students.restore");
Route::get("students/{id}", [App\Http\Controllers\StudentController::class, 'destroy'])->name("students.destroy");
Route::get("students/create/form", [App\Http\Controllers\StudentController::class, 'create'])->name("students.create_page");

Route::get("students/create/reAdmissionForm", [App\Http\Controllers\StudentController::class, 'createReadmission'])->name("students.create_readmission_page");
Route::get("students/edit/{id}", [App\Http\Controllers\StudentController::class, 'edit'])->name("students.edit_page");
Route::get("students_search", [App\Http\Controllers\StudentController::class, 'index'])->name("students.search");
// Route::post("students/readmission", [App\Http\Controllers\StudentController::class, 'readmission_store'])->name("students.readmission_store");

Route::resource('students', App\Http\Controllers\StudentController::class, [
    'names' => [
        'index' => 'students',
        'store' => 'students.store',
    ]
]);
// ./ Group Info


// Division, District, Post
Route::get('division/district_loading_ajax_hit', [App\Http\Controllers\SchoolDivisionController::class, "district_loading_ajax_hit"])->name('district_loading_ajax_hit');
Route::get('division/post_loading_ajax_hit', [App\Http\Controllers\SchoolDivisionController::class, "post_loading_ajax_hit"])->name('post_loading_ajax_hit');





Route::get('permission', 'App\Http\Controllers\PermissionController@index');
Route::post('permission-setting', 'App\Http\Controllers\PermissionController@assign_permission')->name('save');
Route::get('check-permissions/{id}', 'App\Http\Controllers\PermissionController@check_permissions');




/*Fees head routes name --- Mominur*/
Route::get("feeshead/{id}", [App\Http\Controllers\FeesHeadController::class, 'destroy'])->name("feeshead.destroy");
Route::get("feeshead/restore/{id}", [App\Http\Controllers\FeesHeadController::class, 'restore'])->name("feeshead.restore");

Route::get("edit_item/{id}", [App\Http\Controllers\FeesHeadController::class, 'edit_ajax']);
Route::get('update-status-fh/{id}', 'App\Http\Controllers\FeesHeadController@update_status_fh');
Route::resource('feeshead', App\Http\Controllers\FeesHeadController::class, [
    'names' => [
        'index' => 'feeshead',
        'store' => 'feeshead.store',
    ]
]);


/*Fees Sub-head routes name--- Mominur*/
Route::get("subhead/{id}", [App\Http\Controllers\FeesSubHeadController::class, 'destroy'])->name("subhead.destroy");
Route::get("edit_subhead_item/{id}", [App\Http\Controllers\FeesSubHeadController::class, 'edit_subhead_ajax']);
Route::get('update-status-fsh/{id}', 'App\Http\Controllers\FeesSubHeadController@update_status_fsh');
Route::resource('subhead', App\Http\Controllers\FeesSubHeadController::class, [
    'names' => [
        'index' => 'subhead',
        'store' => 'subhead.store',
    ]
]);


/*Assign Class routes name --- Mominur*/
Route::get("assign_class/{id}", [App\Http\Controllers\AssignClassController::class, 'destroy'])->name("assign_class.destroy");
Route::get("assign_class/restore{id}", [App\Http\Controllers\AssignClassController::class, 'restore'])->name("assign_class.restore");

Route::get("update_status/{id}", 'App\Http\Controllers\AssignClassController@update_status')->name("update_status");
Route::get("edit_assign_class/{id}", [App\Http\Controllers\AssignClassController::class, 'edit_assign_class_ajax']);
Route::resource('assign_class', App\Http\Controllers\AssignClassController::class, [
    'names' => [
        'index' => 'assign_class',
        'store' => 'assign_class.store',
    ]
]);






/*class wise fees route name--- Mominur*/
Route::get('class-wise-fees', 'App\Http\Controllers\ClassWiseFeesController@index');
Route::post('store-class-wise-fees', 'App\Http\Controllers\ClassWiseFeesController@store')->name('store_class_wise_fees');
Route::get('fees-amount/{sid}/{cid}/{yid}', 'App\Http\Controllers\ClassWiseFeesController@retrieve_fees_amount');

/*Generate Invoice Controller*/
Route::get('generate-invoices', 'App\Http\Controllers\GenerateInvoiceController@index');
//Route::post('fees_head_with_amount', 'App\Http\Controllers\GenerateInvoiceController@FeesHeadWithAmount');
Route::post('store-generated-invoices', 'App\Http\Controllers\GenerateInvoiceController@store')->name('storeGeneratedInvoices');


/*Fees Waiver routes name --- Mominur*/
Route::get('fees-waiver', 'App\Http\Controllers\FeesWaiverController@index');
Route::post('get-waiver-info', 'App\Http\Controllers\FeesWaiverController@getWaiverInfo');
Route::post('store-fees-waiver', 'App\Http\Controllers\FeesWaiverController@store')->name('store_fees_waiver');
Route::get("getfees/{id}", [App\Http\Controllers\FeesWaiverController::class,'fetch_data'])->name("waaiver.getfees");
Route::get('retrieve-data/{student_id}', 'App\Http\Controllers\FeesWaiverController@fetch_data')->name('retrieve_data');
Route::get('fees-waiver/{yid}/{cid}/{stuid}', 'App\Http\Controllers\FeesWaiverController@fetch_fees_waivers');


/* Fees Collection name--- Mominur*/
Route::get('fees-collection', 'App\Http\Controllers\FeesCollectionController@index');
Route::post('store-fees-collection', 'App\Http\Controllers\FeesCollectionController@store')->name('store_fees');
Route::get('fetch-student-id/{year}/{class}', 'App\Http\Controllers\FeesCollectionController@fetch_student_id');
Route::get('index-fess-collection', 'App\Http\Controllers\FeesCollectionController@data_table_data');
Route::get('stu-fees-collection/{y}/{c}/{stu}', 'App\Http\Controllers\FeesCollectionController@fetch_fees_collection');


Route::get('monthly-collection', 'App\Http\Controllers\FeesCollectionController@monthly_collection');
Route::get('monthly-active-payments', 'App\Http\Controllers\FeesCollectionController@monthly_active_payments');
Route::post('monthly-active-payments_search', 'App\Http\Controllers\FeesCollectionController@monthly_active_payments_search')->name('serachMonthlyActivePayments');

Route::get('monthly-due-payments', 'App\Http\Controllers\FeesCollectionController@monthly_payments_dues');
Route::post('monthly-due-payments_search', 'App\Http\Controllers\FeesCollectionController@monthly_payments_dues_search')->name('serachMonthlyDuePayments');
Route::get('newly_onboard', 'App\Http\Controllers\FeesCollectionController@new_onboard');

Route::post('search-collection-a', 'App\Http\Controllers\FeesCollectionController@serachCollectionA')->name('serachCollectionA');
Route::post('search-tcollection-a', 'App\Http\Controllers\FeesCollectionController@serachTCollectionA')->name('serachTCollectionA');
Route::post('search-wcollection-a', 'App\Http\Controllers\FeesCollectionController@serachWCollectionA')->name('serachWCollectionA');


Route::get('today-collection', 'App\Http\Controllers\FeesCollectionController@todays_collection');
Route::get('weekly-collection', 'App\Http\Controllers\FeesCollectionController@weekly_collection');
Route::get('total-dues', 'App\Http\Controllers\FeesCollectionController@total_dues')->name('totalDues');
Route::post('search-collection-data', 'App\Http\Controllers\FeesCollectionController@serachCollectionData')->name('serachCollectionData');
Route::post('search-total-dues', 'App\Http\Controllers\FeesCollectionController@searchTotalDuesSchoolWise')->name('searchTotalDuesSchoolWise');


/*School Admin Panel Dashboard */
Route::get('monthly-collection-sa', 'App\Http\Controllers\FeesCollectionController@monthly_collection_sa');
Route::get('today-collection-sa', 'App\Http\Controllers\FeesCollectionController@todays_collection_sa');
Route::get('weekly-collection-sa', 'App\Http\Controllers\FeesCollectionController@weekly_collection_sa');
Route::get('total-dues-sa', 'App\Http\Controllers\FeesCollectionController@total_dues_sa')->name('totalDuesSA');
Route::post('search-collection-sa', 'App\Http\Controllers\FeesCollectionController@serachCollectionSA')->name('serachCollectionSA');
Route::post('total-dues-search-sa', 'App\Http\Controllers\FeesCollectionController@TotalDuesSearchSA')->name('TotalDuesSearchSA');

Route::post('student-dues-invoice', 'App\Http\Controllers\FeesCollectionController@StudentsDuesInvoices');
Route::get('invoice-details/{id}', 'App\Http\Controllers\FeesCollectionController@InvoiceDetails');
Route::post('store_fees_collection', 'App\Http\Controllers\FeesCollectionController@store_fc')->name('store_fc');

/*teller panel dashboard*/
Route::get('monthly-collection-tp', 'App\Http\Controllers\tellerpanel\TellerPanelController@monthly_collection_tp');
Route::get('today-collection-tp', 'App\Http\Controllers\tellerpanel\TellerPanelController@todays_collection_tp');
Route::get('weekly-collection-tp', 'App\Http\Controllers\tellerpanel\TellerPanelController@weekly_collection_tp');


/*Transactions Routes name- Mominur*/
Route::get('transactions', 'App\Http\Controllers\TransactionListController@index');
Route::get('transactions-details/{id}', 'App\Http\Controllers\TransactionListController@show_details')->name('trxn_details');
Route::get('edit-transactions/{id}', 'App\Http\Controllers\TransactionListController@edit_trxn');
Route::post('transactions_update', 'App\Http\Controllers\TransactionListController@update_trxn')->name('update_trxn');
Route::post('search-transactions', 'App\Http\Controllers\TransactionListController@search_trxn')->name('search_trxn');

/*Agent panel Routes name- Mominur*/
Route::get('agent', 'App\Http\Controllers\Agent\AgentPanelController@index')->name('agent');

// Route::post('payment-response-agent', 'App\Http\Controllers\Agent\AgentPanelController@payment_response')->name('payment_response');

Route::get('get-school-wise-student/{id}', 'App\Http\Controllers\Agent\AgentPanelController@getSchoolWiseStudents');
Route::post('payment-collection-agent', 'App\Http\Controllers\Agent\AgentPanelController@FeesHeadWisePayment')->name('FeesHeadWisePayment');
Route::post('store-payment-agent', 'App\Http\Controllers\Agent\AgentPanelController@store_payment_agent')->name('store_payment_agent');

Route::post('initiate-payment', 'App\Http\Controllers\Agent\AgentPanelController@initiate_payment')->name('initiate_payment');
Route::get('student-ledger', 'App\Http\Controllers\Agent\AgentPanelController@goStudentLedger');
Route::post('search-student-ledger', 'App\Http\Controllers\Agent\AgentPanelController@searchStudentLedger')->name('searchStudentLedger');
Route::get('todays-collection', 'App\Http\Controllers\Agent\AgentPanelController@goTodaysCollection');
Route::get('collection-summery', 'App\Http\Controllers\Agent\AgentPanelController@goCollectionSummery');
Route::post('search-collection-summery', 'App\Http\Controllers\Agent\AgentPanelController@searchCollectionSummery')->name('searchCollectionSummery');


/*Guardian Panel start Mominur*/
Route::get('student-list', 'App\Http\Controllers\GuardianPanel\GuardianPanelController@index_student_list')->name('studentList');
Route::get('dues-list', 'App\Http\Controllers\GuardianPanel\GuardianPanelController@dues_list');
Route::post('studentwise-dues-list', 'App\Http\Controllers\GuardianPanel\GuardianPanelController@get_dues_list')->name('StudentWiseDuesList');

Route::get('payment-list', 'App\Http\Controllers\GuardianPanel\GuardianPanelController@goPaymentList');
Route::post('search-payment-list', 'App\Http\Controllers\GuardianPanel\GuardianPanelController@paymentList')->name('paymentList');
Route::get('pay-online', 'App\Http\Controllers\GuardianPanel\GuardianPanelController@goPayOnline')->name('payonline');
Route::post('pay-online/search', 'App\Http\Controllers\GuardianPanel\GuardianPanelController@paySearch')->name('paySearch');


Route::post('select-payment-subhead', 'App\Http\Controllers\GuardianPanel\GuardianPanelController@gofeessubhead')->name('gofeessubhead');
Route::post('store_payment', 'App\Http\Controllers\GuardianPanel\GuardianPanelController@store_payment')->name('store_payment');

Route::post('pay-now', 'App\Http\Controllers\GuardianPanel\GuardianPanelController@paynow')->name('paynow');
Route::post('pay-now-intotal', 'App\Http\Controllers\GuardianPanel\GuardianPanelController@payNowInTotal')->name('payNowInTotal');
Route::post('pay-now/partial', 'App\Http\Controllers\GuardianPanel\GuardianPanelController@payNowPartial')->name('payNowPartial');



Route::post('initiate-payonline', 'App\Http\Controllers\GuardianPanel\GuardianPanelController@initiate_payonline')->name('initiate_payonline');

Route::get('student-ledger', 'App\Http\Controllers\GuardianPanel\GuardianPanelController@studentLedgerGurdian');
Route::post('search-student-ledger', 'App\Http\Controllers\GuardianPanel\GuardianPanelController@searchStudentledger')->name('searchStudentledger');

Route::get('waiver-report', 'App\Http\Controllers\GuardianPanel\GuardianPanelController@goWaiverReport');
Route::post('student-waiver-report', 'App\Http\Controllers\GuardianPanel\GuardianPanelController@generateWaiverReport')->name('generateWaiverReport');


Route::post('manage-profile', 'App\Http\Controllers\GuardianPanel\GuardianPanelController@manageProfile')->name('manageProfile');
Route::post('settle-withdraw-req', 'App\Http\Controllers\WithdrawController@settleWithdrawReq')->name('settleWithdrawReq');

Route::get('guardian-notice', 'App\Http\Controllers\AllNotificationController@showGuardianNotice')->name('guardianNotice');
Route::get('school-wise-notice', 'App\Http\Controllers\AllNotificationController@showSchoolNotification')->name('showSchoolNotification');

Route::get('school-wise-students/{id}', 'App\Http\Controllers\GuardianPanel\GuardianPanelController@getSchoolWiseStudents');
Route::post('store-siblings', 'App\Http\Controllers\GuardianPanel\SiblingController@store')->name('storeSibling');
Route::post('check-otp', 'App\Http\Controllers\GuardianPanel\SiblingController@checkOTP')->name('confirm_otp');

/*Guardian Panel end Mominur*/


//Manage Notice
Route::post('notices/create', 'App\Http\Controllers\NoticesController@create');
Route::post('notices/store', 'App\Http\Controllers\NoticesController@store');
Route::get("notices/details/{id}", [App\Http\Controllers\NoticesController::class,'details'])->name("notices.details");
Route::get("notices/{id}", [App\Http\Controllers\NoticesController::class, 'destroy'])->name("notices.destroy");

Route::get("notices/edit/{id}", ['App\Http\Controllers\NoticesController@edit'])->name("notices.edit");

Route::resource('notices', App\Http\Controllers\NoticesController::class, [
    'names' => [
        'index' => 'notices',
    ]
]);
// Route::post('add-notices','NoticesController@create');


/*Officer Panel Done By Mominur*/

Route::get('create-user', 'App\Http\Controllers\officerpanel\CreateUserController@index')->name('createUser');
Route::post('store-create-user', 'App\Http\Controllers\officerpanel\CreateUserController@store')->name('storeCreateUser');
Route::get("userdestroy/{id}", [App\Http\Controllers\officerpanel\CreateUserController::class, 'destroy'])->name("userdestroy.destroy");
Route::get("edit_user/{id}", [App\Http\Controllers\officerpanel\CreateUserController::class, 'edit_user']);

Route::get('permission-setting', 'App\Http\Controllers\officerpanel\OfficerPanelPermissionController@index');
Route::post('save-permission', 'App\Http\Controllers\officerpanel\OfficerPanelPermissionController@assign_permission')->name('save');
Route::get('check-user-permissions/{id}', 'App\Http\Controllers\officerpanel\OfficerPanelPermissionController@check_permissions');


Route::get('notification-index', 'App\Http\Controllers\officerpanel\NotificationController@index')->name('notificationIndex');
Route::get('show-notification-details/{id}', 'App\Http\Controllers\officerpanel\NotificationController@showDetailsNotification');

Route::post('store-notification', 'App\Http\Controllers\officerpanel\NotificationController@store')->name('storeNotification');
Route::get('delete-notification/{id}', 'App\Http\Controllers\officerpanel\NotificationController@destroy')->name('deleteNotification');
Route::get('details-notification/{id}', 'App\Http\Controllers\officerpanel\NotificationController@getDetails');

//Mowmita -change password
Route::post('change_password', 'App\Http\Controllers\officerpanel\CreateUserController@changePassword')->name('changePassword');
//late fee setup
Route::get('late_fee_setup', 'App\Http\Controllers\schoolpanel\SchoolPanelHome@setupFee');
Route::post('late_fee_setup/store', 'App\Http\Controllers\schoolpanel\SchoolPanelHome@setupFeeStore')->name('setupFeeStore');


Route::get('create-user-panel', 'App\Http\Controllers\schoolpanel\SchoolPanelHome@userList')->name('userList');
Route::post('save-create-user-info', 'App\Http\Controllers\schoolpanel\SchoolPanelHome@storeCreateUserInfo')->name('storeCreateUserInfo');

//Email
// /
Route::get("email", [App\Http\Controllers\EmsMailController::class, 'Sendmail']);

// Invoice Generate
Route::get("invoice_view/{invoice_id}", [App\Http\Controllers\InvoiceController::class, 'show'])->name("invoice.show");
Route::get("invoicer_view/{invoice_id}", [App\Http\Controllers\InvoiceController::class, 'showr'])->name("invoice.showr");
Route::get("invoice/partial/{invoice_id}", [App\Http\Controllers\InvoiceController::class, 'fetchInvoice'])->name("fetchInvoice");
Route::get("invoice_view/pdf", [App\Http\Controllers\InvoiceController::class, 'index']);

Route::get("paynow-response/{id}", 'App\Http\Controllers\GuardianPanel\GuardianPanelController@response4Single')->name("shurjopay.response4Single");
Route::post('viewinvoices', 'App\Http\Controllers\schoolpanel\SchoolPanelHome@store_update')->name("invoice.store");
Route::get('view-invoices/Unverified', 'App\Http\Controllers\schoolpanel\SchoolPanelHome@goInvoiceUnverPage');
Route::get('view-invoices/Approve/{id}', 'App\Http\Controllers\schoolpanel\SchoolPanelHome@approved')->name("invoice.approve");
Route::post('view-invoices/Approve_all', 'App\Http\Controllers\schoolpanel\SchoolPanelHome@approveMultiple')->name("invoice.approveMultiple");

Route::get('view-invoices', 'App\Http\Controllers\schoolpanel\SchoolPanelHome@goInvoicePage');
Route::get('view-invoices/edit', 'App\Http\Controllers\schoolpanel\SchoolPanelHome@invoiceEdit')->name('invoice.edit');
Route::post('month-wise-invoices', 'App\Http\Controllers\schoolpanel\SchoolPanelHome@SearchInvoiceMonthWise')->name('SearchInvoiceMonthWise');
Route::post('view-invoices/search', 'App\Http\Controllers\schoolpanel\SchoolPanelHome@SearchInvoicee')->name('SearchInvoice');
Route::any('view-invoices/unverified/search', 'App\Http\Controllers\schoolpanel\SchoolPanelHome@SearchInvoice_unver')->name('SearchInvoice_unver');

Route::get('view-waivers', 'App\Http\Controllers\schoolpanel\SchoolPanelHome@goWaiverPage');
Route::post('search-waivers', 'App\Http\Controllers\schoolpanel\SchoolPanelHome@searchWaiver')->name('searchWaiver');

Route::get('studentid/{class_id}', 'App\Http\Controllers\schoolpanel\SchoolPanelHome@getClassSchoolWiseStudents');
Route::get('dues-report-sp', 'App\Http\Controllers\schoolpanel\SchoolPanelHome@goDuesReport');
Route::post('search-dues-reports', 'App\Http\Controllers\schoolpanel\SchoolPanelHome@SearchDuesReport')->name('SearchDuesReport');

Route::get('edit-invoice-details/{id}', 'App\Http\Controllers\schoolpanel\SchoolPanelHome@editInvoiceDetails');

Route::get("paynowResponse4Multiple/{id}", 'App\Http\Controllers\GuardianPanel\GuardianPanelController@response4Multiple')->name("response4MultiplePayment");



Route::get('manage-fine', 'App\Http\Controllers\schoolpanel\ManageFineController@GoManageFinePage')->name('maneFine');
Route::post('store-fine', 'App\Http\Controllers\schoolpanel\ManageFineController@StoreFine')->name('StoreFine');
Route::get('edit-fine/{id}', 'App\Http\Controllers\schoolpanel\ManageFineController@EditFine');
Route::get('delete-fine/{id}', 'App\Http\Controllers\schoolpanel\ManageFineController@DeleteFineDetails')->name('deleteFine');
Route::get('find-class-wise-students/{id}', 'App\Http\Controllers\schoolpanel\ManageFineController@FindClassWiseStudents');
Route::get('classWiseStudents', 'App\Http\Controllers\schoolpanel\ManageFineController@class_wise_student')->name('class_wise_student');


Route::resource('users','App\Http\Controllers\InvoiceController');


Route::get('paid-students-details/{id}','App\Http\Controllers\FeesCollectionController@PaidStudentsDetails');
Route::get('due-students-details/{id}','App\Http\Controllers\FeesCollectionController@DueStudentsDetails');

Route::get('sendsms/{id}','App\Http\Controllers\SmsController@sendSms');

//payment gateway
Route::resource('payment_gateway', App\Http\Controllers\PaymentGatewayController::class, [
    'names' => [
        'index' => 'payment_gateway',
    ]
]);
Route::get("payment_gateway_list", [App\Http\Controllers\PaymentGatewayController::class, 'list'])->name("list");
Route::post("payment_gateway_store", [App\Http\Controllers\PaymentGatewayController::class, 'store'])->name("p_store");

//exel
Route::get('export', 'App\Http\Controllers\ImportExportController@export')->name('export');
Route::get('importExportView', 'App\Http\Controllers\ImportExportController@importExportView');
Route::post('import', 'App\Http\Controllers\ImportExportController@import')->name('import');
Route::get('export_monthly', 'App\Http\Controllers\ImportExportController@exportMonthly')->name('exportMonthly');
Route::get('export_monthly_due', 'App\Http\Controllers\ImportExportController@exportMonthlyDue')->name('exportMonthlyDue');
Route::get('export_student_list', 'App\Http\Controllers\ImportExportController@exportStudentList')->name('exportStudentList');
//sucess payment
Route::get('success-payment/{id}', 'App\Http\Controllers\SuccessPaymentController@index');

//bulk invoice
Route::get('/inv_bulk', 'App\Http\Controllers\InvoiceController@inv_bulk')->name('inv_bulk');
Route::post('/inv_bulk_store', 'App\Http\Controllers\InvoiceController@bulkInvoice')->name('bulkInvoice');
//bulk account create
Route::get('/acc_bulk', 'App\Http\Controllers\AccController@acc_bulk')->name('acc_bulk');
Route::post('/acc_bulk_store', 'App\Http\Controllers\AccController@bulkAcc')->name('bulkAcc');
