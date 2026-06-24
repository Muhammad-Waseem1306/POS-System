<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RecoveryController extends Controller
{
    // Only accessible from localhost
    private function checkLocal()
    {
        $ip = request()->ip();
        if (!in_array($ip, ['127.0.0.1', '::1', 'localhost'])) {
            abort(403, 'Recovery page is only accessible locally.');
        }
    }

    public function index()
    {
        $this->checkLocal();
        $users = User::with('roles')->orderBy('id')->get();
        return view('recovery.index', compact('users'));
    }

    public function reset(Request $request, User $user)
    {
        $this->checkLocal();
        $request->validate(['password' => 'required|min:6']);
        $user->update(['password' => Hash::make($request->password)]);
        return back()->with('success', "Password reset for {$user->email}");
    }
}
