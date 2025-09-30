<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\PackageRepositoryInterface;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    protected $packageRepository;

    public function __construct(PackageRepositoryInterface $packageRepository)
    {
        $this->packageRepository = $packageRepository;
    }

    /**
     * Display a listing of the packages.
     */
    public function index(Request $request)
    {
        try {
            $query = $this->packageRepository->all();

            if ($request->has('status') && $request->status != '') {
                $query = $query->where('is_active', $request->status === 'active');
            }

            if ($request->has('search') && $request->search != '') {
                $query = $query->filter(function ($package) use ($request) {
                    return stripos($package->name, $request->search) !== false ||
                           stripos($package->description, $request->search) !== false;
                });
            }

            // Convert to paginated collection
            $perPage = 15;
            $currentPage = $request->get('page', 1);
            $offset = ($currentPage - 1) * $perPage;
            $items = $query->slice($offset, $perPage)->values();
            
            $packages = new \Illuminate\Pagination\LengthAwarePaginator(
                $items,
                $query->count(),
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'pageName' => 'page']
            );

            return view('admin.packages.index', compact('packages'));
        } catch (\Exception $e) {
            // Fallback data if there are any issues
            $packages = collect();

            return view('admin.packages.index', compact('packages'))
                ->with('error', 'Unable to load data: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new package.
     */
    public function create()
    {
        return view('admin.packages.create');
    }

    /**
     * Store a newly created package in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'duration_type' => 'required|in:days,weeks,months,years',
            'duration_value' => 'required|integer|min:1',
            'max_visits' => 'nullable|integer|min:1',
            'includes_trainer' => 'boolean',
            'includes_locker' => 'boolean',
            'includes_towel' => 'boolean',
            'features' => 'nullable|string|max:1000',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $data = $request->only([
            'name',
            'description',
            'price',
            'duration_type',
            'duration_value',
            'max_visits',
            'includes_trainer',
            'includes_locker',
            'includes_towel',
            'features',
            'is_popular',
            'is_active',
        ]);

        // Convert features string to array
        if ($request->features) {
            $data['features'] = array_map('trim', explode(',', $request->features));
        }

        // Set boolean defaults
        $data['includes_trainer'] = $request->has('includes_trainer');
        $data['includes_locker'] = $request->has('includes_locker');
        $data['includes_towel'] = $request->has('includes_towel');
        $data['is_popular'] = $request->has('is_popular');
        $data['is_active'] = $request->has('is_active');

        $this->packageRepository->create($data);

        return redirect()->route('admin.packages.index')->with('success', 'Package created successfully.');
    }

    /**
     * Display the specified package.
     */
    public function show(int $id)
    {
        $package = $this->packageRepository->find((int) $id);
        return view('admin.packages.show', compact('package'));
    }

    /**
     * Show the form for editing the specified package.
     */
    public function edit(int $id)
    {
        $package = $this->packageRepository->find((int) $id);
        return view('admin.packages.edit', compact('package'));
    }

    /**
     * Update the specified package in storage.
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'duration_type' => 'required|in:days,weeks,months,years',
            'duration_value' => 'required|integer|min:1',
            'max_visits' => 'nullable|integer|min:1',
            'includes_trainer' => 'boolean',
            'includes_locker' => 'boolean',
            'includes_towel' => 'boolean',
            'features' => 'nullable|string|max:1000',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $data = $request->only([
            'name',
            'description',
            'price',
            'duration_type',
            'duration_value',
            'max_visits',
            'includes_trainer',
            'includes_locker',
            'includes_towel',
            'features',
            'is_popular',
            'is_active',
        ]);

        // Convert features string to array
        if ($request->features) {
            $data['features'] = array_map('trim', explode(',', $request->features));
        }

        // Set boolean defaults
        $data['includes_trainer'] = $request->has('includes_trainer');
        $data['includes_locker'] = $request->has('includes_locker');
        $data['includes_towel'] = $request->has('includes_towel');
        $data['is_popular'] = $request->has('is_popular');
        $data['is_active'] = $request->has('is_active');

        $this->packageRepository->update((int) $id, $data);

        return redirect()->route('admin.packages.index')->with('success', 'Package updated successfully.');
    }

    /**
     * Remove the specified package from storage.
     */
    public function destroy(int $id)
    {
        $this->packageRepository->delete((int) $id);
        return redirect()->route('admin.packages.index')->with('success', 'Package deleted successfully.');
    }

    /**
     * Toggle package status.
     */
    public function toggleStatus(int $id)
    {
        $package = $this->packageRepository->find((int) $id);
        $newStatus = !$package->is_active;
        
        $this->packageRepository->update((int) $id, ['is_active' => $newStatus]);

        return back()->with('success', 'Package status updated successfully.');
    }

    /**
     * Duplicate package.
     */
    public function duplicate(int $id)
    {
        $originalPackage = $this->packageRepository->find($id);
        
        $newPackage = $originalPackage->replicate();
        $newPackage->name = $newPackage->name . ' (Copy)';
        $newPackage->is_active = false; // Duplicate as inactive by default
        $newPackage->save();

        return back()->with('success', 'Package duplicated successfully.');
    }
}