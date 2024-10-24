<?php

use Illuminate\Support\Facades\Route;


Route::namespace('Auth')->group(function () {
    Route::controller('LoginController')->group(function () {
        Route::get('/', 'showLoginForm')->name('login');
        Route::post('/', 'login')->name('login');
        Route::get('logout', 'logout')->name('logout');
    });

    // Admin Password Reset
    Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function () {
        Route::get('reset', 'showLinkRequestForm')->name('reset');
        Route::post('reset', 'sendResetCodeEmail');
        Route::get('code-verify', 'codeVerify')->name('code.verify');
        Route::post('verify-code', 'verifyCode')->name('verify.code');
    });

    Route::controller('ResetPasswordController')->group(function () {
        Route::get('password/reset/{token}', 'showResetForm')->name('password.reset.form');
        Route::post('password/reset/change', 'reset')->name('password.change');
    });
});

Route::middleware('admin')->group(function () {
    Route::controller('AdminController')->group(function () {
        Route::get('dashboard', 'dashboard')->name('dashboard');
        Route::get('profile', 'profile')->name('profile');
        Route::post('profile', 'profileUpdate')->name('profile.update');
        Route::get('password', 'password')->name('password');
        Route::post('password', 'passwordUpdate')->name('password.update');


        //Notification
        Route::get('notifications', 'notifications')->name('notifications');
        Route::get('notification/read/{id}', 'notificationRead')->name('notification.read');
        Route::get('notifications/read-all', 'readAll')->name('notifications.readAll');

        //Report Bugs
        Route::get('request-report', 'requestReport')->name('request.report');
        Route::post('request-report', 'reportSubmit');

        Route::get('download-attachments/{file_hash}', 'downloadAttachment')->name('download.attachment');
    });

    //Department And Location
    Route::controller('DepartmentController')->prefix('department')->name('department.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::post('store/{id?}', 'store')->name('store');
        Route::get('location', 'location')->name('location');
        Route::post('location/store/{id?}', 'locationStore')->name('location.store');
    });

    //Clinic
    Route::controller('ClinicsController')->prefix('clinics')->name('clinics.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('active/{status}', 'active')->name('active');
        Route::get('inactive/{status}', 'inactive')->name('inactive');


        Route::post('status/{id}', 'status')->name('status');
        Route::post('featured/{id}', 'featured')->name('featured');

        Route::get('add', 'form')->name('form');
        Route::post('store/{id?}', 'store')->name('store');
        Route::get('detail/{id}', 'detail')->name('detail');

        Route::get('login/history/{id?}', 'loginHistory')->name('login.history');
        Route::get('login/{id}', 'login')->name('login');


        Route::get('notification-log/{id}', 'notificationLog')->name('notification.log');
        Route::get('send-notification/{id}', 'showNotificationSingleForm')->name('notification.single');
        Route::post('send-notification/{id}', 'sendNotificationSingle')->name('notification.single');

        Route::get('send-notification', 'showNotificationAllForm')->name('notification.all');
        Route::post('send-notification', 'sendNotificationAll')->name('notification.all.send');

        Route::post('country-states','getStateByCountry')->name('change-state-by-country');
        Route::post('states-city','getCityByStates')->name('change-city-by-country');

    });

    //Hospitals
    Route::controller('HospitalsController')->prefix('hospital')->name('hospital.')->group(function () {
        Route::get('index', 'index')->name('index');

        Route::get('add', 'form')->name('form');
        Route::post('store/{id?}', 'store')->name('store');

        Route::get('detail/{id}', 'detail')->name('detail');

    });




    // Route::controller('ClinicsController')->prefix('clinics')->name('clinics.')->group(function () {
    //     Route::get('index', 'index')->name('index');
    //     Route::post('store/{id?}', 'store')->name('store');
    //     Route::post('country-states','getStateByCountry')->name('change-state-by-country');
    //     Route::post('states-city','getCityByStates')->name('change-city-by-country');
    // });

    //Category
    Route::controller('CategoryController')->prefix('category')->name('category.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::post('store/{id?}', 'store')->name('store');
    });

    //Listings
    Route::controller('ListingsController')->prefix('listings')->name('listings.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('add', 'form')->name('form');
        Route::get('edit/{id}', 'editpage')->name('editpage');
        Route::post('store/{id?}', 'store')->name('store');
    });

    //Pets
    Route::controller('PetsController')->prefix('pets')->name('pets.')->group(function () {
        // User Pets
        Route::get('index', 'index')->name('index');
        Route::get('detail-user-pet/{id}', 'detailUserPet')->name('detail-user-pet');
        Route::get('delete-user-pet/{id}', 'deleteUserPet')->name('delete-user-pet');

        Route::post('store/{id?}', 'store')->name('store');
        //pet disease
        Route::get('listing-disease', 'allPetDisease')->name('listing-disease');
        Route::get('add-disease', 'formDisease')->name('form-disease');
        Route::post('store-disease/{id?}', 'storeDisease')->name('store-disease');
        Route::get('detail-disease/{id}', 'detailDisease')->name('detail-disease');
        Route::post('status-disease/{id}', 'statusDisease')->name('status-disease');
        Route::get('delete-disease/{id}', 'deleteDisease')->name('delete-disease');
        //pet type
        Route::get('add', 'form')->name('form');
        Route::post('status/{id}', 'status')->name('status');
        Route::post('store/{id?}', 'storePet')->name('store');
        Route::get('detail/{id}', 'detail')->name('detail');
        Route::get('delete/{id}', 'delete')->name('delete');
        Route::get('listing', 'allPetType')->name('listing');
        Route::get('active/{status}', 'active')->name('active');
        Route::get('inactive/{status}', 'inactive')->name('inactive');

    });

    //Locations
    Route::controller('LocationController')->prefix('location')->name('location.')->group(function () {
        Route::get('countries', 'countries')->name('countries');
        Route::get('states', 'states')->name('states');
        Route::get('addstate', 'formstate')->name('formstate');
        Route::post('storestate/{id?}', 'storestate')->name('storestate');
        Route::get('cities', 'cities')->name('cities');
        Route::get('add', 'form')->name('form');
        Route::post('store/{id?}', 'store')->name('store');
    });

    // Doctors Manager
    Route::controller('ManageDoctorsController')->prefix('doctor')->name('doctor.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('active/{status}', 'active')->name('active');
        Route::get('inactive/{status}', 'inactive')->name('inactive');


        Route::post('status/{id}', 'status')->name('status');
        Route::post('featured/{id}', 'featured')->name('featured');
        Route::post('verify/{id}', 'verify')->name('verify');
        Route::post('unverify/{id}', 'unVerify')->name('unverify');

        Route::get('add', 'form')->name('form');
        Route::post('store/{id?}', 'store')->name('store');
        Route::get('detail/{id}', 'detail')->name('detail');

        Route::get('login/history/{id?}', 'loginHistory')->name('login.history');
        Route::get('login/{id}', 'login')->name('login');


        Route::get('notification-log/{id}', 'notificationLog')->name('notification.log');
        Route::get('reviews/{id}', 'vetReviews')->name('reviews');
        Route::get('send-notification/{id}', 'showNotificationSingleForm')->name('notification.single');
        Route::post('send-notification/{id}', 'sendNotificationSingle')->name('notification.single');

        Route::get('send-notification', 'showNotificationAllForm')->name('notification.all');
        Route::post('send-notification', 'sendNotificationAll')->name('notification.all.send');
    });



    // User Manager
    Route::controller('ManageUserController')->prefix('user')->name('user.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::post('store/{id?}', 'store')->name('store');
        Route::get('detail/{id}', 'detail')->name('detail');



    });




    // Assistant Manager
     Route::controller('ManageAssistantsController')->prefix('assistant')->name('assistant.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('active/{status}', 'active')->name('active');
        Route::get('inactive/{status}', 'inactive')->name('inactive');


        Route::post('status/{id}', 'status')->name('status');
        Route::post('featured/{id}', 'featured')->name('featured');

        Route::get('add', 'form')->name('form');
        Route::post('store/{id?}', 'store')->name('store');
        Route::get('detail/{id}', 'detail')->name('detail');

        Route::get('login/history/{id?}', 'loginHistory')->name('login.history');
        Route::get('login/{id}', 'login')->name('login');


        Route::get('notification-log/{id}', 'notificationLog')->name('notification.log');
        Route::get('send-notification/{id}', 'showNotificationSingleForm')->name('notification.single');
        Route::post('send-notification/{id}', 'sendNotificationSingle')->name('notification.single');

        Route::get('send-notification', 'showNotificationAllForm')->name('notification.all');
        Route::post('send-notification', 'sendNotificationAll')->name('notification.all.send');
    });

     // Assistant Manager
     Route::controller('ManageStaffsController')->prefix('staff')->name('staff.')->group(function () {
        Route::get('index', 'index')->name('index');
        Route::get('active/{status}', 'active')->name('active');
        Route::get('inactive/{status}', 'inactive')->name('inactive');


        Route::post('status/{id}', 'status')->name('status');
        Route::post('featured/{id}', 'featured')->name('featured');

        Route::get('add', 'form')->name('form');
        Route::post('store/{id?}', 'store')->name('store');
        Route::get('detail/{id}', 'detail')->name('detail');

        Route::get('login/history/{id?}', 'loginHistory')->name('login.history');
        Route::get('login/{id}', 'login')->name('login');


        Route::get('notification-log/{id}', 'notificationLog')->name('notification.log');
        Route::get('send-notification/{id}', 'showNotificationSingleForm')->name('notification.single');
        Route::post('send-notification/{id}', 'sendNotificationSingle')->name('notification.single');

        Route::get('send-notification', 'showNotificationAllForm')->name('notification.all');
        Route::post('send-notification', 'sendNotificationAll')->name('notification.all.send');
    });


    Route::controller('AppointmentController')->prefix('appointment')->name('appointment.')->group( function(){
        //Create Appointment
        Route::get('form', 'form')->name('form');
        Route::get('details', 'details')->name('book.details');
        Route::get('booked/date', 'availability')->name('available.date');
        Route::post('store/{id}', 'store')->name('store');

        //Appointment
        Route::post('dealing/{id}', 'done')->name('dealing');
        Route::post('remove/{id}', 'remove')->name('remove');

        Route::get('new', 'new')->name('new');
        Route::get('service/done', 'doneService')->name('done');
        Route::get('trashed', 'serviceTrashed')->name('trashed');
        // Route::get('cancelled', 'serviceCancelled')->name('cancelled');
    });






    // Subscriber
    Route::controller('SubscriberController')->prefix('subscriber')->name('subscriber.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('send-email', 'sendEmailForm')->name('send.email');
        Route::post('remove/{id}', 'remove')->name('remove');
        Route::post('send-email', 'sendEmail')->name('send.email');
    });


    // Deposit Gateway
    Route::name('gateway.')->prefix('gateway')->group(function () {

        // Automatic Gateway
        Route::controller('AutomaticGatewayController')->prefix('automatic')->name('automatic.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('edit/{alias}', 'edit')->name('edit');
            Route::post('update/{code}', 'update')->name('update');
            Route::post('remove/{id}', 'remove')->name('remove');
            Route::post('status/{id}', 'status')->name('status');
        });


        // Manual Methods
        Route::controller('ManualGatewayController')->prefix('manual')->name('manual.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('new', 'create')->name('create');
            Route::post('new', 'store')->name('store');
            Route::get('edit/{alias}', 'edit')->name('edit');
            Route::post('update/{id}', 'update')->name('update');
            Route::post('status/{id}', 'status')->name('status');
        });
    });


    // DEPOSIT SYSTEM
    Route::controller('DepositController')->prefix('deposit')->name('deposit.')->group(function () {
        Route::get('/', 'deposit')->name('list');
        Route::get('pending', 'pending')->name('pending');
        Route::get('rejected', 'rejected')->name('rejected');
        Route::get('approved', 'approved')->name('approved');
        Route::get('successful', 'successful')->name('successful');
        Route::get('initiated', 'initiated')->name('initiated');
        Route::get('details/{id}', 'details')->name('details');
        Route::post('reject', 'reject')->name('reject');
        Route::post('approve/{id}', 'approve')->name('approve');
    });


    // WITHDRAW SYSTEM
    Route::name('withdraw.')->prefix('withdraw')->group(function () {

        Route::controller('WithdrawalController')->group(function () {
            Route::get('pending', 'pending')->name('pending');
            Route::get('approved', 'approved')->name('approved');
            Route::get('rejected', 'rejected')->name('rejected');
            Route::get('log', 'log')->name('log');
            Route::get('details/{id}', 'details')->name('details');
            Route::post('approve', 'approve')->name('approve');
            Route::post('reject', 'reject')->name('reject');
        });


        // Withdraw Method
        Route::controller('WithdrawMethodController')->prefix('method')->name('method.')->group(function () {
            Route::get('/', 'methods')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('create', 'store')->name('store');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('edit/{id}', 'update')->name('update');
            Route::post('status/{id}', 'status')->name('status');
        });
    });

    // Report
    Route::controller('ReportController')->prefix('report')->name('report.')->group(function () {
        Route::get('transaction', 'transaction')->name('transaction');
        Route::get('notification/history', 'notificationHistory')->name('notification.history');
        Route::get('email/detail/{id}', 'emailDetails')->name('email.details');
    });


    // Admin Support
    Route::controller('SupportTicketController')->prefix('ticket')->name('ticket.')->group(function () {
        Route::get('/', 'tickets')->name('index');
        Route::get('pending', 'pendingTicket')->name('pending');
        Route::get('closed', 'closedTicket')->name('closed');
        Route::get('answered', 'answeredTicket')->name('answered');
        Route::get('view/{id}', 'ticketReply')->name('view');
        Route::post('reply/{id}', 'replyTicket')->name('reply');
        Route::post('close/{id}', 'closeTicket')->name('close');
        Route::get('download/{ticket}', 'ticketDownload')->name('download');
        Route::post('delete/{id}', 'ticketDelete')->name('delete');
    });


    // Language Manager
    Route::controller('LanguageController')->prefix('language')->name('language.')->group(function () {
        Route::get('/', 'langManage')->name('manage');
        Route::post('/', 'langStore')->name('manage.store');
        Route::post('delete/{id}', 'langDelete')->name('manage.delete');
        Route::post('update/{id}', 'langUpdate')->name('manage.update');
        Route::get('edit/{id}', 'langEdit')->name('key');
        Route::post('import', 'langImport')->name('import.lang');
        Route::post('store/key/{id}', 'storeLanguageJson')->name('store.key');
        Route::post('delete/key/{id}', 'deleteLanguageJson')->name('delete.key');
        Route::post('update/key/{id}', 'updateLanguageJson')->name('update.key');
    });

    Route::controller('GeneralSettingController')->group(function () {
        // General Setting
        Route::get('general-setting', 'index')->name('setting.index');
        Route::post('general-setting', 'update')->name('setting.update');

        //configuration
        Route::get('setting/system-configuration', 'systemConfiguration')->name('setting.system.configuration');
        Route::post('setting/system-configuration', 'systemConfigurationSubmit');

        // Logo-Icon
        Route::get('setting/logo-icon', 'logoIcon')->name('setting.logo.icon');
        Route::post('setting/logo-icon', 'logoIconUpdate')->name('setting.logo.icon');

        //Custom CSS
        Route::get('custom-css', 'customCss')->name('setting.custom.css');
        Route::post('custom-css', 'customCssSubmit');

        //maintenance_mode
        Route::get('maintenance-mode', 'maintenanceMode')->name('maintenance.mode');
        Route::post('maintenance-mode', 'maintenanceModeSubmit');
    });


    //Notification Setting
    Route::name('setting.notification.')->controller('NotificationController')->prefix('notification')->group(function () {
        //Template Setting
        Route::get('global', 'global')->name('global');
        Route::post('global/update', 'globalUpdate')->name('global.update');
        Route::get('templates', 'templates')->name('templates');
        Route::get('template/edit/{id}', 'templateEdit')->name('template.edit');
        Route::post('template/update/{id}', 'templateUpdate')->name('template.update');

        //Email Setting
        Route::get('email/setting', 'emailSetting')->name('email');
        Route::post('email/setting', 'emailSettingUpdate');
        Route::post('email/test', 'emailTest')->name('email.test');

        //SMS Setting
        Route::get('sms/setting', 'smsSetting')->name('sms');
        Route::post('sms/setting', 'smsSettingUpdate');
        Route::post('sms/test', 'smsTest')->name('sms.test');
    });

    // Plugin
    Route::controller('ExtensionController')->prefix('extensions')->name('extensions.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('status/{id}', 'status')->name('status');
    });


    //System Information
    Route::controller('SystemController')->name('system.')->prefix('system')->group(function () {
        Route::get('info', 'systemInfo')->name('info');
        Route::get('server-info', 'systemServerInfo')->name('server.info');
        Route::get('optimize', 'optimize')->name('optimize');
        Route::get('optimize-clear', 'optimizeClear')->name('optimize.clear');
    });


    // SEO
    Route::get('seo', 'FrontendController@seoEdit')->name('seo');


    // Frontend
    Route::name('frontend.')->prefix('frontend')->group(function () {

        Route::controller('FrontendController')->group(function () {
            Route::get('templates', 'templates')->name('templates');
            Route::post('templates', 'templatesActive')->name('templates.active');
            Route::get('frontend-sections/{key}', 'frontendSections')->name('sections');
            Route::post('frontend-content/{key}', 'frontendContent')->name('sections.content');
            Route::get('frontend-element/{key}/{id?}', 'frontendElement')->name('sections.element');
            Route::post('remove/{id}', 'remove')->name('remove');
        });

        // Page Builder
        Route::controller('PageBuilderController')->group(function () {
            Route::get('manage-pages', 'managePages')->name('manage.pages');
            Route::post('manage-pages', 'managePagesSave')->name('manage.pages.save');
            Route::post('manage-pages/update', 'managePagesUpdate')->name('manage.pages.update');
            Route::post('manage-pages/delete/{id}', 'managePagesDelete')->name('manage.pages.delete');
            Route::get('manage-section/{id}', 'manageSection')->name('manage.section');
            Route::post('manage-section/{id}', 'manageSectionUpdate')->name('manage.section.update');
            Route::post('manage-section-meta/{id}', 'manageSectionMetaUpdate')->name('manage.section.meta.update');
        });
    });
});
