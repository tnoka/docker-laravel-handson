<?php

namespace Tests\Feature;

use App\Product;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductListApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function ProductList_正しい構造のJSONを返却する()
    {
        // 5つのデータを生成
        factory(Product::class, 5)->create();

        $response = $this->json('GET', route('product.index'));

        // データ作成日の降順で取得
        $products = Product::with(['owner', 'favorite'])->orderBy(Product::CREATED_AT, 'desc')->get();

        // data項目の期待値
        $expected_data = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'title' => $product->title,
                'author' => $product->author,
                'recommend' => $product->recommend,
                'text' => $product->text,
                'created_at' => $product->created_at,
                'url' => $product->url,
                'favorite_count' => 0,
                'favorited_by_user' => false,
                'owner' => ['name' => $product->owner->name],
            ];
        })
        ->all();

        $response->assertStatus(200)
        ->assertJsonCount(5, 'data');
    }
}
