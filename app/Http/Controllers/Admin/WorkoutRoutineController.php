<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\WorkoutRoutineRepositoryInterface;
use App\Repositories\Interfaces\TrainerRepositoryInterface;
use Illuminate\Http\Request;

class WorkoutRoutineController extends Controller
{
    protected $workoutRoutineRepository;
    protected $trainerRepository;

    public function __construct(
        WorkoutRoutineRepositoryInterface $workoutRoutineRepository,
        TrainerRepositoryInterface $trainerRepository
    ) {
        $this->workoutRoutineRepository = $workoutRoutineRepository;
        $this->trainerRepository = $trainerRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $this->workoutRoutineRepository->all();

        // Filter by trainer
        if ($request->filled('trainer_id')) {
            $query = $query->where('trainer_id', $request->trainer_id);
        }

        // Filter by status (is_active)
        if ($request->filled('status')) {
            $query = $query->where('is_active', $request->status == '1');
        }

        $sortedQuery = $query->sortByDesc('created_at');
        
        // Convert to paginated collection
        $perPage = 15;
        $currentPage = $request->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $items = $sortedQuery->slice($offset, $perPage)->values();
        
        $workoutRoutines = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $sortedQuery->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'pageName' => 'page']
        );
        $trainers = $this->trainerRepository->all();

        return view('admin.workout-routines.index', compact('workoutRoutines', 'trainers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $trainers = $this->trainerRepository->all();
        $members = $this->customerRepository->all();

        return view('admin.workout-routines.create', compact('trainers', 'members'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'estimated_duration_minutes' => 'required|integer|min:1',
            'target_muscle_group' => 'required|in:full_body,upper_body,lower_body,core,cardio,flexibility',
            'trainer_id' => 'required|exists:trainers,id',
            'member_id' => 'nullable|exists:customers,id',
            'is_active' => 'boolean',
        ]);

        // Set default values
        $validatedData['is_active'] = $validatedData['is_active'] ?? true;

        // Create the workout routine
        $workoutRoutine = $this->workoutRoutineRepository->create($validatedData);

        return redirect()->route('admin.workout-routines.index')->with('success', 'Workout routine created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $workoutRoutine = $this->workoutRoutineRepository->find((int) $id);
        if (!$workoutRoutine) {
            abort(404);
        }
        return view('admin.workout-routines.show', compact('workoutRoutine'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $workoutRoutine = $this->workoutRoutineRepository->find((int) $id);
        if (!$workoutRoutine) {
            abort(404);
        }

        $trainers = $this->trainerRepository->all();

        return view('admin.workout-routines.edit', compact('workoutRoutine', 'trainers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $workoutRoutine = $this->workoutRoutineRepository->find((int) $id);
        if (!$workoutRoutine) {
            abort(404);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'difficulty_level' => 'required|in:beginner,intermediate,advanced',
            'estimated_duration_minutes' => 'required|integer|min:1',
            'target_muscle_group' => 'required|in:full_body,upper_body,lower_body,core,cardio,flexibility',
            'trainer_id' => 'required|exists:trainers,id',
            'is_active' => 'boolean',
        ]);

        // Set default values
        $validatedData['is_active'] = $validatedData['is_active'] ?? true;

        // Update the workout routine
        $this->workoutRoutineRepository->update((int) $id, $validatedData);

        return redirect()->route('admin.workout-routines.index')->with('success', 'Workout routine updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $workoutRoutine = $this->workoutRoutineRepository->find((int) $id);
        if (!$workoutRoutine) {
            abort(404);
        }

        $this->workoutRoutineRepository->delete((int) $id);

        return redirect()->route('admin.workout-routines.index')->with('success', 'Workout routine deleted successfully.');
    }

    /**
     * Duplicate a workout routine
     */
    public function duplicate(string $id)
    {
        $workoutRoutine = $this->workoutRoutineRepository->find((int) $id);
        if (!$workoutRoutine) {
            abort(404);
        }

        $newWorkoutRoutine = $workoutRoutine->replicate();
        $newWorkoutRoutine->name = $workoutRoutine->name . ' (Copy)';
        $newWorkoutRoutine->is_active = false;
        $newWorkoutRoutine->save();

        // Duplicate exercises
        foreach ($workoutRoutine->exercises as $exercise) {
            $newExercise = $exercise->replicate();
            $newExercise->workout_routine_id = $newWorkoutRoutine->id;
            $newExercise->save();
        }

        return redirect()->route('admin.workout-routines.edit', $newWorkoutRoutine->id)->with('success', 'Workout routine duplicated successfully.');
    }

    /**
     * Activate/Deactivate workout routine
     */
    public function toggleStatus(string $id)
    {
        $workoutRoutine = $this->workoutRoutineRepository->find((int) $id);
        if (!$workoutRoutine) {
            abort(404);
        }

        $this->workoutRoutineRepository->update((int) $id, [
            'is_active' => !$workoutRoutine->is_active
        ]);

        $status = $workoutRoutine->is_active ? 'deactivated' : 'activated';
        return redirect()->back()->with('success', "Workout routine {$status} successfully.");
    }
}
