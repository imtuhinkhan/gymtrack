<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Repositories\Interfaces\TrainerRepositoryInterface;
use App\Repositories\Interfaces\AttendanceRepositoryInterface;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BranchDashboardController extends Controller
{
    protected $customerRepository;
    protected $trainerRepository;
    protected $attendanceRepository;
    protected $paymentRepository;

    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        TrainerRepositoryInterface $trainerRepository,
        AttendanceRepositoryInterface $attendanceRepository,
        PaymentRepositoryInterface $paymentRepository
    ) {
        $this->customerRepository = $customerRepository;
        $this->trainerRepository = $trainerRepository;
        $this->attendanceRepository = $attendanceRepository;
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * Display the branch manager's dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        
        if (!$user) {
            abort(401, 'User not authenticated.');
        }
        
        // Get the branch manager's branch
        $branch = \App\Models\Branch::find($user->branch_id);
        
        if (!$branch) {
            \Log::error('Branch not found for user: ' . $user->id . ' with branch_id: ' . $user->branch_id);
            abort(404, 'Branch not found. Please contact administrator.');
        }

        \Log::info('Branch dashboard accessed by user: ' . $user->name . ' for branch: ' . $branch->name);

        // Get branch-specific data
        $customers = $this->customerRepository->findByBranch($branch->id);
        $trainers = $this->trainerRepository->findByBranch($branch->id);
        
        // Get today's attendance for the branch
        $todayAttendance = $this->attendanceRepository->findByBranchAndDate($branch->id, now()->toDateString());
        
        // Get recent payments for the branch
        $recentPayments = $this->paymentRepository->findByBranch($branch->id)->take(5);
        
        // Calculate statistics
        $stats = [
            'total_customers' => $customers->count(),
            'total_trainers' => $trainers->count(),
            'today_attendance' => $todayAttendance->count(),
            'monthly_revenue' => $this->paymentRepository->findByBranch($branch->id)
                ->where('status', 'paid')
                ->whereBetween('payment_date', [now()->startOfMonth(), now()->endOfMonth()])
                ->sum('amount'),
            'active_subscriptions' => $customers->filter(function($customer) {
                return $customer->subscriptions()->where('status', 'active')->exists();
            })->count(),
        ];

        return view('branch.dashboard', compact('branch', 'customers', 'trainers', 'todayAttendance', 'recentPayments', 'stats'));
    }
}
