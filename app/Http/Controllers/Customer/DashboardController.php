<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use App\Repositories\Interfaces\SubscriptionRepositoryInterface;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $subscriptionRepository;
    protected $paymentRepository;

    public function __construct(
        SubscriptionRepositoryInterface $subscriptionRepository,
        PaymentRepositoryInterface $paymentRepository
    ) {
        $this->subscriptionRepository = $subscriptionRepository;
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * Show the customer dashboard.
     */
    public function index()
    {
        $customer = Customer::where('email', auth()->user()->email)->first();
        
        if (!$customer) {
            return redirect()->route('login')->with('error', 'Customer profile not found.');
        }

        $subscriptions = $this->subscriptionRepository->findByCustomer($customer->id);
        $payments = $this->paymentRepository->findByCustomer($customer->id);
        $currentSubscription = $customer->current_subscription;

        $stats = [
            'current_subscription' => $currentSubscription,
            'total_subscriptions' => $subscriptions->count(),
            'total_payments' => $payments->count(),
            'total_amount_paid' => $payments->sum('amount'),
        ];

        $recentPayments = $payments->take(5);
        $subscriptionHistory = $subscriptions->take(5);

        return view('customer.dashboard', compact('customer', 'stats', 'recentPayments', 'subscriptionHistory'));
    }
}