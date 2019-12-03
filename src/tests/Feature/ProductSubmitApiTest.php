<?php

namespace Tests\Feature;

use App\Product;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\http\UploadedFile;
use Illuminate\support\facades\Schema;
use Illuminate\support\facades\Storage;
use Tests\TestCase;

class ProductSubmitApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp():void
    {    
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /**
     * @test
     */
    public function ProductSubmit_ファイルをアップロードできる()
    {
        // テスト用のストレージを使用
        Storage::fake('s3');

        $response = $this->actingAs($this->user) //認証状態にする
            ->json('POST', route('product.create'),[
                'product' => UploadedFile::fake()->image('product.jpg'), // ダミーファイルを作成して送信
            ]);

        $response->assertStatus(201);

        $product = Product::first();

        Storage::cloud()->assertExists($product->filename); //DBに挿入されたファイル名のファイルがストレージに保存されている
    }

    /**
     * @test
     */
    public function ProductSubmitDBError_DBエラーの場合は保存しない()
    {
        // 乱暴だがこれでDBエラーを起こす
        Schema::drop('products');

        Storage::fake('s3');

        $response = $this->actingAs($this->user)
            ->json('POST', route('product.create'), [
                'product' => UploadedFile::fake()->image('photo.jpg'),
            ]);

        // レスポンスが500(INTERNAL SERVER ERROR)であること
        $response->assertStatus(500);

        // ストレージにファイルが保存されていないこと
        $this->assertEquals(0, count(Storage::cloud()->files()));
    }

    /**
     * @test
     */
    public function ProductSubmitFileError_ファイル保存エラーの場合はDBへ挿入しない()
    {
        // ストレージをモックして保存時にエラーを起こさせる
        Storage::shouldReceive('cloud')
            ->once()
            ->andReturnNull();

        $response = $this->actingAs($this->user)
            ->json('POST', route('product.create'),[
                'product' => UploadedFile::fake()->image('product.jpg'),
            ]);

        // レスポンスがINTERNAL SERVER ERRORである
        $response->assertStatus(500);

        // データベースに何も挿入されていない
        $this->assertEmpty(Product::all());
    }
}
