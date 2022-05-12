<?php

namespace App\Http\Controllers\api\v2\seller;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use function App\CPU\translate;
use App\Http\Controllers\Controller;
use App\Model\Apply;
use App\Model\Jobs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\Province;

class JobsController extends Controller
{
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

    public function apply_status(Request $request)
    {
        $order = Apply::where('id', $request['job_id'])->first();

        $order->job_status = $request['job_status'];
        $order->save();

        return response()->json([
            'applied status successfully changed!!',
        ]);
    }

    public function create(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json(
                [
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'company_name' => 'required',
            'province_id' => 'required',
            'city_id' => 'required',
            'district_name' => 'required',
            'logo' => 'required',
            'penempatan' => 'required',
            'keahlian' => 'required',
            'pendidikan' => 'required',
            'status' => 'required',
            'deskripsi' => 'required',
            'gaji' => 'required',
            'satuan' => 'required',
        ], [
            'name.required' => 'Nama pekerjaan diperlukan!',
            'company_name.required' => 'Nama perusahaan diperlukan!',
            'province_id.required' => 'Mohon provinsi nya di isi!',
            'city_id.required' => 'Mohon kota nya di isi!',
            'district_name.required' => 'Mohon kecamatannya nya di isi!',
            'deskripsi.required' => 'Mohon isi deskripsi pekerjaan!',
            'status.required' => 'Mohon isi status pekerjaan!',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $auth = $seller->id;

        $img = ImageManager::upload('jobs/', 'png', $request->file('logo'));
        $prov = Province::where('id', $request['province_id'])->first();
        $city = City::where('id', $request['city_id'])->first();

        if (!isset($prov) || !isset($city)) {
            return response()->json(['message' => 'Provinsi not found']);
        }

        $kost = new Jobs();
        $kost->company_name = $request['company_name'];
        $kost->province = $prov->name;
        $kost->city = $city->name;
        $kost->district = $request['district_name'];
        $kost->note_address = $request['noteAddress'];
        $kost->penempatan = $request['penempatan'];
        $kost->onsite = $request['onsite'];
        $kost->name = $request['name'];
        $kost->keahlian = $request['keahlian'];
        $kost->pendidikan = $request['pendidikan'];
        $kost->status_employe = $request['status'];
        $kost->description = $request['deskripsi'];
        $kost->gaji = $request['gaji'];
        $kost->hide_gaji = $request['hide'];
        $kost->satuan_gaji = $request['satuan'];
        $kost->logo = $img;
        $kost->seller_id = $auth;
        $kost->added_by = 'seller';

        $kost->penanggung_jwb = $request['nama_penanggung_jawab'];
        $kost->hp_penanggung_jwb = $request['hp_penanggung_jawab'];
        $kost->email_penanggung_jwb = $request['email_penanggung_jawab'];
        $kost->expire = $request['expire'];
        $kost->status = 0;
        $kost->request_status = 1;
        $kost->slug = Str::slug($request['name'], '-').'-'.Str::random(6);

        $kost->save();

        return response()->json('Job successfully created', 200);

        return $request;
    }

    public function update(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        $product = Jobs::where(['id' => $request->job_id, 'added_by' => 'seller', 'seller_id' => $seller->id])->first();

        if (!isset($product)) {
            return response()->json(['job not found'], 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'company_name' => 'required',
            'province_id' => 'required',
            'city_id' => 'required',
            'district_name' => 'required',
            'penempatan' => 'required',
            'keahlian' => 'required',
            'pendidikan' => 'required',
            'status' => 'required',
            'deskripsi' => 'required',
            'gaji' => 'required',
            'satuan' => 'required',
        ], [
            'name.required' => 'Nama pekerjaan diperlukan!',
            'company_name.required' => 'Nama perusahaan diperlukan!',
            'province_id.required' => 'Mohon provinsi nya di isi!',
            'city_id.required' => 'Mohon kota nya di isi!',
            'district_name.required' => 'Mohon kecamatannya nya di isi!',
            'deskripsi.required' => 'Mohon isi deskripsi pekerjaan!',
            'status.required' => 'Mohon isi status pekerjaan!',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $prov = Province::where('id', $request['province_id'])->first();
        $city = City::where('id', $request['city_id'])->first();

        $product->company_name = $request['company_name'];
        $product->province = $prov->name;
        $product->city = $city->name;
        $product->district = $request['district_name'];
        $product->note_address = $request['noteAddress'];
        $product->penempatan = $request['penempatan'];
        $product->onsite = $request['onsite'];
        $product->name = $request['name'];
        $product->keahlian = $request['keahlian'];
        $product->pendidikan = $request['pendidikan'];
        $product->status_employe = $request['status'];
        $product->description = $request['deskripsi'];
        $product->gaji = $request['gaji'];
        $product->hide_gaji = $request['hide'];
        $product->satuan_gaji = $request['satuan'];
        // $product->logo = $img;
        // $product->seller_id = $auth;
        // $product->added_by = 'admin';

        $product->penanggung_jwb = $request['nama_penanggung_jawab'];
        $product->hp_penanggung_jwb = $request['hp_penanggung_jawab'];
        $product->email_penanggung_jwb = $request['email_penanggung_jawab'];
        $product->expire = $request['expire'];

        $product->logo = $request->file('logo') ? ImageManager::update('jobs/', $product->logo, 'png', $request->file('logo')) : $product->logo;
        $product->save();

        return response()->json('job successfully updated', 200);
    }

    public function destroy(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        $product = Jobs::find($request->job_id);
        if (!isset($product)) {
            return back();
        }

        if (isset($product->logo)) {
            ImageManager::delete('/jobs/'.$product->logo);
        }

        $product->delete();

        return response()->json('Job successfully deleted', 200);
    }
}
