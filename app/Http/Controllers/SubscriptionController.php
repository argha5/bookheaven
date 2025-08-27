<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::where('status', 'active')
            ->orderBy('price')
            ->get();

        return view('subscriptions.index', compact('plans'));
    }

    public function subscribe(Request $request, $planId)
    {
        $this->middleware('auth');
        
        $plan = SubscriptionPlan::findOrFail($planId);
        $user = Auth::user();

        // Check if user already has an active subscription
        $activeSubscription = Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if ($activeSubscription) {
            return redirect()->back()
                ->with('error_message', 'You already have an active subscription.');
        }

        // Create new subscription
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'start_date' => now(),
            'end_date' => now()->addDays($plan->duration_days),
            'status' => 'active',
            'amount' => $plan->price
        ]);

        return redirect()->route('profile.subscriptions')
            ->with('success_message', 'Subscription activated successfully!');
    }
}
