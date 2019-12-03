<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;


class UserApiTest extends TestCase
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
    public function User_ログイン中のユーザーを返却()
    {
        //認証状態にする
        $response = $this->actingAs($this->user)->json('GET', route('user')); 

        $response->assertStatus(200)
                ->assertJson(['name' => $this->user->name,]);
    }

    /**
     * @test
     */
    public function User_ログインされていない場合は空文字を返却()
    {
        $response = $this->json('GET', route('user'));

        $response->assertStatus(200);
        $this->assertEquals("", $response->content()); //引数が等しいか(空文字になっているか)
    }
}
