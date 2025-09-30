<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Repositories\Interfaces\WorkoutRoutineRepositoryInterface;
use App\Repositories\Interfaces\AttendanceRepositoryInterface;

class CustomerDashboardController extends Controller
{
    protected $customerRepository;
    protected $workoutRoutineRepository;
    protected $attendanceRepository;

    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        WorkoutRoutineRepositoryInterface $workoutRoutineRepository,
        AttendanceRepositoryInterface $attendanceRepository
    ) {
        $this->customerRepository = $customerRepository;
        $this->workoutRoutineRepository = $workoutRoutineRepository;
        $this->attendanceRepository = $attendanceRepository;
    }

    /**
     * Display the customer's dashboard.
     */
    public function index()
    {
        // Get the customer record for the authenticated user
        $customer = \App\Models\Customer::where('user_id', auth()->id())->first();
        
        if (!$customer) {
            abort(404, 'Customer record not found.');
        }

        // Get customer's workout routines
        $workoutRoutines = $this->workoutRoutineRepository->findByMembers([$customer->id]);
        
        // Get customer's recent attendance
        $recentAttendance = $this->attendanceRepository->findByMembersAndDateRange(
            [$customer->id], 
            now()->subDays(30)->toDateString(), 
            now()->toDateString()
        )->take(10);

        // Get statistics
        $stats = [
            'total_workouts' => $workoutRoutines->count(),
            'active_workouts' => $workoutRoutines->where('is_active', true)->count(),
            'total_attendance' => $this->attendanceRepository->countByMembersAndDateRange(
                [$customer->id], 
                now()->subDays(30)->toDateString(), 
                now()->toDateString()
            ),
            'attendance_rate' => 0, // Will be calculated based on membership duration
        ];

        return view('customer.dashboard', compact('customer', 'workoutRoutines', 'recentAttendance', 'stats'));
    }

    /**
     * Display the customer's workout routines.
     */
    public function workouts()
    {
        // Get the customer record for the authenticated user
        $customer = \App\Models\Customer::where('user_id', auth()->id())->first();
        
        if (!$customer) {
            abort(404, 'Customer record not found.');
        }

        // Get customer's workout routines
        $workoutRoutines = $this->workoutRoutineRepository->findByMembers([$customer->id]);

        return view('customer.workouts', compact('customer', 'workoutRoutines'));
    }

    /**
     * Display workout routine details.
     */
    public function workoutDetails($routineId)
    {
        $customer = \App\Models\Customer::where('user_id', auth()->id())->first();
        
        if (!$customer) {
            abort(404, 'Customer record not found.');
        }

        // Get the workout routine and verify it belongs to this customer
        $workoutRoutine = $this->workoutRoutineRepository->find($routineId);
        
        if (!$workoutRoutine || $workoutRoutine->member_id !== $customer->id) {
            abort(404, 'Workout routine not found or not assigned to you.');
        }

        return view('customer.workout-details', compact('customer', 'workoutRoutine'));
    }

    /**
     * Display the customer's attendance history.
     */
    public function attendance()
    {
        // Get the customer record for the authenticated user
        $customer = \App\Models\Customer::where('user_id', auth()->id())->first();
        
        if (!$customer) {
            abort(404, 'Customer record not found.');
        }

        // Get customer's attendance records
        $attendanceRecords = $this->attendanceRepository->findByMembersAndDateRange(
            [$customer->id], 
            now()->subDays(90)->toDateString(), 
            now()->toDateString()
        );

        return view('customer.attendance', compact('customer', 'attendanceRecords'));
    }

    /**
     * Display the customer's membership information.
     */
    public function membership()
    {
        // Get the customer record for the authenticated user
        $customer = \App\Models\Customer::where('user_id', auth()->id())->first();
        
        if (!$customer) {
            abort(404, 'Customer record not found.');
        }

        // Get customer's subscription information
        $subscription = $customer->subscriptions()->where('status', 'active')->first();

        return view('customer.membership', compact('customer', 'subscription'));
    }

    /**
     * Handle customer clock in.
     */
    public function clockIn()
    {
        $customer = \App\Models\Customer::where('user_id', auth()->id())->first();
        
        if (!$customer) {
            return redirect()->back()->withErrors(['error' => 'Customer record not found.']);
        }

        // Check if already clocked in today
        $todayAttendance = \App\Models\Attendance::where('customer_id', $customer->id)
            ->whereDate('date', now()->toDateString())
            ->first();

        if ($todayAttendance && $todayAttendance->check_in_time) {
            return redirect()->back()->withErrors(['error' => 'You have already clocked in today.']);
        }

        // Create or update attendance record
        $data = [
            'customer_id' => $customer->id,
            'date' => now()->toDateString(),
            'check_in_time' => now()->format('H:i:s'),
            'status' => 'present',
            'attendable_type' => 'App\Models\Customer',
            'attendable_id' => $customer->id,
            'branch_id' => $customer->branch_id,
        ];

        if ($todayAttendance) {
            $this->attendanceRepository->update($todayAttendance->id, $data);
        } else {
            $this->attendanceRepository->create($data);
        }

        return redirect()->back()->with('success', 'Successfully clocked in!');
    }

    /**
     * Handle customer clock out.
     */
    public function clockOut()
    {
        \Log::info('Clock out method called');
        
        $customer = \App\Models\Customer::where('user_id', auth()->id())->first();
        
        if (!$customer) {
            \Log::error('Customer record not found for user: ' . auth()->id());
            return redirect()->back()->withErrors(['error' => 'Customer record not found.']);
        }

        \Log::info('Customer found: ' . $customer->id . ' - ' . $customer->first_name . ' ' . $customer->last_name);

        // Find today's attendance record
        $todayAttendance = \App\Models\Attendance::where('customer_id', $customer->id)
            ->whereDate('date', now()->toDateString())
            ->first();

        if (!$todayAttendance) {
            \Log::error('No attendance record found for customer: ' . $customer->id . ' on date: ' . now()->toDateString());
            return redirect()->back()->withErrors(['error' => 'No attendance record found for today.']);
        }

        \Log::info('Attendance found: ' . $todayAttendance->id . ' - Check in: ' . $todayAttendance->check_in_time . ' - Check out: ' . $todayAttendance->check_out_time);

        if (!$todayAttendance->check_in_time) {
            \Log::error('No check in time found for attendance: ' . $todayAttendance->id);
            return redirect()->back()->withErrors(['error' => 'You must clock in first before clocking out.']);
        }

        if ($todayAttendance->check_out_time) {
            \Log::error('Already checked out for attendance: ' . $todayAttendance->id);
            return redirect()->back()->withErrors(['error' => 'You have already clocked out today.']);
        }

        // Update attendance record with check out time
        $updateResult = $this->attendanceRepository->update($todayAttendance->id, [
            'check_out_time' => now()->format('H:i:s'),
        ]);

        \Log::info('Update result: ' . ($updateResult ? 'success' : 'failed'));

        if ($updateResult) {
            return redirect()->back()->with('success', 'Successfully clocked out!');
        } else {
            return redirect()->back()->withErrors(['error' => 'Failed to clock out. Please try again.']);
        }
    }

    /**
     * Display customer's payment history.
     */
    public function payments()
    {
        $customer = \App\Models\Customer::where('user_id', auth()->id())->first();
        
        if (!$customer) {
            abort(404, 'Customer record not found.');
        }

        // Get customer's payments
        $payments = $customer->payments()->orderBy('payment_date', 'desc')->get();

        return view('customer.payments', compact('customer', 'payments'));
    }

    /**
     * Download payment invoice.
     */
    public function downloadInvoice($paymentId)
    {
        $customer = \App\Models\Customer::where('user_id', auth()->id())->first();
        
        if (!$customer) {
            abort(404, 'Customer record not found.');
        }

        $payment = $customer->payments()->findOrFail($paymentId);

        // Generate PDF invoice
        $pdf = \PDF::loadView('customer.invoice', compact('payment', 'customer'));
        
        return $pdf->download('invoice-' . $payment->id . '.pdf');
    }
}