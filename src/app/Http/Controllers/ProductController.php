<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProduct;
use App\Product;
use illuminate\Support\Facades\Auth;
use illuminate\Support\Facades\DB;
use illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        // 認証
        $this->middleware('auth');
    }

    public function store(StoreProduct $request)
    {
        $data = $request->all();
        
    }



    public function create(StoreProduct $request)
    {
        // 拡張子を取得
        $extension = $request->product->extension();

        $product = new Product();

        $product->filename = $product->id . '.' . $extension;

        // S3にファイルを保存
        Storage::cloud()->putFileAs('', $request->product, $product->filename, 'public');

        // エラー時にファイルを削除を行うため
        DB::beginTransaction();

        try {
            Auth::user()->product()->save($product);
            DB::commit();
        } catch(\Exception $extension) {
            DB::rollBack();
            // DBとの不整合を避けるためアップロードしたファイルを削除
            Storage::cloud()->delete($product->filename);
            throw $extension;
        }
        return response($product, 201);
    }

}