<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\AttendanceRepositoryInterface;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Repositories\Interfaces\TrainerRepositoryInterface;
use App\Repositories\Interfaces\BranchRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    protected $attendanceRepository;
    protected $customerRepository;
    protected $trainerRepository;
    protected $branchRepository;

    public function __construct(
        AttendanceRepositoryInterface $attendanceRepository,
        CustomerRepositoryInterface $customerRepository,
        TrainerRepositoryInterface $trainerRepository,
        BranchRepositoryInterface $branchRepository
    ) {
        $this->attendanceRepository = $attendanceRepository;
        $this->customerRepository = $customerRepository;
        $this->trainerRepository = $trainerRepository;
        $this->branchRepository = $branchRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $user = auth()->user();
        $branchId = null;
        
        // If user is branch manager, filter by their branch
        if ($user->hasRole('branch_manager') && $user->branch_id) {
            $branchId = $user->branch_id;
        } else {
            $branchId = $user->branch_id; // Admin can see all branches or specific branch
        }

        try {
            // If user is branch manager, only show customers and trainers from their branch
            if ($user->hasRole('branch_manager') && $user->branch_id) {
                $customers = $this->customerRepository->findByBranch($user->branch_id);
                $trainers = $this->trainerRepository->findByBranch($user->branch_id);
            } else {
                $customers = $this->customerRepository->all();
                $trainers = $this->trainerRepository->all();
            }
            
            // If branchId is null, get all attendance records, otherwise filter by branch
            if ($branchId) {
                $attendanceRecords = $this->attendanceRepository->getAttendanceByDate($date, $branchId);
            } else {
                $attendanceRecords = $this->attendanceRepository->getAttendanceByDate($date);
            }

            $customerAttendance = $attendanceRecords->where('attendable_type', 'App\\Models\\Customer')
                ->keyBy('attendable_id');
            $trainerAttendance = $attendanceRecords->where('attendable_type', 'App\\Models\\Trainer')
                ->keyBy('attendable_id');

            return view('admin.attendance.index', compact('date', 'customers', 'trainers', 'customerAttendance', 'trainerAttendance'));
        } catch (\Exception $e) {
            // Fallback data if there are any issues
            $customers = collect();
            $trainers = collect();
            $customerAttendance = collect();
            $trainerAttendance = collect();

            return view('admin.attendance.index', compact('date', 'customers', 'trainers', 'customerAttendance', 'trainerAttendance'))
                ->with('error', 'Unable to load attendance data: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        
        // If user is branch manager, only show their branch
        if ($user->hasRole('branch_manager') && $user->branch_id) {
            $branches = $this->branchRepository->findByBranch($user->branch_id);
            $customers = $this->customerRepository->findByBranch($user->branch_id);
            $trainers = $this->trainerRepository->findByBranch($user->branch_id);
        } else {
            $branches = $this->branchRepository->all();
            $customers = $this->customerRepository->all();
            $trainers = $this->trainerRepository->all();
        }

        return view('admin.attendance.create', compact('branches', 'customers', 'trainers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'attendable_id' => 'required|integer',
            'attendable_type' => 'required|string|in:App\\Models\\Customer,App\\Models\\Trainer',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,leave',
        ]);

        $data = $request->only([
            'attendable_id', 'attendable_type', 'date', 'status'
        ]);
        
        $user = auth()->user();
        $branchId = $user->branch_id;
        
        // If user is branch manager, use their branch_id
        if ($user->hasRole('branch_manager') && $user->branch_id) {
            $branchId = $user->branch_id;
        }
        
        // If user doesn't have a branch assigned, use the first available branch or show error
        if (!$branchId) {
            $firstBranch = \App\Models\Branch::first();
            if (!$firstBranch) {
                return back()->with('error', 'No branches available. Please contact an administrator.');
            }
            $branchId = $firstBranch->id;
        }
        
        $data['branch_id'] = $branchId;

        $this->attendanceRepository->markAttendance($data);

        return back()->with('success', 'Attendance marked successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $attendance = $this->attendanceRepository->find((int) $id);
        if (!$attendance) {
            abort(404);
        }
        return view('admin.attendance.show', compact('attendance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $attendance = $this->attendanceRepository->find((int) $id);
        if (!$attendance) {
            abort(404);
        }

        $branches = $this->branchRepository->all();
        $customers = $this->customerRepository->all();
        $trainers = $this->trainerRepository->all();

        return view('admin.attendance.edit', compact('attendance', 'branches', 'customers', 'trainers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $attendance = $this->attendanceRepository->find((int) $id);
        if (!$attendance) {
            abort(404);
        }

        $validatedData = $request->validate([
            'attendable_type' => 'required|in:customer,trainer',
            'attendable_id' => 'required|integer',
            'branch_id' => 'required|exists:branches,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent',
        ]);

        // Convert type to model class
        $validatedData['attendable_type'] = $validatedData['attendable_type'] === 'customer' 
            ? 'App\Models\Customer' 
            : 'App\Models\Trainer';

        $this->attendanceRepository->update((int) $id, $validatedData);

        return redirect()->route('admin.attendance.index')->with('success', 'Attendance updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $attendance = $this->attendanceRepository->find((int) $id);
        if (!$attendance) {
            abort(404);
        }

        $this->attendanceRepository->delete((int) $id);

        return redirect()->route('admin.attendance.index')->with('success', 'Attendance deleted successfully.');
    }

    /**
     * Mark attendance for multiple people
     */
    public function markBulk(Request $request)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'branch_id' => 'required|exists:branches,id',
            'attendances' => 'required|array',
            'attendances.*.attendable_type' => 'required|in:customer,trainer',
            'attendances.*.attendable_id' => 'required|integer',
            'attendances.*.status' => 'required|in:present,absent',
        ]);

        $date = $validatedData['date'];
        $branchId = $validatedData['branch_id'];

        foreach ($validatedData['attendances'] as $attendanceData) {
            // Convert type to model class
            $attendableType = $attendanceData['attendable_type'] === 'customer' 
                ? 'App\Models\Customer' 
                : 'App\Models\Trainer';

            // Check if attendance already exists
            $existingAttendance = $this->attendanceRepository->all()
                ->where('attendable_type', $attendableType)
                ->where('attendable_id', $attendanceData['attendable_id'])
                ->where('date', $date)
                ->first();

            if (!$existingAttendance) {
                $this->attendanceRepository->create([
                    'attendable_type' => $attendableType,
                    'attendable_id' => $attendanceData['attendable_id'],
                    'branch_id' => $branchId,
                    'date' => $date,
                    'status' => $attendanceData['status'],
                ]);
            }
        }

        return redirect()->route('admin.attendance.index')->with('success', 'Bulk attendance recorded successfully.');
    }

    /**
     * Show manual attendance entry form
     */
    public function manualEntry(Request $request)
    {
        $branches = $this->branchRepository->all();
        $customers = $this->customerRepository->all();
        $trainers = $this->trainerRepository->all();
        $selectedBranch = $request->input('branch_id');
        $selectedDate = $request->input('date', now()->toDateString());

        // If no branch is selected, default to the first branch
        if (!$selectedBranch && $branches->isNotEmpty()) {
            $selectedBranch = $branches->first()->id;
        }

        // Filter customers and trainers by selected branch
        if ($selectedBranch) {
            $customers = $customers->filter(function ($customer) use ($selectedBranch) {
                return $customer->branch_id == $selectedBranch;
            });
            $trainers = $trainers->filter(function ($trainer) use ($selectedBranch) {
                return $trainer->branch_id == $selectedBranch;
            });
        }

        return view('admin.attendance.manual', compact('branches', 'customers', 'trainers', 'selectedBranch', 'selectedDate'));
    }

    /**
     * Store manual attendance entry
     */
    public function storeManual(Request $request)
    {

        $request->validate([
            'date' => 'required|date',
            'branch_id' => 'nullable|exists:branches,id',
            'entries' => 'required|array|min:1',
            'entries.*.attendable_type' => 'required|in:customer,trainer',
            'entries.*.attendable_id' => 'required|integer',
            'entries.*.check_in_time' => 'nullable|date_format:H:i',
            'entries.*.check_out_time' => 'nullable|date_format:H:i',
            'entries.*.notes' => 'nullable|string|max:500',
        ]);


        // Filter out empty entries (no check_in_time or check_out_time)
        $entries = collect($request->input('entries'))->filter(function($entry) {
            return !empty($entry['check_in_time']) || !empty($entry['check_out_time']) || !empty($entry['notes']);
        })->toArray();

        if (empty($entries)) {
            return redirect()->back()->with('error', 'Please fill in at least one field (check-in time, check-out time, or notes) for at least one person.');
        }

        $date = $request->input('date');
        $branchId = $request->input('branch_id');
        
        // If branch_id is empty (All Branches), determine branch from the first entry
        if (empty($branchId)) {
            $firstEntry = collect($entries)->first();
            if ($firstEntry) {
                if ($firstEntry['attendable_type'] === 'customer') {
                    $customer = \App\Models\Customer::find($firstEntry['attendable_id']);
                    $branchId = $customer ? $customer->branch_id : null;
                } else {
                    $trainer = \App\Models\Trainer::find($firstEntry['attendable_id']);
                    $branchId = $trainer ? $trainer->branch_id : null;
                }
            }
            
            // If still no branch_id, default to first branch
            if (!$branchId) {
                $firstBranch = \App\Models\Branch::first();
                $branchId = $firstBranch ? $firstBranch->id : null;
            }
        }
        
        if (!$branchId) {
            return redirect()->back()->with('error', 'No valid branch found. Please select a specific branch.');
        }
        $successCount = 0;
        $errorCount = 0;

        foreach ($entries as $entry) {
            try {
                // Check if attendance already exists for this date
                $attendableType = $entry['attendable_type'] === 'customer' ? 'App\\Models\\Customer' : 'App\\Models\\Trainer';
                $existingAttendance = \App\Models\Attendance::where('date', $date)
                    ->where('attendable_type', $attendableType)
                    ->where('attendable_id', $entry['attendable_id'])
                    ->first();

                $attendanceData = [
                    'attendable_type' => $entry['attendable_type'] === 'customer' ? 'App\\Models\\Customer' : 'App\\Models\\Trainer',
                    'attendable_id' => $entry['attendable_id'],
                    'branch_id' => $branchId,
                    'date' => $date,
                    'check_in_time' => $entry['check_in_time'] ?? null,
                    'check_out_time' => $entry['check_out_time'] ?? null,
                    'status' => 'present', // Default status
                    'notes' => $entry['notes'] ?? null,
                ];

                if ($existingAttendance) {
                    // Update existing attendance
                    $existingAttendance->update($attendanceData);
                } else {
                    // Create new attendance
                    \App\Models\Attendance::create($attendanceData);
                }
                
                $successCount++;
            } catch (\Exception $e) {
                $errorCount++;
                \Log::error('Manual attendance entry error: ' . $e->getMessage());
            }
        }

        $message = "Attendance recorded successfully for {$successCount} entries.";
        if ($errorCount > 0) {
            $message .= " {$errorCount} entries failed to process.";
        }

        return redirect()->route('admin.attendance.index', ['date' => $date])
            ->with('success', $message);
    }

    /**
     * Get attendance statistics
     */
    public function statistics(Request $request)
    {
        $today = now()->toDateString();
        
        // Today's statistics
        $todayAttendance = $this->attendanceRepository->getAttendanceByDate($today);
        $todayStats = [
            'present' => $todayAttendance->where('status', 'present')->count(),
            'absent' => $todayAttendance->where('status', 'absent')->count(),
            'leave' => $todayAttendance->where('status', 'leave')->count(),
        ];

        // Branch statistics
        $branchStats = [];
        $branches = \App\Models\Branch::all();
        foreach ($branches as $branch) {
            $branchAttendance = $this->attendanceRepository->getAttendanceByDate($today, $branch->id);
            $branchStats[$branch->id] = [
                'name' => $branch->name,
                'present' => $branchAttendance->where('status', 'present')->count(),
                'absent' => $branchAttendance->where('status', 'absent')->count(),
                'leave' => $branchAttendance->where('status', 'leave')->count(),
            ];
        }

        // Recent attendance records (last 7 days) with pagination
        $recentAttendanceQuery = $this->attendanceRepository->all()
            ->where('date', '>=', now()->subDays(7)->toDateString())
            ->sortByDesc('date');
        
        // Convert to paginated collection
        $perPage = min(max($request->get('per_page', 15), 5), 100); // Between 5 and 100
        $currentPage = $request->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $items = $recentAttendanceQuery->slice($offset, $perPage)->values();
        
        $recentAttendance = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $recentAttendanceQuery->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'pageName' => 'page']
        );

        // Weekly attendance trend data for chart (last 7 days)
        $weeklyTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $dayAttendance = $this->attendanceRepository->getAttendanceByDate($date);
            
            $weeklyTrend[] = [
                'date' => $date,
                'day' => now()->subDays($i)->format('D'),
                'present' => $dayAttendance->where('status', 'present')->count(),
                'absent' => $dayAttendance->where('status', 'absent')->count(),
                'leave' => $dayAttendance->where('status', 'leave')->count(),
            ];
        }

        return view('admin.attendance.statistics', compact('todayStats', 'branchStats', 'recentAttendance', 'weeklyTrend'));
    }
}
