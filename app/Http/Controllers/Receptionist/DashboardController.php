<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use App\Repositories\Interfaces\SubscriptionRepositoryInterface;
use App\Repositories\Interfaces\TrainerRepositoryInterface;
use App\Repositories\Interfaces\AttendanceRepositoryInterface;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $customerRepository;
    protected $trainerRepository;
    protected $subscriptionRepository;
    protected $paymentRepository;
    protected $attendanceRepository;

    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        TrainerRepositoryInterface $trainerRepository,
        SubscriptionRepositoryInterface $subscriptionRepository,
        PaymentRepositoryInterface $paymentRepository,
        AttendanceRepositoryInterface $attendanceRepository
    ) {
        $this->customerRepository = $customerRepository;
        $this->trainerRepository = $trainerRepository;
        $this->subscriptionRepository = $subscriptionRepository;
        $this->paymentRepository = $paymentRepository;
        $this->attendanceRepository = $attendanceRepository;
    }

    /**
     * Show the receptionist dashboard.
     */
    public function index()
    {
        $branchId = auth()->user()->branch_id;

        // If user doesn't have a branch assigned, redirect to admin dashboard or show error
        if (!$branchId) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'You are not assigned to any branch. Please contact an administrator.');
        }

        try {
            $stats = [
                'total_customers' => $this->customerRepository->findByBranch($branchId)->count(),
                'total_trainers' => $this->trainerRepository->findByBranch($branchId)->count(),
                'active_subscriptions' => $this->subscriptionRepository->findByBranch($branchId)
                    ->where('status', 'active')
                    ->where('end_date', '>=', now()->toDateString())
                    ->count(),
                'monthly_revenue' => $this->paymentRepository->getRevenueByBranch(
                    $branchId,
                    now()->startOfMonth()->toDateString(),
                    now()->endOfMonth()->toDateString()
                ),
                'pending_payments' => $this->paymentRepository->findByBranch($branchId)
                    ->where('status', 'pending')
                    ->count(),
                'overdue_payments' => $this->paymentRepository->findByBranch($branchId)
                    ->where('status', 'pending')
                    ->where('due_date', '<', now()->toDateString())
                    ->count(),
                'today_attendance' => $this->attendanceRepository->getAttendanceByDate(now()->toDateString(), $branchId)
                    ->where('status', 'present')
                    ->count(),
            ];

            $recentCustomers = $this->customerRepository->findByBranch($branchId)->take(5);
            $expiringSubscriptions = $this->subscriptionRepository->findByBranch($branchId)
                ->where('end_date', '<=', now()->addDays(7)->toDateString())
                ->where('end_date', '>=', now()->toDateString())
                ->take(5);
            $recentPayments = $this->paymentRepository->findByBranch($branchId)->take(5);

            return view('receptionist.dashboard', compact('stats', 'recentCustomers', 'expiringSubscriptions', 'recentPayments'));
        } catch (\Exception $e) {
            // Fallback data if there are any issues
            $stats = [
                'total_customers' => 0,
                'total_trainers' => 0,
                'active_subscriptions' => 0,
                'monthly_revenue' => 0,
                'pending_payments' => 0,
                'overdue_payments' => 0,
                'today_attendance' => 0,
            ];

            $recentCustomers = collect();
            $expiringSubscriptions = collect();
            $recentPayments = collect();

            return view('receptionist.dashboard', compact('stats', 'recentCustomers', 'expiringSubscriptions', 'recentPayments'))
                ->with('error', 'Unable to load dashboard data: ' . $e->getMessage());
        }
    }
}