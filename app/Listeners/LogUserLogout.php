<?php
namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use App\Models\Log;
use Illuminate\Support\Facades\Request;

class LogUserLogout
{
    public function handle(Logout $event)
    {
        Log::log(
            $event->user->id,
            'User Logout',
            null,
            null,
            ['email' => $event->user->email, 'ip' => Request::ip()]
        );
    }
}
