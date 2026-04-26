<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->profile ?? new Profile();

        return view('mypage.profile', compact('user', 'profile'));
    }

    public function update(ProfileRequest $request)
    {
        $user = Auth::user();

        $profile = $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'post_code' => $request->post_code,
                'address' => $request->address,
                'building' => $request->building,
            ]
        );

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images','public');
            $profile->image = $path;
            $profile->save();
        }

        $user->name = $request->name;
        $user->save();

        return redirect()->route('products.index', ['tab' => 'mylist']);

    }
}
