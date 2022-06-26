<?php

namespace App\Http\Controllers;

use App\CPU\Helpers;
use App\Model\Booked;
use Carbon\Carbon;

class ReminderController extends Controller
{
    public function checkDeadline()
    {
        $now = Carbon::now()->addMonth(1)->toDateString();
        // dd($now);
        $booked = Booked::with('roomDetail', 'order', 'detail_order')->where('next_payment_date', 'like', "%{$now}%")->get();
        if (count($booked) > 0) {
            foreach ($booked as $b) {
                $data = [
                    'title' => 'Payment for the next month only 5 days left',
                    'description' => 'Please complete the payment for the room '.$b->roomDetail->name.' on time. Ignore this message if you already paid.',
                    'order_id' => $b->id,
                    'image' => '',
                ];
                $fcm_token = $b->customer->cm_firebase_token;
                Helpers::reminderWa($b);
                Helpers::send_push_reminder_to_device($fcm_token, $data);
            }
        }
    }
}
