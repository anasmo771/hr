<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Log;
use App\Models\File;
use App\Models\Task;
use App\Models\User;
use App\Models\Course;
use App\Models\Report;
// use App\Models\absents; // تمت الإزالة
use App\Models\Bonus;
use App\Models\Employee;
use App\Models\Feedback;
use App\Models\Vacation;
use App\Models\Punishment;
use App\Models\Settlement;
use App\Models\subSection;
use App\Models\RequestSett;
use App\Models\Notification;
use App\Models\Promotion;
use App\Models\Archive;
use App\Models\ModelFiles;
use App\Models\Specialty;

use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    public function home()
    {
        return redirect()->route('admin.home');
    }

    public function userHome()
    {
        $users = User::count();
        $subCount = subSection::count();
        $vac = Vacation::count();
        $v = Vacation::take(10)->get();
        $employees = Employee::where('last_sett', '!=', null)
            ->orderBy('last_sett', 'DESC')
            ->take(10)
            ->get();

        return view('users.dashboard', compact('subCount', 'employees', 'vac', 'users', 'v'));
    }

    public function adminHome()
    {
        $users = User::count();
        $sub = subSection::where('parent_id', null)->get();
        // $sub = subSection::take(10)->get();
        $subCount = subSection::count();
        $vac = Vacation::count();
        $v = Vacation::take(10)->get();
        $emp = Employee::count();

        $Archive = Archive::count();
        $ModelFiles = ModelFiles::count();
        $resignations = Employee::where('status', 'مستقيل')->count();
        $Specialty = Specialty::count();

        // $absents = absents::count(); // تمت الإزالة
        $feedbacks = Feedback::count();
        $courses = Course::count();
        $punishments = Punishment::count();
        $tasks = Task::count();
        $promotionsCount = Promotion::count();
        $bonusesCount = Bonus::count();

        $notifications = Notification::count();
        $sett = Vacation::where('accept', false)->count();
        $employees = Employee::where('last_sett', '!=', null)
            ->orderBy('last_sett', 'DESC')
            ->take(10)
            ->get();

        $bonuses = Employee::where('futureBonus', '!=', null)
            ->whereYear('futureBonus', Carbon::now()->year)
            ->whereMonth('futureBonus', Carbon::now()->month)
            ->get();

        $promotions = Employee::where('futurepromotion', '!=', null)
            ->whereYear('futurepromotion', Carbon::now()->year)
            ->get();

        return view(
            'admin.dashboard',
            compact(
                'users',
                'sub',
                // 'absents', // تمت الإزالة من الـ compact
                'bonusesCount',
                'promotionsCount',
                'feedbacks',
                'courses',
                'punishments',
                'tasks',
                'subCount',
                'bonuses',
                'promotions',
                'vac',
                'notifications',
                'sett',
                'emp',
                'Archive',
                'ModelFiles',
                'resignations',
                'Specialty',
                'v'
            )
        );
    }

    public function login()
    {
        return view('auth-login');
    }

    public function notifications()
    {
        // ملاحظة: لديك use لنموذج Notification، لكن هنا تُستخدم بصيغة صغيرة `notification::`
        // أبقيتها كما هي لتوافق الكود الموجود لديك.
        $data = notification::where([['receive_id', auth()->user()->id], ['show', 0]])->get();
        $count = notification::Where([['receive_id', auth()->user()->id], ['read', 0]])->count();
        return json_encode(['data' => $data, 'count' => $count]);
    }

    public function changeShowNotification()
    {
        Notification::where([['receive_id', auth()->user()->id], ['show', 0]])
            ->update(['show' => 1]);
        return;
    }

    public function downloadFile($id)
    {
        $arr = File::find($id);
        if ($arr && $arr->path) {
            return response()->download(storage_path('app/public/' . $arr->path));
        } else {
            return redirect()->back()->with('error', 'عذرا لايوجد ملف لهذه الوثيقة');
        }
    }
}
