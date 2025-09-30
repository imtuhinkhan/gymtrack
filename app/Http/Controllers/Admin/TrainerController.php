<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\TrainerRepositoryInterface;
use App\Repositories\Interfaces\BranchRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class TrainerController extends Controller
{
    protected $trainerRepository;
    protected $branchRepository;

    public function __construct(
        TrainerRepositoryInterface $trainerRepository,
        BranchRepositoryInterface $branchRepository
    ) {
        $this->trainerRepository = $trainerRepository;
        $this->branchRepository = $branchRepository;
    }

    /**
     * Display a listing of the trainers.
     */
    public function index(Request $request)
    {
        try {
            $user = auth()->user();
            $branchId = null;
            
            // If user is branch manager, filter by their branch
            if ($user->hasRole('branch_manager') && $user->branch_id) {
                $branchId = $user->branch_id;
            }
            
            $query = $this->trainerRepository->findByBranch($branchId);

            if ($request->has('status') && $request->status != '') {
                $query = $query->where('status', $request->status);
            }

            if ($request->has('branch_id') && $request->branch_id != '') {
                $query = $query->where('branch_id', $request->branch_id);
            }

            if ($request->has('search') && $request->search != '') {
                $query = $this->trainerRepository->search($request->search);
            }

            // Convert to paginated collection
            $perPage = 15;
            $currentPage = $request->get('page', 1);
            $offset = ($currentPage - 1) * $perPage;
            $items = $query->slice($offset, $perPage)->values();
            
            $trainers = new \Illuminate\Pagination\LengthAwarePaginator(
                $items,
                $query->count(),
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'pageName' => 'page']
            );

            // If user is branch manager, only show their branch
            if ($user->hasRole('branch_manager') && $user->branch_id) {
                $branches = $this->branchRepository->findByBranch($user->branch_id);
            } else {
                $branches = $this->branchRepository->all();
            }

            return view('admin.trainers.index', compact('trainers', 'branches'));
        } catch (\Exception $e) {
            // Fallback data if there are any issues
            $trainers = collect();
            $branches = collect();

            return view('admin.trainers.index', compact('trainers', 'branches'))
                ->with('error', 'Unable to load data: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new trainer.
     */
    public function create()
    {
        $user = auth()->user();
        
        // If user is branch manager, only show their branch
        if ($user->hasRole('branch_manager') && $user->branch_id) {
            $branches = $this->branchRepository->findByBranch($user->branch_id);
        } else {
            $branches = $this->branchRepository->all();
        }
        
        return view('admin.trainers.create', compact('branches'));
    }

    /**
     * Store a newly created trainer in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        // If user is branch manager, force their branch_id
        if ($user->hasRole('branch_manager') && $user->branch_id) {
            $request->merge(['branch_id' => $user->branch_id]);
        }
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:trainers,email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'branch_id' => 'required|exists:branches,id',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'specializations' => 'nullable|string|max:1000',
            'certifications' => 'nullable|string|max:1000',
            'experience_years' => 'nullable|integer|min:0|max:50',
            'hourly_rate' => 'nullable|numeric|min:0',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'hire_date' => 'nullable|date',
            'bio' => 'nullable|string|max:1000',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only([
            'first_name',
            'last_name',
            'email',
            'phone',
            'branch_id',
            'date_of_birth',
            'gender',
            'specializations',
            'certifications',
            'experience_years',
            'hourly_rate',
            'address',
            'city',
            'state',
            'postal_code',
            'country',
            'hire_date',
            'bio',
        ]);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('profile_images', 'public');
            $data['profile_image'] = $imagePath;
        }

        // Convert specializations string to array
        if ($request->specializations) {
            $data['specializations'] = array_map('trim', explode(',', $request->specializations));
        }

        // Convert certifications string to array
        if ($request->certifications) {
            $data['certifications'] = array_map('trim', explode(',', $request->certifications));
        }

        $data['status'] = 'active';
        $data['hire_date'] = $data['hire_date'] ?? now();

        // Create user account for the trainer
        $user = User::create([
            'name' => $data['first_name'] . ' ' . $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make('password123'), // Default password, user should change it
            'email_verified_at' => now(),
        ]);

        // Assign trainer role to the user
        $user->assignRole('trainer');

        // Add user_id to trainer data
        $data['user_id'] = $user->id;

        $this->trainerRepository->create($data);

        return redirect()->route('admin.trainers.index')->with('success', 'Trainer created successfully. A user account has been automatically created with email: ' . $data['email'] . ' and default password: password123');
    }

    /**
     * Display the specified trainer.
     */
    public function show(int $id)
    {
        $trainer = $this->trainerRepository->find((int) $id);
        return view('admin.trainers.show', compact('trainer'));
    }

    /**
     * Show the form for editing the specified trainer.
     */
    public function edit(int $id)
    {
        $trainer = $this->trainerRepository->find((int) $id);
        $branches = $this->branchRepository->all();
        return view('admin.trainers.edit', compact('trainer', 'branches'));
    }

    /**
     * Update the specified trainer in storage.
     */
    public function update(Request $request, int $id)
    {
        $trainer = $this->trainerRepository->find((int) $id);
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:trainers,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'branch_id' => 'required|exists:branches,id',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'specializations' => 'nullable|string|max:1000',
            'certifications' => 'nullable|string|max:1000',
            'experience_years' => 'nullable|integer|min:0|max:50',
            'hourly_rate' => 'nullable|numeric|min:0',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'hire_date' => 'nullable|date',
            'bio' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive,on_leave',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only([
            'first_name',
            'last_name',
            'email',
            'phone',
            'branch_id',
            'date_of_birth',
            'gender',
            'specializations',
            'certifications',
            'experience_years',
            'hourly_rate',
            'address',
            'city',
            'state',
            'postal_code',
            'country',
            'hire_date',
            'bio',
            'status',
        ]);

        // Convert specializations string to array
        if ($request->specializations) {
            $data['specializations'] = array_map('trim', explode(',', $request->specializations));
        }

        // Convert certifications string to array
        if ($request->certifications) {
            $data['certifications'] = array_map('trim', explode(',', $request->certifications));
        }

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($trainer->profile_image && \Storage::disk('public')->exists($trainer->profile_image)) {
                \Storage::disk('public')->delete($trainer->profile_image);
            }
            
            $imagePath = $request->file('profile_image')->store('profile_images', 'public');
            $data['profile_image'] = $imagePath;
        }

        $this->trainerRepository->update((int) $id, $data);

        return redirect()->route('admin.trainers.index')->with('success', 'Trainer updated successfully.');
    }

    /**
     * Remove the specified trainer from storage.
     */
    public function destroy(int $id)
    {
        $this->trainerRepository->delete((int) $id);

        return redirect()->route('admin.trainers.index')->with('success', 'Trainer deleted successfully.');
    }

    /**
     * Toggle trainer status.
     */
    public function toggleStatus(int $id)
    {
        $trainer = $this->trainerRepository->find((int) $id);
        $newStatus = $trainer->status === 'active' ? 'inactive' : 'active';
        
        $this->trainerRepository->update((int) $id, ['status' => $newStatus]);

        return back()->with('success', 'Trainer status updated successfully.');
    }
}