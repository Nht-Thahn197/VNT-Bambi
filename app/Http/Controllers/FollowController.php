<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function store(User $user)
    {
        $currentUser = Auth::user();
        if (! $currentUser) {
            return redirect()->route('login');
        }

        if ($currentUser->id === $user->id) {
            return back()->withErrors([
                'follow' => 'Khong the tu theo doi chinh minh.',
            ]);
        }

        $currentUser->following()->syncWithoutDetaching([$user->id]);

        return back()->with('success', 'Da theo doi thanh vien.');
    }

    public function destroy(User $user)
    {
        $currentUser = Auth::user();
        if (! $currentUser) {
            return redirect()->route('login');
        }

        if ($currentUser->id === $user->id) {
            return back()->withErrors([
                'follow' => 'Khong the tu bo theo doi chinh minh.',
            ]);
        }

        $currentUser->following()->detach($user->id);

        return back()->with('success', 'Da bo theo doi thanh vien.');
    }
}
