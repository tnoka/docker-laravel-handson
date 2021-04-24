<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Product;
use App\User;

class FavoriteApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp():void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();

        factory(Product::class)->create();
        $this->product = Product::first();
    }
    /**
     * @test
     */
    public function addFavorite_読みたい本を追加できる()
    {
        $response = $this->actingAs($this->user)
            ->json('PUT', route('product.favorite',[
                'id' => $this->product->id,
            ]));

            // DBに１件保存されているか
            $this->assertEquals(1, $this->product->favorite()->count());
    }

    /**
     * @test
     */
    public function doubleFavorite_2回押しても1個しかつかない()
    {
        $param = ['id' => $this->product->id];
        $this->actingAs($this->user)->json('PUT', route('product.favorite', $param));
        $this->actingAs($this->user)->json('PUT', route('product.favorite', $param));

        $this->assertEquals(1, $this->product->favorite()->count());

    }

    /**
     * @test
     */
    public function cancelFavorite_読みたい本を解除できる()
    {

        $this->product->favorite()->attach($this->user->id);

        $response = $this->actingAs($this->user)
            ->json('DELETE', route('product.favorite', [
                'id' => $this->product->id,
            ]));

        $response->assertStatus(200)
                ->assertJsonFragment([ // 合致するか
                    'product_id' => $this->product->id,
                ]);

        $this->assertEquals(0, $this->product->favorite()->count());
    }
}
