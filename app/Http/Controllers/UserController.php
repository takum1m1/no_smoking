<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' =>  ['required', 'email'],
            'password' => ['required'],
            'display_name' => ['required'],
            'daily_cigarettes' => ['required', 'integer'],
            'cigarette_pack_cost' => ['required', 'integer'],
        ]);
        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $user = new User();
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->saveOrFail();

        $userProfile = new UserProfile();
        $userProfile->user_id = $user->id;
        $userProfile->display_name = $request->display_name;
        $userProfile->daily_cigarettes = $request->daily_cigarettes;
        $userProfile->cigarette_pack_cost = $request->cigarette_pack_cost;
        $userProfile->saveOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token], 200);
    }
}
