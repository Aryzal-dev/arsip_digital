<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();

        return view('users.index', compact('users'));
    }
    public function updateRole($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect('/users')
                ->with('error', 'Anda tidak dapat mengubah role akun yang sedang login.');
        }

        if ($user->role === 'admin') {
            $user->role = 'user';
        } else {
            $user->role = 'admin';
        }

        $user->save();

        return redirect('/users')
            ->with('success', 'Role user berhasil diubah.');
    }
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect('/users')
                ->with('error', 'Anda tidak dapat menghapus akun yang sedang login.');
        }

        $user->delete();

        return redirect('/users')
            ->with('success', 'User berhasil dihapus.');
    }   
}