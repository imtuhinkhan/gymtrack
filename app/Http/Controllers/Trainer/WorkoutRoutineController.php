<?php

namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\WorkoutRoutineRepositoryInterface;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use Illuminate\Http\Request;

class WorkoutRoutineController extends Controller
{
    protected $workoutRoutineRepository;
    protected $customerRepository;

    public function __construct(
        WorkoutRoutineRepositoryInterface $workoutRoutineRepository,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->workoutRoutineRepository = $workoutRoutineRepository;
        $this->customerRepository = $customerRepository;
    }

    /**
     * Display a listing of workout routines for trainer's members.
     */
    public function index()
    {
        // Get the trainer record for the authenticated user
        $trainer = \App\Models\Trainer::where('user_id', auth()->id())->first();
        
        if (!$trainer) {
            $members = collect();
            $workoutRoutines = collect();
        } else {
            $members = $this->customerRepository->findByTrainer($trainer->id);
            $workoutRoutines = $this->workoutRoutineRepository->findByTrainer($trainer->id)->load(['member.user', 'trainer']);
        }
        
        return view('trainer.workout-routines.index', compact('workoutRoutines', 'members'));
    }

    /**
     * Show the form for creating a new workout routine.
     */
    public function create()
    {
        // Get the trainer record for the authenticated user
        $trainer = \App\Models\Trainer::where('user_id', auth()->id())->first();
        
        if (!$trainer) {
            abort(404, 'Trainer record not found.');
        }
        
        $members = $this->customerRepository->findByTrainer($trainer->id);
        
        return view('trainer.workout-routines.create', compact('members'));
    }

    /**
     * Store a newly created workout routine.
     */
    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:customers,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_muscle_group' => 'required|in:full_body,upper_body,lower_body,core,cardio,flexibility',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'estimated_duration_minutes' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        // Get the trainer record for the authenticated user
        $trainer = \App\Models\Trainer::where('user_id', auth()->id())->first();
        
        if (!$trainer) {
            return redirect()->back()->withErrors(['error' => 'Trainer record not found.']);
        }

        // Verify the member belongs to this trainer
        $member = $this->customerRepository->findByTrainer($trainer->id)->where('id', $request->member_id)->first();
        
        if (!$member) {
            return redirect()->back()
                ->withErrors(['member_id' => 'Member not found or not assigned to you.'])
                ->withInput();
        }

        $data = $request->only([
            'member_id',
            'name', 
            'description',
            'target_muscle_group',
            'difficulty_level',
            'estimated_duration_minutes',
            'is_active'
        ]);
        $data['trainer_id'] = $trainer->id;
        $data['is_active'] = $request->has('is_active') ? (bool)$request->is_active : true;
        
        $workoutRoutine = $this->workoutRoutineRepository->create($data);

        return redirect()->route('trainer.workout-routines.index')
            ->with('success', 'Workout routine created successfully.');
    }

    /**
     * Display the specified workout routine.
     */
    public function show($id)
    {
        // Get the trainer record for the authenticated user
        $trainer = \App\Models\Trainer::where('user_id', auth()->id())->first();
        
        if (!$trainer) {
            abort(404, 'Trainer record not found.');
        }
        
        $workoutRoutine = $this->workoutRoutineRepository->find($id)->load(['member.user', 'trainer']);
        
        // Verify the workout routine belongs to this trainer
        if (!$workoutRoutine || $workoutRoutine->trainer_id !== $trainer->id) {
            abort(404, 'Workout routine not found or not assigned to you.');
        }
        
        return view('trainer.workout-routines.show', compact('workoutRoutine'));
    }

    /**
     * Show the form for editing the specified workout routine.
     */
    public function edit($id)
    {
        // Get the trainer record for the authenticated user
        $trainer = \App\Models\Trainer::where('user_id', auth()->id())->first();
        
        if (!$trainer) {
            abort(404, 'Trainer record not found.');
        }
        
        $workoutRoutine = $this->workoutRoutineRepository->find($id);
        
        // Verify the workout routine belongs to this trainer
        if (!$workoutRoutine || $workoutRoutine->trainer_id !== $trainer->id) {
            abort(404, 'Workout routine not found or not assigned to you.');
        }
        
        $members = $this->customerRepository->findByTrainer($trainer->id);
        
        return view('trainer.workout-routines.edit', compact('workoutRoutine', 'members'));
    }

    /**
     * Update the specified workout routine.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'member_id' => 'required|exists:customers,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_muscle_group' => 'required|in:full_body,upper_body,lower_body,core,cardio,flexibility',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'estimated_duration_minutes' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $trainer = \App\Models\Trainer::where('user_id', auth()->id())->first();
        
        if (!$trainer) {
            return redirect()->back()->withErrors(['error' => 'Trainer record not found.']);
        }
        
        $workoutRoutine = $this->workoutRoutineRepository->find($id);
        
        // Verify the workout routine belongs to this trainer
        if (!$workoutRoutine || $workoutRoutine->trainer_id !== $trainer->id) {
            abort(404, 'Workout routine not found or not assigned to you.');
        }

        // Verify the member belongs to this trainer
        $member = $this->customerRepository->findByTrainer($trainer->id)->where('id', $request->member_id)->first();
        
        if (!$member) {
            return redirect()->back()->withErrors(['member_id' => 'Member not found or not assigned to you.']);
        }

        $data = $request->only([
            'member_id',
            'name', 
            'description',
            'target_muscle_group',
            'difficulty_level',
            'estimated_duration_minutes',
            'is_active'
        ]);
        $data['trainer_id'] = $trainer->id;
        
        $this->workoutRoutineRepository->update($id, $data);

        return redirect()->route('trainer.workout-routines.index')
            ->with('success', 'Workout routine updated successfully.');
    }

    /**
     * Remove the specified workout routine.
     */
    public function destroy($id)
    {
        // Get the trainer record for the authenticated user
        $trainer = \App\Models\Trainer::where('user_id', auth()->id())->first();
        
        if (!$trainer) {
            abort(404, 'Trainer record not found.');
        }
        
        $workoutRoutine = $this->workoutRoutineRepository->find($id);
        
        // Verify the workout routine belongs to this trainer
        if (!$workoutRoutine || $workoutRoutine->trainer_id !== $trainer->id) {
            abort(404, 'Workout routine not found or not assigned to you.');
        }

        $this->workoutRoutineRepository->delete($id);

        return redirect()->route('trainer.workout-routines.index')
            ->with('success', 'Workout routine deleted successfully.');
    }
}
