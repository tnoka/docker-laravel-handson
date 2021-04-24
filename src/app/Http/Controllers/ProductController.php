<?php

namespace App\Http\Controllers;

use App\Product;
use App\Comment;
use App\Follow;
use App\Favorite;
use App\Http\Requests\StoreProduct;
use illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function __construct()
    {
    // 認証と認証不要の設定
        $this->middleware('auth')->except(['index', 'indexRank', 'show', 'search']);
    }

    // 本の一覧（新着順）
    public function index()
    {
        $products = Product::with(['owner', 'favorite'])->orderBy(Product::CREATED_AT, 'desc')->paginate();

        return $products;
    }

    // 本の一覧（ランキング）
    public function indexRank()
    {
        $products = Product::with(['owner', 'favorite'])->withCount('favorite')->orderBy('favorite_count', 'desc')->paginate();

        return $products;
    }

    // 本の一覧（フィード）
    public function indexFeed(Product $product, Follow $follow)
    {
        $user = auth()->user();
        // ログインしているユーザーがフォローしているユーザーのIDを取得
        $follow_ids = $follow->followIds($user->id);
        $following_ids = $follow_ids->pluck('followed_id')->toArray();

        $products = $product->getFeed($user->id, $following_ids); 

        return $products;
    }

    // 本の検索機能
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        if (!empty($keyword)) {
            $products = Product::where('title', 'like', '%' . $keyword . '%')->orWhere('author', 'like', '%' . $keyword . '%')->orderBy(Product::CREATED_AT, 'desc')->paginate();
        } else {
            $products = Product::orderBy(Product::CREATED_AT, 'desc')->paginate();
        }

        return view('products.search', [
            'keyword' => $keyword,
            'products' => $products,
        ]);
    }

    // 本の詳細
    public function show(Product $product, Comment $comment)
    {
        $user = auth()->user();
        $product = $product->getProduct($product->id);
        $comments = $comment->getComment($product->id);

        return view('products.show', [
            'user' => $user,
            'product' => $product,
            'comments' => $comments
        ]);
    }

    // 本の投稿
    public function store(StoreProduct $request, product $product)
    {
        $user = auth()->user();
        $extension = $request->product_image->extension();
        $product = new Product();
        $product->product_image = $product->id . '.' . $extension;
        // S3に保存
        Storage::cloud()->putFileAs('', $request->product_image, $product->product_image, 'public');
        // エラー処理（DBとS3の整合性）
        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['product_image'] = $product->product_image;
            $product->productStore($user->id, $data);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            // DBとの不整合を避けるためアップロードしたファイルを削除
            Storage::cloud()->delete($product->product_image);
            throw $exception;
        }

        return response($product, 201);

    }

    // 本の編集機能
    public function update(Request $request, Product $product)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'title' => 'required|max:100',
            'author' => 'required|max:100',
            'recommend' => 'required|string',
            'text' => 'required|string|max:750',                           
        ]);

        $validator->validate();
        $product->productUpdate($product->id, $data);
        $user = auth()->user();
        return redirect('users/'. $user->id);
    }

    // 本の編集画面
    public function edit(Product $product)
    {
        $user = auth()->user();
        $products = $product->getEditProduct($user->id, $product->id);

        if(!isset($products)) {
            return redirect('/users');
        }
        return view('products.edit', [
            'user' => $user,
            'products' => $products
        ]);
    }

    // 本の削除機能
    public function destroy(Product $product)
    {
        $user = auth()->user();
        $product->productDestroy($user->id, $product->id);

        return redirect('users/'. $user->id);
    }

    // いいね（読みたい本）
    public function favorite(string $id)
    {
        $product = Product::where('id', $id)->with('favorite')->first();

        if(! $product) {
            abort(404);
        }

        $product->favorite()->detach(Auth::user()->id);
        $product->favorite()->attach(Auth::user()->id);

        return["product_id" => $id];
    }

    // いいね解除
    public function unFavorite(string $id)
    {
        $product = Product::where('id', $id)->with('favorite')->first();

        if(! $product) {
            abort(404);
        }

        $product->favorite()->detach(Auth::user()->id);

        return ["product_id" => $id];
    }

}