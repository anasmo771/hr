<?php

namespace App\Listeners;

use App\Events\PasswordChanged;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogoutUserOnPasswordChange
{
    public function handle(PasswordChanged $event)
    {
        // Invalidate all sessions for the user except the current one
        DB::table('sessions')
            ->where('user_id', $event->user->id)
            ->delete();

        // Optionally, you can retain the current session
        // ->where('id', '!=', Session::getId())
        // ->delete();
    }
}
