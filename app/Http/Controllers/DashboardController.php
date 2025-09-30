<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Handle dashboard routing based on user role
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to access the dashboard.');
        }

        // Redirect based on user role
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isBranchManager()) {
            return redirect()->route('branch.dashboard');
        } elseif ($user->isTrainer()) {
            return redirect()->route('trainer.dashboard');
        } elseif ($user->isReceptionist()) {
            return redirect()->route('branch.dashboard'); // Receptionist uses branch dashboard
        } elseif ($user->isCustomer()) {
            return redirect()->route('customer.dashboard');
        }

        // Fallback for users without proper roles
        return redirect()->route('login')->with('error', 'Your account does not have access to any dashboard.');
    }
}