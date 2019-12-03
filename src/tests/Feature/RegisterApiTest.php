<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;


class RegisterApiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function register_新しいユーザーを作成して返却()
    {
        //テスト用アカウントのデータを作成
        $data = [
            'name' => 'userName',
            'email' => 'test@email.com',
            'password' => '12345678',
            'password_confirmation' => '12345678',
        ];

        // ルートregisterに$dataがPOST送信された場合の処理を$responseに入れる
        $response = $this->json('POST', route('register'), $data);
        
        // ユーザークラスからユーザーデータの１番最初（ID昇順）を配列で抜き出す
        $user = User::first();
        // $data['name']と$user->nameが一致しているかチェック
        $this->assertSame($data['name'], $user->name);
        
        $response
            ->assertStatus(201)
            ->assertJson(['name' => $user->name]); //レスポンスが指定したJSONデータを持っているか
    }
}
