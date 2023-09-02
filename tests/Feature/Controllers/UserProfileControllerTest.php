<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * プロフィールアップデート
     */
    public function testUpdate()
    {
        $user = User::factory()->create();

        UserProfile::factory()->create([
            'user_id' => $user->id
        ]);

        $token = $user->createToken('test_token')->plainTextToken;

        $data = [
            'display_name' => 'hogehoge',
            'daily_cigarettes' => 10,
            'cigarette_pack_cost' => 500
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->patchJson('/api/profile/update', $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('user_profiles', ['display_name' => $data['display_name']]);
    }

    /**
     * プロフィールアップデート失敗 (未ログイン)
     */
    public function testUpdate02()
    {
        $data = [
            'display_name' => 'hogehoge',
            'daily_cigarettes' => 10,
            'cigarette_pack_cost' => 500
        ];

        $response = $this->patchJson('/api/profile/update', $data);

        $response->assertStatus(401);
    }

    /**
     * プロフィールアップデート失敗 (バリデーションエラー)
     */
    public function testUpdate03()
    {
        $user = User::factory()->create();

        UserProfile::factory()->create([
            'user_id' => $user->id
        ]);

        $token = $user->createToken('test_token')->plainTextToken;

        $data = [
            'display_name' => 'hogehoge',
            'daily_cigarettes' => 10,
            'cigarette_pack_cost' => 'aaa'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->patchJson('/api/profile/update', $data);

        $response->assertStatus(400);
    }
}
