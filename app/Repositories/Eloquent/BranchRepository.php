<?php

namespace App\Repositories\Eloquent;

use App\Models\Branch;
use App\Repositories\Interfaces\BranchRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class BranchRepository implements BranchRepositoryInterface
{
    public function all(): Collection
    {
        return Branch::all();
    }

    public function find(int $id): ?Branch
    {
        return Branch::find($id);
    }

    public function findByBranch(?int $branchId): Collection
    {
        if ($branchId === null) {
            return Branch::all();
        }
        
        return Branch::where('id', $branchId)->get();
    }

    public function create(array $data): Branch
    {
        return Branch::create($data);
    }

    public function update(int $id, array $data): bool
    {
        return Branch::where('id', $id)->update($data) > 0;
    }

    public function delete(int $id): bool
    {
        return Branch::destroy($id) > 0;
    }

    public function active(): Collection
    {
        return Branch::active()->get();
    }

    public function findByCity(string $city): Collection
    {
        return Branch::where('city', 'like', "%{$city}%")->get();
    }

    public function search(string $query): Collection
    {
        return Branch::where('name', 'like', "%{$query}%")
            ->orWhere('address', 'like', "%{$query}%")
            ->orWhere('city', 'like', "%{$query}%")
            ->orWhere('state', 'like', "%{$query}%")
            ->get();
    }
}
