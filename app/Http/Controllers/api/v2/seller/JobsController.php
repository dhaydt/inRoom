<?php

namespace App\Http\Controllers\api\v2\seller;

use App\CPU\Helpers;
use function App\CPU\translate;
use App\Http\Controllers\Controller;
use App\Model\Apply;
use App\Model\Jobs;
use Illuminate\Http\Request;

class JobsController extends Controller
{
    public function list(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];

            $jobs = Jobs::with('seller')->where(['added_by' => 'seller', 'seller_id' => $seller->id])->orderBy('created_at', 'desc')->get();

            return response()->json($jobs, 200);
        } else {
            return response()->json(
                [
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }
    }

    public function applied(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        $apply = Apply::with('customer', 'job')->whereHas('job', function ($q) use ($seller) {
            $q->where(['added_by' => 'seller', 'seller_id' => $seller->id]);
        })->get();

        return response()->json($apply, 200);
    }
}
