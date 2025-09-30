<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\BranchRepositoryInterface;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use App\Repositories\Interfaces\SubscriptionRepositoryInterface;
use App\Repositories\Interfaces\TrainerRepositoryInterface;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $branchRepository;
    protected $customerRepository;
    protected $trainerRepository;
    protected $subscriptionRepository;
    protected $paymentRepository;

    public function __construct(
        BranchRepositoryInterface $branchRepository,
        CustomerRepositoryInterface $customerRepository,
        TrainerRepositoryInterface $trainerRepository,
        SubscriptionRepositoryInterface $subscriptionRepository,
        PaymentRepositoryInterface $paymentRepository
    ) {
        $this->branchRepository = $branchRepository;
        $this->customerRepository = $customerRepository;
        $this->trainerRepository = $trainerRepository;
        $this->subscriptionRepository = $subscriptionRepository;
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * Show the admin dashboard.
     */
    public function index()
    {
        try {
            $stats = [
                'total_branches' => $this->branchRepository->all()->where('is_active', true)->count(),
                'total_customers' => $this->customerRepository->all()->where('status', true)->count(),
                'total_trainers' => $this->trainerRepository->all()->where('status', true)->count(),
                'active_subscriptions' => $this->subscriptionRepository->all()->where('status', 'active')->count(),
                'monthly_revenue' => $this->paymentRepository->all()->where('status', 'paid')->sum('amount') ?? 0,
                'pending_payments' => $this->paymentRepository->all()->where('status', 'pending')->count(),
                'overdue_payments' => $this->paymentRepository->all()->where('status', 'overdue')->count(),
            ];

            $recentCustomers = $this->customerRepository->all()->take(5);
            $expiringSubscriptions = $this->subscriptionRepository->all()->where('status', 'active')->take(5);
            $recentPayments = $this->paymentRepository->all()->where('status', 'paid')->take(5);

            return view('admin.dashboard', compact('stats', 'recentCustomers', 'expiringSubscriptions', 'recentPayments'));
        } catch (\Exception $e) {
            // Fallback data if there are any issues
            $stats = [
                'total_branches' => 0,
                'total_customers' => 0,
                'total_trainers' => 0,
                'active_subscriptions' => 0,
                'monthly_revenue' => 0,
                'pending_payments' => 0,
                'overdue_payments' => 0,
            ];

            $recentCustomers = collect();
            $expiringSubscriptions = collect();
            $recentPayments = collect();

            return view('admin.dashboard', compact('stats', 'recentCustomers', 'expiringSubscriptions', 'recentPayments'));
        }
    }
}