<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Repositories\Interfaces\AttendanceRepositoryInterface;
use App\Repositories\Interfaces\WorkoutRoutineRepositoryInterface;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    protected $customerRepository;
    protected $attendanceRepository;
    protected $workoutRoutineRepository;

    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        AttendanceRepositoryInterface $attendanceRepository,
        WorkoutRoutineRepositoryInterface $workoutRoutineRepository
    ) {
        $this->customerRepository = $customerRepository;
        $this->attendanceRepository = $attendanceRepository;
        $this->workoutRoutineRepository = $workoutRoutineRepository;
    }

    /**
     * Display trainer's reports dashboard.
     */
    public function index(Request $request)
    {
        // Get the trainer record for the authenticated user
        $trainer = \App\Models\Trainer::where('user_id', auth()->id())->first();
        
        if (!$trainer) {
            $members = collect();
            $stats = [
                'total_members' => 0,
                'active_members' => 0,
                'total_workouts' => 0,
                'total_attendance' => 0,
            ];
            $recentAttendance = collect();
            $workoutRoutines = collect();
        } else {
            $members = $this->customerRepository->findByTrainer($trainer->id);
            $memberIds = $members->pluck('id')->toArray();
            
            // Get date range
            $startDate = $request->input('start_date', now()->subDays(30)->toDateString());
            $endDate = $request->input('end_date', now()->toDateString());
            
            // Get statistics
            $stats = [
                'total_members' => $members->count(),
                'active_members' => $members->where('status', 'active')->count(),
                'total_workouts' => $this->workoutRoutineRepository->countByMembers($memberIds),
                'total_attendance' => $this->attendanceRepository->countByMembersAndDateRange($memberIds, $startDate, $endDate),
            ];
            
            // Get recent attendance
            $recentAttendance = $this->attendanceRepository->findByMembersAndDateRange($memberIds, $startDate, $endDate)->take(10);
            
            // Get workout routines
            $workoutRoutines = $this->workoutRoutineRepository->findByTrainer($trainer->id)->take(5);
        }
        
        return view('trainer.reports.index', compact('stats', 'recentAttendance', 'workoutRoutines', 'members', 'startDate', 'endDate'));
    }
}
