<?php

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
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
     * ログイン
     */
    // public function testLogin()
    // {

    // }
}
