<?php

namespace App\Http\Controllers\Web;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Apply;
use App\Model\Jobs;
use App\Model\Seller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class LokerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $job = Jobs::where('id', $id)->first();
    }

    public function order()
    {
        $check = Auth('customer')->user();
        if (!$check) {
            return redirect()->route('customer.auth.login');
        }
        $orders = Apply::with('job')->where('customer_id', auth('customer')->id())->orderBy('id', 'DESC')->get();
        // dd($orders);

        return view('web-views.users-profile.account-apply', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'pendidikan' => 'required',
            'keahlian' => 'required',
            'penghasilan' => 'required',
            'gaji' => 'required',
            // 'onsite' => 'required',
        ],
            [
                'name.required' => 'Mohon masukan nama kandidat',
                'email.required' => 'Mohon masukan email kandidat',
                'phone.required' => 'Mohon masukan telepon kandidat',
                'address.required' => 'Mohon masukan alamat kandidat',
                'pendidikan.required' => 'Mohon masukan pendidikan kandidat',
                'keahlian.required' => 'Mohon masukan keahlian kandidat',
                'penghasilan.required' => 'Mohon masukan penghasilan sebelumnya',
                'gaji.required' => 'Mohon masukan gaji yang diinginkan',
            ]);

        $on = 0;
        if ($request->onsite == 'on') {
            $on = 1;
        } else {
            $on = 0;
        }
        $pdf = $request->file('cv');
        if (!$pdf) {
            Toastr::warning('Mohon upload CV anda!!');

            return redirect()->back();
        } else {
            $cvName = ImageManager::upload('cv/', 'pdf', $pdf);
        }
        $apply = new Apply();
        $apply->name = $request->name;
        $apply->customer_id = auth('customer')->id();
        $apply->job_id = $request->id;
        $apply->email = $request->email;
        $apply->phone = $request->phone;
        $apply->address = $request->address;
        $apply->pendidikan = $request->pendidikan;
        $apply->keahlian = $request->keahlian;
        $apply->experience = $request->experience;
        $apply->penghasilan = $request->penghasilan;
        $apply->gaji = $request->gaji;
        $apply->job_status = 'applied';
        $apply->cv = $cvName;
        $apply->onsite = $on;
        $apply->save();
        Toastr::success('Lamaran anda berhasil dikirim, mohon tunggu info selanjutnya..');

        $jobss = Jobs::where('id', $request->id)->first('seller_id');
        if ($jobss['seller_id'] != 0) {
            $user = Seller::find($jobss['seller_id']);
            if ($user->cm_firebase_token !== null) {
                $fcm_token = $user->cm_firebase_token;

                $data = [
                        'title' => 'Lamaran masuk',
                        'description' => 'Pekerjaan anda dilamar oleh '.$request->name,
                        'order_id' => $jobss->name,
                        'image' => '',
                    ];
                Helpers::send_push_notif_to_device($fcm_token, $data);
            }
        }

        // if ($user->cm_firebase_token !== null) {
        //     $fcm_token = $user->cm_firebase_token;

        //     $data = [
        //             'title' => 'Payment Successfully',
        //             'description' => 'Your payment for room '.$room->name.' successfully',
        //             'order_id' => $order->id,
        //             'image' => '',
        //         ];
        //     Helpers::send_push_notif_to_device($fcm_token, $data);
        // }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
