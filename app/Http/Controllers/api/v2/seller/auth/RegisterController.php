<?php

namespace App\Http\Controllers\api\v2\seller\auth;

use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Seller;
use App\Model\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function registerSeller(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|unique:sellers',
            'password' => 'required|min:8',
        ]);

        DB::transaction(function ($r) use ($request) {
            $seller = new Seller();
            $seller->f_name = $request->f_name;
            $seller->l_name = $request->l_name;
            $seller->country = 'ID';
            $seller->phone = $request->phone;
            $seller->email = $request->email;
            $seller->image = ImageManager::upload('seller/', 'png', $request->file('image'));
            $seller->password = bcrypt($request->password);
            $seller->status = 'pending';
            $seller->cm_firebase_token = $request->cm_firebase_token;
            $seller->save();

            $shop = new Shop();
            $shop->seller_id = $seller->id;
            $shop->country = 'ID';
            $shop->save();

            DB::table('seller_wallets')->insert([
                'seller_id' => $seller['id'],
                'withdrawn' => 0,
                'commission_given' => 0,
                'total_earning' => 0,
                'pending_withdraw' => 0,
                'delivery_charge_earned' => 0,
                'collected_cash' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        return response()->json('Pemilik kos berhasil didaftarkan');
    }
}
