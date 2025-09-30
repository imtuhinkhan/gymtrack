<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\BranchRepositoryInterface;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Repositories\Interfaces\TrainerRepositoryInterface;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use App\Repositories\Interfaces\SubscriptionRepositoryInterface;
use App\Repositories\Interfaces\AttendanceRepositoryInterface;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportsController extends Controller
{
    protected $branchRepository;
    protected $memberRepository;
    protected $trainerRepository;
    protected $paymentRepository;
    protected $subscriptionRepository;
    protected $attendanceRepository;

    public function __construct(
        BranchRepositoryInterface $branchRepository,
        CustomerRepositoryInterface $memberRepository,
        TrainerRepositoryInterface $trainerRepository,
        PaymentRepositoryInterface $paymentRepository,
        SubscriptionRepositoryInterface $subscriptionRepository,
        AttendanceRepositoryInterface $attendanceRepository
    ) {
        $this->branchRepository = $branchRepository;
        $this->memberRepository = $memberRepository;
        $this->trainerRepository = $trainerRepository;
        $this->paymentRepository = $paymentRepository;
        $this->subscriptionRepository = $subscriptionRepository;
        $this->attendanceRepository = $attendanceRepository;
    }

    /**
     * Display the reports dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        $branchId = null;
        
        // If user is branch manager, filter by their branch
        if ($user->hasRole('branch_manager') && $user->branch_id) {
            $branchId = $user->branch_id;
        }
        
        // If user is branch manager, only show their branch
        if ($user->hasRole('branch_manager') && $user->branch_id) {
            $branches = $this->branchRepository->findByBranch($user->branch_id);
        } else {
            $branches = $this->branchRepository->all();
        }
        
        // Default date range (last 30 days)
        $startDate = now()->subDays(30)->toDateString();
        $endDate = now()->toDateString();

        $stats = [
            'total_revenue' => $this->paymentRepository->getRevenueByPeriod($startDate, $endDate),
            'total_members' => $this->memberRepository->findByBranch($branchId)->count(),
            'total_trainers' => $this->trainerRepository->findByBranch($branchId)->count(),
            'total_branches' => $branches->count(),
            'active_subscriptions' => $this->subscriptionRepository->findByBranch($branchId)->where('status', 'active')->count(),
            'monthly_revenue' => $this->paymentRepository->getRevenueByPeriod(
                now()->startOfMonth()->toDateString(),
                now()->endOfMonth()->toDateString()
            ),
        ];

        return view('admin.reports.index', compact('stats', 'branches', 'startDate', 'endDate'));
    }

    /**
     * Revenue report.
     */
    public function revenue(Request $request)
    {
        $user = auth()->user();
        $startDate = $request->input('start_date', now()->subDays(30)->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        $branchId = $request->input('branch_id');
        
        // If user is branch manager, force their branch_id
        if ($user->hasRole('branch_manager') && $user->branch_id) {
            $branchId = $user->branch_id;
        }

        $payments = $this->paymentRepository->findByBranch($branchId)
            ->where('status', 'paid')
            ->where('payment_date', '>=', $startDate)
            ->where('payment_date', '<=', $endDate);

        // If user is branch manager, only show their branch
        if ($user->hasRole('branch_manager') && $user->branch_id) {
            $branches = $this->branchRepository->findByBranch($user->branch_id);
        } else {
            $branches = $this->branchRepository->all();
        }

        // Group by date for chart
        $revenueByDate = $payments->groupBy(function ($payment) {
            return Carbon::parse($payment->payment_date)->format('Y-m-d');
        })->map(function ($group) {
            return $group->sum('amount');
        });

        // Group by branch for chart
        $revenueByBranch = $payments->groupBy('branch_id')->map(function ($group) {
            return $group->sum('amount');
        });

        $totalRevenue = $payments->sum('amount');


        return view('admin.reports.revenue', compact(
            'payments', 'branches', 'startDate', 'endDate', 'branchId',
            'revenueByDate', 'revenueByBranch', 'totalRevenue'
        ));
    }

    /**
     * Member growth report.
     */
    public function memberGrowth(Request $request)
    {
        $user = auth()->user();
        $startDate = $request->input('start_date', now()->subDays(90)->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        $branchId = $request->input('branch_id');
        
        // If user is branch manager, force their branch_id
        if ($user->hasRole('branch_manager') && $user->branch_id) {
            $branchId = $user->branch_id;
        }

        $members = $this->memberRepository->findByBranch($branchId);

        // If user is branch manager, only show their branch
        if ($user->hasRole('branch_manager') && $user->branch_id) {
            $branches = $this->branchRepository->findByBranch($user->branch_id);
        } else {
            $branches = $this->branchRepository->all();
        }

        // Group by month for chart
        $growthByMonth = $members->groupBy(function ($member) {
            return Carbon::parse($member->created_at)->format('Y-m');
        })->map(function ($group) {
            return $group->count();
        });

        $totalMembers = $members->count();
        
        // Calculate additional statistics
        $activeMembers = $members->where('status', 'active')->count();
        $inactiveMembers = $members->where('status', 'inactive')->count();
        $suspendedMembers = $members->where('status', 'suspended')->count();
        
        // Calculate growth rate (comparing current month to previous month)
        $currentMonth = now()->format('Y-m');
        $previousMonth = now()->subMonth()->format('Y-m');
        $currentMonthMembers = $growthByMonth[$currentMonth] ?? 0;
        $previousMonthMembers = $growthByMonth[$previousMonth] ?? 0;
        $growthRate = $previousMonthMembers > 0 ? 
            round((($currentMonthMembers - $previousMonthMembers) / $previousMonthMembers) * 100, 1) : 0;
        
        // New members this month
        $newThisMonth = $members->filter(function ($member) {
            return Carbon::parse($member->created_at)->isCurrentMonth();
        })->count();

        return view('admin.reports.member-growth', compact(
            'members', 'branches', 'startDate', 'endDate', 'branchId',
            'growthByMonth', 'totalMembers', 'activeMembers', 'inactiveMembers', 
            'suspendedMembers', 'growthRate', 'newThisMonth'
        ));
    }

    /**
     * Attendance report.
     */
    public function attendance(Request $request)
    {
        $user = auth()->user();
        $startDate = $request->input('start_date', now()->subDays(30)->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        $branchId = $request->input('branch_id');
        
        // If user is branch manager, force their branch_id
        if ($user->hasRole('branch_manager') && $user->branch_id) {
            $branchId = $user->branch_id;
        }

        $attendanceRecordsQuery = $this->attendanceRepository->findByBranch($branchId)
            ->where('date', '>=', $startDate)
            ->where('date', '<=', $endDate)
            ->sortByDesc('date');

        // If user is branch manager, only show their branch
        if ($user->hasRole('branch_manager') && $user->branch_id) {
            $branches = $this->branchRepository->findByBranch($user->branch_id);
        } else {
            $branches = $this->branchRepository->all();
        }

        // Group by date for chart (use all records for charts)
        $attendanceByDate = $attendanceRecordsQuery->groupBy('date')->map(function ($group) {
            return [
                'present' => $group->where('status', 'present')->count(),
                'absent' => $group->where('status', 'absent')->count(),
                'leave' => $group->where('status', 'leave')->count(),
            ];
        });

        // Group by type (customer/trainer) (use all records for charts)
        $attendanceByType = $attendanceRecordsQuery->groupBy('attendable_type')->map(function ($group) {
            return [
                'present' => $group->where('status', 'present')->count(),
                'absent' => $group->where('status', 'absent')->count(),
                'leave' => $group->where('status', 'leave')->count(),
            ];
        });

        $totalAttendance = $attendanceRecordsQuery->count();
        $presentCount = $attendanceRecordsQuery->where('status', 'present')->count();

        // Convert to paginated collection for table display
        $perPage = $request->get('per_page', 15);
        $perPage = min(max($perPage, 5), 100); // Between 5 and 100
        $currentPage = $request->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $items = $attendanceRecordsQuery->slice($offset, $perPage)->values();
        
        $attendanceRecords = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $attendanceRecordsQuery->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'pageName' => 'page']
        );

        return view('admin.reports.attendance', compact(
            'attendanceRecords', 'branches', 'startDate', 'endDate', 'branchId',
            'attendanceByDate', 'attendanceByType', 'totalAttendance', 'presentCount'
        ));
    }

    /**
     * Export report data.
     */
    public function export(Request $request)
    {
        $user = auth()->user();
        $type = $request->input('type');
        $startDate = $request->input('start_date', now()->subDays(30)->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        $branchId = $request->input('branch_id');
        
        // If user is branch manager, force their branch_id
        if ($user->hasRole('branch_manager') && $user->branch_id) {
            $branchId = $user->branch_id;
        }

        switch ($type) {
            case 'revenue':
                return $this->exportRevenue($startDate, $endDate, $branchId);
            case 'members':
                return $this->exportMembers($branchId);
            case 'attendance':
                return $this->exportAttendance($startDate, $endDate, $branchId);
            default:
                return back()->with('error', 'Invalid export type.');
        }
    }

    /**
     * Export revenue data.
     */
    private function exportRevenue($startDate, $endDate, $branchId)
    {
        $payments = $this->paymentRepository->findByBranch($branchId)
            ->where('status', 'paid')
            ->where('payment_date', '>=', $startDate)
            ->where('payment_date', '<=', $endDate);

        $filename = 'revenue_report_' . $startDate . '_to_' . $endDate . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, ['Customer Name', 'Email', 'Amount', 'Payment Date', 'Status', 'Payment Method', 'Branch']);
            
            // CSV Data
            foreach ($payments as $payment) {
                fputcsv($file, [
                    ($payment->customer->first_name ?? 'N/A') . ' ' . ($payment->customer->last_name ?? ''),
                    $payment->customer->email ?? 'N/A',
                    '$' . number_format($payment->amount, 2),
                    $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') : 'N/A',
                    ucfirst($payment->status),
                    ucfirst($payment->payment_method ?? 'N/A'),
                    $payment->branch->name ?? 'N/A'
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export members data.
     */
    private function exportMembers($branchId)
    {
        $members = $this->memberRepository->findByBranch($branchId);

        $filename = 'members_report_' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($members) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, ['Name', 'Email', 'Phone', 'Join Date', 'Status', 'Branch']);
            
            // CSV Data
            foreach ($members as $member) {
                fputcsv($file, [
                    $member->first_name . ' ' . $member->last_name,
                    $member->email,
                    $member->phone ?? 'N/A',
                    \Carbon\Carbon::parse($member->created_at)->format('Y-m-d'),
                    ucfirst($member->status),
                    $member->branch->name ?? 'N/A'
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export attendance data.
     */
    private function exportAttendance($startDate, $endDate, $branchId)
    {
        $attendanceRecords = $this->attendanceRepository->findByBranch($branchId)
            ->where('date', '>=', $startDate)
            ->where('date', '<=', $endDate);

        $filename = 'attendance_report_' . $startDate . '_to_' . $endDate . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($attendanceRecords) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, ['Name', 'Type', 'Date', 'Check In Time', 'Status', 'Branch']);
            
            // CSV Data
            foreach ($attendanceRecords as $record) {
                $name = 'N/A';
                if ($record->attendable) {
                    $name = ($record->attendable->first_name ?? 'N/A') . ' ' . ($record->attendable->last_name ?? '');
                }
                
                fputcsv($file, [
                    $name,
                    $record->attendable_type === 'App\\Models\\Customer' ? 'Member' : 'Trainer',
                    \Carbon\Carbon::parse($record->date)->format('Y-m-d'),
                    $record->check_in_time ? \Carbon\Carbon::parse($record->check_in_time)->format('H:i:s') : 'N/A',
                    ucfirst($record->status),
                    $record->branch->name ?? 'N/A'
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
