<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'display_name' => ['required'],
            'daily_cigarettes' => ['required', 'integer'],
            'cigarette_pack_cost' => ['required', 'integer'],
        ]);
        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $userProfile = $user->userProfile;
        $userProfile->display_name = $request->display_name;
        $userProfile->daily_cigarettes = $request->daily_cigarettes;
        $userProfile->cigarette_pack_cost = $request->cigarette_pack_cost;
        $userProfile->saveOrFail();

        return response()->json(['message' => 'Profile updated successfully'], 200);
    }

    public function show($id)
    {
        $userProfile = UserProfile::find($id);

        // 禁煙日数の計算：禁煙開始日から現在までの日数
        $quitDate = CarbonImmutable::parse($userProfile->created_at);
        $now = CarbonImmutable::now();
        $quitDays = $quitDate->diffInDays($now);

        // 禁煙できた本数の計算：一日の喫煙本数 * 禁煙日数
        $quitCigarettes = $userProfile->daily_cigarettes * $quitDays;

        // 節約できた金額の計算：一箱の値段 * 禁煙できた本数 / 1箱あたりの本数
        $savedMoney = $userProfile->cigarette_pack_cost * $quitCigarettes / 20;

        // 伸びた寿命の計算：禁煙できた本数 * 10分（1本あたりの平均寿命損失）
        $extendedLife = $quitCigarettes * 10;

        return response()->json([
            'quitDays' => $quitDays,
            'quitCigarettes' => $quitCigarettes,
            'savedMoney' => $savedMoney,
            'extendedLife' => $extendedLife,
        ],200);
    }
}
