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
            'display_name' => ['required', 'max:20'],
            'daily_cigarettes' => ['required', 'integer', 'min:1'],
            'cigarette_pack_cost' => ['required', 'integer', 'min:400', 'max:3000'],
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

        $quitDate = $userProfile->quit_date;
        $now = CarbonImmutable::now();

        $quitDays = $quitDate->diffInDays($now);
        $quitCigarettes = $userProfile->daily_cigarettes * $quitDays;
        $savedMoney = $userProfile->cigarette_pack_cost * $quitCigarettes / 20;
        $extendedLife = $quitCigarettes * 10;

        return response()->json([
            'display_name' => $userProfile->display_name,
            'quitDays' => $quitDays,
            'quitCigarettes' => $quitCigarettes,
            'savedMoney' => $savedMoney,
            'extendedLife' => $extendedLife,
        ],200);
    }
}
