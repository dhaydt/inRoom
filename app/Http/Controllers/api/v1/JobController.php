<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Apply;
use App\Model\Jobs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    public function jobList()
    {
        $data = Jobs::orderBy('created_at', 'desc')->get();

        return response()->json($data);
    }

    public function lokerDetail($id)
    {
        $data = Jobs::find($id);

        return $data;
    }

    public function apply(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'address' => 'required',
                'pendidikan' => 'required',
                'keahlian' => 'required',
                'penghasilan_sebelumnya' => 'required',
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
                    'penghasilan_sebelumnya.required' => 'Mohon masukan penghasilan sebelumnya',
                    'gaji.required' => 'Mohon masukan gaji yang diinginkan',
                ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $on = 0;
        if ($request->onsite == 'on') {
            $on = 1;
        } else {
            $on = 0;
        }
        $pdf = $request->file('cv');
        if (!$pdf) {
            return response()->json(['errors' => 'Mohon upload CV anda'], 401);
        } else {
            $cvName = ImageManager::upload('cv/', 'pdf', $pdf);
        }
        $apply = new Apply();
        $apply->name = $request->name;
        $apply->customer_id = $request->user()->id;
        $apply->job_id = $request->loker_id;
        $apply->email = $request->email;
        $apply->phone = $request->phone;
        $apply->address = $request->address;
        $apply->pendidikan = $request->pendidikan;
        $apply->keahlian = $request->keahlian;
        $apply->experience = $request->experience;
        $apply->penghasilan = $request->penghasilan_sebelumnya;
        $apply->gaji = $request->gaji;
        $apply->job_status = 'applied';
        $apply->cv = $cvName;
        $apply->onsite = $on;
        $apply->save();

        return response()->json(['success' => 'Lamaran berhasil dikirim, mohon tunggu notifikasi selanjutya'], 200);
    }
}
