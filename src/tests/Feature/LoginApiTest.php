<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class LoginApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // テストユーザーを作成
        $this->user = factory(User::class)->create();
    }

    /**
     * @test
     */
    public function login_登録済みのユーザーを認証して返却()
    {
        // loginにPOST送信された結果を$responseに入れる
        $response = $this->json('POST', route('login'), [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        $response
            ->assertStatus(200)
            ->assertJson(['name' => $this->user->name]); //レスポンスが指定したデータを持っているか

        $this->assertAuthenticatedAs($this->user); // 指定したユーザーが認証されているか

    }
}
