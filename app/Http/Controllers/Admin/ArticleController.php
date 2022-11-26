<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = BusinessSetting::where('type', 'article_footer')->first();
        $panduan = BusinessSetting::where('type', 'how_to_use')->first();
        if (!$panduan) {
            $new = new BusinessSetting();
            $new->type = 'how_to_use';
            $new->value = '';
            $new->save();

            $panduan = $new;
        }

        return view('admin-views.article.article', compact('data', 'panduan'));
    }

    public function panduan(Request $request)
    {
        $article = BusinessSetting::where('type', 'how_to_use')->first();
        $article->value = $request['panduan'];
        if ($request->ajax()) {
            return response()->json([], 200);
        } else {
            $article->save();
            Toastr::success('Cara menggunakan aplikasi berhasil diubah.');

            return redirect()->back();
        }
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $article = BusinessSetting::where('type', 'article_footer')->first();
        $article->value = $request['description'];
        if ($request->ajax()) {
            return response()->json([], 200);
        } else {
            $article->save();
            Toastr::success('Article berhasil diubah.');

            return redirect()->back();
        }
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
