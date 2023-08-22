<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\AccountBan;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json(['message' => 'Users retrieved successfully', 'users' => $users], 200);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->notify(new AccountBan());

        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
