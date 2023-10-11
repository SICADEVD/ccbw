<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;

Route::namespace('Manager\Auth')->group(function () {

    //Manager Login
    Route::controller('LoginController')->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login');
        Route::get('logout', 'logout')->name('logout');
    });
    //Manager Password Forgot
    Route::controller('ForgotPasswordController')->name('password.')->prefix('password')->group(function () {
        Route::get('reset', 'showLinkRequestForm')->name('request');
        Route::post('email', 'sendResetCodeEmail')->name('email');
        Route::get('code-verify', 'codeVerify')->name('code.verify');
        Route::post('verify-code', 'verifyCode')->name('verify.code');
    });
    //Manager Password Rest
    Route::controller('ResetPasswordController')->name('password.')->prefix('password')->group(function () {
        Route::get('password/reset/{token}', 'showResetForm')->name('reset.form');
        Route::post('password/reset/change', 'reset')->name('change');
    });
});


Route::controller('SiteController')->group(function () { 
    Route::get('placeholder-image/{size}', 'placeholderImage')->name('placeholder.image'); 
    Route::get('/', 'index')->name('home');
});

Route::middleware('auth')->group(function () { 
        Route::middleware('manager')->group(function () {
            //Home Controller
            Route::controller('Manager\ManagerController')->group(function () {
                Route::get('dashboard', 'dashboard')->name('dashboard');

                //Manage Profile
                Route::get('password', 'password')->name('password');
                Route::get('profile', 'profile')->name('profile');
                Route::post('profile/update', 'profileUpdate')->name('profile.update.data');
                Route::post('password/update', 'passwordUpdate')->name('password.update.data');

                //Manage Cooperative
                Route::name('cooperative.')->prefix('cooperative')->group(function () {
                    Route::get('list', 'cooperativeList')->name('index');
                    Route::get('income', 'cooperativeIncome')->name('income');
                });
            });
            
            Route::controller('Manager\CooperativeLocaliteController')->name('cooperative.localite.')->prefix('cooperative-localite')->group(function () {
                Route::get('list', 'index')->name('index');
                Route::get('create', 'create')->name('create');
                Route::post('store', 'store')->name('store');
                Route::get('edit/{id}', 'edit')->name('edit'); 
                Route::post('status/{id}', 'status')->name('status');
                Route::post('/uploadcontent', 'uploadContent')->name('uploadcontent');
            });
            //Manage Staff
            Route::controller('Manager\StaffController')->name('staff.')->prefix('staff')->group(function () {
                Route::get('create', 'create')->name('create');
                Route::get('list', 'index')->name('index');
                Route::post('store', 'store')->name('store');
                Route::get('edit/{id}', 'edit')->name('edit');
                Route::post('status/{id}', 'status')->name('status');

                Route::get('magasin/{id}', 'magasinIndex')->name('magasin.index');
                Route::post('magasin/store', 'magasinStore')->name('magasin.store');
                Route::post('magasin/status/{id}', 'magasinStatus')->name('magasin.status');
                Route::get('/exportStaffsExcel', 'exportExcel')->name('exportExcel.staffAll');
                Route::get('staff/dashboard/{id}', 'staffLogin')->name('stafflogin');
            });

            //Manage section
            Route::controller('Manager\SectionController')->name('section.')->prefix('section')->group(function () {
                Route::get('create', 'create')->name('create');
                Route::get('list', 'index')->name('index');
                Route::post('store', 'store')->name('store');
                Route::get('edit/{id}', 'edit')->name('edit');
                Route::post('update/{id}', 'update')->name('update');

                // //route pour créer une localité en fonction de la section
                // Route::post('localites/section/store', 'storelocalitesection')->name('storelocalitesection');
                // //route pour modifier une localité en fonction de la section
                // Route::post('localites/section/update/{id}', 'updatelocalitesection')->name('updatelocalitesection');
                // //route pour lister les localités en fonction de la section
                // Route::get('localites/section/{id}', 'localitesection')->name('localitesection');
                // //route pour modifier une localité appartenant à une section
                // Route::get('localite/section/edit/{id}', 'localitesectionedit')->name('localitesectionedit');
            });

            //Manage programme durabilite

            Route::controller('Manager\EmployeeController')->name('hr.')->prefix('hr')->group(function () {
                Route::get('all/employee/card', 'cardAllEmployee')->name('all.employee.card');
                Route::get('all/employee/list', 'listAllEmployee')->name('all.employee.list');
                Route::post('all/employee/save', 'saveRecord')->name('all.employee.save');
                Route::get('all/employee/view/edit/{employee_id}', 'viewRecord');
                Route::post('all/employee/update', 'updateRecord')->name('all.employee.update');
                Route::get('all/employee/delete/{employee_id}', 'deleteRecord');
                Route::post('all/employee/search', 'employeeSearch')->name('all.employee.search');
                Route::post('all/employee/list/search', 'employeeListSearch')->name('all.employee.list.search');
            
                Route::get('form/departments/page', 'index')->name('form.departments.page');    
                Route::post('form/departments/save', 'saveRecordDepartment')->name('form.departments.save');    
                Route::post('form/department/update', 'updateRecordDepartment')->name('form.department.update');    
                Route::post('form/department/delete', 'deleteRecordDepartment')->name('form.department.delete');  
                
                Route::get('form/designations/page', 'designationsIndex')->name('form.designations.page');    
                Route::post('form/designations/save', 'saveRecordDesignations')->name('form.designations.save');    
                Route::post('form/designations/update', 'updateRecordDesignations')->name('form.designations.update');    
                Route::post('form/designations/delete', 'deleteRecordDesignations')->name('form.designations.delete');
                
                Route::get('form/timesheet/page', 'timeSheetIndex')->name('form.timesheet.page');    
                Route::post('form/timesheet/save', 'saveRecordTimeSheets')->name('form.timesheet.save');    
                Route::post('form/timesheet/update', 'updateRecordTimeSheets')->name('form.timesheet.update');    
                Route::post('form/timesheet/delete', 'deleteRecordTimeSheets')->name('form.timesheet.delete');
                
                Route::get('form/overtime/page', 'overTimeIndex')->name('form.overtime.page');    
                Route::post('form/overtime/save', 'saveRecordOverTime')->name('form.overtime.save');    
                Route::post('form/overtime/update', 'updateRecordOverTime')->name('form.overtime.update');    
                Route::post('form/overtime/delete', 'deleteRecordOverTime')->name('form.overtime.delete');  
            });

// ----------------------------- form leaves ------------------------------//
Route::controller('Manager\LeavesController')->name('hr.leaves.')->prefix('hr')->group(function () {
    Route::get('form/leaves/new', 'leaves')->name('form.leaves.new');
    Route::get('form/leavesemployee/new', 'leavesEmployee')->name('form.leavesemployee.new');
    Route::post('form/leaves/save', 'saveRecord')->name('form.leaves.save');
    Route::post('form/leaves/edit', 'editRecordLeave')->name('form.leaves.edit');
    Route::post('form/leaves/edit/delete','deleteLeave')->name('form.leaves.edit.delete');    
});

// ----------------------------- form attendance  ------------------------------//
Route::controller('Manager\LeavesController')->name('hr.attendance.')->prefix('hr')->group(function () {
    Route::get('form/leavesettings/page', 'leaveSettings')->name('form.leavesettings.page');
    Route::get('attendance/page', 'attendanceIndex')->name('attendance/page');
    Route::get('attendance/employee/page', 'AttendanceEmployee')->name('attendance.employee.page');
    Route::get('form/shiftscheduling/page', 'shiftScheduLing')->name('form.shiftscheduling.page');
    Route::get('form/shiftlist/page', 'shiftList')->name('form.shiftlist.page');    
});
            Route::controller('Manager\ProgrammeController')->name('durabilite.')->prefix('durabilite')->group(function () {
                Route::get('create', 'create')->name('create');
                Route::get('list', 'index')->name('index');
                Route::post('store', 'store')->name('store');
                Route::get('edit/{id}', 'edit')->name('edit');
                Route::post('update/{id}', 'update')->name('update');
            });


            //Manage Producteur
            Route::controller('Manager\ProducteurController')->name('traca.producteur.')->prefix('producteur')->group(function () {
                Route::get('list', 'index')->name('index');
                Route::get('infos/{id}', 'infos')->name('infos');
                Route::get('create', 'create')->name('create');
                Route::post('store', 'store')->name('store');
                Route::post('update/{id}','update')->name('update');
                Route::post('info/store', 'storeinfo')->name('storeinfo');
                Route::get('infos/edit/{id}', 'editinfo')->name('editinfo');
                Route::get('edit/{id}', 'edit')->name('edit');
                Route::post('status/{id}', 'status')->name('status');
                Route::get('/exportProducteursExcel', 'exportExcel')->name('exportExcel.producteurAll');
                Route::post('/uploadcontent', 'uploadContent')->name('uploadcontent');
            });

            //Manage Parcelle
            Route::controller('Manager\ParcelleController')->name('traca.parcelle.')->prefix('parcelle')->group(function () {
                Route::get('list', 'index')->name('index'); 
                Route::get('create', 'create')->name('create');
                Route::post('store', 'store')->name('store'); 
                Route::get('edit/{id}', 'edit')->name('edit');
                Route::post('status/{id}', 'status')->name('status');
                Route::get('/exportParcellesExcel', 'exportExcel')->name('exportExcel.parcelleAll');
                Route::post('/uploadcontent', 'uploadContent')->name('uploadcontent');
            });

            //Manage Estimation
            Route::controller('Manager\EstimationController')->name('traca.estimation.')->prefix('estimation')->group(function () {
                Route::get('list', 'index')->name('index'); 
                Route::get('create', 'create')->name('create');
                Route::post('store', 'store')->name('store'); 
                Route::get('edit/{id}', 'edit')->name('edit');
                Route::post('status/{id}', 'status')->name('status');
                Route::get('/exportEstimationsExcel', 'exportExcel')->name('exportExcel.estimationAll');
                Route::post('/uploadcontent', 'uploadContent')->name('uploadcontent');
            });

            //Manage Suivi Menage
            Route::controller('Manager\MenageController')->name('suivi.menage.')->prefix('menage')->group(function () {
                Route::get('list', 'index')->name('index'); 
                Route::get('create', 'create')->name('create');
                Route::post('store', 'store')->name('store'); 
                Route::get('edit/{id}', 'edit')->name('edit');
                Route::post('status/{id}', 'status')->name('status');
                Route::get('/exportMenagesExcel', 'exportExcel')->name('exportExcel.menageAll');
            });


             //Manage Suivi Parcelle
            Route::controller('Manager\SuiviParcelleController')->name('suivi.parcelles.')->prefix('suivi/parcelles')->group(function () {
                Route::get('list', 'index')->name('index'); 
                Route::get('create', 'create')->name('create');
                Route::post('store', 'store')->name('store'); 
                Route::get('edit/{id}', 'edit')->name('edit');
                Route::post('status/{id}', 'status')->name('status');
                Route::get('/exportSuiviParcellesExcel', 'exportExcel')->name('exportExcel.suiviParcelleAll');
            });

            //Manage Suivi Formation
            Route::controller('Manager\FormationController')->name('suivi.formation.')->prefix('formation')->group(function () {
                Route::get('list', 'index')->name('index'); 
                Route::get('create', 'create')->name('create');
                Route::post('store', 'store')->name('store'); 
                Route::get('edit/{id}', 'edit')->name('edit');
                Route::post('status/{id}', 'status')->name('status');
                Route::get('/exportFormationsExcel', 'exportExcel')->name('exportExcel.formationAll');
            });

            //Manage Suivi Inspection
            Route::controller('Manager\InspectionController')->name('suivi.inspection.')->prefix('inspection')->group(function () {
                Route::get('list', 'index')->name('index'); 
                Route::get('create', 'create')->name('create');
                Route::post('store', 'store')->name('store'); 
                Route::get('edit/{id}', 'edit')->name('edit');
                Route::post('status/{id}', 'status')->name('status');
                Route::get('/exportInspectionsExcel', 'exportExcel')->name('exportExcel.inspectionAll');
            });
            //Manage Suivi Application
            Route::controller('Manager\ApplicationController')->name('suivi.application.')->prefix('application')->group(function () {
                Route::get('list', 'index')->name('index'); 
                Route::get('create', 'create')->name('create');
                Route::post('store', 'store')->name('store'); 
                Route::get('edit/{id}', 'edit')->name('edit');
                Route::post('status/{id}', 'status')->name('status');
                Route::get('/exportApplicationsExcel', 'exportExcel')->name('exportExcel.applicationAll');
            });

            //Manage Suivi Ssrteclmrs
            Route::controller('Manager\SsrteclmrsController')->name('suivi.ssrteclmrs.')->prefix('ssrteclmrs')->group(function () {
                Route::get('list', 'index')->name('index'); 
                Route::get('create', 'create')->name('create');
                Route::post('store', 'store')->name('store'); 
                Route::get('edit/{id}', 'edit')->name('edit');
                Route::post('status/{id}', 'status')->name('status');
                Route::get('/exportSsrteclmrsExcel', 'exportExcel')->name('exportExcel.ssrteclmrsAll');
            });

            //Manage Agroapprovisionnements
            Route::controller('Manager\AgroapprovisionnementController')->name('agro.approvisionnement.')->prefix('agro/approvisionnement')->group(function () {
                Route::get('list', 'index')->name('index'); 
                Route::get('create', 'create')->name('create');
                Route::post('store', 'store')->name('store'); 
                Route::get('edit/{id}', 'edit')->name('edit');
                Route::post('status/{id}', 'status')->name('status');
                Route::get('/exportApprovisionnementExcel', 'exportExcel')->name('exportExcel.approvisionnementAll');
            });

            //Manage Agrodistributions
            Route::controller('Manager\AgrodistributionController')->name('agro.distribution.')->prefix('agro/distribution')->group(function () {
                Route::get('list', 'index')->name('index'); 
                Route::get('create', 'create')->name('create');
                Route::post('store', 'store')->name('store'); 
                Route::post('update', 'update')->name('update'); 
                Route::get('edit/{id}', 'edit')->name('edit');
                Route::post('status/{id}', 'status')->name('status');
                Route::get('/exportDistributionsExcel', 'exportExcel')->name('exportExcel.distributionAll');
                Route::post('/get/agroparcelles/arbres', 'getAgroParcellesArbres')->name('getAgroParcellesArbres');
            });

            //Manage Agroevaluations
            Route::controller('Manager\AgroevaluationController')->name('agro.evaluation.')->prefix('agro/evaluation')->group(function () {
                Route::get('list', 'index')->name('index'); 
                Route::get('create', 'create')->name('create');
                Route::post('store', 'store')->name('store'); 
                Route::get('destroy/{id}', 'destroy')->name('destroy'); 
                Route::get('edit/{id}', 'edit')->name('edit');
                Route::post('status/{id}', 'status')->name('status');
                Route::get('/exportEvaluationsExcel', 'exportExcel')->name('exportExcel.evaluationsAll');
            });

            //Manage Agrodeforestations
            Route::controller('Manager\AgrodeforestationController')->name('agro.deforestation.')->prefix('agro/deforestation')->group(function () {
                Route::get('list', 'index')->name('index'); 
                Route::get('create', 'create')->name('create');
                Route::post('store', 'store')->name('store'); 
                Route::get('edit/{id}', 'edit')->name('edit');
                Route::post('status/{id}', 'status')->name('status');
                Route::get('/exportDeforestationsExcel', 'exportExcel')->name('exportExcel.deforestationsAll');
            });

            //Manage Livraison
         
            Route::controller('Manager\LivraisonController')->name('livraison.')->prefix('livraison')->group(function () {
                Route::get('list', 'livraisonInfo')->name('index');
                Route::get('dispatch/list', 'dispatchLivraison')->name('dispatch');
                Route::get('upcoming/list', 'upcoming')->name('upcoming');
                Route::get('sent-queue/list', 'sentInQueue')->name('sentQueue');
                Route::get('delivery-queue/list', 'deliveryInQueue')->name('deliveryInQueue');
                Route::get('delivered', 'delivered')->name('delivered');
                Route::get('search', 'livraisonSearch')->name('search');
                Route::get('invoice/{id}', 'invoice')->name('invoice');
                Route::get('sent', 'sentLivraison')->name('sent');
                Route::get('/exportLivraisonsExcel', 'exportExcel')->name('exportExcel.livraisonAll');
            });

            Route::controller('Manager\ManagerTicketController')->prefix('ticket')->name('ticket.')->group(function () {
                Route::get('/', 'supportTicket')->name('index');
                Route::get('/new', 'openSupportTicket')->name('open');
                Route::post('/create', 'storeSupportTicket')->name('store');
                Route::get('/view/{ticket}', 'viewTicket')->name('view');
                Route::post('/reply/{ticket}', 'replyTicket')->name('reply');
                Route::post('/close/{ticket}', 'closeTicket')->name('close');
                Route::get('/download/{ticket}', 'ticketDownload')->name('download');
            });

            


        }); 

});



