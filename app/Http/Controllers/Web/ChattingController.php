<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Model\Chatting;
use Auth;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChattingController extends Controller
{
    public function chat_with_seller(Request $request)
    {
        // $last_chat = Chatting::with('seller_info')->where('user_id', auth('customer')->id())
        //     ->orderBy('created_at', 'DESC')
        //     ->first();
        $last_chat = Chatting::with(['kost'])->where('user_id', auth('customer')->id())
            ->orderBy('created_at', 'DESC')
            ->first();

        if (isset($last_chat)) {
            $chattings = Chatting::join('kosts', 'kosts.id', '=', 'chattings.shop_id')
                ->select('chattings.*', 'kosts.name', 'kosts.images')
                ->where('chattings.user_id', auth('customer')->id())
                ->where('shop_id', $last_chat->shop_id)
                ->get();

            $unique_shops = Chatting::join('kosts', 'kosts.id', '=', 'chattings.shop_id')
                ->select('chattings.*', 'kosts.name', 'kosts.images')
                ->where('chattings.user_id', auth('customer')->id())
                ->orderBy('chattings.created_at', 'desc')
                ->get()
                ->unique('shop_id');

            return view('web-views.users-profile.profile.chat-with-seller', compact('chattings', 'unique_shops', 'last_chat'));
        }

        return view('web-views.users-profile.profile.chat-with-seller');
    }

    public function messages_delete($id)
    {
        $user = auth('customer')->id();
        $chat = Chatting::where(['user_id' => $user, 'seller_id' => $id])->get();
        foreach ($chat as $c) {
            $c->delete();
        }
        Toastr::success('Chat berhasil dihapus');

        return redirect()->back();
    }

    public function messages(Request $request)
    {
        $last_chat = Chatting::where('user_id', auth('customer')->id())
            ->where('shop_id', $request->shop_id)
            ->orderBy('created_at', 'DESC')
            ->first();

        // dd($last_chat);

        $last_chat->seen_by_customer = 0;
        $last_chat->save();

        $shops = Chatting::join('kosts', 'kosts.id', '=', 'chattings.shop_id')
            ->select('chattings.*', 'kosts.name', 'kosts.images')
            ->where('user_id', auth('customer')->id())
            ->where('chattings.shop_id', json_decode($request->shop_id))
            ->orderBy('created_at', 'ASC')
            ->get();

        return response()->json($shops);
    }

    public function messages_store(Request $request)
    {
        if ($request->message == '') {
            Toastr::warning('Type Something!');

            return response()->json('type something!');
        } else {
            $message = $request->message;
            DB::table('chattings')->insert([
                'user_id' => auth('customer')->id(),
                'shop_id' => $request->shop_id,
                'seller_id' => $request->seller_id,

                'message' => $request->message,
                'sent_by_customer' => 1,
                'seen_by_customer' => 0,
                'created_at' => now(),
            ]);

            return response()->json($message);
        }
    }
}
