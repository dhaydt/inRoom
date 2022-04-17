<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Model\Jobs;

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
}
