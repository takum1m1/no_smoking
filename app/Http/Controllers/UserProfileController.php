<?php

namespace App\Http\Controllers;

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
}
