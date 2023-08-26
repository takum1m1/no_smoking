<?php

namespace App\Http\Controllers;

use Carbon\CarbonImmutable;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' =>  ['required', 'email', 'max:256', 'unique:users'],
            'password' => ['required', 'min:6', 'max:128' ,'regex:/^(?=.*[a-zA-Z])(?=.*\d).+$/'],
            'display_name' => ['required', 'max:20'],
            'daily_cigarettes' => ['required', 'integer', 'min:1'],
            'cigarette_pack_cost' => ['required', 'integer', 'min:400', 'max:3000'],
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
        $userProfile->quit_date = CarbonImmutable::now();
        $userProfile->saveOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token], 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' =>  ['required', 'email', 'max:256'],
            'password' => ['required', 'min:6', 'max:128' ,'regex:/^(?=.*[a-zA-Z])(?=.*\d).+$/'],
        ]);
        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 400);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'User logged out successfully'], 200);
    }

    public function destroy()
    {
        $user = Auth::user();
        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
