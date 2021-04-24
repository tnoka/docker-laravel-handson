<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\Database\Migrations;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Product;
use App\User;
use App\Comment;

class CommentApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }
    /**
     * @test
     */
    public function Comment_コメントを追加して削除できるか()
    {
        // コメントを追加できるか
        factory(Product::class)->create();
        $this->product = Product::first();

        $text = 'Sample';

        $response = $this->actingAs($this->user)
        ->POST('/comments', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'text' => $text
        ]);

        $comment = Comment::first();

        $response->assertStatus(302);

        // DBにコメントが１件登録されているか
        $this->assertEquals(1, $comment->count());


        // コメントを削除できるか
        $this->comment = Comment::first();

        $responses = $this->actingAs($this->user)
            ->DELETE('comments' . '/' . $this->comment->id, [
                'id' => $this->comment->id,
            ]);

        $responses->assertStatus(302);

        $comments = Comment::all();

        $this->assertEquals(0, $comments->count());

    }
}
