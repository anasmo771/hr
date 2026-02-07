<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;

use App\Http\Controllers\VacationController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\subSectionController;
use App\Http\Controllers\UnitStaffingController;
use App\Http\Controllers\SpecialtyController;
use App\Http\Controllers\StaffingController;

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\PunishmentController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\BounsController;
use App\Http\Controllers\FeedbackController;

use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\ArchiveTypeController;
use App\Http\Controllers\ModelController;

use App\Http\Controllers\ResignationController;
use App\Http\Controllers\SettlementController;
use App\Http\Controllers\LogController;


/*
|--------------------------------------------------------------------------
| Auth (Login/Logout)
|--------------------------------------------------------------------------
*/
Route::post('/login',  [LoginController::class, 'login'])->name('login');
Route::get('/login',   [HomeController::class, 'login']); 
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| Routes that require authentication
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    |----------------------------------------------------------------------
    | Home (لغير الأدمن) + إشعارات
    |----------------------------------------------------------------------
    */
    Route::get('/',     [HomeController::class, 'home'])->name('home');
    Route::get('/home', [HomeController::class, 'userHome'])->name('user.home');

    Route::get('/notificationsCheck',       [HomeController::class, 'notifications'])->name('notificationsCheck');
    Route::get('/changeShowNotification',   [HomeController::class, 'changeShowNotification'])->name('changeShowNotification');
    Route::resource('notifications', NotificationController::class);

    /*
    |----------------------------------------------------------------------
    | الموارد العامة
    |----------------------------------------------------------------------
    */
    Route::resource('roles', RoleController::class);
    Route::get('/search/role', [RoleController::class, 'search'])->name('searchRole');
    
    Route::resource('employees', EmployeeController::class)->except(['show']);
    Route::get('/EmployeeDetails/{id}', [EmployeeController::class, 'EmployeeDetails'])->name('EmployeeDetails');
    Route::get('/searchEmployee',       [EmployeeController::class, 'show'])->name('searchEmployee');
    Route::get('employees-print/',      [EmployeeController::class, 'print'])->name('employees.print');

    Route::resource('subSection', subSectionController::class);
    Route::post('/subSection/store',    [subSectionController::class, 'subSectionStore'])->name('subSectionStore');
    Route::delete('/deletesub/{id}',    [subSectionController::class, 'destroy'])->name('deletesub');

    Route::resource('unit-staffings', UnitStaffingController::class)
        ->only(['store','update','destroy'])
        ->names([
            'store'   => 'unitStaffings.store',
            'update'  => 'unitStaffings.update',
            'destroy' => 'unitStaffings.destroy',
        ]);
    // روت AJAX لإرجاع الوحدات حسب القسم/الإدارة
    Route::get('ajax/unit-staffings/{subSection}', [UnitStaffingController::class, 'bySubSection'])
    ->name('ajax.unit-staffings');

    Route::resource('Specialties',SpecialtyController::class);
    Route::resource('Staffing',  StaffingController::class);

    /*
    |----------------------------------------------------------------------
    | Vacations
    |----------------------------------------------------------------------
    */
    Route::resource('vacations', VacationController::class)->except(['create','store','update']);
    Route::get('/createVacation/{id}',   [VacationController::class, 'create'])->name('createVacation');
    Route::post('/saveVacation/{id}',    [VacationController::class, 'store'])->name('saveVacation');
    Route::put('/vacations/{id}',        [VacationController::class, 'updateForm'])->name('vacations.update.form');
    Route::post('/vacations/update/',    [VacationController::class, 'update'])->name('vacations.update');
    Route::get('/endVecation/{id}',      [VacationController::class, 'endVecation'])->name('endVecation');
    Route::get('/vacation/create-new/',  [VacationController::class, 'createAll'])->name('vacations.createNew');
    Route::get('vacation-print/',        [VacationController::class, 'print'])->name('vacation.print');

    // alias  لتوافق أي شفرات كتبت vacation.index بدل vacations.index
    Route::get('/vacation', fn() => redirect()->route('vacations.index'))->name('vacation.index');

    /*
    |----------------------------------------------------------------------
    | Tasks
    |----------------------------------------------------------------------
    */
    Route::resource('tasks', TaskController::class)->except(['create','store']);
    Route::get('/createTask/{id}',      [TaskController::class, 'create'])->name('createTask');
    Route::post('/saveTask/{id}',       [TaskController::class, 'store'])->name('saveTask');
    Route::get('/task/create-new/',     [TaskController::class, 'createAll'])->name('tasks.createNew');

    /*
    |----------------------------------------------------------------------
    | Courses
    |----------------------------------------------------------------------
    */
    Route::resource('/courses', CourseController::class)->except(['create','store']);
    Route::get('/createCourse/{id}', [CourseController::class, 'create'])->name('createCourse');
    Route::post('/course/store/{id}', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/searchCourses',      [CourseController::class, 'search'])->name('searchCourses');
    Route::get('/course/create-new',  [CourseController::class, 'createAll'])->name('courses.createNew');
    Route::get('course-print/',       [CourseController::class, 'print'])->name('course.print');

    /*
    |----------------------------------------------------------------------
    | Punishments
    |----------------------------------------------------------------------
    */
    Route::resource('punishments', PunishmentController::class)->except(['create','store']);
    Route::get('/createPunishment/{id}', [PunishmentController::class, 'create'])->name('createPunishment');
    Route::post('/Punishments/store/{id}', [PunishmentController::class, 'store'])->name('punishments.store');
    Route::get('/searchPunishments', [PunishmentController::class, 'search'])->name('searchEmployeePunshe');
    Route::get('/punishment/create-new', [PunishmentController::class, 'createAll'])->name('punishments.createNew');
    Route::get('punishment-print/',     [PunishmentController::class, 'print'])->name('punishment.print');

    /*
    |----------------------------------------------------------------------
    | Archives & Models
    |----------------------------------------------------------------------
    */
    Route::resource('archives', ArchiveController::class)->except(['index','create','update','show']);
    Route::post('/update-archive',        [ArchiveController::class, 'update'])->name('archivesUpdate');
    Route::get('/show-archives/{id}',     [ArchiveController::class, 'index'])->name('archives.index');
    Route::get('/show-archives/{id}/{type}', [ArchiveController::class, 'show'])->name('archivesEmployee.show');
    Route::resource('archiveTypes', ArchiveTypeController::class);
    Route::get('admin/searchArchuves',    [ArchiveController::class, 'create'])->name('admin.searchArchuves');
    Route::get('/archives/preview/{id}',  [ArchiveController::class, 'preview'])->name('archives.preview');

    Route::resource('models', ModelController::class)->except(['create','update','show']);
    Route::post('/update-models',         [ModelController::class, 'update'])->name('modelsUpdate');
    Route::get('admin/searchModel',       [ModelController::class, 'show'])->name('admin.searchModels');
    Route::get('/model-files/{id}/preview',[ModelController::class, 'preview'])->name('modelfiles.preview');

    Route::get('/downloadFile/{id}', [HomeController::class, 'downloadFile'])->name('downloadFile');

    /*
    |----------------------------------------------------------------------
    | Attendance
    |----------------------------------------------------------------------
    */
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/',            [AttendanceController::class, 'index'])->name('index');
        Route::post('/check-in',   [AttendanceController::class, 'checkIn'])->name('checkin');
        Route::post('/check-out',  [AttendanceController::class, 'checkOut'])->name('checkout');

        Route::get('/report',          [AttendanceController::class, 'showReportForm'])->name('report.form');
        Route::get('/report/generate', [AttendanceController::class, 'generateReport'])->name('report.generate');

        Route::get('/monthly-report',  [AttendanceController::class, 'monthlyReport'])->name('monthly.report');
        Route::get('/absence-report/{employee}', [AttendanceController::class, 'absenceReport'])->name('absence.report');
    });

}); // end auth group


/*
|--------------------------------------------------------------------------
| Admin-only routes
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => ['auth', 'isAdmin']], function () {

    // Dashboard 
    Route::get('/admin/home', [DashboardController::class, 'index'])->name('admin.home');

    // Logs & Users
    Route::get('/admin/logs', [LogController::class, 'index'])->name('admin.logs');
    Route::resource('users', UserController::class);

    // Outgoing employees
    Route::resource('resignation', ResignationController::class);
    Route::get('/searchResignationEmployee', [ResignationController::class, 'show'])->name('searchResignationEmployee');
    
    // Feedback
    Route::resource('feedback', FeedbackController::class);
    Route::get('/searchEmployeefeedback', [\App\Http\Controllers\FeedbackController::class, 'show1'])->name('searchEmployeefeedback');
    Route::get('/feedback/create/{id}',   [\App\Http\Controllers\FeedbackController::class, 'create'])->name('createFeedback');
    Route::post('/feedback/store/{id}',   [\App\Http\Controllers\FeedbackController::class, 'store'])->name('storeFeedback');
    Route::get('/feedbacks/create-new',   [\App\Http\Controllers\FeedbackController::class, 'createAll'])->name('feedback.createNew');

    // Promotions
    Route::get('/promotion/create-new', [PromotionController::class, 'createAll'])->name('promotion.createNew');
    Route::get('/promotion/create/{id}', [PromotionController::class, 'create'])->whereNumber('id')->name('createPromotion');
    Route::post('/promotion/store/{id}', [PromotionController::class, 'store'])->whereNumber('id')->name('storePromotion');
    Route::get('/promotion/{id}',        [PromotionController::class, 'show'])->whereNumber('id')->name('promotion.show');
    Route::get('/searchEmployeepromotion', [PromotionController::class, 'show1'])->name('searchEmployeepromotion');
    Route::post('/promotion/quick-store', [PromotionController::class, 'quickStore'])->name('promotion.quickStore');
    Route::post('/promotion',             [PromotionController::class, 'store'])->name('promotion.store');
    Route::get('/promotions',             [PromotionController::class, 'index'])->name('promotions.index');
    Route::resource('promotion', PromotionController::class)->except(['create','store','show']);

    // Bonuses
    Route::resource('bouns', BounsController::class);
    Route::get('/searchEmployeebouns', [BounsController::class, 'show1'])->name('searchEmployeebouns');
    Route::get('/bouns/create/{id}',   [BounsController::class, 'create'])->name('createBouns');
    Route::post('/bouns/store/{id}',   [BounsController::class, 'store'])->name('storeBouns');
    Route::get('/bonuses',             [BounsController::class, 'index'])->name('bonuses.index');
    Route::post('/bonuses/quick-store',[BounsController::class, 'quickStore'])->name('bonuses.quickStore');

    // توافق قديم: absents.index → attendance.monthly.report
    Route::get('/absents', fn () => redirect()->route('attendance.monthly.report'))->name('absents.index');
});


/*
|--------------------------------------------------------------------------
| Fallback
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return redirect()->route('home');
});
