<?php

namespace App\Http\Controllers\api\v2\seller;

use App\CPU\Helpers;
use function App\CPU\translate;
use App\Http\Controllers\Controller;
use App\Kost;
use Illuminate\Http\Request;

class KostController extends Controller
{
    public function list(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        return response()->json(Kost::where(['added_by' => 'seller', 'seller_id' => $seller['id']])->get(), 200);
    }

    public function create(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        return $request;
    }
}
