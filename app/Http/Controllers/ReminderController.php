<?php

namespace App\Http\Controllers;

use App\Model\Booked;
use Carbon\Carbon;

class ReminderController extends Controller
{
    public function checkDeadline()
    {
        $now = Carbon::now()->addDay(7)->toDateString();
        $booked = Booked::where('next_payment_date', 'like', "%{$now}%")->get();
        if (count($booked) > 0) {
            dd($booked);
        } else {
            dd($now);
        }
    }
}
