<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
//route model binding
Route::model('custom_field', 'App\Models\CustomField');
Route::model('member', 'App\Models\Member');
Route::model('setting', 'App\Models\Setting');
Route::model('calendar', 'App\Models\EventCalendar');
Route::model('location', 'App\Models\EventLocation');
Route::model('event', 'App\Models\Event');
Route::model('event_payment', 'App\Models\EventPayment');
Route::model('volunteer_role', 'App\Models\VolunteerRole');
Route::model('volunteer', 'App\Models\EventVolunteer');
Route::model('contribution_batch', 'App\Models\ContributionBatch');
Route::model('fund', 'App\Models\Fund');
Route::model('user', 'App\Models\User');
Route::model('expense', 'App\Models\Expense');
Route::model('expense_type', 'App\Models\ExpenseType');
Route::model('contribution', 'App\Models\Contribution');
Route::model('campaign', 'App\Models\Campaign');
Route::model('other_income', 'App\Models\OtherIncome');
Route::model('other_income_type', 'App\Models\OtherIncomeType');
Route::model('payroll', 'App\Models\Payroll');
Route::model('payment_method', 'App\Models\PaymentMethod');
Route::model('permission', 'App\Models\Permission');
Route::model('pledge', 'App\Models\Pledge');
Route::model('pledge_payment', 'App\Models\PledgePayment');
Route::model('follow_up', 'App\Models\FollowUp');
Route::model('follow_up_category', 'App\Models\FollowUpCategory');
Route::model('asset', 'App\Models\Asset');
Route::model('asset_type', 'App\Models\AssetType');
Route::model('asset_valuation', 'App\Models\AssetValuation');
Route::model('capital', 'App\Models\Capital');
Route::model('guarantor', 'App\Models\Guarantor');
Route::model('family_member', 'App\Models\FamilyMember');
Route::model('tag', 'App\Models\Tag');
Route::model('sms_gateway', 'App\Models\SmsGateway');
//route for installation
Route::get('install', 'InstallController@index');
Route::group(['prefix' => 'install'], function () {
    Route::get('start', 'InstallController@index');
    Route::get('requirements', 'InstallController@requirements');
    Route::get('permissions', 'InstallController@permissions');
    Route::any('database', 'InstallController@database');
    Route::any('installation', 'InstallController@installation');
    Route::get('complete', 'InstallController@complete');

});
//cron route
Route::get('cron', 'CronController@index');
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('config:clear');
    return redirect('/');

});
Route::get('/config-cache', function () {
    Artisan::call('config:cache');
    return redirect('/');
});
Route::get('/', 'HomeController@index');
Route::get('login', 'HomeController@login');
Route::get('client', 'HomeController@clientLogin');
Route::post('client', 'HomeController@processClientLogin');
Route::get('client_logout', 'HomeController@clientLogout');
Route::get('admin', 'HomeController@adminLogin');

Route::get('logout', 'HomeController@logout');
Route::post('login', 'HomeController@processLogin');
Route::post('register', 'HomeController@register');
Route::post('reset', 'HomeController@passwordReset');
Route::get('reset/{id}/{code}', 'HomeController@confirmReset');
Route::post('reset/{id}/{code}', 'HomeController@completeReset');
Route::get('check/{id}', 'HomeController@checkStatus');
Route::get('dashboard', [
    'middleware' => 'sentinel',
    function () {
        $monthly_overview_data = array();
        $date = date("Y-m-d");
        $start_date1 = date_format(date_sub(date_create($date),
            date_interval_create_from_date_string('1 years')),
            'Y-m-d');

        for ($i = 1; $i < 14; $i++) {
            $d = explode('-', $start_date1);
            $contributions = \App\Models\Contribution::where('year', $d[0])->where('month',
                $d[1])->sum('amount');
            $pledges = \App\Models\PledgePayment::where('year', $d[0])->where('month', $d[1])->sum('amount');
            $other_income = \App\Models\OtherIncome::where('year', $d[0])->where('month',
                $d[1])->sum('amount');
            $events = \App\Models\EventPayment::where('year', $d[0])->where('month',
                $d[1])->sum('amount');
            $ext = ' ' . $d[0];
            array_push($monthly_overview_data, array(
                'month' => date_format(date_create($start_date1),
                    'M' . $ext),
                'contributions' => $contributions,
                'pledges' => $pledges,
                'other_income' => $other_income,
                'events' => $events
            ));
            //add 1 month to start date
            $start_date1 = date_format(date_add(date_create($start_date1),
                date_interval_create_from_date_string('1 months')),
                'Y-m-d');
        }

        $monthly_overview_data = json_encode($monthly_overview_data);
        $events = [];
        foreach (\App\Models\Event::all() as $event) {
            //determine color
            if (!empty($event->calendar)) {
                $color = $event->calendar->color;
            } else {
                $color = "#283593";
            }
            if ($event->all_day == 1) {
                array_push($events, array(
                    'id' => $event->id,
                    'title' => $event->name,
                    'start' => $event->start_date,
                    'end' => $event->end_date,
                    'color' => $color,
                    'type' => 'event',
                    'url' => url('event/' . $event->id . '/show')
                ));
            } else {
                array_push($events, array(
                    'id' => $event->id,
                    'title' => $event->name,
                    'start' => $event->start_date . 'T' . $event->start_time,
                    'end' => $event->end_date . 'T' . $event->end_time,
                    'color' => $color,
                    'type' => 'event',
                    'url' => url('event/' . $event->id . '/show')
                ));
            }

        }
        $events = json_encode($events);
        return view('dashboard', compact('monthly_overview_data', 'events'));
    }
]);
//route for custom fields
Route::group(['prefix' => 'custom_field'], function () {

    Route::get('data', 'CustomFieldController@index');
    Route::get('create', 'CustomFieldController@create');
    Route::post('store', 'CustomFieldController@store');
    Route::get('{custom_field}/show', 'CustomFieldController@show');
    Route::get('{custom_field}/edit', 'CustomFieldController@edit');
    Route::post('{id}/update', 'CustomFieldController@update');
    Route::get('{id}/delete', 'CustomFieldController@delete');

});
// General Controller
Route::group(['prefix' => 'general'], function () {
    Route::post('states', 'GeneralController@states');
    Route::post('branch', 'GeneralController@branches');
});

//route for borrowers
Route::group(['prefix' => 'member'], function () {

    Route::get('data', 'MemberController@index');
    Route::get('get_members', 'MemberController@get_members');
    Route::get('pending', 'MemberController@pending');
    Route::get('create', 'MemberController@create');
    Route::post('store', 'MemberController@store');
    Route::get('{member}/show', 'MemberController@show');
    Route::get('{member}/edit/{transfer_request?}', 'MemberController@edit');
    Route::post('{id}/update', 'MemberController@update');
    Route::get('{id}/delete', 'MemberController@delete');
    Route::get('{id}/approve', 'MemberController@approve');
    Route::get('{id}/decline', 'MemberController@decline');
    Route::get('{id}/delete_file', 'MemberController@deleteFile');
    Route::get('{id}/blacklist', 'MemberController@blacklist');
    Route::get('{id}/unblacklist', 'MemberController@unBlacklist');
    Route::get('{member}/family/create', 'MemberController@createFamily');
    Route::get('{id}/family/delete_family_member', 'MemberController@deleteFamilyMember');
    Route::post('{id}/family/store_family_member', 'MemberController@storeFamilyMember');
    Route::get('{family_member}/family/edit_family_member', 'MemberController@editFamilyMember');
    Route::post('{id}/family/update_family_member', 'MemberController@updateFamilyMember');
    Route::get('{member}/statement/pdf', 'MemberController@pdfStatement');
    Route::get('{member}/statement/print', 'MemberController@printStatement');
    Route::get('{member}/statement/email', 'MemberController@emailStatement');

	/*Member Transfer*/
    Route::get('/transfer/data', 'MemberTransferController@data');
    Route::get('/transfer/create', 'MemberTransferController@create');
    Route::post('/transfer/store', 'MemberTransferController@store');
    Route::get('/transfer/{id}/edit', 'MemberTransferController@edit');
    Route::post('/transfer/{id}/update', 'MemberTransferController@update');
	Route::post('/transfer/get-branches', 'MemberTransferController@dioces_get_branches');
    Route::post('/transfer/status', 'MemberTransferController@status');
    Route::get('/transfer/{id}/delete', 'MemberTransferController@delete');

	
	
});


//route for groups
Route::group(['prefix' => 'Groups'], function () {
Route::match(['get','post'],'/data','GroupController@index');
Route::match(['get','post'],'/get_groups', 'GroupController@get_groups');
Route::match(['get','post'],'/create_group','GroupController@create_group');
Route::match(['get','post'],'{group_id}/details','GroupController@details');
Route::match(['get','post'],'{group_id}/edit','GroupController@edit');	
Route::match(['get','post'],'{group_id}/edit_group','GroupController@edit_group');	
Route::match(['get','post'],'{group_id}/update_group','GroupController@update_group');	
Route::match(['get','post'],'/create','GroupController@create');
Route::match(['get','post'],'/type','GroupController@type');
Route::match(['get','post'],'/create_type','GroupController@create_type');
Route::match(['get','post'],'/get_group_types','GroupController@get_group_types');
Route::match(['get','post'],'/add_type','GroupController@add_type');
Route::match(['get','post'],'/create_group_type','GroupController@create_group_type');
Route::match(['get','post'],'{group_id}/edit_group_type','GroupController@edit_group_type');
Route::match(['get','post'],'{group_id}/delete','GroupController@delete_group');	
Route::match(['get','post'],'{group_id}/update_group_type','GroupController@update_group_type');
Route::match(['get','post'],'{group_id}/delete_group_type','GroupController@delete_group_type');
Route::match(['get','post'],'{group_id}/get_group_details','GroupController@get_group_details');
Route::match(['get','post'],'{group_id}/detail_member_view','GroupController@detail_member_view');
Route::match(['get','post'],'{group_id}/edit_member','GroupController@edit_member');
Route::match(['get','post'],'{group_id}/remove_member','GroupController@remove_member');
Route::match(['get','post'],'{group_member_id}/update_member_group','GroupController@update_member_group');
});

Route::get('update',
    function () {
        \Illuminate\Support\Facades\Artisan::call('migrate');
        \Laracasts\Flash\Flash::success("Successfully Updated");
        return redirect('/');
    });
Route::group(['prefix' => 'update'], function () {
    Route::get('download', 'UpdateController@download');
    Route::get('install', 'UpdateController@install');
    Route::get('clean', 'UpdateController@clean');
    Route::get('finish', 'UpdateController@finish');
});
Route::get('fix', 'UpdateController@fix');
//route for setting
Route::group(['prefix' => 'setting'], function () {
    Route::get('data', 'SettingController@index');
    Route::post('update', 'SettingController@update');
    Route::get('update_system', 'SettingController@updateSystem');
});
//route for user
Route::group(['prefix' => 'user'], function () {
    Route::get('data', 'UserController@index');
    Route::get('get_users', 'UserController@get_users');
    Route::get('create', 'UserController@create');
    Route::post('store', 'UserController@store');
    Route::get('{user}/edit', 'UserController@edit');
    Route::get('{user}/show', 'UserController@show');
    Route::post('{id}/update', 'UserController@update');
    Route::get('{id}/delete', 'UserController@delete');
    Route::get('{id}/profile', 'UserController@profile');
    Route::post('{id}/profile', 'UserController@profileUpdate');
    //manage permissions
    Route::get('permission/data', 'UserController@indexPermission');
    Route::get('permission/create', 'UserController@createPermission');
    Route::post('permission/store', 'UserController@storePermission');
    Route::get('permission/{permission}/edit', 'UserController@editPermission');
    Route::post('permission/{id}/update', 'UserController@updatePermission');
    Route::get('permission/{id}/delete', 'UserController@deletePermission');
    //manage roles
    Route::get('role/data', 'UserController@indexRole');
    Route::get('role/create', 'UserController@createRole');
    Route::post('role/store', 'UserController@storeRole');
    Route::get('role/{id}/edit', 'UserController@editRole');
    Route::post('role/{id}/update', 'UserController@updateRole');
    Route::get('role/{id}/delete', 'UserController@deleteRole');

	Route::post('/getstate', 'UserController@getstate');
	Route::post('/branches', 'UserController@branches');
	
	Route::post('validate-email','UserController@validate_email');

});

//route for tax
Route::group(['prefix' => 'tax'], function () {
    Route::get('data', 'TaxController@index');
    Route::get('create', 'TaxController@create');
    Route::post('store', 'TaxController@store');
    Route::get('{tax}/edit', 'TaxController@edit');
    Route::get('{id}/show', 'TaxController@show');
    Route::post('{id}/update', 'TaxController@update');
    Route::get('{id}/delete', 'TaxController@destroy');
});

Route::group(['prefix' => 'pension_fund'], function () {
    Route::get('/data', 'PensionFundController@index');
    Route::post('/store', 'PensionFundController@store')->name('pension_fund.store');

});


//route for payroll
Route::group(['prefix' => 'payroll'], function () {
    Route::get('data', 'PayrollController@index');
    Route::get('get_payroll', 'PayrollController@get_payroll');
    Route::get('create', 'PayrollController@create');
    Route::post('store', 'PayrollController@store');
    Route::get('{payroll}/show', 'PayrollController@show');
    Route::get('{payroll}/edit', 'PayrollController@edit');
    Route::post('{id}/update', 'PayrollController@update');
    Route::get('{id}/delete', 'PayrollController@delete');
    Route::get('getUser/{id}', 'PayrollController@getUser');
    Route::get('{payroll}/payslip', 'PayrollController@pdfPayslip');
    Route::get('{user}/data', 'PayrollController@staffPayroll');
//template
    Route::any('template', 'PayrollController@indexTemplate');
    Route::get('template/{id}/edit', 'PayrollController@editTemplate');
    Route::post('template/{id}/update', 'PayrollController@updateTemplate');
    Route::get('template/{id}/delete_meta', 'PayrollController@deleteTemplateMeta');
    Route::post('template/{id}/add_row', 'PayrollController@addTemplateRow');
});

//route for pension
Route::group(['prefix' => 'pension'], function () {
    Route::get('data', 'PensionController@index');
    Route::get('get_pension', 'PensionController@get_pension');
    Route::get('create', 'PensionController@create');
    Route::post('store', 'PensionController@store');
    Route::get('{pension}/show', 'PensionController@show');
    Route::get('{pension}/edit', 'PensionController@edit');
    Route::post('{id}/update', 'PensionController@update');
    Route::get('{id}/delete', 'PensionController@delete');
    Route::get('getUser/{id}', 'PensionController@getUser');
    Route::get('{pension}/payslip', 'PensionController@pdfPayslip');
    Route::get('{user}/data', 'PensionController@staffPension');
//template
    Route::any('template', 'PensionController@indexTemplate');
    Route::get('template/{id}/edit', 'PensionController@editTemplate');
    Route::post('template/{id}/update', 'PensionController@updateTemplate');
    Route::get('template/{id}/delete_meta', 'PensionController@deleteTemplateMeta');
    Route::post('template/{id}/add_row', 'PensionController@addTemplateRow');
});

//route for expenses
Route::group(['prefix' => 'expense'], function () {
    Route::get('data', 'ExpenseController@index');
    Route::get('get_expenses', 'ExpenseController@get_expenses');
    Route::get('create', 'ExpenseController@create');
    Route::post('store', 'ExpenseController@store');
    Route::get('{expense}/edit', 'ExpenseController@edit');
    Route::get('{expense}/show', 'ExpenseController@show');
    Route::post('{id}/update', 'ExpenseController@update');
    Route::get('{id}/delete', 'ExpenseController@delete');
    Route::get('{id}/delete_file', 'ExpenseController@deleteFile');

    //expense types
    Route::get('type/data', 'ExpenseController@indexType');
    Route::get('type/create', 'ExpenseController@createType');
    Route::post('type/store', 'ExpenseController@storeType');
    Route::get('type/{expense_type}/edit', 'ExpenseController@editType');
    Route::get('type/{expense_type}/show', 'ExpenseController@showType');
    Route::post('type/{id}/update', 'ExpenseController@updateType');
    Route::get('type/{id}/delete', 'ExpenseController@deleteType');
});
//route for other income
Route::group(['prefix' => 'other_income'], function () {
    Route::get('data', 'OtherIncomeController@index');
    Route::get('get_other_income', 'OtherIncomeController@get_other_income');
    Route::get('create', 'OtherIncomeController@create');
    Route::post('store', 'OtherIncomeController@store');
    Route::get('{other_income}/edit', 'OtherIncomeController@edit');
    Route::get('{other_income}/show', 'OtherIncomeController@show');
    Route::post('{id}/update', 'OtherIncomeController@update');
    Route::get('{id}/delete', 'OtherIncomeController@delete');
    Route::get('{id}/delete_file', 'OtherIncomeController@deleteFile');
    //income types
    Route::get('type/data', 'OtherIncomeController@indexType');
    Route::get('type/create', 'OtherIncomeController@createType');
    Route::post('type/store', 'OtherIncomeController@storeType');
    Route::get('type/{other_income_type}/edit', 'OtherIncomeController@editType');
    Route::get('type/{other_income_type}/show', 'OtherIncomeController@showType');
    Route::post('type/{id}/update', 'OtherIncomeController@updateType');
    Route::get('type/{id}/delete', 'OtherIncomeController@deleteType');
});

//route for reports
Route::group(['prefix' => 'report'], function () {
    Route::any('cash_flow', 'ReportController@cash_flow');
    Route::any('profit_loss', 'ReportController@profit_loss');
    Route::any('collection', 'ReportController@collection_report');
    Route::any('loan_product', 'ReportController@loan_product');
    Route::any('balance_sheet', 'ReportController@balance_sheet');
    Route::any('contribution_estimate', 'ReportController@contribution_estimate');
    Route::any('expense_estimate', 'ReportController@expense_estimate');
    Route::any('contribution', 'ReportController@contribution');
    Route::any('contribution_report', 'ReportController@contribution_report');
    Route::any('contribution_batch', 'ReportController@contribution_batch');
    Route::any('contribution_summary', 'ReportController@contribution_summary');
    Route::any('propotional_contribution', 'ReportController@propotional_contribution');
    Route::any('contribution_type', 'ReportController@contribution_type');

});
//route for communication
Route::group(['prefix' => 'communication'], function () {
    Route::get('email', 'CommunicationController@indexEmail');
    Route::get('get_emails', 'CommunicationController@get_emails');
    Route::get('sms', 'CommunicationController@indexSms');
    Route::get('get_sms', 'CommunicationController@get_sms');
    Route::get('email/create', 'CommunicationController@createEmail');
    Route::post('email/store', 'CommunicationController@storeEmail');
    Route::get('email/{id}/delete', 'CommunicationController@deleteEmail');
    Route::get('sms/create', 'CommunicationController@createSms');
    Route::post('sms/store', 'CommunicationController@storeSms');
    Route::get('sms/{id}/delete', 'CommunicationController@deleteSms');

});


//routes for assets
Route::group(['prefix' => 'asset'], function () {
    Route::get('data', 'AssetController@index');
    Route::get('get_assets', 'AssetController@get_assets');
    Route::get('create', 'AssetController@create');
    Route::post('store', 'AssetController@store');
    Route::get('{asset}/edit', 'AssetController@edit');
    Route::get('{asset}/show', 'AssetController@show');
    Route::post('{id}/update', 'AssetController@update');
    Route::get('{id}/delete', 'AssetController@delete');
    Route::get('{id}/delete_file', 'AssetController@deleteFile');

    //expense types
    Route::get('type/data', 'AssetController@indexType');
    Route::get('type/create', 'AssetController@createType');
    Route::post('type/store', 'AssetController@storeType');
    Route::get('type/{asset_type}/edit', 'AssetController@editType');
    Route::get('type/{asset_type}/show', 'AssetController@showType');
    Route::post('type/{id}/update', 'AssetController@updateType');
    Route::get('type/{id}/delete', 'AssetController@deleteType');
});
//new church routes
//route for tags
Route::group(['prefix' => 'tag'], function () {
    Route::get('data', 'TagController@index');
    Route::get('create', 'TagController@create');
    Route::post('store', 'TagController@store');
    Route::post('add_members', 'TagController@add_members');
    Route::post('sms_members', 'TagController@sms_members');
    Route::post('email_members', 'TagController@email_members');
    Route::get('{tag}/edit', 'TagController@edit');
    Route::get('{tag}/tag_data', 'TagController@tag_data');
    Route::get('{tag}/show', 'TagController@show');
    Route::post('{id}/update', 'TagController@update');
    Route::get('{id}/delete', 'TagController@delete');
    Route::get('{id}/remove_member', 'TagController@remove_member');
});
//routes for events
Route::group(['prefix' => 'event'], function () {
    Route::get('data', 'EventController@index');
    Route::get('create', 'EventController@create');
    Route::post('store', 'EventController@store');
    Route::get('{event}/edit', 'EventController@edit');
    Route::get('{event}/show', 'EventController@show');
    Route::post('sms_members', 'EventController@sms_members');
    Route::post('email_members', 'EventController@email_members');
    Route::post('sms_volunteers', 'EventController@sms_volunteers');
    Route::post('email_volunteers', 'EventController@email_volunteers');
    Route::get('{event}/attender', 'EventController@attender');
    Route::get('{event}/report', 'EventController@report');
    Route::get('{event}/print', 'EventController@print_event');
    Route::get('{event}/check_in', 'EventController@check_in');
    Route::get('{event}/volunteer', 'EventController@volunteer');
    Route::post('add_volunteer', 'EventController@add_volunteer');
    Route::post('add_checkin', 'EventController@add_checkin');
    Route::get('{id}/remove_checkin', 'EventController@remove_checkin');
    Route::get('{id}/remove_volunteer', 'EventController@remove_volunteer');
    Route::post('{id}/update_volunteer', 'EventController@update_volunteer');
    Route::get('{volunteer}/get_volunteer', 'EventController@get_volunteer');
    Route::get('{volunteer}/volunteer_detail', 'EventController@volunteer_detail');
    Route::post('{id}/update', 'EventController@update');
    Route::get('{id}/delete', 'EventController@delete');
    Route::get('{id}/delete_file', 'EventController@deleteFile');
    Route::get('{event}/payment', 'EventController@payment');
    Route::get('{event}/payment/create', 'EventController@create_payment');
    Route::post('{id}/payment/store', 'EventController@store_payment');
    Route::get('payment/{event_payment}/edit', 'EventController@edit_payment');
    Route::post('payment/{id}/update', 'EventController@update_payment');
    Route::get('payment/{id}/delete', 'EventController@delete_payment');
    //location
    Route::get('location/data', 'EventLocationController@index');
    Route::get('location/create', 'EventLocationController@create');
    Route::post('location/store', 'EventLocationController@store');
    Route::get('location/{location}/edit', 'EventLocationController@edit');
    Route::get('location/{location}/show', 'EventLocationController@show');
    Route::post('location/{id}/update', 'EventLocationController@update');
    Route::get('location/{id}/delete', 'EventLocationController@delete');
    //calendar
    Route::get('calendar/data', 'EventCalendarController@index');
    Route::get('calendar/create', 'EventCalendarController@create');
    Route::post('calendar/store', 'EventCalendarController@store');
    Route::get('calendar/{calendar}/edit', 'EventCalendarController@edit');
    Route::get('calendar/{calendar}/show', 'EventCalendarController@show');
    Route::post('calendar/{id}/update', 'EventCalendarController@update');
    Route::get('calendar/{id}/delete', 'EventCalendarController@delete');
    //volunteer role
    Route::get('role/data', 'VolunteerRoleController@index');
    Route::get('role/create', 'VolunteerRoleController@create');
    Route::post('role/store', 'VolunteerRoleController@store');
    Route::get('role/{volunteer_role}/edit', 'VolunteerRoleController@edit');
    Route::get('role/{volunteer_role}/show', 'VolunteerRoleController@show');
    Route::post('role/{id}/update', 'VolunteerRoleController@update');
    Route::get('role/{id}/delete', 'VolunteerRoleController@delete');
});
Route::get('audit_trail/data', 'AuditTrailController@index');
Route::get('audit_trail/get_audit_trail', 'AuditTrailController@get_audit_trail');
//routes for contributions
Route::group(['prefix' => 'contribution'], function () {
    Route::get('data', 'ContributionController@index');
    Route::get('get_contributions', 'ContributionController@get_contributions');
    Route::get('create', 'ContributionController@create');
    Route::post('store', 'ContributionController@store');
    Route::get('{contribution}/edit', 'ContributionController@edit');
    Route::get('{contribution}/show', 'ContributionController@show');
    Route::post('{id}/update', 'ContributionController@update');
    Route::get('{id}/delete', 'ContributionController@delete');
    Route::get('{id}/delete_file', 'ContributionController@deleteFile');
    Route::post('get_member_groups', 'ContributionController@get_member_groups');

	//payment methods
    Route::get('payment_method/data', 'PaymentMethodController@index');
    Route::get('payment_method/create', 'PaymentMethodController@create');
    Route::post('payment_method/store', 'PaymentMethodController@store');
    Route::get('payment_method/{payment_method}/edit', 'PaymentMethodController@edit');
    Route::get('payment_method/{payment_method}/show', 'PaymentMethodController@show');
    Route::post('payment_method/{id}/update', 'PaymentMethodController@update');
    Route::get('payment_method/{id}/delete', 'PaymentMethodController@delete');
    //funds
    Route::get('fund/data', 'FundController@index');
    Route::get('fund/create', 'FundController@create');
    Route::post('fund/store', 'FundController@store');
    Route::get('fund/{fund}/edit', 'FundController@edit');
    Route::get('fund/{fund}/show', 'FundController@show');
    Route::post('fund/{id}/update', 'FundController@update');
    Route::get('fund/{id}/delete', 'FundController@delete');
    //batches
    Route::get('batch/data', 'ContributionBatchController@index');
    Route::get('batch/create', 'ContributionBatchController@create');
    Route::post('batch/store', 'ContributionBatchController@store');
    Route::get('batch/{contribution_batch}/edit', 'ContributionBatchController@edit');
    Route::get('batch/{contribution_batch}/show', 'ContributionBatchController@show');
    Route::post('batch/{id}/update', 'ContributionBatchController@update');
    Route::get('batch/{id}/delete', 'ContributionBatchController@delete');
    Route::get('batch/{id}/open', 'ContributionBatchController@open');
    Route::get('batch/{id}/close', 'ContributionBatchController@close');
    //types
    Route::get('type/data', 'ContributionTypeController@index');
    Route::get('type/create', 'ContributionTypeController@create');
    Route::post('type/store', 'ContributionTypeController@store');
    Route::get('type/{id}/edit', 'ContributionTypeController@edit');
    Route::get('type/{id}/show', 'ContributionTypeController@show');
    Route::post('type/{id}/update', 'ContributionTypeController@update');
    Route::get('type/{id}/delete', 'ContributionTypeController@delete');
    Route::get('type/{id}/open', 'ContributionTypeController@open');
    Route::get('type/{id}/close', 'ContributionTypeController@close');
});



//route for deductions
Route::group(['prefix' => 'deduction'], function () {

    Route::get('view', 'DeductionController@index')->name('deduction.show');
    Route::get('create', 'DeductionController@create')->name('deduction.create');
    Route::get('edit/{id}', 'DeductionController@edit')->name('deduction.edit');
    Route::post('save', 'DeductionController@store')->name('deduction.store');
    Route::post('update', 'DeductionController@update')->name('deduction.update');
    Route::get('delete/{id}', 'DeductionController@delete')->name('deduction.delete');
    Route::get('branch_deductions', 'DeductionController@branch_deductions')->name('branch_deductions');
   
});





//route for pledges
Route::group(['prefix' => 'pledge'], function () {
    Route::get('data', 'PledgeController@index');
    Route::get('get_pledges', 'PledgeController@get_pledges');
    Route::get('create', 'PledgeController@create');
    Route::post('store', 'PledgeController@store');
    Route::get('{pledge}/edit', 'PledgeController@edit');
    Route::get('{pledge}/show', 'PledgeController@show');
    Route::post('{id}/update', 'PledgeController@update');
    Route::get('{id}/delete', 'PledgeController@delete');
    Route::get('{id}/delete_file', 'PledgeController@deleteFile');
    Route::post('/get_member_groups', 'PledgeController@get_member_groups');
	
	//campaigns
    Route::get('campaign/data', 'CampaignController@index');
    Route::get('campaign/create', 'CampaignController@create');
    Route::post('campaign/store', 'CampaignController@store');
    Route::get('campaign/{campaign}/edit', 'CampaignController@edit');
    Route::get('campaign/{campaign}/show', 'CampaignController@show');
    Route::post('campaign/{id}/update', 'CampaignController@update');
    Route::get('campaign/{id}/delete', 'CampaignController@delete');
    Route::get('campaign/{id}/open', 'CampaignController@open');
    Route::get('campaign/{id}/close', 'CampaignController@close');
    //payments
    Route::get('{id}/payment/data', 'PledgePaymentController@index');
    Route::get('{id}/payment/data', 'PledgePaymentController@index');
    Route::get('get_pledge_payments', 'PledgePaymentController@get_pledge_payments');
    Route::get('{id}/payment/create', 'PledgePaymentController@create');
    Route::post('{id}/payment/store', 'PledgePaymentController@store');
    Route::get('payment/{pledge_payment}/edit', 'PledgePaymentController@edit');
    Route::get('payment/{pledge_payment}/show', 'PledgePaymentController@show');
    Route::post('payment/{id}/update', 'PledgePaymentController@update');
    Route::get('payment/{id}/delete', 'PledgePaymentController@delete');

    //types
    Route::get('campaign_type/data', 'PledgeTypeController@index');
    Route::get('campaign_type/create', 'PledgeTypeController@create');
    Route::post('campaign_type/store', 'PledgeTypeController@store');
    Route::get('campaign_type/{id}/edit', 'PledgeTypeController@edit');
    Route::get('campaign_type/{id}/show', 'PledgeTypeController@show');
    Route::post('campaign_type/{id}/update', 'PledgeTypeController@update');
    Route::get('campaign_type/{id}/delete', 'PledgeTypeController@delete');
    Route::get('campaign_type/{id}/open', 'PledgeTypeController@open');
    Route::get('campaign_type/{id}/close', 'PledgeTypeController@close');	
	
});
//routes for follow ups
Route::group(['prefix' => 'follow_up'], function () {
    Route::get('data', 'FollowUpController@index');
    Route::get('get_follow_ups', 'FollowUpController@get_follow_ups');
    Route::get('my_follow_ups', 'FollowUpController@my_follow_ups');
    Route::get('create', 'FollowUpController@create');
    Route::post('store', 'FollowUpController@store');
    Route::get('{follow_up}/edit', 'FollowUpController@edit');
    Route::get('{follow_up}/show', 'FollowUpController@show');
    Route::post('{id}/update', 'FollowUpController@update');
    Route::get('{id}/delete', 'FollowUpController@delete');
    Route::get('{id}/complete', 'FollowUpController@complete');
    Route::get('{id}/incomplete', 'FollowUpController@incomplete');
    Route::get('{id}/delete_file', 'FollowUpController@deleteFile');
    //categories
    Route::get('category/data', 'FollowUpCategoryController@index');
    Route::get('category/create', 'FollowUpCategoryController@create');
    Route::post('category/store', 'FollowUpCategoryController@store');
    Route::get('category/{follow_up_category}/edit', 'FollowUpCategoryController@edit');
    Route::get('category/{follow_up_category}/show', 'FollowUpCategoryController@show');
    Route::post('category/{id}/update', 'FollowUpCategoryController@update');
    Route::get('category/{id}/delete', 'FollowUpCategoryController@delete');
    Route::get('category/{id}/open', 'FollowUpCategoryController@open');
    Route::get('category/{id}/close', 'FollowUpCategoryController@close');


});
//route for sms gateways
Route::group(['prefix' => 'sms_gateway'], function () {
    Route::get('data', 'SmsGatewayController@index');
    Route::get('create', 'SmsGatewayController@create');
    Route::post('store', 'SmsGatewayController@store');
    Route::get('{sms_gateway}/edit', 'SmsGatewayController@edit');
    Route::get('{sms_gateway}/show', 'SmsGatewayController@show');
    Route::post('{id}/update', 'SmsGatewayController@update');
    Route::get('{id}/delete', 'SmsGatewayController@delete');
});
//route for soul winning
Route::group(['prefix' => 'soul_winning'], function () {
    Route::get('data', 'SoulWinningController@index');
    Route::get('get_soul_winnings', 'SoulWinningController@get_soul_winnings');
    Route::get('{id}/convert_to_member', 'SoulWinningController@convert_to_member');
    Route::get('create', 'SoulWinningController@create');
    Route::post('store', 'SoulWinningController@store');
    Route::get('{id}/edit', 'SoulWinningController@edit');
    Route::get('{id}/show', 'SoulWinningController@show');
    Route::post('{id}/update', 'SoulWinningController@update');
    Route::get('{id}/delete', 'SoulWinningController@delete');
});
//route for branches
Route::group(['prefix' => 'branch'], function () {
    Route::get('data', 'BranchController@index');
    Route::get('create', 'BranchController@create');
    Route::post('store', 'BranchController@store');
    Route::get('{id}/edit', 'BranchController@edit');
    Route::get('{id}/show', 'BranchController@show');
    Route::post('{id}/update', 'BranchController@update');
    Route::get('{id}/delete', 'BranchController@delete');
	Route::post('get_state','BranchController@get_state');
});

// Routes For Dioces
Route::group(['prefix' => 'dioces'], function () {
	Route::get('create','DiocesController@create');
    Route::post('store', 'DiocesController@store');
    Route::get('data', 'DiocesController@index');
    Route::get('{id}/edit', 'DiocesController@edit');
    Route::post('{id}/update', 'DiocesController@update');
    Route::get('{id}/delete', 'DiocesController@delete');
});

// Routes For State
Route::group(['prefix' => 'state'], function () {
	Route::get('create','StateController@create');
    Route::post('store', 'StateController@store');
    Route::get('data', 'StateController@index');
    Route::get('{id}/edit', 'StateController@edit');
    Route::post('{id}/update', 'StateController@update');
    Route::get('{id}/delete', 'StateController@delete');
});

// Fund Transfer
Route::group(['prefix' => 'fund_transfer'], function () {
    Route::get('data', 'FundTransferController@index');
    Route::get('create', 'FundTransferController@create');
    Route::post('store', 'FundTransferController@store');
	Route::get('{id}/edit', 'FundTransferController@edit');
    Route::post('/update/{id}', 'FundTransferController@update');
    Route::get('{id}/delete', 'FundTransferController@delete');
});

// Announcement 
Route::group(['prefix' => 'announcement'], function () {
    Route::get('data', 'AnnouncementController@index');
    Route::get('create', 'AnnouncementController@create');
    Route::post('store', 'AnnouncementController@store');
	Route::get('{id}/edit', 'AnnouncementController@edit');
    Route::post('{id}/update', 'AnnouncementController@update');
    Route::get('{id}/delete', 'AnnouncementController@delete');
    Route::get('{id}/show', 'AnnouncementController@show');

	
    Route::get('type', 'AnnouncementTypeController@index');
    Route::post('type/store', 'AnnouncementTypeController@store');	
    Route::get('type/{id}/delete', 'AnnouncementTypeController@delete');
});


