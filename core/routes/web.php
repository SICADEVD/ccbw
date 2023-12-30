<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\Manager\LeaveController;
use App\Http\Controllers\Manager\AttendanceController;
use App\Http\Controllers\Manager\EmployeeController;
use App\Http\Controllers\Manager\ImportController;
use App\Http\Controllers\Manager\TimelogController;
use App\Http\Controllers\Manager\TimelogCalendarController;
use App\Http\Controllers\Manager\DepartmentController;
use App\Http\Controllers\Manager\DesignationController;
use App\Http\Controllers\Manager\ImageController;
use App\Http\Controllers\Manager\HolidayController;
use App\Http\Controllers\Manager\SettingsController;
use App\Http\Controllers\Manager\AttendanceSettingController;
use App\Http\Controllers\Manager\LeaveSettingController;
use App\Http\Controllers\Manager\EmployeeShiftController;
use App\Http\Controllers\Manager\LeaveTypeController;
use App\Http\Controllers\Manager\LeaveFileController;
use App\Http\Controllers\Manager\LeavesQuotaController;
use App\Http\Controllers\Manager\EmployeeDocController;
use App\Http\Controllers\Manager\CooperativeSettingController;
use App\Http\Controllers\Manager\ProgrammeSettingController;
use App\Http\Controllers\Manager\SectionSettingController;
use App\Http\Controllers\Manager\LocaliteSettingController;
use App\Http\Controllers\Manager\SettingController;
use App\Http\Controllers\Manager\ArchivageController;
use App\Http\Controllers\Manager\FormationStaffController;
use App\Http\Controllers\Manager\EmployeeFileController;
use App\Http\Controllers\Manager\EmergencyContactController;
use App\Http\Controllers\Manager\LivraisonCentraleController;

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
            Route::get('/change/{lang?}', 'changeLanguage')->name('lang');
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
        //Présentation cooperative
        Route::controller('Manager\PresentationCoopController')->name('presentation-coop.')->prefix('presentation-coop')->group(function () {
            Route::get('index', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
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

        // employee routes
        Route::controller('Manager\EmployeeController')->name('hr.')->prefix('hr')->group(function () {
            Route::post('employees/apply-quick-action', [EmployeeController::class, 'applyQuickAction'])->name('employees.apply_quick_action');
            Route::post('employees/assignRole', [EmployeeController::class, 'assignRole'])->name('employees.assign_role');
            Route::get('employees/byDepartment/{id}', [EmployeeController::class, 'byDepartment'])->name('employees.by_department');
            Route::get('employees/invite-member', [EmployeeController::class, 'inviteMember'])->name('employees.invite_member');
            Route::get('employees/import', [EmployeeController::class, 'importMember'])->name('employees.import');
            Route::post('employees/import', [EmployeeController::class, 'importStore'])->name('employees.import.store');
            Route::post('employees/import/process', [EmployeeController::class, 'importProcess'])->name('employees.import.process');
            Route::post('employees/send-invite', [EmployeeController::class, 'sendInvite'])->name('employees.send_invite');
            Route::post('employees/create-link', [EmployeeController::class, 'createLink'])->name('employees.create_link');
        });

        // Holidays
        Route::get('holidays/mark-holiday', [HolidayController::class, 'markHoliday'])->name('holidays.mark_holiday');
        Route::post('holidays/mark-holiday-store', [HolidayController::class, 'markDayHoliday'])->name('holidays.mark_holiday_store');
        Route::get('holidays/table-view', [HolidayController::class, 'tableView'])->name('holidays.table_view');
        Route::post('holidays/apply-quick-action', [HolidayController::class, 'applyQuickAction'])->name('holidays.apply_quick_action');
        Route::resource('holidays', HolidayController::class);

        Route::get('designations/designation-hierarchy', [DesignationController::class, 'hierarchyData'])->name('designation.hierarchy');
        Route::post('designations/changeParent', [DesignationController::class, 'changeParent'])->name('designation.changeParent');
        Route::post('designations/search-filter', [DesignationController::class, 'searchFilter'])->name('designation.srchFilter');
        Route::post('designations/apply-quick-action', [DesignationController::class, 'applyQuickAction'])->name('designations.apply_quick_action');
        Route::resource('designations', DesignationController::class);

        Route::post('departments/apply-quick-action', [DepartmentController::class, 'applyQuickAction'])->name('departments.apply_quick_action');
        Route::get('departments/department-hierarchy', [DepartmentController::class, 'hierarchyData'])->name('department.hierarchy');
        Route::post('department/changeParent', [DepartmentController::class, 'changeParent'])->name('department.changeParent');
        Route::get('department/search', [DepartmentController::class, 'searchDepartment'])->name('departments.search');
        Route::get('department/{id}', [DepartmentController::class, 'getMembers'])->name('departments.members');
        Route::resource('departments', DepartmentController::class);
        // Get quill image uploaded
        Route::get('quill-image/{image}', [ImageController::class, 'getImage'])->name('image.getImage');
        // Cropper Model
        Route::get('cropper/{element}', [ImageController::class, 'cropper'])->name('cropper');

        Route::post('formation-staff/status/{id}', [FormationStaffController::class, 'status'])->name('formation-staff.status');
        Route::post('formation-staff/exportFormationsExcel', [FormationStaffController::class, 'exportExcel'])->name('formation-staff.exportExcel.formationAll');
        Route::resource('formation-staff', FormationStaffController::class);

        Route::controller('Manager\ImportController')->name('hr.')->prefix('hr')->group(function () {
            Route::get('import/process/{name}/{id}', [ImportController::class, 'getImportProgress'])->name('import.process.progress');
            Route::get('employees/import/exception/{name}', [ImportController::class, 'getQueueException'])->name('import.process.exception');
        });

        Route::name('settings.')->prefix('settings')->group(function () {
            Route::resource('attendance-settings', AttendanceSettingController::class);
            Route::resource('leaves-settings', LeaveSettingController::class);
            Route::resource('cooperative-settings', CooperativeSettingController::class);
            Route::resource('durabilite-settings', ProgrammeSettingController::class);
            Route::resource('section-settings', SectionSettingController::class);
            Route::resource('localite-settings', LocaliteSettingController::class);
            Route::post('localite-settings/status/{id}', [LocaliteSettingController::class, 'status'])->name('localite-settings.status');
            Route::post('localite-settings/uploadcontent', [LocaliteSettingController::class, 'uploadContent'])->name('localite-settings.uploadcontent');
            Route::post('leaves-settings/change-permission', [LeaveSettingController::class, 'changePermission'])->name('leaves-settings.changePermission');
            Route::get('campagne/', [SettingController::class, 'campagneIndex'])->name('campagne.index');
            Route::post('campagne/store', [SettingController::class, 'campagneStore'])->name('campagne.store');
            Route::post('campagne/status/{id}', [SettingController::class, 'campagneStatus'])->name('campagne.status');
            Route::get('travaux-dangereux/', [SettingController::class, 'travauxDangereuxIndex'])->name('travauxDangereux.index');
            Route::post('travaux-dangereux/store', [SettingController::class, 'travauxDangereuxStore'])->name('travauxDangereux.store');
            Route::post('travaux-dangereux/status/{id}', [SettingController::class, 'travauxDangereuxStatus'])->name('travauxDangereux.status');
            Route::get('travaux-legers/', [SettingController::class, 'travauxLegersIndex'])->name('travauxLegers.index');
            Route::post('travaux-legers/store', [SettingController::class, 'travauxLegersStore'])->name('travauxLegers.store');
            Route::post('travaux-legers/status/{id}', [SettingController::class, 'travauxLegersStatus'])->name('travauxLegers.status');
            Route::get('arret-ecole/', [SettingController::class, 'arretEcoleIndex'])->name('arretEcole.index');
            Route::post('arret-ecole/store', [SettingController::class, 'arretEcoleStore'])->name('arretEcole.store');
            Route::post('arret-ecole/status/{id}', [SettingController::class, 'arretEcoleStatus'])->name('arretEcole.status');
            Route::get('type-formation/', [SettingController::class, 'typeFormationIndex'])->name('typeFormation.index');
            Route::post('type-formation/store', [SettingController::class, 'typeFormationStore'])->name('typeFormation.store');
            Route::post('type-formation/status/{id}', [SettingController::class, 'typeFormationStatus'])->name('typeFormation.status');
            Route::get('theme-formation/', [SettingController::class, 'themeFormationIndex'])->name('themeFormation.index');
            Route::post('theme-formation/store', [SettingController::class, 'themeFormationStore'])->name('themeFormation.store');
            Route::post('theme-formation/status/{id}', [SettingController::class, 'themeFormationStatus'])->name('themeFormation.status');
            Route::get('sous-theme-formation/', [SettingController::class, 'sousThemeFormationIndex'])->name('sousThemeFormation.index');
            Route::post('sous-theme-formation/store', [SettingController::class, 'sousThemeFormationStore'])->name('sousThemeFormation.store');
            Route::post('sous-theme-formation/status/{id}', [SettingController::class, 'sousThemeFormationStatus'])->name('sousThemeFormation.status');
            Route::get('module-formation-staff/', [SettingController::class, 'moduleFormationStaffIndex'])->name('moduleFormationStaff.index');
            Route::post('module-formation-staff/store', [SettingController::class, 'moduleFormationStaffStore'])->name('moduleFormationStaff.store');
            Route::post('module-formation-staff/status/{id}', [SettingController::class, 'moduleFormationStaffStatus'])->name('moduleFormationStaff.status');
            Route::get('theme-formation-staff/', [SettingController::class, 'themeFormationStaffIndex'])->name('themeFormationStaff.index');
            Route::post('theme-formation-staff/store', [SettingController::class, 'themeFormationStaffStore'])->name('themeFormationStaff.store');
            Route::post('theme-formation-staff/status/{id}', [SettingController::class, 'themeFormationStaffStatus'])->name('themeFormationStaff.status');
            Route::get('categorie-questionnaire/', [SettingController::class, 'categorieQuestionnaireIndex'])->name('categorieQuestionnaire.index');
            Route::post('categorie-questionnaire/store', [SettingController::class, 'categorieQuestionnaireStore'])->name('categorieQuestionnaire.store');
            Route::post('categorie-questionnaire/status/{id}', [SettingController::class, 'categorieQuestionnaireStatus'])->name('categorieQuestionnaire.status');
            Route::get('questionnaire/', [SettingController::class, 'questionnaireIndex'])->name('questionnaire.index');
            Route::post('questionnaire/store', [SettingController::class, 'questionnaireStore'])->name('questionnaire.store');
            Route::post('questionnaire/status/{id}', [SettingController::class, 'questionnaireStatus'])->name('questionnaire.status');
            Route::get('espece-arbre/', [SettingController::class, 'especeArbreIndex'])->name('especeArbre.index');
            Route::post('espece-arbre/store', [SettingController::class, 'especeArbreStore'])->name('especeArbre.store');
            Route::post('espece-arbre/status/{id}', [SettingController::class, 'especeArbreStatus'])->name('especeArbre.status');
            Route::get('type-archive/', [SettingController::class, 'typeArchiveIndex'])->name('typeArchive.index');
            Route::post('type-archive/store', [SettingController::class, 'typeArchiveStore'])->name('typeArchive.store');
            Route::post('type-archive/status/{id}', [SettingController::class, 'typeArchiveStatus'])->name('typeArchive.status');

            Route::get('departement/', [SettingController::class, 'departementIndex'])->name('departements.index');
            Route::post('departement/store', [SettingController::class, 'departementStore'])->name('departements.store');
            Route::post('departement/status/{id}', [SettingController::class, 'departementStatus'])->name('departements.status');

            Route::get('designation/', [SettingController::class, 'designationIndex'])->name('designations.index');
            Route::post('designation/store', [SettingController::class, 'designationStore'])->name('designations.store');
            Route::post('designation/status/{id}', [SettingController::class, 'designationStatus'])->name('designations.status');

            Route::get('instance/', [SettingController::class, 'instanceIndex'])->name('instance.index');
            Route::post('instance/store', [SettingController::class, 'instanceStore'])->name('instance.store');
            Route::get('document-ad/', [SettingController::class, 'documentadIndex'])->name('documentad.index');
            Route::post('document-ad/store', [SettingController::class, 'documentadStore'])->name('documentad.store');

            Route::get('magasin-section/', [SettingController::class, 'magasinSectionIndex'])->name('magasinSection.index');
            Route::post('magasin-section/store', [SettingController::class, 'magasinSectionStore'])->name('magasinSection.store');
            Route::post('magasin-section/status/{id}', [SettingController::class, 'magasinSectionStatus'])->name('magasinSection.status');

            Route::get('magasin-central/', [SettingController::class, 'magasinCentralIndex'])->name('magasinCentral.index');
            Route::post('magasin-central/store', [SettingController::class, 'magasinCentralStore'])->name('magasinCentral.store');
            Route::post('magasin-central/status/{id}', [SettingController::class, 'magasinCentralStatus'])->name('magasinCentral.status');

            Route::get('formateur-staff/', [SettingController::class, 'formateurStaffIndex'])->name('formateurStaff.index');
            Route::post('formateur-staff/store', [SettingController::class, 'formateurStaffStore'])->name('formateurStaff.store');
            Route::get('vehicule/', [SettingController::class, 'vehiculeIndex'])->name('vehicule.index');
            Route::post('vehicule/store', [SettingController::class, 'vehiculeStore'])->name('vehicule.store');
            Route::post('vehicule/status/{id}', [SettingController::class, 'vehiculeStatus'])->name('vehicule.status');

            //route pour les remorques

            Route::get('remorque/', [SettingController::class, 'remorqueIndex'])->name('remorque.index');
            Route::post('remorque/store', [SettingController::class, 'remorqueStore'])->name('remorque.store');
            Route::post('remorque/status/{id}', [SettingController::class, 'remorqueStatus'])->name('remorque.status');

            Route::get('transporteur/modal', [SettingController::class, 'transporteurModalIndex'])->name('transporteurModal.index');
            Route::get('transporteur/', [SettingController::class, 'transporteurIndex'])->name('transporteur.index');
            Route::post('transporteur/store', [SettingController::class, 'transporteurStore'])->name('transporteur.store');
            Route::post('transporteur/modal/store', [SettingController::class, 'transporteurModalStore'])->name('transporteurModal.store');
            Route::post('transporteur/status/{id}', [SettingController::class, 'transporteurStatus'])->name('transporteur.status');
            
            Route::get('entreprise/', [SettingController::class, 'entrepriseIndex'])->name('entreprise.index');
            Route::post('entreprise/store', [SettingController::class, 'entrepriseStore'])->name('entreprise.store');
        });

        Route::resource('employee-files', EmployeeFileController::class);
        Route::resource('leaveType', LeaveTypeController::class);
        Route::post('employee-shifts/set-default', [EmployeeShiftController::class, 'setDefaultShift'])->name('employee-shifts.set_default');
        Route::resource('employee-shifts', EmployeeShiftController::class);
        Route::resource('settings', SettingsController::class)->only(['edit', 'update', 'index']);

        Route::resource('employees', EmployeeController::class);
        Route::resource('emergency-contacts', EmergencyContactController::class);
        Route::get('employee-docs/download/{id}', [EmployeeDocController::class, 'download'])->name('employee-docs.download');
        Route::resource('employee-docs', EmployeeDocController::class);

        Route::get('employee-leaves/employeeLeaveTypes/{id}', [LeavesQuotaController::class, 'employeeLeaveTypes'])->name('employee-leaves.employee_leave_types');
        Route::resource('employee-leaves', LeavesQuotaController::class);
        // ----------------------------- form leaves ------------------------------//

        Route::get('leaves/leaves-date', [LeaveController::class, 'getDate'])->name('leaves.date');
        Route::get('leaves/personal', [LeaveController::class, 'personalLeaves'])->name('leaves.personal');
        Route::get('leaves/calendar', [LeaveController::class, 'leaveCalendar'])->name('leaves.calendar');
        Route::post('leaves/data', [LeaveController::class, 'data'])->name('leaves.data');
        Route::post('leaves/leaveAction', [LeaveController::class, 'leaveAction'])->name('leaves.leave_action');
        Route::get('leaves/show-reject-modal', [LeaveController::class, 'rejectLeave'])->name('leaves.show_reject_modal');
        Route::get('leaves/show-approved-modal', [LeaveController::class, 'approveLeave'])->name('leaves.show_approved_modal');
        Route::post('leaves/pre-approve-leave', [LeaveController::class, 'preApprove'])->name('leaves.pre_approve_leave');
        Route::post('leaves/apply-quick-action', [LeaveController::class, 'applyQuickAction'])->name('leaves.apply_quick_action');
        Route::get('leaves/view-related-leave/{id}', [LeaveController::class, 'viewRelatedLeave'])->name('leaves.view_related_leave');
        Route::resource('leaves', LeaveController::class);

        // leaves files routes
        Route::get('leave-files/download/{id}', [LeaveFileController::class, 'download'])->name('leave-files.download');
        Route::resource('leave-files', LeaveFileController::class);

        Route::match(['GET', 'POST'], '/archivages/export', [ArchivageController::class, 'export'])->name('archivages.export');
        Route::match(['GET', 'POST'], '/archivages/status/{id}', [ArchivageController::class, 'status'])->name('archivages.status');
        Route::resource('archivages', ArchivageController::class);

        Route::controller('Manager\AttendanceController')->name('hr.')->prefix('hr')->group(function () {
            // Attendance
            Route::get('attendances/export-attendance/{year}/{month}/{id}', [AttendanceController::class, 'exportAttendanceByMember'])->name('attendances.export_attendance');
            Route::get('attendances/export-all-attendance/{year}/{month}/{id}/{department}/{designation}', [AttendanceController::class, 'exportAllAttendance'])->name('attendances.export_all_attendance');
            Route::post('attendances/employee-data', [AttendanceController::class, 'employeeData'])->name('attendances.employee_data');
            Route::get('attendances/mark/{id}/{day}/{month}/{year}', [AttendanceController::class, 'mark'])->name('attendances.mark');
            Route::get('attendances/by-member', [AttendanceController::class, 'byMember'])->name('attendances.by_member');
            Route::get('attendances/by-hour', [AttendanceController::class, 'byHour'])->name('attendances.by_hour');
            Route::post('attendances/bulk-mark', [AttendanceController::class, 'bulkMark'])->name('attendances.bulk_mark');
            Route::get('attendances/import', [AttendanceController::class, 'importAttendance'])->name('attendances.import');
            Route::post('attendances/import', [AttendanceController::class, 'importStore'])->name('attendances.import.store');
            Route::post('attendances/import/process', [AttendanceController::class, 'importProcess'])->name('attendances.import.process');
            Route::get('attendances/by-map-location', [AttendanceController::class, 'byMapLocation'])->name('attendances.by_map_location');
            Route::resource('attendances', AttendanceController::class);
            Route::get('attendance/{id}/{day}/{month}/{year}', [AttendanceController::class, 'addAttendance'])->name('attendances.add-user-attendance');
        });

        // Timelogs

        Route::controller('Manager\TimelogController')->name('hr.')->prefix('hr')->group(function () {

            Route::get('by-employee', [TimelogController::class, 'byEmployee'])->name('timelogs.by_employee');
            Route::get('export', [TimelogController::class, 'export'])->name('timelogs.export');
            Route::get('show-active-timer', [TimelogController::class, 'showActiveTimer'])->name('timelogs.show_active_timer');
            Route::get('show-timer', [TimelogController::class, 'showTimer'])->name('timelogs.show_timer');
            Route::post('start-timer', [TimelogController::class, 'startTimer'])->name('timelogs.start_timer');
            Route::get('timer-data', [TimelogController::class, 'timerData'])->name('timelogs.timer_data');
            Route::post('stop-timer', [TimelogController::class, 'stopTimer'])->name('timelogs.stop_timer');
            Route::post('pause-timer', [TimelogController::class, 'pauseTimer'])->name('timelogs.pause_timer');
            Route::post('resume-timer', [TimelogController::class, 'resumeTimer'])->name('timelogs.resume_timer');
            Route::post('apply-quick-action', [TimelogController::class, 'applyQuickAction'])->name('timelogs.apply_quick_action');

            Route::post('employee_data', [TimelogController::class, 'employeeData'])->name('timelogs.employee_data');
            Route::post('user_time_logs', [TimelogController::class, 'userTimelogs'])->name('timelogs.user_time_logs');
            Route::post('approve_timelog', [TimelogController::class, 'approveTimelog'])->name('timelogs.approve_timelog');
        });
        Route::resource('timelog-calendar', TimelogCalendarController::class);
        Route::resource('timelogs', TimelogController::class);

        //Manage Producteur
        Route::controller('Manager\ProducteurController')->name('traca.producteur.')->prefix('producteur')->group(function () {
            Route::get('list', 'index')->name('index');
            Route::get('infos/{id}', 'infos')->name('infos');
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::post('update/{id}', 'update')->name('update');
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
            Route::get('mapping', 'mapping')->name('mapping');
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


            Route::get('visiteur/{id}', 'visiteur')->name('visiteurs');
            Route::get('visiteur/create/{id}', 'createvisiteur')->name('createvisiteur');
            Route::post('visiteur/store', 'storevisiteur')->name('storevisiteur');
            Route::get('visiteur/edit/{id}', 'editvisiteur')->name('editvisiteur');
        });



        //Manage Suivi Inspection
        Route::controller('Manager\InspectionController')->name('suivi.inspection.')->prefix('inspection')->group(function () {
            Route::get('list', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('status/{id}', 'status')->name('status');
            Route::get('certificat', 'getCertificat')->name('getcertificat');
            Route::get('questionnaire', 'getQuestionnaire')->name('getquestionnaire');
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
            Route::get('', 'index')->name('index');
            Route::get('section', 'section')->name('section');
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::get('create-section', 'create_section')->name('create-section');
            Route::post('store-section', 'store_section')->name('store-section');
            Route::get('edit-section/{id}', 'edit_section')->name('edit-section');
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
        Route::name('livraison.')->prefix('livraison')->group(function () {
            Route::get('magcentral/stock', [LivraisonCentraleController::class,'stock'])->name('magcentral.stock');
            Route::post('magcentral/delivery', [LivraisonCentraleController::class,'deliveryStore'])->name('magcentral.delivery');
            Route::get('magcentral/invoice/{id}', [LivraisonCentraleController::class,'invoice'])->name('magcentral.invoice');
            Route::get('magcentral/producteur', [LivraisonCentraleController::class,'getProducteur'])->name('magcentral.get.producteur');
            Route::get('magcentral/producteur/liste', [LivraisonCentraleController::class,'getListeProducteurConnaiss'])->name('magcentral.get.listeproducteur');
            Route::get('magcentral/connaissement', [LivraisonCentraleController::class,'connaissement'])->name('usine.connaissement');
            Route::post('magcentral/usine/delivery', [LivraisonCentraleController::class,'deliveryUsineStore'])->name('usine.delivery');
            Route::post('magcentral/usine/refoule', [LivraisonCentraleController::class,'refouleUsineStore'])->name('usine.refoule');
            Route::get('magcentral/usine/invoice/{id}', [LivraisonCentraleController::class,'usineInvoice'])->name('usine.invoice');
            Route::get('magcentral/prime', [LivraisonCentraleController::class,'prime'])->name('prime.producteur');
            Route::post('magcentral/prime', [LivraisonCentraleController::class,'deliveryPrimeStore'])->name('prime.delivery');
            Route::get('magcentral/prime/invoice', [LivraisonCentraleController::class,'primeInvoice'])->name('prime.invoice');
            Route::get('magcentral/usine/suivi/{id}', [LivraisonCentraleController::class,'suiviLivraison'])->name('usine.suivi');
            Route::post('magcentral/suivi/store', [LivraisonCentraleController::class,'suiviStore'])->name('magcentral.suivi.store');
            Route::resource('magcentral', LivraisonCentraleController::class); 
            
        });

        Route::controller('Manager\LivraisonController')->name('livraison.')->prefix('livraison')->group(function () {
            Route::get('send', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::post('update/{id}', 'update')->name('update');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::get('list', 'livraisonInfo')->name('index');
            Route::get('stock', 'stockSection')->name('stock.section');
            Route::post('stock/store', 'sectionStore')->name('section.store');
            Route::get('stock/create', 'stockSectionCreate')->name('stock.section.create');
            Route::get('parcelle', 'getParcelle')->name('get.parcelle');
            Route::get('producteur', 'getProducteur')->name('get.producteur');
            Route::get('certificat', 'getCertificat')->name('get.certificat');
            Route::get('producteur/liste', 'getListeProducteurConnaiss')->name('get.listeproducteur');
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
