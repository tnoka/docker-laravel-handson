<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Product;
use App\User;


class DestroyProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        factory(Product::class)->create();
        $this->product = Product::first();

        $this->user = User::first();
    }
    /**
     * @test
     */
    public function DestroyProduct_本を削除することができるか()
    {

        $response = $this->actingAs($this->user)
        ->DELETE('products'. '/'. $this->product->id, [
            'id' => $this->product->id,
        ]);

        $response->assertStatus(302);

        $products = Product::all();

        // DBの件数が0であるか
        $this->assertEquals(0, $products->count());

    }
}
