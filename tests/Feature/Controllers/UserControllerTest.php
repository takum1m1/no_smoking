<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ユーザー登録
     */
    public function testRegister()
    {
        $data = [
            'email' => 'a@example.com',
            'password' => 'Password123',
            'display_name' => 'hogehoge',
            'daily_cigarettes' => 10,
            'cigarette_pack_cost' => 500
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['email' => $data['email']]);
        $this->assertDatabaseHas('user_profiles', ['display_name' => $data['display_name']]);
    }

    /**
     * ユーザー登録失敗 (emailが既に存在する)
     */
    public function testRegister02()
    {
        User::factory()->create([
            'email' => 'hogehoge@hoge.hoge',
            'password' => Hash::make('Password123')
        ]);

        $data = [
            'email' => 'hogehoge@hoge.hoge',
            'password' => 'Password123',
            'display_name' => 'hogehoge',
            'daily_cigarettes' => 10,
            'cigarette_pack_cost' => 500
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(400);
    }

    /**
     * ユーザー登録失敗 (passwordが6文字未満)
     */
    public function testRegister03()
    {
        $data = [
            'email' => 'hogehoge@hoge.hoge',
            'password' => 'Pass1',
            'display_name' => 'hogehoge',
            'daily_cigarettes' => 10,
            'cigarette_pack_cost' => 500
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(400);
    }

    /**
     * ログイン
     */
    public function testLogin01()
    {
        User::factory()->create([
            'email' => 'hogehoge@hoge.hoge',
            'password' => Hash::make('Password123')
        ]);

        $data = [
            'email' => 'hogehoge@hoge.hoge',
            'password' => 'Password123'
        ];

        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(200);
    }

    /**
     * ログイン失敗 (emailが存在しない)
     */
    public function testLogin02()
    {
        User::factory()->create([
            'email' => 'hogehoge@hoge.hoge',
            'password' => Hash::make('Password123')
        ]);

        $data = [
            'email' => 'hugahuga@huga.huga',
            'password' => 'Password123'
        ];

        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(400);
    }

    /**
     * ログイン失敗 (passwordが間違っている)
     */
    public function testLogin03()
    {
        User::factory()->create([
            'email' => 'hogehoge@hoge.hoge',
            'password' => Hash::make('Password123')
        ]);

        $data = [
            'email' => 'hogehoge@hoge.hoge',
            'password' => 'hogehoge123'
        ];

        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(400);
    }
}
