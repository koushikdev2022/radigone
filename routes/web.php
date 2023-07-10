<?php

use Illuminate\Support\Facades\Route;

Route::get('/clear', function(){
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/


Route::namespace('Gateway')->prefix('ipn')->name('ipn.')->group(function () {
    Route::post('paypal', 'paypal\ProcessController@ipn')->name('paypal');
    Route::get('paypal_sdk', 'paypal_sdk\ProcessController@ipn')->name('paypal_sdk');
    Route::post('perfect_money', 'perfect_money\ProcessController@ipn')->name('perfect_money');
    Route::post('stripe', 'stripe\ProcessController@ipn')->name('stripe');
    Route::post('stripe_js', 'stripe_js\ProcessController@ipn')->name('stripe_js');
    Route::post('stripe_v3', 'stripe_v3\ProcessController@ipn')->name('stripe_v3');
    Route::post('skrill', 'skrill\ProcessController@ipn')->name('skrill');
    Route::post('paytm', 'paytm\ProcessController@ipn')->name('paytm');
    Route::post('payeer', 'payeer\ProcessController@ipn')->name('payeer');
    Route::post('paystack', 'paystack\ProcessController@ipn')->name('paystack');
    Route::post('voguepay', 'voguepay\ProcessController@ipn')->name('voguepay');
    Route::get('flutterwave/{trx}/{type}', 'flutterwave\ProcessController@ipn')->name('flutterwave');
    Route::post('razorpay', 'razorpay\ProcessController@ipn')->name('razorpay');
    Route::post('instamojo', 'instamojo\ProcessController@ipn')->name('instamojo');
    Route::get('blockchain', 'blockchain\ProcessController@ipn')->name('blockchain');
    Route::get('blockio', 'blockio\ProcessController@ipn')->name('blockio');
    Route::post('coinpayments', 'coinpayments\ProcessController@ipn')->name('coinpayments');
    Route::post('coinpayments_fiat', 'coinpayments_fiat\ProcessController@ipn')->name('coinpayments_fiat');
    Route::post('coingate', 'coingate\ProcessController@ipn')->name('coingate');
    Route::post('coinbase_commerce', 'coinbase_commerce\ProcessController@ipn')->name('coinbase_commerce');
    Route::get('mollie', 'mollie\ProcessController@ipn')->name('mollie');
    Route::post('cashmaal', 'cashmaal\ProcessController@ipn')->name('cashmaal');
});

Route::namespace('Gateway')->prefix('ipn/buy-views')->name('ipn.buy_views.')->group(function () {
    Route::post('razorpay', 'razorpay\ProcessController@ipnBuyViews')->name('razorpay');
});

// User Support Ticket
Route::prefix('ticket')->group(function () {
    Route::get('/', 'TicketController@supportTicket')->name('ticket');
    Route::get('/new', 'TicketController@openSupportTicket')->name('ticket.open');
    Route::post('/create', 'TicketController@storeSupportTicket')->name('ticket.store');
    Route::get('/view/{ticket}', 'TicketController@viewTicket')->name('ticket.view');
    Route::get('/user-view/{ticket}', 'TicketController@viewUserTicket')->name('ticket.view.user');
    Route::post('/reply/{ticket}', 'TicketController@replyTicket')->name('ticket.reply');
    Route::get('/download/{ticket}', 'TicketController@ticketDownload')->name('ticket.download');
});

// User Support Ticket
Route::prefix('ticket')->group(function () {
    Route::get('surveyor/ticket/', 'SurveyorTicketController@supportTicket')->name('surveyor.ticket');
    Route::get('surveyor/ticket/new', 'SurveyorTicketController@openSupportTicket')->name('surveyor.ticket.open');
    Route::post('surveyor/ticket/create', 'SurveyorTicketController@storeSupportTicket')->name('surveyor.ticket.store');
    Route::get('surveyor/ticket/view/{ticket}', 'SurveyorTicketController@viewTicket')->name('surveyor.ticket.view');
    Route::post('surveyor/ticket/reply/{ticket}', 'SurveyorTicketController@replyTicket')->name('surveyor.ticket.reply');
    Route::get('surveyor/ticket/download/{ticket}', 'SurveyorTicketController@ticketDownload')->name('surveyor.ticket.download');
});

Route::prefix('ticket')->group(function () {
    Route::get('agent/ticket/', 'AgentTicketController@supportTicket')->name('agent.ticket');
    Route::get('agent/ticket/new', 'AgentTicketController@openSupportTicket')->name('agent.ticket.open');
    Route::post('agent/ticket/create', 'AgentTicketController@storeSupportTicket')->name('agent.ticket.store');
    Route::get('agent/ticket/view/{ticket}', 'AgentTicketController@viewTicket')->name('agent.ticket.view');
    Route::post('agent/ticket/reply/{ticket}', 'AgentTicketController@replyTicket')->name('agent.ticket.reply');
    Route::get('agent/ticket/download/{ticket}', 'AgentTicketController@ticketDownload')->name('agent.ticket.download');
});
/*
|--------------------------------------------------------------------------
| Start Admin Area
|--------------------------------------------------------------------------
*/

Route::namespace('Admin')->prefix('admin')->name('admin.')->group(function () {
    Route::namespace('Auth')->group(function () {
        Route::get('/', 'LoginController@showLoginForm')->name('login');
        Route::post('/', 'LoginController@login')->name('login');
        Route::get('logout', 'LoginController@logout')->name('logout');

        // Admin Password Reset
        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
        Route::post('password/reset', 'ForgotPasswordController@sendResetLinkEmail');
        Route::post('password/verify-code', 'ForgotPasswordController@verifyCode')->name('password.verify-code');
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.change-link');
        Route::post('password/reset/change', 'ResetPasswordController@reset')->name('password.change');
    });

    Route::middleware(['admin','access'])->group(function () {
        Route::get('dashboard', 'AdminController@dashboard')->name('dashboard');
        Route::get('profile', 'AdminController@profile')->name('profile');
        Route::post('profile', 'AdminController@profileUpdate')->name('profile.update');
        Route::get('password', 'AdminController@password')->name('password');
        Route::post('password', 'AdminController@passwordUpdate')->name('password.update');

        // Route::get('slider-second', 'CategoryController@sliderSecond')->name('slider-second')->middleware(['permission:manage-slider-second,admin']);
        // Route::post('slider-second/new', 'CategoryController@storeadType')->name('slider-second.store')->middleware(['permission:manage-slider-second,admin']);
        // Route::post('slider-second/activate', 'CategoryController@activateadType')->name('slider-second.activate')->middleware(['permission:manage-slider-second,admin']);
        // Route::post('slider-second/update/{id}', 'CategoryController@updateType')->name('slider-second.update')->middleware(['permission:manage-slider-second,admin']);

        Route::get('ad-type', 'CategoryController@adType')->name('add-type')->middleware(['permission:manage-ad-type,admin']);
        Route::post('ad-type/new', 'CategoryController@storeadType')->name('ad-type.store')->middleware(['permission:manage-ad-type,admin']);
        Route::post('ad-type/activate', 'CategoryController@activateadType')->name('ad-type.activate')->middleware(['permission:manage-ad-type,admin']);
        Route::post('ad-type/update/{id}', 'CategoryController@updateType')->name('ad-type.update')->middleware(['permission:manage-ad-type,admin']);
        //Manage Category
        Route::get('category', 'CategoryController@categories')->name('category')->middleware(['permission:manage-category,admin']);
        Route::post('category/new', 'CategoryController@storeCategory')->name('category.store')->middleware(['permission:manage-category,admin']);
        Route::post('category/update/{id}', 'CategoryController@updateCategory')->name('category.update')->middleware(['permission:manage-category,admin']);
        Route::get('category/search', 'CategoryController@searchCategory')->name('category.search')->middleware(['permission:manage-category,admin']);
        Route::post('category/activate', 'CategoryController@activate')->name('category.activate')->middleware(['permission:manage-category,admin']);
        Route::post('category/deactivate', 'CategoryController@deactivate')->name('category.deactivate')->middleware(['permission:manage-category,admin']);
        Route::get('subcategory/request/{id}', 'CategoryController@subCategoryRequestUpdate')->name('subcategory_request_update')->middleware(['permission:manage-category,admin']);

        //Manage Registration fees
        Route::get('registration', 'AdminController@registration')->name('registration');
        Route::post('registration/new', 'AdminController@storeRegistration')->name('registration.store');
        Route::get('stop-resume', 'AdminController@stopResume')->name('stopresume');
        Route::post('stop-resume/new', 'AdminController@stopResumenew')->name('stopresume.store');


        Route::get('slider-second', 'CategoryController@sliderSecond')->name('slider-second');
        Route::post('slider-second/new', 'CategoryController@stopSlider')->name('slider-second.store');
        Route::post('slider-second/update/{id}', 'CategoryController@stopUpdate')->name('slider-second.update');
          Route::get('offer-type', 'CategoryController@offertype')->name('offer-type');
          Route::post('offer-type/new', 'CategoryController@stopOffertype')->name('offer-type.store');
          Route::post('offer-type/update/{id}', 'CategoryController@OffertypeUpdate')->name('offer-type.update');
        Route::get('transactions', 'AdminController@transactions')->name('transactions')->middleware(['permission:manage-transactions,admin']);
        Route::get('refunds', 'AdminController@refunds')->name('refunds')->middleware(['permission:manage-transactions,admin']);
        Route::post('refunds', 'AdminController@refundsTransfer')->name('refunds_post')->middleware(['permission:manage-transactions,admin']);

        // Route::post('registration/update/{id}', 'CategoryController@updateCategory')->name('category.update');
        // Route::get('registration/search', 'CategoryController@searchCategory')->name('category.search');
        // Route::post('registration/activate', 'CategoryController@activate')->name('category.activate');
        // Route::post('registration/deactivate', 'CategoryController@deactivate')->name('category.deactivate');

        // Users Manager
        Route::get('users', 'ManageUsersController@allUsers')->name('users.all')->middleware(['permission:manage-users,admin']);
        Route::get('users/active', 'ManageUsersController@activeUsers')->name('users.active')->middleware(['permission:manage-users,admin']);
        Route::get('users/banned', 'ManageUsersController@bannedUsers')->name('users.banned')->middleware(['permission:manage-users,admin']);
        Route::get('users/email-verified', 'ManageUsersController@emailVerifiedUsers')->name('users.emailVerified')->middleware(['permission:manage-users,admin']);
        Route::get('users/email-unverified', 'ManageUsersController@emailUnverifiedUsers')->name('users.emailUnverified')->middleware(['permission:manage-users,admin']);
        Route::get('users/sms-unverified', 'ManageUsersController@smsUnverifiedUsers')->name('users.smsUnverified')->middleware(['permission:manage-users,admin']);
        Route::get('users/sms-verified', 'ManageUsersController@smsVerifiedUsers')->name('users.smsVerified')->middleware(['permission:manage-users,admin']);

        Route::get('roles', 'RoleController@index')->name('roles.all')->middleware(['permission:manage-users,admin']);
        Route::get('roles/create', 'RoleController@create')->name('roles.create')->middleware(['permission:manage-users,admin']);
        Route::post('roles/store', 'RoleController@store')->name('roles.store')->middleware(['permission:manage-users,admin']);
        Route::get('roles/{id}/edit', 'RoleController@edit')->name('roles.edit')->middleware(['permission:manage-users,admin']);
        Route::post('roles/{id}/update', 'RoleController@update')->name('roles.update')->middleware(['permission:manage-users,admin']);
        Route::post('roles/{id}/delete', 'RoleController@destroy')->name('roles.delete')->middleware(['permission:manage-users,admin']);

        Route::get('admins', 'UserController@index')->name('admins.all')->middleware(['permission:manage-users,admin']);
        Route::get('admins/create', 'UserController@create')->name('admins.create')->middleware(['permission:manage-users,admin']);
        Route::post('admins/store', 'UserController@store')->name('admins.store')->middleware(['permission:manage-users,admin']);
        Route::get('admins/{id}/edit', 'UserController@edit')->name('admins.edit')->middleware(['permission:manage-users,admin']);
        Route::post('admins/{id}/update', 'UserController@update')->name('admins.update')->middleware(['permission:manage-users,admin']);
        Route::post('admins/{id}/delete', 'UserController@destroy')->name('admins.delete')->middleware(['permission:manage-users,admin']);

        Route::get('users/{scope}/search', 'ManageUsersController@search')->name('users.search')->middleware(['permission:manage-users,admin']);
        Route::get('user/detail/{id}', 'ManageUsersController@detail')->name('users.detail')->middleware(['permission:manage-users,admin']);
        Route::post('user/update/{id}', 'ManageUsersController@update')->name('users.update')->middleware(['permission:manage-users,admin']);
        Route::post('user/add-sub-balance/{id}', 'ManageUsersController@addSubBalance')->name('users.addSubBalance')->middleware(['permission:manage-users,admin']);
        Route::get('user/send-email/{id}', 'ManageUsersController@showEmailSingleForm')->name('users.email.single')->middleware(['permission:manage-users,admin']);
        Route::post('user/send-email/{id}', 'ManageUsersController@sendEmailSingle')->name('users.email.single')->middleware(['permission:manage-users,admin']);
        Route::get('user/transactions/{id}', 'ManageUsersController@transactions')->name('users.transactions')->middleware(['permission:manage-users,admin']);
        Route::get('user/deposits/via/{method}/{type?}/{userId}', 'ManageUsersController@depViaMethod')->name('users.deposits.method')->middleware(['permission:manage-users,admin']);
        Route::get('user/withdrawals/{id}', 'ManageUsersController@withdrawals')->name('users.withdrawals')->middleware(['permission:manage-users,admin']);
        Route::get('user/withdrawals/via/{method}/{type?}/{userId}', 'ManageUsersController@withdrawalsViaMethod')->name('users.withdrawals.method')->middleware(['permission:manage-users,admin']);
         //Agent Manager
         Route::get('agent', 'ManageAgentController@allAgent')->name('agent.all')->middleware(['permission:manage-agent,admin']);
        Route::get('agent/detail/{id}', 'ManageAgentController@detail')->name('agent.detail')->middleware(['permission:manage-agent,admin']);
         Route::get('agent/active', 'ManageAgentController@activeAgent')->name('agent.active')->middleware(['permission:manage-agent,admin']);
         Route::get('agent/banned', 'ManageAgentController@bannedAgent')->name('agent.banned')->middleware(['permission:manage-agent,admin']);
         Route::get('agent/email-verified', 'ManageAgentController@emailVerifiedAgent')->name('agent.emailVerified')->middleware(['permission:manage-agent,admin']);
        Route::get('agent-earnings', 'ManageAgentController@agentEarnings')->name('agent.earnings')->middleware(['permission:manage-agent,admin']);
        // Surveyors Manager
        Route::get('surveyors', 'ManageSurveyorsController@allSurveyors')->name('surveyors.all')->middleware(['permission:manage-sponsor,admin']);

        Route::get('surveyors/active', 'ManageSurveyorsController@activeSurveyors')->name('surveyors.active')->middleware(['permission:manage-sponsor,admin']);
        Route::get('surveyors/post-arrangement/request', 'ManageSurveyorsController@postArrangementRequestSurveyors')->name('surveyors.post_arrangement.request')->middleware(['permission:manage-sponsor,admin']);
        Route::get('surveyors/banned', 'ManageSurveyorsController@bannedSurveyors')->name('surveyors.banned')->middleware(['permission:manage-sponsor,admin']);
        Route::get('surveyors/email-verified', 'ManageSurveyorsController@emailVerifiedSurveyors')->name('surveyors.emailVerified')->middleware(['permission:manage-sponsor,admin']);
        Route::get('surveyors/email-unverified', 'ManageSurveyorsController@emailUnverifiedSurveyors')->name('surveyors.emailUnverified')->middleware(['permission:manage-sponsor,admin']);
        Route::get('surveyors/sms-unverified', 'ManageSurveyorsController@smsUnverifiedSurveyors')->name('surveyors.smsUnverified')->middleware(['permission:manage-sponsor,admin']);
        Route::get('surveyors/sms-verified', 'ManageSurveyorsController@smsVerifiedSurveyors')->name('surveyors.smsVerified')->middleware(['permission:manage-sponsor,admin']);

        Route::get('surveyors/{scope}/search', 'ManageSurveyorsController@search')->name('surveyors.search')->middleware(['permission:manage-sponsor,admin']);
        Route::get('surveyor/detail/{id}', 'ManageSurveyorsController@detail')->name('surveyors.detail')->middleware(['permission:manage-sponsor,admin']);
        Route::post('surveyor/update/{id}', 'ManageSurveyorsController@update')->name('surveyors.update')->middleware(['permission:manage-sponsor,admin']);
        Route::post('surveyor/update-views/{id}', 'ManageSurveyorsController@updateViews')->name('surveyors.update.views')->middleware(['permission:manage-sponsor,admin']);
        Route::post('surveyor/add-sub-balance/{id}', 'ManageSurveyorsController@addSubBalance')->name('surveyors.addSubBalance')->middleware(['permission:manage-sponsor,admin']);
        Route::get('surveyor/send-email/{id}', 'ManageSurveyorsController@showEmailSingleForm')->name('surveyors.email.single')->middleware(['permission:manage-sponsor,admin']);
        Route::post('surveyor/send-email/{id}', 'ManageSurveyorsController@sendEmailSingle')->name('surveyors.email.single')->middleware(['permission:manage-sponsor,admin']);
        Route::get('surveyor/transactions/{id}', 'ManageSurveyorsController@transactions')->name('surveyors.transactions')->middleware(['permission:manage-sponsor,admin']);
        Route::get('surveyor/deposits/{id}', 'ManageSurveyorsController@deposits')->name('surveyors.deposits')->middleware(['permission:manage-sponsor,admin']);
        Route::get('surveyor/deposits/via/{method}/{type?}/{userId}', 'ManageSurveyorsController@depViaMethod')->name('surveyors.deposits.method')->middleware(['permission:manage-sponsor,admin']);
        Route::get('surveyor/{id}/survey/all', 'ManageSurveyorsController@surveyAll')->name('surveyors.survey.all')->middleware(['permission:manage-sponsor,admin']);
        Route::get('surveyor/{id}/survey/alldump', 'ManageSurveyorsController@surveyAlldump')->name('surveyors.survey.alldump')->middleware(['permission:manage-sponsor,admin']);
        // Login History
        Route::get('users/login/history/{id}', 'ManageUsersController@userLoginHistory')->name('users.login.history.single');
        Route::get('users/send-email', 'ManageUsersController@showEmailAllForm')->name('users.email.all');
        Route::post('users/send-email', 'ManageUsersController@sendEmailAll')->name('users.email.send');

        //Surveyors Login History
        Route::get('surveyors/login/history/{id}', 'ManageSurveyorsController@surveyorLoginHistory')->name('surveyors.login.history.single');
        Route::get('surveyors/send-email', 'ManageSurveyorsController@showEmailAllForm')->name('surveyors.email.all');
        Route::post('surveyors/send-email', 'ManageSurveyorsController@sendEmailAll')->name('surveyors.email.send');

        // Subscriber
        Route::get('subscriber', 'SubscriberController@index')->name('subscriber.index');
        Route::get('subscriber/send-email', 'SubscriberController@sendEmailForm')->name('subscriber.sendEmail');
        Route::post('subscriber/remove', 'SubscriberController@remove')->name('subscriber.remove');
        Route::post('subscriber/send-email', 'SubscriberController@sendEmail')->name('subscriber.sendEmail');


        // Deposit Gateway
        Route::name('gateway.')->prefix('gateway')->group(function(){

            // Automatic Gateway
            Route::get('automatic', 'GatewayController@index')->name('automatic.index')->middleware(['permission:manage-transactions,admin']);
            Route::get('automatic/edit/{alias}', 'GatewayController@edit')->name('automatic.edit')->middleware(['permission:manage-transactions,admin']);
            Route::post('automatic/update/{code}', 'GatewayController@update')->name('automatic.update')->middleware(['permission:manage-transactions,admin']);
            Route::post('automatic/remove/{code}', 'GatewayController@remove')->name('automatic.remove')->middleware(['permission:manage-transactions,admin']);
            Route::post('automatic/activate', 'GatewayController@activate')->name('automatic.activate')->middleware(['permission:manage-transactions,admin']);
            Route::post('automatic/deactivate', 'GatewayController@deactivate')->name('automatic.deactivate')->middleware(['permission:manage-transactions,admin']);

            // Manual Methods
            Route::get('manual', 'ManualGatewayController@index')->name('manual.index')->middleware(['permission:manage-transactions,admin']);
            Route::get('manual/new', 'ManualGatewayController@create')->name('manual.create')->middleware(['permission:manage-transactions,admin']);
            Route::post('manual/new', 'ManualGatewayController@store')->name('manual.store')->middleware(['permission:manage-transactions,admin']);
            Route::get('manual/edit/{alias}', 'ManualGatewayController@edit')->name('manual.edit')->middleware(['permission:manage-transactions,admin']);
            Route::post('manual/update/{id}', 'ManualGatewayController@update')->name('manual.update')->middleware(['permission:manage-transactions,admin']);
            Route::post('manual/activate', 'ManualGatewayController@activate')->name('manual.activate')->middleware(['permission:manage-transactions,admin']);
            Route::post('manual/deactivate', 'ManualGatewayController@deactivate')->name('manual.deactivate')->middleware(['permission:manage-transactions,admin']);
        });


        // DEPOSIT SYSTEM
        Route::name('deposit.')->prefix('deposit')->group(function(){

            Route::get('/', 'DepositController@deposit')->name('list')->middleware(['permission:manage-transactions,admin']);
            Route::get('pending', 'DepositController@pending')->name('pending')->middleware(['permission:manage-transactions,admin']);
            Route::get('rejected', 'DepositController@rejected')->name('rejected')->middleware(['permission:manage-transactions,admin']);
            Route::get('approved', 'DepositController@approved')->name('approved')->middleware(['permission:manage-transactions,admin']);
            Route::get('successful', 'DepositController@successful')->name('successful')->middleware(['permission:manage-transactions,admin']);
            Route::get('details/{id}', 'DepositController@details')->name('details')->middleware(['permission:manage-transactions,admin']);

            Route::post('reject', 'DepositController@reject')->name('reject')->middleware(['permission:manage-transactions,admin']);
            Route::post('approve', 'DepositController@approve')->name('approve')->middleware(['permission:manage-transactions,admin']);
            Route::get('via/{method}/{type?}', 'DepositController@depViaMethod')->name('method')->middleware(['permission:manage-transactions,admin']);
            Route::get('/{scope}/search', 'DepositController@search')->name('search')->middleware(['permission:manage-transactions,admin']);
            Route::get('date-search/{scope}', 'DepositController@dateSearch')->name('dateSearch')->middleware(['permission:manage-transactions,admin']);

        });


        // WITHDRAW SYSTEM
        Route::name('withdraw.')->prefix('withdraw')->group(function(){

            Route::get('pending', 'WithdrawalController@pending')->name('pending')->middleware(['permission:manage-transactions,admin']);
            Route::get('approved', 'WithdrawalController@approved')->name('approved')->middleware(['permission:manage-transactions,admin']);
            Route::get('rejected', 'WithdrawalController@rejected')->name('rejected')->middleware(['permission:manage-transactions,admin']);
            Route::get('log', 'WithdrawalController@log')->name('log')->middleware(['permission:manage-transactions,admin']);
            Route::get('via/{method_id}/{type?}', 'WithdrawalController@logViaMethod')->name('method')->middleware(['permission:manage-transactions,admin']);
            Route::get('{scope}/search', 'WithdrawalController@search')->name('search')->middleware(['permission:manage-transactions,admin']);
            Route::get('date-search/{scope}', 'WithdrawalController@dateSearch')->name('dateSearch')->middleware(['permission:manage-transactions,admin']);
            Route::get('details/{id}', 'WithdrawalController@details')->name('details')->middleware(['permission:manage-transactions,admin']);
            Route::post('approve', 'WithdrawalController@approve')->name('approve')->middleware(['permission:manage-transactions,admin']);
            Route::post('reject', 'WithdrawalController@reject')->name('reject')->middleware(['permission:manage-transactions,admin']);


            // Withdraw Method
            Route::get('method/', 'WithdrawMethodController@methods')->name('method.index')->middleware(['permission:manage-transactions,admin']);
            Route::get('method/create', 'WithdrawMethodController@create')->name('method.create')->middleware(['permission:manage-transactions,admin']);
            Route::post('method/create', 'WithdrawMethodController@store')->name('method.store')->middleware(['permission:manage-transactions,admin']);
            Route::get('method/edit/{id}', 'WithdrawMethodController@edit')->name('method.edit')->middleware(['permission:manage-transactions,admin']);
            Route::post('method/edit/{id}', 'WithdrawMethodController@update')->name('method.update')->middleware(['permission:manage-transactions,admin']);
            Route::post('method/activate', 'WithdrawMethodController@activate')->name('method.activate')->middleware(['permission:manage-transactions,admin']);
            Route::post('method/deactivate', 'WithdrawMethodController@deactivate')->name('method.deactivate')->middleware(['permission:manage-transactions,admin']);
        });

        // Report
        Route::get('report/transaction', 'ReportController@transaction')->name('report.transaction');
        Route::get('report/transaction/search', 'ReportController@transactionSearch')->name('report.transaction.search');
        Route::get('report/surveyor/transaction', 'ReportController@surveyorTransaction')->name('report.surveyor.transaction');
        Route::get('report/surveyor/transaction/search', 'ReportController@surveyorTransactionSearch')->name('report.surveyor.transaction.search');
        Route::get('report/login/history', 'ReportController@loginHistory')->name('report.login.history');
        Route::get('report/login/ipHistory/{ip}', 'ReportController@loginIpHistory')->name('report.login.ipHistory');
        Route::get('report/surveyor/login/history', 'ReportController@surveyorLoginHistory')->name('report.surveyor.login.history');
        Route::get('report/surveyor/login/ipHistory/{ip}', 'ReportController@surveyorLoginIpHistory')->name('report.surveyor.login.ipHistory');


        // Admin Support
        Route::get('tickets', 'SupportTicketController@tickets')->name('ticket');
        Route::get('tickets/pending', 'SupportTicketController@pendingTicket')->name('ticket.pending');
        Route::get('tickets/closed', 'SupportTicketController@closedTicket')->name('ticket.closed');
        Route::get('tickets/answered', 'SupportTicketController@answeredTicket')->name('ticket.answered');
        Route::get('tickets/view/{id}', 'SupportTicketController@ticketReply')->name('ticket.view');
        Route::post('ticket/reply/{id}', 'SupportTicketController@ticketReplySend')->name('ticket.reply');
        Route::get('ticket/download/{ticket}', 'SupportTicketController@ticketDownload')->name('ticket.download');
        Route::post('ticket/delete', 'SupportTicketController@ticketDelete')->name('ticket.delete');


        // Language Manager
        Route::get('/language', 'LanguageController@langManage')->name('language.manage');
        Route::post('/language', 'LanguageController@langStore')->name('language.manage.store');
        Route::post('/language/delete/{id}', 'LanguageController@langDel')->name('language.manage.del');
        Route::post('/language/update/{id}', 'LanguageController@langUpdatepp')->name('language.manage.update');
        Route::get('/language/edit/{id}', 'LanguageController@langEdit')->name('language.key');
        Route::post('/language/import', 'LanguageController@langImport')->name('language.import_lang');


        Route::post('language/store/key/{id}', 'LanguageController@storeLanguageJson')->name('language.store.key');
        Route::post('language/delete/key/{id}', 'LanguageController@deleteLanguageJson')->name('language.delete.key');
        Route::post('language/update/key/{id}', 'LanguageController@updateLanguageJson')->name('language.update.key');


        // General Setting
        Route::get('general-setting', 'GeneralSettingController@index')->name('setting.index');
        Route::post('general-setting', 'GeneralSettingController@update')->name('setting.update');

        // Logo-Icon
        Route::get('setting/logo-icon', 'GeneralSettingController@logoIcon')->name('setting.logo_icon');
        Route::post('setting/logo-icon', 'GeneralSettingController@logoIconUpdate')->name('setting.logo_icon');

        // Plugin
        Route::get('extensions', 'ExtensionController@index')->name('extensions.index');
        Route::post('extensions/update/{id}', 'ExtensionController@update')->name('extensions.update');
        Route::post('extensions/activate', 'ExtensionController@activate')->name('extensions.activate');
        Route::post('extensions/deactivate', 'ExtensionController@deactivate')->name('extensions.deactivate');


        // Email Setting
        Route::get('email-template/global', 'EmailTemplateController@emailTemplate')->name('email.template.global');
        Route::post('email-template/global', 'EmailTemplateController@emailTemplateUpdate')->name('email.template.global');
        Route::get('email-template/setting', 'EmailTemplateController@emailSetting')->name('email.template.setting');
        Route::post('email-template/setting', 'EmailTemplateController@emailSettingUpdate')->name('email.template.setting');
        Route::get('email-template/index', 'EmailTemplateController@index')->name('email.template.index');
        Route::get('email-template/{id}/edit', 'EmailTemplateController@edit')->name('email.template.edit');
        Route::post('email-template/{id}/update', 'EmailTemplateController@update')->name('email.template.update');
        Route::post('email-template/send-test-mail', 'EmailTemplateController@sendTestMail')->name('email.template.sendTestMail');


        // SMS Setting
        Route::get('sms-template/global', 'SmsTemplateController@smsSetting')->name('sms.template.global');
        Route::post('sms-template/global', 'SmsTemplateController@smsSettingUpdate')->name('sms.template.global');
        Route::get('sms-template/index', 'SmsTemplateController@index')->name('sms.template.index');
        Route::get('sms-template/edit/{id}', 'SmsTemplateController@edit')->name('sms.template.edit');
        Route::post('sms-template/update/{id}', 'SmsTemplateController@update')->name('sms.template.update');
        Route::post('email-template/send-test-sms', 'SmsTemplateController@sendTestSMS')->name('sms.template.sendTestSMS');

        // SEO
        Route::get('seo', 'FrontendController@seoEdit')->name('seo');


        // Frontend
        Route::name('frontend.')->prefix('frontend')->group(function () {

            Route::get('templates', 'FrontendController@templates')->name('templates');
            Route::post('templates', 'FrontendController@templatesActive')->name('templates.active');

            Route::get('frontend-sections/{key}', 'FrontendController@frontendSections')->name('sections');
            Route::post('frontend-content/{key}', 'FrontendController@frontendContent')->name('sections.content');
            Route::get('frontend-element/{key}/{id?}', 'FrontendController@frontendElement')->name('sections.element');
            Route::post('remove', 'FrontendController@remove')->name('remove');

            // Page Builder
            Route::get('manage-pages', 'PageBuilderController@managePages')->name('manage.pages');
            Route::post('manage-pages', 'PageBuilderController@managePagesSave')->name('manage.pages.save');
            Route::post('manage-pages/update', 'PageBuilderController@managePagesUpdate')->name('manage.pages.update');
            Route::post('manage-pages/delete', 'PageBuilderController@managePagesDelete')->name('manage.pages.delete');
            Route::get('manage-section/{id}', 'PageBuilderController@manageSection')->name('manage.section');
            Route::post('manage-section/{id}', 'PageBuilderController@manageSectionUpdate')->name('manage.section.update');
        });

        //Survey
        Route::get('survey/pending', 'SurveyController@pending')->name('manage.survey.pending');
        Route::post('survey/approve/{id}', 'SurveyController@approve')->name('manage.survey.approve');
        Route::get('survey/approved', 'SurveyController@approved')->name('manage.survey.approved');
        Route::post('survey/reject/{id}', 'SurveyController@reject')->name('manage.survey.reject');
        Route::get('survey/rejected', 'SurveyController@rejected')->name('manage.survey.rejected');
        Route::get('survey/{scope}/search', 'SurveyController@search')->name('manage.survey.search');



        //Question
        Route::get('question/all/{id}', 'SurveyController@questionAll')->name('manage.survey.question.all');
        Route::get('question/view/{q_id}//{s_id}', 'SurveyController@questionView')->name('manage.survey.question.view');
    });
});

/*
|--------------------------------------------------------------------------
| Start Surveyor Area
|--------------------------------------------------------------------------
*/
Route::namespace('Agent')->prefix('agent')->name('agent.')->group(function () {
    Route::namespace('Auth')->group(function () {
        Route::get('/', 'LoginController@showLoginForm')->name('login');
        Route::post('/', 'LoginController@login')->name('login');
        Route::get('logout', 'LoginController@logout')->name('logout');

        Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
        Route::post('register', 'RegisterController@register')->name('regStatus');

        // surveyor Password Reset
        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
        Route::post('password/reset', 'ForgotPasswordController@sendResetLinkEmail');
        Route::post('password/verify-code', 'ForgotPasswordController@verifyCode')->name('password.verify-code');
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.change-link');
        Route::post('password/reset/change', 'ResetPasswordController@reset')->name('password.change');
    });
});

Route::namespace('Surveyor')->prefix('surveyor')->name('surveyor.')->group(function () {
    Route::namespace('Auth')->group(function () {
        Route::get('/', 'LoginController@showLoginForm')->name('login');
        Route::post('/', 'LoginController@login')->name('login');
        Route::get('logout', 'LoginController@logout')->name('logout');

        Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
        Route::get('register/{id?}', 'RegisterController@showRegistrationForm')->name('register');


        Route::post('register', 'RegisterController@register')->name('regStatus');
        Route::post('registercompany', 'RegisterController@registercomp')->name('regStatuscomp');
        // surveyor Password Reset
        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
        Route::post('password/reset', 'ForgotPasswordController@sendResetLinkEmail');
        Route::post('password/verify-code', 'ForgotPasswordController@verifyCode')->name('password.verify-code');
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.change-link');
        Route::post('password/reset/change', 'ResetPasswordController@reset')->name('password.change');
    });
});

Route::namespace('Agent')->name('agent.')->prefix('agent')->group(function () {
    Route::middleware('agent')->group(function () {
         Route::get('authorization', 'AgentAuthorizationController@authorizeForm')->name('authorization');
         Route::get('resend-verify', 'AgentAuthorizationController@sendVerifyCode')->name('send_verify_code');
         Route::post('verify-email', 'AgentAuthorizationController@emailVerification')->name('verify_email');
         Route::post('verify-sms', 'AgentAuthorizationController@smsVerification')->name('verify_sms');
         Route::post('verify-g2fa', 'AgentAuthorizationController@g2faVerification')->name('go2fa.verify');
        Route::post('payment/registration', 'AgentAuthorizationController@registrationPayment')->name('payment.registration');
         Route::middleware(['checkAgentStatus'])->group(function () {
               Route::get('dashboard', 'AgentController@dashboard')->name('dashboard');
               Route::post('share/message/send', 'AgentController@sendMsgShare')->name('send_msg_share');
               Route::get('refferrals', 'AgentController@refferrals')->name('refferrals');
               Route::get('earnings', 'AgentController@earnings')->name('earnings');
               //Transaction
               Route::get('transactions', 'AgentController@transactionHistory')->name('transactions');
               Route::get('transactions/search', 'AgentController@transactionSearch')->name('transactions.search');
               //Profile
               Route::get('profile', 'AgentController@profile')->name('profile');
               Route::post('profile/update', 'AgentController@profileUpdate')->name('profile.update');
               //Password
               Route::get('password', 'AgentController@password')->name('password');
               Route::post('password/update', 'AgentController@passwordUpdate')->name('password.update');


               //2FA
            Route::get('twofactor', 'AgentController@show2faForm')->name('twofactor');
            Route::post('twofactor/enable', 'AgentController@create2fa')->name('twofactor.enable');
            Route::post('twofactor/disable', 'AgentController@disable2fa')->name('twofactor.disable');
            Route::get('deposit/history', 'AgentController@depositHistory')->name('deposit.history');




         });
    });
});



Route::namespace('Surveyor')->name('surveyor.')->prefix('surveyor')->group(function () {
    Route::middleware('surveyor')->group(function () {

        Route::get('authorization', 'SurveyorAuthorizationController@authorizeForm')->name('authorization');
        Route::get('resend-verify', 'SurveyorAuthorizationController@sendVerifyCode')->name('send_verify_code');
        Route::post('verify-email', 'SurveyorAuthorizationController@emailVerification')->name('verify_email');
        Route::post('verify-sms', 'SurveyorAuthorizationController@smsVerification')->name('verify_sms');
        Route::post('verify-g2fa', 'SurveyorAuthorizationController@g2faVerification')->name('go2fa.verify');
        Route::post('payment/registration', 'SurveyorAuthorizationController@registrationPayment')->name('payment.registration');

        Route::middleware(['checkSurveyorStatus'])->group(function () {
            Route::get('dashboard', 'SurveyorController@dashboard')->name('dashboard');

            Route::get('profile', 'SurveyorController@profile')->name('profile');
            Route::post('profile/update', 'SurveyorController@profileUpdate')->name('profile.update');
            Route::post('profile/category/request', 'SurveyorController@categoryRequest')->name('profile.category_request');
            Route::get('password', 'SurveyorController@password')->name('password');
            Route::post('password/update', 'SurveyorController@passwordUpdate')->name('password.update');

            //2FA
            Route::get('twofactor', 'SurveyorController@show2faForm')->name('twofactor');
            Route::post('twofactor/enable', 'SurveyorController@create2fa')->name('twofactor.enable');
            Route::post('twofactor/disable', 'SurveyorController@disable2fa')->name('twofactor.disable');
            Route::get('deposit/history', 'SurveyorController@depositHistory')->name('deposit.history');

            //Survey
            Route::get('survey/all', 'SurveyorController@surveyAll')->name('survey.all');
            Route::post('survey/newsave', 'SurveyorController@surveynewsave')->name('survey.save');
            Route::get('survey/alldump', 'SurveyorController@surveydump')->name('survey.alldump');
            Route::get('survey/new', 'SurveyorController@surveyNew')->name('survey.new');
            Route::post('survey/store', 'SurveyorController@surveyStore')->name('survey.store');
            Route::get('survey/edit-before-submit/{id}', 'SurveyorController@surveyEditBeforeSubmit')->name('survey.edit_before_submit');
            Route::post('survey/update-before-submit/{id}', 'SurveyorController@surveyUpdateBeforeSubmit')->name('survey.update_before_submit');
            Route::get('survey/edit-reject-ad/{id}', 'SurveyorController@surveyEditRejectAd')->name('survey.edit_reject_ad');
            Route::post('survey/update-reject-ad/{id}', 'SurveyorController@surveyUpdateRejectAd')->name('survey.update_reject_ad');
            Route::get('survey/edit/{id}', 'SurveyorController@surveyEdit')->name('survey.edit');
            Route::post('survey/update/{id}', 'SurveyorController@surveyUpdate')->name('survey.update');
            Route::get('survey/invoice-download/{id}', 'SurveyorController@downloadSurveyInvoice')->name('survey.invoice_download');
            Route::get('survey/republish/{id}', 'SurveyorController@surveyRepublishGet')->name('survey.republish.get');
            Route::post('survey/republish/{id}', 'SurveyorController@surveyRepublish')->name('survey.republish');


            //Survey
            Route::get('survey/business_card', 'SurveyorController@business_cardAll')->name('survey.business_cardall');
            Route::get('survey/business_cardnew', 'SurveyorController@business_cardNew')->name('survey.business_cardnew');
            Route::post('survey/business_cardstore', 'SurveyorController@business_cardStore')->name('survey.business_cardstore');
            Route::get('survey/business_cardedit/{id}', 'SurveyorController@business_cardEdit')->name('business_card.edit');
            Route::post('survey/business_cardupdate/{id}', 'SurveyorController@business_cardUpdate')->name('business_card.update');


            //Question
            Route::get('question/all/{id}', 'SurveyorController@questionAll')->name('survey.question.all');
            Route::get('question/new/{id}', 'SurveyorController@questionNew')->name('survey.question.new');
            Route::post('question/store', 'SurveyorController@questionStore')->name('survey.question.store');
            Route::get('question/edit/{q_id}/{s_id}', 'SurveyorController@questionEdit')->name('survey.question.edit');
            Route::post('question/update/{id}', 'SurveyorController@questionUpdate')->name('survey.question.update');
            Route::get('question/view/{q_id}/{s_id}', 'SurveyorController@questionView')->name('survey.question.view');

            //Survey
            Route::get('survey/report', 'SurveyorController@report')->name('report');
            Route::get('survey/report/{id}/question', 'SurveyorController@reportQuestion')->name('report.question');
            Route::get('survey/report/question/{question_id}/profiles', 'SurveyorController@reportQuestionProfiles')->name('report.question.profiles');
            Route::get('survey/report/download/{id}', 'SurveyorController@reportDownload')->name('report.download');

            Route::get('survey/visitor/report/download/{id}', 'SurveyorController@visitorReportDownload')->name('visitor.report.download');
            Route::get('survey/visitor/pdf/report/download/{id}', 'SurveyorController@visitorPDFReport')->name('visitor.pdf.report.download');

            //Transaction
            Route::get('transactions', 'SurveyorController@transactionHistory')->name('transactions');
            Route::get('transactions/search', 'SurveyorController@transactionSearch')->name('transactions.search');

            Route::post('refund/request', 'SurveyorController@refundRequest')->name('refund_request');

            //Calendar
            Route::get('calendarview', 'SurveyorController@viewcalendar')->name('calendarview');





        });
    });
});

Route::name('surveyor.')->prefix('surveyor')->group(function () {
    Route::middleware(['surveyor','checkSurveyorStatus'])->group(function () {

         // Deposit
         Route::any('/deposit', 'Gateway\PaymentController@deposit')->name('deposit');
         Route::post('insufficient-balance', 'Gateway\PaymentController@insufficientBalance')->name('insufficient_balance');
         Route::post('deposit/insert', 'Gateway\PaymentController@depositInsert')->name('deposit.insert');
         Route::get('deposit/preview', 'Gateway\PaymentController@depositPreview')->name('deposit.preview');
         Route::get('deposit/confirm', 'Gateway\PaymentController@depositConfirm')->name('deposit.confirm');
         Route::get('deposit/manual', 'Gateway\PaymentController@manualDepositConfirm')->name('deposit.manual.confirm');
         Route::post('deposit/manual', 'Gateway\PaymentController@manualDepositUpdate')->name('deposit.manual.update');
    });
});

Route::name('surveyor.buy_views.')->prefix('surveyor')->group(function () {
    Route::middleware(['surveyor','checkSurveyorStatus'])->group(function () {

        Route::any('/buy-views/deposit', 'Gateway\BuyViewController@deposit')->name('deposit');
        Route::post('/buy-views/insufficient-balance', 'Gateway\BuyViewController@insufficientBalance')->name('insufficient_balance');
        Route::post('/buy-views/deposit/insert', 'Gateway\BuyViewController@depositInsert')->name('deposit.insert');
        Route::get('/buy-views/deposit/preview', 'Gateway\BuyViewController@depositPreview')->name('deposit.preview');
        Route::get('/buy-views/deposit/confirm', 'Gateway\BuyViewController@depositConfirm')->name('deposit.confirm');
        Route::get('/buy-views/deposit/manual', 'Gateway\BuyViewController@manualDepositConfirm')->name('deposit.manual.confirm');
        Route::post('/buy-views/deposit/manual', 'Gateway\BuyViewController@manualDepositUpdate')->name('deposit.manual.update');
    });
});


/*
|--------------------------------------------------------------------------
| Start User Area
|--------------------------------------------------------------------------
*/

Route::name('user.')->group(function () {

    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('/login', 'Auth\LoginController@login');
    Route::get('logout', 'Auth\LoginController@logout')->name('logout');

    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    //Route::get('register/{id?}', 'Auth\RegisterController@showRegistrationForm')->name('register');



    Route::post('register', 'Auth\RegisterController@register')->middleware('regStatus');

    Route::group(['middleware' => ['guest']], function () {
        Route::get('register/{reference}', 'Auth\RegisterController@referralRegister')->name('refer.register');
    });
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/code-verify', 'Auth\ForgotPasswordController@codeVerify')->name('password.code_verify');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/verify-code', 'Auth\ForgotPasswordController@verifyCode')->name('password.verify-code');

});

Route::name('user.')->prefix('user')->group(function () {

    Route::middleware('auth')->group(function () {
        Route::get('authorization', 'AuthorizationController@authorizeForm')->name('authorization');
        Route::get('resend-verify', 'AuthorizationController@sendVerifyCode')->name('send_verify_code');
        Route::post('verify-email', 'AuthorizationController@emailVerification')->name('verify_email');
        Route::post('verify-sms', 'AuthorizationController@smsVerification')->name('verify_sms');
        Route::post('verify-g2fa', 'AuthorizationController@g2faVerification')->name('go2fa.verify');
        Route::post('payment/registration', 'AuthorizationController@registrationPayment')->name('payment.registration');

        Route::middleware(['checkStatus'])->group(function () {
            Route::get('dashboard', 'UserController@home')->name('home');
            Route::get('radigone_point_summery', 'UserController@radigone_point');
            Route::get('add-prefernce', 'UserController@adddashboard')->name('add-prefernce');
            Route::get('profile-setting', 'UserController@profile')->name('profile-setting');
            Route::post('profile-setting', 'UserController@submitProfile');
            Route::post('add-prefernce-post', 'UserController@adddashboardpost')->name('add-prefernce-post');
            Route::get('change-password', 'UserController@changePassword')->name('change-password');
            Route::post('change-password', 'UserController@submitPassword');

            //2FA
            Route::get('twofactor', 'UserController@show2faForm')->name('twofactor');
            Route::post('twofactor/enable', 'UserController@create2fa')->name('twofactor.enable');
            Route::post('twofactor/disable', 'UserController@disable2fa')->name('twofactor.disable');

            // Withdraw
            Route::get('/withdraw', 'UserController@withdrawMoney')->name('withdraw');
            Route::post('/withdraw', 'UserController@withdrawStore')->name('withdraw.money');
            Route::get('/withdraw/preview', 'UserController@withdrawPreview')->name('withdraw.preview');
            Route::post('/withdraw/preview', 'UserController@withdrawSubmit')->name('withdraw.submit');
            Route::get('/withdraw/history', 'UserController@withdrawLog')->name('withdraw.history');

            //Transaction
            Route::get('/transaction', 'UserController@transaction')->name('transaction');

            //Survey
            Route::get('/survey', 'UserController@surveyAvailable')->name('survey');
            Route::get('/survey-completed', 'UserController@surveyCompleted')->name('survey.completed');
            Route::get('/survey-favorite-list', 'UserController@surveyFavoriteList')->name('survey.favorite.list');
            Route::get('/survey/{id}/ad-view', 'UserController@surveyAdView')->name('survey.ad.view');
            Route::get('/survey/{id}/qustions', 'UserController@surveyQuestions')->name('survey.questions');
            Route::post('/survey/{id}/answer', 'UserController@surveyQuestionsAnswers')->name('survey.questions.answers');
            Route::get('/block-surveyor/{surveyor_id}', 'UserController@blockSurveyor')->name('block_surveyor');
            Route::get('/sponsor-block-list', 'UserController@sponsorBlockList')->name('sponsor_block_list');
            Route::get('/unblock-surveyor/{surveyor_id}', 'UserController@unblockSurveyor')->name('unblock_surveyor');
            Route::post('/dummy-question-answer', 'UserController@dummyQuestionAnswer')->name('dummy_question_answer');
            Route::post('/survey-favorite', 'UserController@surveyFavorite')->name('survey_favorite');
            Route::post('/survey-unfavorite', 'UserController@surveyUnfavorite')->name('survey_unfavorite');


        });
    });
});

Route::get('/contact', 'SiteController@contact')->name('contact');
Route::post('/contact', 'SiteController@contactSubmit')->name('contact.send');
Route::get('/change/{lang?}', 'SiteController@changeLanguage')->name('lang');

Route::get('placeholder-image/{size}', 'SiteController@placeholderImage')->name('placeholderImage');

Route::get('/{slug}', 'SiteController@pages')->name('pages');
Route::get('/survey/{id}', 'SiteController@survey')->name('survey.share');
Route::get('/', 'SiteController@index')->name('home');

//Subscriber Store
Route::post('subscriber', 'SiteController@subscriberStore')->name('subscriber.store');
Route::post('usernamecheck', 'UserController@usernamecheck');
Route::post('usercheckmobile', 'UserController@usercheckmobile');
Route::post('usercheckmobilewapp', 'UserController@usercheckmobilewapp');
Route::post('usernameuser','UserController@usernameuser');
Route::post('smobile','UserController@smobile');
Route::post('email','UserController@email');
Route::post('userinputemail','UserController@userinputemail');
Route::post('downloadagreement','UserController@downloadagreement');