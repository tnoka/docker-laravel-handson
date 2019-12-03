<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class LogoutApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp():void
    {
        parent::setUp();

        // テストユーザー作成
        $this->user = factory(User::class)->create();                       
    }
    /**
     * @test
     */
    public function logout_認証済のユーザーをログアウトさせる()
    {
        $response = $this->actingAs($this->user) //認証状態にする
                        ->json('POST', route('logout')); //認証状態でログアウトを実施する

        $response->assertStatus(200); //正常にアクセスできた場合は200
        $this->assertGuest(); //ユーザーが認証されていない、つまりログアウトしているか
    }
}
