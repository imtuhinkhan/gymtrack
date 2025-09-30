<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Repositories\Interfaces\WorkoutRoutineRepositoryInterface;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $customerRepository;
    protected $workoutRoutineRepository;

    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        WorkoutRoutineRepositoryInterface $workoutRoutineRepository
    ) {
        $this->customerRepository = $customerRepository;
        $this->workoutRoutineRepository = $workoutRoutineRepository;
    }

    /**
     * Show the trainer dashboard.
     */
    public function index()
    {
        // Get the trainer record for the authenticated user
        $trainer = \App\Models\Trainer::where('user_id', auth()->id())->first();
        
        if (!$trainer) {
            // If no trainer record found, return empty data
            $customers = collect();
            $workoutRoutines = collect();
            $stats = [
                'total_customers' => 0,
                'active_customers' => 0,
                'expired_customers' => 0,
                'total_workouts' => 0,
                'active_workouts' => 0,
            ];
            $recentCustomers = collect();
            $recentWorkouts = collect();
        } else {
            // Get trainer's customers using the actual trainer ID
            $customers = $this->customerRepository->findByTrainer($trainer->id);
            
            // Get trainer's workout routines
            $workoutRoutines = $this->workoutRoutineRepository->findByTrainer($trainer->id);

            $stats = [
                'total_customers' => $customers->count(),
                'active_customers' => $customers->where('status', 'active')->count(),
                'expired_customers' => $customers->where('status', 'expired')->count(),
                'total_workouts' => $workoutRoutines->count(),
                'active_workouts' => $workoutRoutines->where('is_active', true)->count(),
            ];

            $recentCustomers = $customers->take(5);
            $recentWorkouts = $workoutRoutines->take(5);
        }

        return view('trainer.dashboard', compact('stats', 'recentCustomers', 'recentWorkouts'));
    }
}