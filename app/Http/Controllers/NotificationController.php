<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\User;
use App\Models\Employee;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Mail;

class NotificationController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:notification-list', ['only' => ['index', 'show', 'search']]);
        $this->middleware('permission:notification-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:notification-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:notification-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Notification::where([['receive_id', auth()->user()->id], ['read', 0]])->update(['read' => 1, 'show' => 1]);
        if (auth()->user()->id == 1) {
            $notifications = Notification::latest()->paginate(15);
        } else {
            $notifications = Notification::where('receive_id', auth()->user()->id)->latest()->paginate(15);
        }
        return view('admin.Notification.index', compact('notifications'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::where('id', '!=', 1)->get();
        return view('admin.Notification.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate(
            [
                'title'  => "required",
                'priority'  => "required",
            ],
            [
                'title.required' => 'يجب إدخال عنوان الاشعار',
                'priority.required' => 'يجب تحديد أهمية الاشعار',
            ]
        );

        $finalArray = array();
        $title = request('title');
        $desc = request('desc');
        $priority = request('priority');
        if (request('role') != "1") {
            $users = User::role(request('role'))->get();
        } else {
            // $users = User::where('role_id', request('role'))->get();
            $users = User::get();
        }
        $max = Notification::max('num');
        $max += 1;
        foreach ($users as $user) {
            array_push(
                $finalArray,
                array(
                    'receive_id' => $user->id,
                    'title' => $title,
                    'desc' => $desc,
                    'num' => $max,
                    'priority' => $priority,
                    'created_at' => \Carbon\Carbon::now()
                )
            );
        }

        Notification::insert($finalArray);

        $log = new Log;
        $log->user_id = auth()->user()->id;
        $log->type = 3;
        $log->title = " اضافة اشعار جديد (" . request('title') . ")";
        $log->log = " تمت إضافة اشعار جديد (" . request('title') . ")";
        $log->save();

        return redirect()->back()->with('success', 'تــمــت إضــافــة الاشــعــار بــنــجــاح');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Notification::where('num', $id)->delete();
        return redirect()->back()->with('success', 'تــم حــذفـ الاشــعــار بــنــجــاح');
    }
}
