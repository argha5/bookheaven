<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->only(['name', 'email', 'phone', 'address']);

        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile_image_url) {
                Storage::delete('public/' . $user->profile_image_url);
            }
            
            $imagePath = $request->file('profile_image')->store('profile_images', 'public');
            $data['profile_image_url'] = $imagePath;
        }

        $user->update($data);

        return redirect()->route('profile.show')
            ->with('success_message', 'Profile updated successfully!');
    }

    public function orders()
    {
        $orders = Order::with(['items.book'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('profile.orders', compact('orders'));
    }

    public function subscriptions()
    {
        $subscriptions = Subscription::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('profile.subscriptions', compact('subscriptions'));
    }
}
