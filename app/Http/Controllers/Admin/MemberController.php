<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Repositories\Interfaces\BranchRepositoryInterface;
use App\Repositories\Interfaces\TrainerRepositoryInterface;
use App\Repositories\Interfaces\PackageRepositoryInterface;
use App\Repositories\Interfaces\SubscriptionRepositoryInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class MemberController extends Controller
{
    protected $memberRepository;
    protected $branchRepository;
    protected $trainerRepository;
    protected $packageRepository;
    protected $subscriptionRepository;

    public function __construct(
        CustomerRepositoryInterface $memberRepository,
        BranchRepositoryInterface $branchRepository,
        TrainerRepositoryInterface $trainerRepository,
        PackageRepositoryInterface $packageRepository,
        SubscriptionRepositoryInterface $subscriptionRepository
    ) {
        $this->memberRepository = $memberRepository;
        $this->branchRepository = $branchRepository;
        $this->trainerRepository = $trainerRepository;
        $this->packageRepository = $packageRepository;
        $this->subscriptionRepository = $subscriptionRepository;
    }

    /**
     * Display a listing of the members.
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
            
            $query = $this->memberRepository->findByBranch($branchId);

            if ($request->has('status') && $request->status != '') {
                $query = $query->where('status', $request->status);
            }

            if ($request->has('branch_id') && $request->branch_id != '') {
                $query = $query->where('branch_id', $request->branch_id);
            }

            if ($request->has('search') && $request->search != '') {
                $query = $this->memberRepository->search($request->search);
            }

            // Convert to paginated collection
            $perPage = 15;
            $currentPage = $request->get('page', 1);
            $offset = ($currentPage - 1) * $perPage;
            $items = $query->slice($offset, $perPage)->values();
            
            $members = new \Illuminate\Pagination\LengthAwarePaginator(
                $items,
                $query->count(),
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'pageName' => 'page']
            );

            $branches = $this->branchRepository->all();
            $trainers = $this->trainerRepository->all();
            $packages = $this->packageRepository->all();

            return view('admin.members.index', compact('members', 'branches', 'trainers', 'packages'));
        } catch (\Exception $e) {
            // Fallback data if there are any issues
            $members = collect();
            $branches = collect();
            $trainers = collect();
            $packages = collect();

            return view('admin.members.index', compact('members', 'branches', 'trainers', 'packages'))
                ->with('error', 'Unable to load data: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new member.
     */
    public function create()
    {
        $user = auth()->user();
        $branchId = null;
        
        // If user is branch manager, only show their branch
        if ($user->hasRole('branch_manager') && $user->branch_id) {
            $branchId = $user->branch_id;
            $branches = $this->branchRepository->findByBranch($branchId);
        } else {
            $branches = $this->branchRepository->all();
        }
        
        $trainers = $this->trainerRepository->findByBranch($branchId);
        $packages = $this->packageRepository->all();
        return view('admin.members.create', compact('branches', 'trainers', 'packages'));
    }

    /**
     * Store a newly created member in storage.
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
            'email' => 'required|email|unique:customers,email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'branch_id' => 'required|exists:branches,id',
            'trainer_id' => 'nullable|exists:trainers,id',
            'package_id' => 'nullable|exists:packages,id',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'medical_conditions' => 'nullable|string|max:1000',
            'allergies' => 'nullable|string|max:1000',
            'join_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:join_date',
            'notes' => 'nullable|string|max:1000',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only([
            'first_name',
            'last_name',
            'email',
            'phone',
            'branch_id',
            'trainer_id',
            'date_of_birth',
            'gender',
            'address',
            'city',
            'state',
            'postal_code',
            'country',
            'emergency_contact_name',
            'emergency_contact_phone',
            'medical_conditions',
            'allergies',
            'join_date',
            'expiry_date',
            'notes',
        ]);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('profile_images', 'public');
            $data['profile_image'] = $imagePath;
        }

        $data['status'] = 'active';
        $data['join_date'] = $data['join_date'] ?? now();

        // Create user account for the member
        $user = User::create([
            'name' => $data['first_name'] . ' ' . $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make('password123'), // Default password, user should change it
            'email_verified_at' => now(),
        ]);

        // Assign customer role to the user
        $user->assignRole('customer');

        // Add user_id to member data
        $data['user_id'] = $user->id;

        $member = $this->memberRepository->create($data);

        // Create subscription if package is selected
        if ($request->package_id) {
            $package = $this->packageRepository->find($request->package_id);
            $startDate = now();
            $endDate = $startDate->copy()->addDays($package->duration_in_days);

            $this->subscriptionRepository->create([
                'customer_id' => $member->id,
                'package_id' => $package->id,
                'branch_id' => $member->branch_id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 'active',
                'visits_used' => 0,
                'visits_remaining' => $package->max_visits,
                'amount_paid' => $package->price,
            ]);
        }

        return redirect()->route('admin.members.index')->with('success', 'Member created successfully. A user account has been automatically created with email: ' . $data['email'] . ' and default password: password123');
    }

    /**
     * Display the specified member.
     */
    public function show(int $id)
    {
        $member = $this->memberRepository->find((int) $id);
        return view('admin.members.show', compact('member'));
    }

    /**
     * Show the form for editing the specified member.
     */
    public function edit(int $id)
    {
        $member = $this->memberRepository->find((int) $id);
        $branches = $this->branchRepository->all();
        $trainers = $this->trainerRepository->all();
        $packages = $this->packageRepository->all();
        return view('admin.members.edit', compact('member', 'branches', 'trainers', 'packages'));
    }

    /**
     * Update the specified member in storage.
     */
    public function update(Request $request, int $id)
    {
        $member = $this->memberRepository->find((int) $id);
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'branch_id' => 'required|exists:branches,id',
            'trainer_id' => 'nullable|exists:trainers,id',
            'package_id' => 'nullable|exists:packages,id',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'medical_conditions' => 'nullable|string|max:1000',
            'allergies' => 'nullable|string|max:1000',
            'join_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:join_date',
            'notes' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive,expired,suspended',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only([
            'first_name',
            'last_name',
            'email',
            'phone',
            'branch_id',
            'trainer_id',
            'date_of_birth',
            'gender',
            'address',
            'city',
            'state',
            'postal_code',
            'country',
            'emergency_contact_name',
            'emergency_contact_phone',
            'medical_conditions',
            'allergies',
            'join_date',
            'expiry_date',
            'notes',
            'status',
        ]);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($member->profile_image && \Storage::disk('public')->exists($member->profile_image)) {
                \Storage::disk('public')->delete($member->profile_image);
            }
            
            $imagePath = $request->file('profile_image')->store('profile_images', 'public');
            $data['profile_image'] = $imagePath;
        }

        $this->memberRepository->update((int) $id, $data);

        // Handle package assignment/change
        if ($request->package_id) {
            // Check if member already has an active subscription
            $activeSubscription = $member->subscriptions()->where('status', 'active')->first();
            
            if ($activeSubscription) {
                // Update existing subscription
                $package = $this->packageRepository->find($request->package_id);
                $startDate = now();
                $endDate = $startDate->copy()->addDays($package->duration_in_days);

                $this->subscriptionRepository->update($activeSubscription->id, [
                    'package_id' => $package->id,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'visits_remaining' => $package->max_visits,
                    'amount_paid' => $package->price,
                ]);
            } else {
                // Create new subscription
                $package = $this->packageRepository->find($request->package_id);
                $startDate = now();
                $endDate = $startDate->copy()->addDays($package->duration_in_days);

                $this->subscriptionRepository->create([
                    'customer_id' => $member->id,
                    'package_id' => $package->id,
                    'branch_id' => $member->branch_id,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'status' => 'active',
                    'visits_used' => 0,
                    'visits_remaining' => $package->max_visits,
                    'amount_paid' => $package->price,
                ]);
            }
        }

        return redirect()->route('admin.members.index')->with('success', 'Member updated successfully.');
    }

    /**
     * Remove the specified member from storage.
     */
    public function destroy(int $id)
    {
        $this->memberRepository->delete((int) $id);

        return redirect()->route('admin.members.index')->with('success', 'Member deleted successfully.');
    }

    /**
     * Toggle member status.
     */
    public function toggleStatus(int $id)
    {
        $member = $this->memberRepository->find((int) $id);
        $newStatus = $member->status === 'active' ? 'inactive' : 'active';
        
        $this->memberRepository->update((int) $id, ['status' => $newStatus]);

        return back()->with('success', 'Member status updated successfully.');
    }

    /**
     * Assign package to member.
     */
    public function assignPackage(Request $request, int $id)
    {
        $request->validate([
            'package_id' => 'required|exists:packages,id',
        ]);

        $member = $this->memberRepository->find((int) $id);
        $package = $this->packageRepository->find($request->package_id);

        // Deactivate any existing active subscriptions
        $member->subscriptions()->where('status', 'active')->update(['status' => 'inactive']);

        // Create new subscription
        $startDate = now();
        $endDate = $startDate->copy()->addDays($package->duration_in_days);

        $this->subscriptionRepository->create([
            'customer_id' => $member->id,
            'package_id' => $package->id,
            'branch_id' => $member->branch_id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'active',
            'visits_used' => 0,
            'visits_remaining' => $package->max_visits,
            'amount_paid' => $package->price,
        ]);

        return back()->with('success', 'Package assigned successfully.');
    }
}